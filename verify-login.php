<?php 
	session_start();
	require 'scripts/database.php';
	// Check if username and password have been set and aren't blank
	if (isset($_POST['username']) && isset($_POST['password'])) {
		if ($_POST['username'] !== '' && $_POST['password'] !== '') {
			// $db = mysqli_connect('localhost', $username, $password, 'book-club');
			$db = getDBConnection();
			if (is_null($db)) {
				header("Location: login.php?failed=1");
				exit;
			}
			$username = trim(strtolower($_POST['username']));
			$query = sprintf(
				"SELECT * FROM users WHERE username='%s'",
				mysqli_real_escape_string($db, $username)
			);
			$result = mysqli_fetch_assoc(makeQuery($db, $query));
			if ($result) {
				if (password_verify($_POST['password'], $result['password'])) {
					$_SESSION['id'] = $result['id'];
					$_SESSION['username'] = $result['username'];
					header("Location: all-books.php");
					exit;
				}
				else {
					header("Location: login.php?failed=1");
					exit;
				}
			}
			else {
				header("Location: login.php?failed=1");
				exit;
			}
		}
	}