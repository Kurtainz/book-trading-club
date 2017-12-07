<?php 
	session_start();
	session_unset();
	session_destroy();
?>

<h1>You were logged out</h1>

<a href="index.php">Click to return to homepage</a>

<?php 
	require 'footer.php';
?>