<?php 
	require "header.php";
	require "scripts/database.php";

	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}

	$db = getDBConnection();
	$query = sprintf("SELECT * FROM books");
	$result = mysqli_fetch_all(makeQuery($db, $query));
	$num_of_trade_requests = 0;
	$num_of_active_trades = 0;
	$trade_requests = [];
	$active_trades = [];
	foreach ($result as $book => $arr) {
		if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][5])) || $result[$book][5] === $_SESSION['id']) {
			$num_of_trade_requests += 1;
			array_push($trade_requests, $result[$book]);
		}
		if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][6])) || $result[$book][6] === $_SESSION['id']) {
			$num_of_active_trades += 1;
			array_push($active_trades, $result[$book]);
		}
	}
	var_dump($trade_requests);
?>

<div class="container-fluid">
	<h1>All Books</h1>
	<div>
		<button class="btn btn-secondary" href="">Active Trades <?php echo $num_of_active_trades ?></button>
		<div id="active-trades"></div>
		<button class="btn btn-success" href="">Trade Requests <?php echo $num_of_trade_requests ?></button>
		<div id="trade-requests"></div>
	</div>
	<div>
		<a class="btn btn-primary btn-lg" href="add-books.php">Add Books</a>
	</div>

	<div id="book-collection" class="row">

		<?php
			if ($result) {
				// Loop through each book and append to page
				foreach ($result as $book => $arr) {
					if ($title === "My Books") {
						if ($result[$book][1] === $_SESSION['id']) {
							$disabled = '';
							if (!is_null($result[$book][6]) || !is_null($result[$book][5])) {
								$disabled = 'disabled';
							}
							echo "<div class='col-md-3'>
									<h2 class='book-title'>{$result[$book][2]}</h2>
									<img src='{$result[$book][4]}'></img>
									<p>{$result[$book][3]}</p>
									<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
						  		  </div>
						";
						}
						else {
							continue;
						}
					}
					else {
						$disabled = '';
						if (!is_null($result[$book][6]) || !is_null($result[$book][5])) {
							$disabled = 'disabled';
						}
						echo "<div class='col-md-3'>
								<h2 class='book-title'>{$result[$book][2]}</h2>
								<img src='{$result[$book][4]}'></img>
								<p>{$result[$book][3]}</p>
						";
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
					// Checks to see if book is already loaned out. If so, we use this variable in creating the button to disable it
					// $disabled = '';
					// if (!is_null($result[$book][6]) || !is_null($result[$book][5])) {
					// 	$disabled = 'disabled';
					// }

					// If "My Books" page, we know user owns all books and can append delete buttons to each
					// if ($title === "My Books") {
					// 	echo "
					// 		<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
					// 	  </div>
					// 	";
					// }
					// Else, it's the "All Books" page which means we need to check for ownership
					// else {
						// Check to see if book is owned by current user and append delete button if so
						// First statement adds Delete button if necessary
						// if ($result[$book][1] === $_SESSION['id']) {
						// 	echo "
						// 		<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
						// 	  </div>
						// 	";
						// }
						// // Else add request book button
						// else {
						// 	$innerText = "Request Book";
						// 	if (!empty($disabled)) {
						// 		$innerText = "Book Already Requested";
						// 	}
						// 	echo "
						// 			<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-success request-button'>$innerText</button>
						// 		</div>
						// 	";
						// }
					// }
				}
			}
		?>
		</div>
	</div>

<?php 
	require "footer.php";
?>