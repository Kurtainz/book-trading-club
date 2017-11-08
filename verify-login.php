<?php 
	session_start();
	require 'scripts/database.php';
	// Check if username and password have been set and aren't blank
	if (isset($_POST['username']) && isset($_POST['password'])) {
		if ($_POST['username'] !== '' && $_POST['password'] !== '') {
			$db = mysqli_connect('localhost', $username, $password, 'book-club');
			$query = sprintf(
				"SELECT * FROM users WHERE username='%s'",
				mysqli_real_escape_string($db, $_POST['username'])
			);
			$result = mysqli_fetch_assoc(mysqli_query($db, $query));
			if ($result) {
				if (password_verify($_POST['password'], $result['password'])) {
					$_SESSION['id'] = $result['id'];
					$_SESSION['username'] = $result['username'];
					$_SESSION['admin'] = $result['admin'];
					mysqli_close($db);
					header("Location: index.php");
					exit(0);
				}
				else {
					mysqli_close($db);
					header("Location: login.php?failed=1");
					exit(0);
				}
			}
			else {
				mysqli_close($db);
				header("Location: login.php?failed=1");
				exit(0);
			}
		}
	}