<?php 
	session_start();

	// Session will expire after 1800 seconds (30 minutes)
	$expiresAfter = 1800;

	if (isset($_SESSION['last_action'])) {
		// Calculate how many seconds have passed since user's last action
		$secondsInactive = time() - $_SESSION['last_action'];
		if ($secondsInactive >= $expiresAfter) {
			session_unset();
			session_destroy();
			header("Location: inactive.html");
			exit();
		}
	}

	$_SESSION['last_action'] = time();