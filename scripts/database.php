<?php 

	function getDBConnection() {
		return mysqli_connect('localhost', 'root', 'VR3K0UA5GNbCLDTU', 'book-club');
	}

	function makeQuery($db, $query) {
		$result = mysqli_query($db, $query);
		mysqli_close($db);
		return $result;
	}