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
			$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$db = getDBConnection();
			// SQL Query to check username doesn't already exist
			$checkQuery = sprintf("
				SELECT * FROM users WHERE username='%s'
				", 
				mysqli_real_escape_string($_POST['username'])
			);
			// If username does exist, redirect back to signup page with message
			if (mysqli_query($db, $checkQuery)) {
				header("Location: signup.php?exists=1");
				exit(0);
			}
			// Else, add user into database
			$query = sprintf(
				"INSERT INTO users (`first-name`, `last-name`, username, password) VALUES ('%s', '%s', '%s', '%s')",
				mysqli_real_escape_string($db, $_POST['firstname']),
				mysqli_real_escape_string($db, $_POST['lastname']),
				mysqli_real_escape_string($db, $_POST['username']),
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