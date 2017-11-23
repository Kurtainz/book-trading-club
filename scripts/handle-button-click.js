var trade_buttons = document.querySelectorAll('#active-button, #trade-button');

trade_buttons.forEach(function(button) {
	button.addEventListener('click', function(e) {
		if (e.target.id === 'active-button') {
			var div = document.querySelector('#active-trades');
		}
		else {
			var div = document.querySelector('#trade-requests');
		}
		var display_style = window.getComputedStyle(div).display;
		if (display_style === 'none') {
			div.style.display = 'block'
		}
		else {
			div.style.display = 'none';
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
		var request = new XMLHttpRequest();
		request.open('POST', 'trade.php');
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(post_string);

		request.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {
	        	console.log(this.responseText);
	        }
	    }
	});
});

var book_buttons = document.querySelectorAll('.delete-button, .request-button');

book_buttons.forEach(function(button) {
	button.addEventListener('click', function(e) {
		var isbn = e.target.dataset.isbn;
		var request = new XMLHttpRequest();
		// If button clicked is delete button
		if (button.classList.contains('delete-button')) {
			if (confirm("Are you sure you want to delete this book?")) {
				request.open("POST", 'delete-book.php');
			}
			else {
				return;
			}
		}
		// Else it must be request button
		else {
			request.open("POST", 'request-book.php');
		}
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send('isbn=' + isbn);

		request.onreadystatechange = function() {
	        if (this.readyState == 4 && this.status == 200) {
	        	if (this.responseText === 'deleted') {
	        		window.location.reload();
	        	}
	        	else if (this.responseText === 'requested') {
	        		button.innerText = "Requested";
	        		button.setAttribute('disabled', '');
	        	}
	        	else {
	        		console.log(this.responseText);
	        	}
	        }
	    }
	});
});