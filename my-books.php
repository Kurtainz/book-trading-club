<?php 
	$title = "My Books";
	require "header.php";
	require "scripts/database.php";

	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
	}

	$db = getDBConnection();
	$query = sprintf("
		SELECT * FROM books WHERE `owner-id`='%s'
		",
		$_SESSION['id']);
	$result = mysqli_fetch_all(mysqli_query($db, $query));
?>

	<div class="container-fluid">
		<h1>My Book Collection</h1>
		<div>
			<a class="btn btn-secondary" href="">Active Trades</a>
			<a class="btn btn-success" href="">Trade Requests</a>
		</div>
		<div>
			<a class="btn btn-primary btn-lg" href="add-books.php">Add Books</a>
		</div>

		<div id="book-collection">

			<?php 
				if ($result) {
					foreach ($result as $book => $arr) {
						echo "<div>
								<h2>{$result[$book][2]}</h2>
								<img src='{$result[$book][4]}'></img>
								<p>{$result[$book][3]}</p>
							  </div>
						";
					}
				}
			?>

		</div>
	</div>

<?php 
	require "footer.php";
?>