<?php 
	$title = "Add Books";
	require "header.php";
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
	}
?>

	<div class="container-fluid">
		<h1>Add Books</h1>

		<div id="search">
			<input id="search-box" type="text" name="search">
		</div>
	</div>

	<script src="scripts/search.js"></script>

<?php 
	require "footer.php";
?>