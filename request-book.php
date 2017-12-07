<?php 
	require "session_expire.php";
	require "scripts/database.php";

	if (isset($_SESSION['id'])) {
		$db = getDBConnection();
		if (is_null($db)) {
			exit(null);
		}
		$query = sprintf("
			UPDATE books SET `requested-by`='%s' WHERE isbn='%s'
			AND `owner-id`<>'%s' AND `requested-by` IS NULL
			",
			$_SESSION['id'],
			mysqli_real_escape_string($db, $_POST['isbn']),
			$_SESSION['id']);
		$result = makeQuery($db, $query);
		if ($result) {
			$response = [
				'type' => 'requested',
				'isbn' => htmlspecialchars($_POST['isbn']),
				'owner' => $_SESSION['id']
			];
			$response = json_encode($response);
		}
		else {
			$response = mysqli_error($db);
		}
		exit($response);
	}
