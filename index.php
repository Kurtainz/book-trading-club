<?php 
	$title = 'Book Trading Club';
	require 'header.php';
	if (isset($_SESSION['id'])) {
		header("Location: all-books.php");
	}
?>

	<div class="jumbotron">
		<div class="jumbo-div">
			<h1 class="display-3 jumbo-head">Book Trading Club</h1>
			<p class="lead">Welcome to Book Trading Club, your number one source for a better reading experience!</p>
			<hr class="my-4">
			<p>Click below to get started!</p>
			<p class='lead'>
				<div class="row">
					<div class="col-md-6">
							<a class='btn btn-primary btn-lg index-button' href='signup.php' role='button'>Sign Up</a>
					</div>
					<div class="col-md-6">
							<a class='btn btn-primary btn-lg index-button' href='login.php' role='button'>Log In</a>
					</div>
				</div>
			</p>
		</div>
	</div>

	<div class="container-fluid index-container">
		<div class="row index-articles">
			<div class="col-md-12">
				<h2 class="index-minor-header">Why Join Us?</h2>
			</div>
			<hr class="index-hr">
			<div class="col-md-3 index-article">
				<h3>Browse Other User's Book Collections</h3>
			</div>
			<hr class="index-hr">
			<div class="col-md-3 index-article">
				<h3>Trade Books With Other Users</h3>
			</div>
			<hr class="index-hr">
			<div class="col-md-3 index-article">
				<h3>Easily Manage Your Trades From Our Control Panel</h3>
			</div>
			<hr class="index-hr">
			<div class="col-md-3 index-article">
				<h3>Catalogue Your Book Collection</h3>
			</div>
		</div>
	</div>

<?php
	require 'footer.php';
?>