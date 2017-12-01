<?php 
	if (isset($_POST['update'])) {
		session_start();
	}
	require "scripts/database.php";

	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}

	$db = getDBConnection();
	$query = "SELECT * FROM books";
	$result = mysqli_fetch_all(makeQuery($db, $query));

	$book_arr = [
		'num_of_active_trades' => 0,
		'num_of_trade_requests' => 0,
		'trade_requests' => '',
		'active_trades' => ''
	];

	// Loop through all books to populate trade request data
	foreach ($result as $book => $arr) {
		// String will indicate what kind of loan or request if in progress
		$request_text = '';
		if ($result[$book][1] === $_SESSION['id']) {
			if (!is_null($result[$book][5])) {
				$request_text = 'Book has been requested by another user';
			}
			elseif (!is_null($result[$book][6])) {
				$request_text = 'You have loaned this book out';
			}
		}
		elseif ($result[$book][5] === $_SESSION['id']) {
			$request_text = 'You have requested this book';
		}
		elseif ($result[$book][6] === $_SESSION['id']) {
			$request_text = 'You are currently borrowing this book';
		}
		$new_book_elements = "
			<div data-isbn='{$result[$book][0]}' data-owner='{$result[$book][1]}' data-requestedBy='{$result[$book][5]}' class='col-md-3'>
				<h2 class='book-title'>{$result[$book][2]}</h2>
				<img src='{$result[$book][4]}'></img>
				<p class='request-text'>$request_text</p>
				<p>{$result[$book][3]}</p>
				<a class='fa fa-check confirm'></i></a>
				<a class='fa fa-times cancel'></a>
			</div>
		";
		// If users owns this book and has been requested or if user has requested it
		if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][5])) || $result[$book][5] === $_SESSION['id']) {
			$book_arr['num_of_trade_requests'] += 1;
			$book_arr['trade_requests'] .= $new_book_elements;
		}
		// If user owns book and is already loaned out or book is currently loaned to user
		elseif (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][6])) || $result[$book][6] === $_SESSION['id']) {
			$book_arr['num_of_active_trades'] += 1;
			$book_arr['active_trades'] .= $new_book_elements;
		}
	}

	if (isset($_POST['update'])) {
		$book_arr['type'] = 'update';
		exit(json_encode($book_arr));
	}
?>

<div class="container-fluid">
	<h1><?php echo $title; ?></h1>
	<div>
		<button id="active-button" class="btn btn-secondary" href="">Active Trades <span id="active-trade-num"><?php echo $book_arr['num_of_active_trades']; ?></span></button>
		<div id="active-trades"><?php echo $book_arr['active_trades']; ?></div>
		<button id="trade-button" class="btn btn-success" href="">Trade Requests <span id="trade-request-num"><?php echo $book_arr['num_of_trade_requests']; ?></span></button>
		<div id="trade-requests">
			<?php echo $book_arr['trade_requests']; ?>
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
							echo "<div class='col-md-3'>
									<h2 class='book-title'>{$result[$book][2]}</h2>
									<img src='{$result[$book][4]}'></img>
									<p>{$result[$book][3]}</p>
									<button data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
						  		  </div>
						";
						}
						else {
							continue;
						}
					}
					else {
						echo "<div class='col-md-3'>
								<h2 class='book-title'>{$result[$book][2]}</h2>
								<img src='{$result[$book][4]}'></img>
								<p>{$result[$book][3]}</p>
						";
						if ($result[$book][1] === $_SESSION['id']) {
							echo "
								<button data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
							  </div>
							";
						}
						// Else add request book button
						else {
							$innerText = "Request Book";
							echo "
									<button data-isbn='{$result[$book][0]}' class='btn btn-success request-button'>$innerText</button>
								</div>
							";
						}
					}
				}
			}
		?>
		</div>
	</div>
	<script src="scripts/handle-button-click.js"></script>

<?php 
	require "footer.php";
?>