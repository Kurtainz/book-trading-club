<?php 
	session_start();
	require "scripts/database.php";
	if (isset($_SESSION['id'])) {
		$db = getDBConnection();
		$query = sprintf("
			DELETE FROM books WHERE `owner-id`='%s' AND isbn='%s'",
			$_SESSION['id'],
			mysqli_real_escape_string($db, $_POST['isbn']));
		if (makeQuery($db, $query)) {
			$response = ['type' => 'deleted'];
			exit(json_encode($response));
		}
		else {
			exit(mysqli_error($db));
		}
	}
	