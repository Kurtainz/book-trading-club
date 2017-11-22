var buttons = document.querySelectorAll('.delete-button, .request-button');

buttons.forEach(function(button) {
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
		        		button.setAttribute('disabled');
		        	}
		        	else {
		        		console.log(this.responseText);
		        	}
		        }
		    }
		// if (confirm("Are you sure you want to delete this book?")) {
		// 	var request = new XMLHttpRequest();
		// 	request.open("POST", 'delete-book.php');
		// 	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// 	request.send('isbn=' + isbn);

		// 	request.onreadystatechange = function() {
		//         if (this.readyState == 4 && this.status == 200) {
		//         	if (this.responseText === 'true') {
		//         		window.location.reload();
		//         	}
		//         	else {
		//         		console.log(this.responseText);
		//         	}
		//         }
		//     }
		// }
	});
});