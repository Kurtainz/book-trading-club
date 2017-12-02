<?php 
	$title = 'Log In';
	require 'header.php';
	if (isset($_SESSION['username'])) {
		header("Location: index.php");
		exit;
	}
?>

<div class="container" id="login-form">
	<form method="post" action="verify-login.php">
		<div class="form-group">
			<label for="username">Username</label>
			<input required type="text" name="username" class="form-control">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input required type="password" name="password" class="form-control">
		</div>
		<button name="submit" type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>

<?php 
	if (isset($_GET['failed'])) {
		echo "<h1>Error Logging In</h1>";
	}
	require 'footer.php';
?>