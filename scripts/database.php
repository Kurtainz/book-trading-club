<?php 

	$username = 'root';
	$password = 'VR3K0UA5GNbCLDTU';
	$db = mysqli_connect('localhost', $username, $password, 'book-club');

	function makeQuery($query) {
		if (mysqli_query($db, $query)) {
			mysqli_close($db);
			return mysqli_query($db, $query);
		}
		else {
			mysqli_close($db);
			return null;
		}
	}