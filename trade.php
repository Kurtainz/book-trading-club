<?php 
	session_start();
	require "scripts/database.php";

	if (isset($_SESSION['id'])) {
		$db = getDBConnection();

		// Accept an incoming trade request
		if ($_POST['trade_action'] === 'confirm' && $_SESSION['id'] === $_POST['owner_id']) {
			$query = sprintf("
				UPDATE books SET `requested-by`=NULL, `loaned-to`='%s'
				WHERE isbn='%s' AND `owner-id`='%s'
				",
				mysqli_real_escape_string($db, $_POST['requested_by']),
				mysqli_real_escape_string($db, $_POST['isbn']),
				$_SESSION['id']
			);
		}
		else {
			// Cancel a trade request or ongoing trade where user is owner
			if ($_SESSION['id'] === $_POST['owner_id']) {
				$query = sprintf("
					UPDATE books SET `requested-by`=NULL, `loaned-to`=NULL 
					WHERE isbn='%s' AND `owner-id`='%s'
					",
					mysqli_real_escape_string($db, $_POST['isbn']),
					$_SESSION['id']
				);
			}
			// Cancel trade request user has made to other or where user is borrowing book
			else {
				$query = sprintf("
					UPDATE books SET `requested-by`=NULL, `loaned-to`=NULL 
					WHERE isbn='%s' AND `owner-id`='%s' AND (`requested-by`='%s' OR `loaned-to`='%s')
					",
					mysqli_real_escape_string($db, $_POST['isbn']),
					$_POST['owner_id'],
					$_SESSION['id'],
					$_SESSION['id']
				);
			}
		}
		if (isset($query)) {
			$result = makeQuery($db, $query);
			if ($result) {
				exit($query);
			}
			else {
				exit(mysqli_error($db));
			}
		}
	}
	