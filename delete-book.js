var buttons = document.querySelectorAll('.delete-button');

buttons.forEach(function(button) {
	button.addEventListener('click', function(e) {
		var isbn = e.target.dataset.isbn;
		if (confirm("Are you sure you want to delete this book?")) {
			var request = new XMLHttpRequest();
			request.open("POST", 'delete-book.php');
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.send('isbn=' + isbn);

			request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        	if (this.responseText === 'true') {
        		window.location.reload();
        	}
        	else {
        		console.log(this.responseText);
        	}
        }
    }
		}
	});
});