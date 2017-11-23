<?php 
	session_start();
	require "scripts/database.php";

	$db = getDBConnection();

	if ($_POST['owner_id'] === $_SESSION['id']) {
		// If accepting a trade request
		if ($_POST['trade_action'] === 'confirm') {
			$query = sprintf("
				UPDATE books SET `requested-by`=NULL, `loaned-to`='%s'
				WHERE isbn='%s' AND `owner-id`='%s'
				",
				mysqli_real_escape_string($db, $_POST['requested_by']),
				mysqli_real_escape_string($db, $_POST['isbn']),
				$_SESSION['id']
			);
		}
		// Rejecting a trade request
		else {
			$query = sprintf("
				UPDATE books SET `requested-by`=NULL 
				WHERE isbn='%s' AND `owner-id`='%s'
				",
				mysqli_real_escape_string($db, $_POST['isbn']),
				$_SESSION['id']
			);
		}
	}

	if (isset($query)) {
		$result = makeQuery($db, $query);
		if ($result) {
			exit('success');
		}
		else {
			exit(mysqli_error($db));
		}
	}