const search = document.getElementById('search');

search.addEventListener('keyup', (e) => {
	if (e.key === 'Enter') {
		getData(search.value);
	}
})

const getData = (query) => {
	console.log('getData');
	const request = new XMLHttpRequest();
	request.open('GET', `https://www.googleapis.com/books/v1/volumes?q=${query}`);
	request.send();

	request.onload = () => {
		appendData(JSON.parse(request.response));
	}
}

const appendData = (result) => {
	result.items.forEach((item) => {
		const titles = document.getElementById('titles');
		const li = document.createElement('li');
		li.innerHTML = `<h2>${item.volumeInfo.title}</h2>
						<img src="${item.volumeInfo.imageLinks.thumbnail}">`
		titles.appendChild(li);
	});
}