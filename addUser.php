<?php 
	require 'scripts/database.php';
	$formValues = [
		'username',
		'password',
		'firstname',
		'lastname'
	];

	if (isset($_POST['submit'])) {

		$ok = true;
		foreach ($formValues as $value) {
					// Check if value has been passed by user and is not blank
					if (!isset($_POST[$value]) || $_POST[$value] === '') {
						$ok = false;
					}
				}
		if ($ok) {
			$username = trim(strtolower($_POST['username']));
			$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$db = getDBConnection();
			// SQL Query to check username doesn't already exist
			$checkQuery = sprintf("
				SELECT * FROM users WHERE username='%s'
				", 
				mysqli_real_escape_string($db, $_POST['username'])
			);
			// If username does exist, redirect back to signup page with message
			$result = mysqli_fetch_all(makeQuery($db, $checkQuery));
			if (!empty($result)) {
				header("Location: signup.php?exists=1");
				exit();
			}
			// Else, add user into database
			$db = getDBConnection();
			$query = sprintf(
				"INSERT INTO users (`first-name`, `last-name`, username, password) VALUES ('%s', '%s', '%s', '%s')",
				mysqli_real_escape_string($db, trim($_POST['firstname'])),
				mysqli_real_escape_string($db, trim($_POST['lastname'])),
				mysqli_real_escape_string($db, $username),
				mysqli_real_escape_string($db, $hash)
			);
			if (!makeQuery($db, $query)) {
				header('Location:signup.php?failed=1');
				exit(0);
			}
			else {
				header('Location:login.php?newUser=1');
				exit(0);
			}
		}
	}