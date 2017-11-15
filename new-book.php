<?php 
	session_start();
	require "scripts/database.php";

	$json_string = json_decode(file_get_contents('php://input'), true);
	if ($json_string) {
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

	// if (isset($_POST['submit'])) {
	// 	$db = getDBConnection();

	// 	$query = sprintf("
	// 		INSERT INTO books (isbn, `owner-id`, name, author, picture) VALUES ('%s', '%s', '%s', '%s', '%s')
	// 		", 
	// 		mysqli_real_escape_string($db, $_POST['ISBN']));
	// 		mysqli_real_escape_string($db, $_SESSION['id']));
	// 		mysqli_real_escape_string($db, $_POST['name']));
	// 		mysqli_real_escape_string($db, $_POST['author']));
	// 		mysqli_real_escape_string($db, $_POST['picture']));

	// 	if (makeQuery($db, $query)) {
	// 		exit('true');
	// 	}
	// 	else {
	// 		exit(mysqli_error($db));
	// 	}
	// }

	// if (isset($_POST['submit'])) {
	// 	exit('true');
	// }