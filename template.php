<?php 
	$signedIn = false;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="#">BTC</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="signup.php">Sign Up</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="login.php">
					<?php 
					if ($signedIn) {
						echo "Log Out";
					}
					else {
						echo "Log In";
					}
					?>		
				</a>
			</li>
		</ul>
	</div>
</nav>