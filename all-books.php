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
	$trade_requests = 0;
	$active_trades = 0;
	foreach ($result as $book => $arr) {
		if (!is_null($result[$book][5])) {
			$trade_requests += 1;
		}
		if (!is_null($result[$book][6])) {
			$active_trades += 1;
		}
	}
?>

<div class="container-fluid">
		<h1>All Books</h1>
		<div>
			<a class="btn btn-secondary" href="">Active Trades <?php echo $active_trades ?></a>
			<a class="btn btn-success" href="">Trade Requests <?php echo $trade_requests ?></a>
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
						// Checks to see if book is already loaned out. If so, we use this variable in creating the button to disable it
						$disabled = '';
						if (!is_null($result[$book][6]) || !is_null($result[$book][5])) {
							$disabled = 'disabled';
						}
						// Check to see if book is owned by current user and append delete button if so
						// First statement adds Delete button if necessary
						if ($result[$book][1] === $_SESSION['id']) {
							echo "
								<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
							  </div>
							";
						}
						// Else add request book button
						else {
							$innerText = "Request Book";
							if (!empty($disabled)) {
								$innerText = "Book Already Requested";
							}
							echo "
									<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-success request-button'>$innerText</button>
								</div>
							";
						}
					}
				}
			?>

		</div>
	</div>
	<script src="delete-book.js"></script>

<?php 
	require "footer.php";
?>