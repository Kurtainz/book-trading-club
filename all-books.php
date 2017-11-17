<?php 
	$title = "All Books";
	require "header.php";
	require "scripts/database.php";

	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
	}

	$db = getDBConnection();
	$query = sprintf("SELECT * FROM books");
	$result = mysqli_fetch_all(makeQuery($db, $query));
?>

<div class="container-fluid">
		<h1>All Books</h1>
		<div>
			<a class="btn btn-secondary" href="">Active Trades</a>
			<a class="btn btn-success" href="">Trade Requests</a>
		</div>
		<div>
			<a class="btn btn-primary btn-lg" href="add-books.php">Add Books</a>
		</div>

		<div id="book-collection" class="row">

			<?php 
				if ($result) {
					// Loop through each book and append to page
					foreach ($result as $book => $arr) {
						echo "<div class='col-md-3'>
								<h2 class='book-title'>{$result[$book][2]}</h2>
								<img src='{$result[$book][4]}'></img>
								<p>{$result[$book][3]}</p>
						";
						// Check to see if book is owned by current user and append delete button if so
						if ($result[$book][1] === $_SESSION['id']) {
							echo "
								<button data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
							  </div>
							";
						}
						else {
							echo "
									<button data-isbn='{$result[$book][0]}' class='btn btn-success'>Request Book</button>
								</div>
							";
						}
					}
				}
			?>

		</div>
	</div>

<?php 
	require "footer.php";
?>