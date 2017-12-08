var search = document.getElementById('search-box');

// This function makes the API call with the supplied query string
function makeSearch(query) {
	clearData();
	query = query.replace(" ", "+");
	var request = new XMLHttpRequest();
	var url = "https://www.googleapis.com/books/v1/volumes?q=" + query;
	request.open('GET', url);
	request.send();

	// Successful query will return JSON object
	request.onload = function() {
		appendData(JSON.parse(request.response));
	}
}

function clearData() {
	var search_results = document.getElementsByClassName('search-results')[0];
	if (search_results) {
		while (search_results.firstChild) {
			search_results.removeChild(search_results.firstChild);
		}
	}
}

// Creates a new div and calls createNewElements to populate with data
function appendData(data) {
	var suggestions = document.createElement('div');
	suggestions.setAttribute('class', 'row search-results');
	document.getElementById('search').appendChild(suggestions);
	data.items.forEach(function(item) {
		var newElement = createNewElements(item);
		if (newElement) {
			suggestions.appendChild(createNewElements(item));			
		}
	});
}

// Creates inner DOM elements with data from JSON data
function createNewElements(item) {
	var book = createNewBook(item);
	if (book) {
		var div = document.createElement('div');
		var h2 = div.appendChild(document.createElement('h2'));
		var img = div.appendChild(document.createElement('img'));
		var p = div.appendChild(document.createElement('p'));
		var a = div.appendChild(document.createElement('a'));
		div.setAttribute('class', 'col-md-3 book-container');
		h2.setAttribute('class', 'book-title');
		h2.innerHTML = book.name;
		p.innerHTML = book.authors;
		p.setAttribute('class', 'book-author');
		img.src = book.picture;
		img.setAttribute('class', 'book-image');
		a.innerHTML = "Add Book";
		a.book = book;
		a.setAttribute('class', 'btn btn-success');
		a.addEventListener('click', addBook);
		return div;
	}
}

function createNewBook(item) {
	var authors = '';
	var picture = '';
	if (item.volumeInfo.industryIdentifiers && item.volumeInfo.title) {
		var ISBN = item.volumeInfo.industryIdentifiers[0].identifier;
		var name = item.volumeInfo.title;
		if (item.volumeInfo.authors) {
			authors = item.volumeInfo.authors.toString();
		}
		if (item.volumeInfo.imageLinks) {
			picture = item.volumeInfo.imageLinks.thumbnail;
		}
	}
	else {
		return null;
	}
	return book = {
		ISBN : ISBN,
		name : name,
		authors : authors,
		picture : picture
	}
}

function addBook(e) {
	var p = e.target.parentNode.appendChild(document.createElement('p'));
	var json_string = JSON.stringify(e.target.book);
	request = new XMLHttpRequest();
	request.open("POST", 'new-book.php', true);
	request.setRequestHeader("Content-type", "application/json");
	request.send(json_string);

	request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        	if (this.responseText === 'true') {
        		p.innerHTML = "Added to My Books";
        	}
        	else if (this.responseText === 'owned') {
        		p.innerHTML = "You already own this book";
        	}
        	else {
        		p.innerHTML = "Failed to add book";
        		console.log(this.responseText);
        	}
        }
    }
}

// Executes search when enter is pressed
search.addEventListener('keyup', function(e) {
	if (e.key === 'Enter') {
		makeSearch(e.target.value);
	}
});
