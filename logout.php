<?php 
	session_start();
	// Remove session variables
	$_SESSION = [];
	// Delete session cookie
	if (ini_get("session.use_cookies")) {
    	$params = session_get_cookie_params();
    	setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
	session_destroy();
?>

<h1>You were logged out</h1>

<a href="index.php">Click to return to homepage</a>

<?php 
	require 'footer.php';
?>