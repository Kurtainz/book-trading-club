<?php 
	$db = mysqli_connect('localhost', $username, $password, 'book-club');
	$query = sprintf(
		"SELECT * FROM users WHERE username='%s'",
		$_SESSION['username']
		);
	