window.onload = add_button_listeners();

// Add listeners for all buttons
function add_button_listeners() {
	add_listener_to_trade_buttons();
	add_listener_to_book_buttons();
	add_listener_to_confirm_buttons();
	change_buttons();
}

// Adds listeners to Active Trades and Trade Requests buttons at top of page
function add_listener_to_trade_buttons() {
	var trade_buttons = document.querySelectorAll('#active-button, #trade-button');

	trade_buttons.forEach(function(button) {
		button.addEventListener('click', function(e) {
			if (e.target.id === 'active-button') {
				var target_div = document.querySelector('#active-trades');
				var other_div = document.querySelector('#trade-requests');
			}
			else {
				var target_div = document.querySelector('#trade-requests');
				var other_div = document.querySelector('#active-trades');
			}
			var display_style = window.getComputedStyle(target_div).display;
			if (display_style === 'none') {
				target_div.style.display = 'block'
				other_div.style.display = 'none';
			}
			else {
				target_div.style.display = 'none';
			}
		});
	});
}

// Adds listeners to buttons that appear under each book
function add_listener_to_book_buttons() {
	var book_buttons = document.querySelectorAll('.delete-button, .request-button');

	book_buttons.forEach(function(button) {
		button.addEventListener('click', function(e) {
			var isbn = e.target.dataset.isbn;
			var destination = 'request-book.php';
			// If button clicked is delete button
			if (button.classList.contains('delete-button')) {
				if (confirm("Are you sure you want to delete this book?")) {
					destination = 'delete-book.php';
				}
				else {
					return;
				}
			}
			make_XHR_request(destination, 'isbn=' + isbn);
		});
	});
}

// Adds listeners to confirm and cancel buttons in Active Trades and Trade Requests drop down divs
function add_listener_to_confirm_buttons() {
	var confirm_buttons = document.querySelectorAll('.confirm, .cancel');

	confirm_buttons.forEach(function(button) {
		button.addEventListener('click', function(e) {
			var props = {
				isbn : e.target.parentNode.dataset.isbn,
				owner_id : e.target.parentNode.dataset.owner,
				requested_by : e.target.parentNode.dataset.requestedby,
				trade_action : ''
			}
			if (e.target.classList.contains('confirm')) {
				props.trade_action = 'confirm';
			}
			else {
				props.trade_action = 'cancel';
			}
			var post_string = '';
			for (prop in props) {
				post_string += prop + "=" + props[prop] + "&";
			}

			make_XHR_request('trade.php', post_string);
		});
	});
}

// Deals with AJAX calls for all functions
function make_XHR_request(destination, data) {
	var request = new XMLHttpRequest();
	request.open('POST', destination);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(data);

	request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        	process_response(this.responseText);
        }
    }
}

// Callback which deals with AJAX responses from different PHP scripts. Action taken is based on the type of response
// which is specified in the 'type' on the returned JSON object
function process_response(response) {
	response = JSON.parse(response);
	switch (response.type) {
		case "deleted":
			window.location.reload();
			break;
		case "requested":
			clear_divs();
			break;
		case "trade":
			if (response.error) {
				console.log(response.error);
			}
			else {
				clear_divs();
			}
			break;
		case "update":
			update_divs(response);
	}
}

// Clears the active-trades and trade-requests buttons and makes an AJAX call to update them 
// which leads to update_divs being called below
function clear_divs() {
	document.querySelectorAll('#active-trades, #trade-requests').forEach(function(div) {
		while (div.firstChild) {
		    div.removeChild(div.firstChild);
		}
		if (window.getComputedStyle(div).display != 'none') {
			div.innerHTML = "<div class='loader'></div>";
		}
	});
	make_XHR_request('display-books.php', 'update=1');
}

// Populates active-trades and trade-requests divs with updated data
// after a trade request has been made, requested, confirmed or cancelled
function update_divs(data) {
	var active_trades = document.querySelector('#active-trades')
	var trade_requests = document.querySelector('#trade-requests');
	var loader = document.querySelector('.loader');
	if (loader) {
		loader.style.display = 'none';
	}
	active_trades.innerHTML = data.active_trades;
	trade_requests.innerHTML = data.trade_requests;
	document.querySelector('#active-trade-num').innerHTML = active_trades.childElementCount;
	document.querySelector('#trade-request-num').innerHTML = trade_requests.childElementCount;
	add_listener_to_confirm_buttons();
	change_buttons();
}

// Adds or removes disabled properties on buttons and changes their text based on their trade condition
function change_buttons() {
	document.querySelectorAll('.request-button, .delete-button').forEach(function(button) {
		var trade_request = document.querySelector('#trade-requests > div[data-isbn="' + button.dataset.isbn + '"]');
		var active_trade = document.querySelector('#active-trades > div[data-isbn="' + button.dataset.isbn + '"]');
		if (trade_request || active_trade) {
			button.setAttribute('disabled', '');
			if (trade_request) {
				button.innerText = 'Trade Requested';
			}
			else if (active_trade) {
				button.innerText = 'Loaning';
			}
		}
		else {
			button.removeAttribute('disabled');
			if (button.classList.contains('request-button')) {
				button.innerText = 'Request Book';	
			}
			else if (button.classList.contains('delete-button')) {
				button.innerText = 'Delete Book';
			}
		}
	});
}
