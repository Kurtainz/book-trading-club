<?php
	$title = 'Sign Up';
	require 'header.php';
	if (isset($_GET['exists'])) {
		echo "
		<div>
			<h2>Sorry, this username is already in use</h2>
		</div>
		";
	}
?>

	<div class="container">
		<form id="signupForm" method="post" action="addUser.php">
		  <div class="form-group">
		    <label for="username">Username</label>
		    <input required name="username" type="text" class="form-control" id="username" placeholder="Enter username">
		  </div>
		  <div class="form-group">
		    <label for="password">Password</label>
		    <input required name="password" type="password" class="form-control" id="password" placeholder="Password">
		  </div>
		  <div class="form-group">
		  	<label for="firstname">First Name</label>
		  	<input required name="firstname" type="text" id="firstname" class="form-control" placeholder="First Name">
		  </div>
		  <div class="form-group">
		  	<label for="lastname">Last Name</label>
		  	<input required name="lastname" type="text" id="lastname" class="form-control" placeholder="Last Name">
		  </div>
		  <button name="submit" type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>

<?php 
	require 'footer.php';
?>