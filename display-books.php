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

	// Create new div elements for Trade Request and Active Trades buttons
	$trade_requests = '';
	$active_trades = '';

	// Loop through all books to populate trade request data
	foreach ($result as $book => $arr) {
		$new_book_elements = "
			<div data-isbn='{$result[$book][0]}' data-owner='{$result[$book][1]}' data-requestedBy='{$result[$book][5]}' class='col-md-3'>
				<h2 class='book-title'>{$result[$book][2]}</h2>
				<img src='{$result[$book][4]}'></img>
				<p>{$result[$book][3]}</p>
				<a class='fa fa-check confirm'></i></a>
				<a class='fa fa-times cancel'></a>
			</div>
		";
		// If book is owned by current user
		if ($result[$book][1] === $_SESSION['id']) {
			// If book has been requested by other user
			if (!is_null($result[$book][5])) {
				$num_of_trade_requests += 1;
				$trade_requests .= $new_book_elements;
			}
			// If book has been loaned to another user
			elseif (!is_null($result[$book][6])) {
				$num_of_active_trades += 1;
				$active_trades .= $new_book_elements;
			}
		}
		// If book owned by another user has been requested by current user
		elseif ($result[$book][5] === $_SESSION['id']) {
			$num_of_trade_requests += 1;
			$trade_requests .= $new_book_elements;
		}
		// If book owned by other user is currently loaned to current user
		elseif ($result[$book][6] === $_SESSION['id']) {
			$num_of_active_trades += 1;
			$active_trades .= $new_book_elements;
		}

		// if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][5])) || $result[$book][5] === $_SESSION['id']) {
		// 	$num_of_trade_requests += 1;
		// }
		// if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][6])) || $result[$book][6] === $_SESSION['id']) {
		// 	$num_of_active_trades += 1;
		// }
	}

?>

<div class="container-fluid">
	<h1>All Books</h1>
	<div>
		<button id="active-button" class="btn btn-secondary" href="">Active Trades <?php echo $num_of_active_trades ?></button>
		<div id="active-trades"><?php echo $active_trades; ?></div>
		<button id="trade-button" class="btn btn-success" href="">Trade Requests <?php echo $num_of_trade_requests ?></button>
		<div id="trade-requests">
			<?php echo $trade_requests; ?>
		</div>
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
	<script src="scripts/handle-button-click.js"></script>

<?php 
	require "footer.php";
?>