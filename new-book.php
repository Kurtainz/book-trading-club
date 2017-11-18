<?php 
	session_start();
	require "scripts/database.php";

	$json_string = json_decode(file_get_contents('php://input'), true);

	if ($json_string) {
		$db = getDBConnection();
		// Check to see if user already owns this book
		$checkQuery = sprintf("
			SELECT * FROM books WHERE `owner-id`='%s' AND isbn='%s'
			", 
			$_SESSION['id'],
			mysqli_real_escape_string($db, $json_string['ISBN']));
		// $checkResult = mysqli_fetch_assoc(makeQuery($db, $checkQuery));
		$checkResult = mysqli_fetch_assoc(makeQuery($db, $checkQuery));
		// If result isn't empty, abort script and send response
		if (!is_null($checkResult)) {
			exit('owned');
		}
		$db = getDBConnection();
		$query = sprintf("
			INSERT INTO books (isbn, `owner-id`, name, author, picture) VALUES ('%s', '%s', '%s', '%s', '%s')
			", 
			mysqli_real_escape_string($db, $json_string['ISBN']),
			mysqli_real_escape_string($db, $_SESSION['id']),
			mysqli_real_escape_string($db, $json_string['name']),
			mysqli_real_escape_string($db, $json_string['authors']),
			mysqli_real_escape_string($db, $json_string['picture']));
		if (makeQuery($db, $query)) {
			exit('true');
		}
		else {
			exit(mysqli_error($db));
		}
	}
	else {
		exit('false');
	}

