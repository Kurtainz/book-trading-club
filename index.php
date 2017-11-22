<?php 
	$title = 'Book Trading Club';
	require 'header.php';
	if (isset($_SESSION['id'])) {
		header("Location: all-books.php");
	}
?>

	<div class="jumbotron">
	  <h1 class="display-3">Book Trading Club</h1>
	  <p class="lead">Welcome to Book Trading Club, your number one source for a better reading experience!</p>
	  <hr class="my-4">
	  <p>Click below to get started!</p>
	  <p class='lead'>
		  <a class='btn btn-primary btn-lg' href='signup.php' role='button'>Sign Up</a>
		  <a class='btn btn-primary btn-lg' href='login.php' role='button'>Log In</a>
	  </p>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h2 class="index-minor-header">Why Join Us?</h2>
			</div>
			<div class="col-md-3">
				<h3>Browse Other User's Book Collections</h3>
			</div>
			<div class="col-md-3">
				<h3>Trade Books With Other Users</h3>
			</div>
			<div class="col-md-3">
				<h3>Easily Manage Your Trades From Our Control Panel</h3>
			</div>
			<div class="col-md-3">
				<h3>Catalogue Your Book Collection</h3>
			</div>
		</div>
	</div>
	

<?php
	require 'footer.php';
?>