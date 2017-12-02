<?php 

	function getDBConnection() {
		mysqli_report(MYSQLI_REPORT_ALL);
		try {
			$db = mysqli_connect('localhost', 'root', 'VR3K0UA5GNbCLDTU', 'book-club');
		}
		catch (Exception $e) {
			return null;
		}
		return $db;
	}

	function makeQuery($db, $query) {
		mysqli_report(MYSQLI_REPORT_OFF);
		if (!is_null($db)) {
			$result = mysqli_query($db, $query);
			mysqli_close($db);
			return $result;
		}
		else {
			return null;
		}
	}