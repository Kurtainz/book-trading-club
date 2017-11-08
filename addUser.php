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
			$db = mysqli_connect('localhost', $username, $password, 'book-club');
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
			if (!mysqli_query($db, $query)) {
				echo "Error happened\n";
				echo mysqli_error($db);
				mysqli_close($db);
				header('Location:signup.php');
				exit(0);
			}
			else {
				mysqli_close($db);
				header('Location:login.php?newUser=1');
				exit(0);
			}
		}
	}