<?php 
	$title = 'Update Details';
	require "header.php";
	require "scripts/database.php";
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit(0);
	}

	$updated = '';

	// $db = mysqli_connect('localhost', $username, $password, 'book-club');

	if (isset($_POST['details-submit'])) {
		$query = sprintf("
			UPDATE users SET `first-name`='%s', `last-name`='%s', town='%s' 
			WHERE id='%s'
			", 
			mysqli_real_escape_string($db, $_POST['firstname']),
			mysqli_real_escape_string($db, $_POST['lastname']),
			mysqli_real_escape_string($db, $_POST['town']),
			$_SESSION['id']
		);
		if (makeQuery($query)) {
			$updated = "<p>Updated details =)</p>";	
		}
		else {
			$updated = "<p>Sorry, there was an error updating =(</p>";
		}
		// mysqli_query($db, $query);
		// mysqli_close($db);
	}
	elseif (isset($_POST['password-submit'])) {
		$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$query = sprintf("
			UPDATE users SET password='%s' WHERE id='%s'
			",
			$hash,
			$_SESSION['id']
		);
		if (makeQuery($query)) {
			$updated = "<p>Password updated =)</p>";
		}
		else {
			$updated = "<p>Sorry, there was an error updating =(</p>";
		}
		// mysqli_query($db, $query);
		// mysqli_close($db);
		// $updated = true;
	}

	$query = sprintf(
		"SELECT * FROM users WHERE id='%s'",
		$_SESSION['id']
		);
	if (makeQuery($query)) {
		$result = mysqli_fetch_assoc(makeQuery($query));
	}
	// if (!mysqli_query($db, $query)) {
	// 	echo "Error happened\n";
	// 	echo mysqli_error($db);
	// 	mysqli_close($db);
	// }
	// else {
	// 	$result = mysqli_fetch_assoc(mysqli_query($db, $query));
	// 	mysqli_close($db);
	// }


?>

	<h1>Update Details</h1>

	<?php 
		if ($updated) {
			echo $updated;
		}
	?>

	<div class="container">
		<form id="update-form" method="post" action="">
			<div class="form-group">
			  	<label for="firstname">First Name</label>
			  	<input required name="firstname" type="text" id="firstname" class="form-control" placeholder="First Name" value="<?php 
			  		if (isset($result['first-name'])) {
			  			echo $result['first-name'];
			  		}
			  	?>">
			</div>
			<div class="form-group">
			 	<label for="lastname">Last Name</label>
			 	<input required name="lastname" type="text" id="lastname" class="form-control" placeholder="Last Name" value="<?php 
			 		if (isset($result['last-name'])) {
			 			echo $result['last-name'];
			 		}
			 	?>">
			</div>
			<div class="form-group">
				<label for="town">Town</label>
				<input name="town" type="text" class="form-control" id="town" placeholder="Town" value="<?php 
					if (isset($result['town'])) {
						echo $result['town'];
					}
				?>">
			</div>
			<button name="details-submit" type="submit" class="btn btn-primary">Submit</button>
		</form>

		<form method="post" action="">
			<div class="form-group">
				<label for="password1">Password</label>
				<input name="password1" type="password" class="form-control" id="password1" placeholder="Password">
			</div>
			<div class="form-group">
				<label for="password2">Password</label>
				<input name="password2" type="password" class="form-control" id="password2" placeholder="Confirm Password">
			</div>
			<button name="password-submit" type="submit" class="btn btn-primary">Submit</button>
		</form>

	</div>

<?php 
	require "footer.php";
?>