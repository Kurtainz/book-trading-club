<?php 
	require "session_expire.php";
	$signedIn = false;
	if (isset($_SESSION['username'])) {
		$signedIn = true;
		$escaped_username = htmlspecialchars($_SESSION['username']);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<script src="https://use.fontawesome.com/12b34b3018.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Spectral+SC" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>

	<nav class="navbar navbar-expand-lg">
		<a class="navbar-brand" href="index.php">BTC</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
				<?php 
					if (!$signedIn) {
						echo "
						<li class='nav-item'>
							<a class='nav-link' href='signup.php'>Sign Up</a>
						</li>";
						echo "
						<li class='nav-item'>
							<a class='nav-link' href='login.php'>Log In</a>
						</li>";
					}
					else {
						echo "
							<li class='nav-item'>
								<a class='nav-link' href='all-books.php'>All Books</a>
							</li>";
						echo "
							<li class='nav-item'>
								<a class='nav-link' href='my-books.php'>My Books</a>
							</li>";
						echo "
							<li class='nav-item'>
								<a class='nav-link' href='update-profile.php'><i class='fa fa-cogs' aria-hidden='true'></i></a>
							</li>";
						echo "
							<li class='nav-item'>
								<a class='nav-link' href='logout.php'>Log Out " . $escaped_username . "</a>
							</li>";
					}
				?>		
			</ul>
		</div>
	</nav>