<?php 
	$title = 'Update Details';
	require "header.php";
	require "scripts/database.php";
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit(0);
	}

	$updated = '';
	$db = getDBConnection();

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
		if (makeQuery($db, $query)) {
			$updated = "<p>Updated details =)</p>";	
			$db = getDBConnection();
		}
		else {
			$updated = "<p>Sorry, there was an error updating =(</p>";
		}
		// mysqli_query($db, $query);
		// mysqli_close($db);
	}
	elseif (isset($_POST['password-submit'])) {
		if ($_POST['password1'] === $_POST['password2']) {
			$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);
			$query = sprintf("
				UPDATE users SET password='%s' WHERE id='%s'
				",
				$hash,
				$_SESSION['id']
			);
			if (makeQuery($db, $query)) {
				$updated = "<p>Updated password =)</p>";
				$db = getDBConnection();
			}
			else {
				$updated = "<p>Unable to update your password =(. Try again perhaps? </p>";
			}
		}
		else {
			$updated = "<p>Passwords did not match</p>";
		}
		// mysqli_query($db, $query);
		// mysqli_close($db);
		// $updated = true;
	}

	$query = sprintf(
		"SELECT * FROM users WHERE id='%s'",
		$_SESSION['id']
		);
	$result = mysqli_fetch_assoc(makeQuery($db, $query));
	if (!$result) {
		echo "Error retrieving data";
	}

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
				<input required name="password1" type="password" class="form-control" id="password1" placeholder="Password">
			</div>
			<div class="form-group">
				<label for="password2">Confirm Password</label>
				<input required name="password2" type="password" class="form-control" id="password2" placeholder="Confirm Password">
			</div>
			<button name="password-submit" type="submit" class="btn btn-primary">Submit</button>
		</form>

	</div>

<?php 
	require "footer.php";
?>