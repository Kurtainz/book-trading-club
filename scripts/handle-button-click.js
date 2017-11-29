function make_XHR_request(destination, data) {
	var request = new XMLHttpRequest();
	request.open('POST', destination);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(data);

	request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        	processResponse(this.responseText);
        }
    }
}

// Callback which deals with AJAX responses from different PHP scripts. Action taken is based on the type of response
// which is specified in the 'type' on the returned JSON object
function processResponse(response) {
	response = JSON.parse(response);
	switch (response.type) {
		case "deleted":
			window.location.reload();
			break;
		case "requested":
			var request_button = document.querySelector("button[data-isbn='" + response.isbn + "']");
			request_button.innerText = "Requested";
			request_button.setAttribute('disabled', '');
			trade_buttons[1].innerText = "Trade Requests " + document.querySelector('#trade-requests').childElementCount;
			break;
		case "trade":
			if (response.error) {
				console.log(response.error);
			}
			else {
				clearDivs();
			}
			break;
		case "update":
			updateDivs(response);
	}
}

function clearDivs() {
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

function updateDivs(data) {
	document.querySelector('.loader').style.display = 'none';
	document.querySelector('#active-trades').innerHTML = data.active_trades;
	document.querySelector('#trade-requests').innerHTML = data.trade_requests;
}

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
