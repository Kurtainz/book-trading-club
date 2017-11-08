<?php 
	session_start();
	$signedIn = false;
	if (isset($_SESSION['username'])) {
		$signedIn = true;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
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
								<a class='nav-link' href='#'>My Books</a>
							</li>";
						echo "
							<li class='nav-item'>
								<a class='nav-link' href='logout.php'>Log Out " . $_SESSION['username'] . "</a>
							</li>";
					}
				?>		
			</ul>
		</div>
	</nav>