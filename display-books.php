<?php 
	if (isset($_POST['update'])) {
		require "session_expire.php";
	}
	require "scripts/database.php";

	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}

	$db = getDBConnection();
	if (is_null($db)) {
		echo "<h1>Error Connecting To Database</h1>";
		exit;
	}
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
		// $request_text = '';
		// if ($result[$book][1] === $_SESSION['id']) {
		// 	if (!is_null($result[$book][5])) {
		// 		$request_text = 'Book has been requested by another user';
		// 	}
		// 	elseif (!is_null($result[$book][6])) {
		// 		$request_text = 'You have loaned this book out';
		// 	}
		// }
		// elseif ($result[$book][5] === $_SESSION['id']) {
		// 	$request_text = 'You have requested this book';
		// }
		// elseif ($result[$book][6] === $_SESSION['id']) {
		// 	$request_text = 'You are currently borrowing this book';
		// }
		$new_book_elements = "
			<div data-isbn='{$result[$book][0]}' data-owner='{$result[$book][1]}' data-requestedBy='{$result[$book][5]}' class='small-book-container'>
				<p class='small-book-title'>{$result[$book][2]}</p>
				<img class='small-book-image' src='{$result[$book][4]}'></img>
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
	if (empty($book_arr['active_trades'])) {
		$book_arr['active_trades'] = "<h2>You Have No Active Trades</h2>";
	}
	if (empty($book_arr['trade_requests'])) {
		$book_arr['trade_requests'] = "<h2>You Have No Trade Requests</h2>";
	}

	if (isset($_POST['update'])) {
		$book_arr['type'] = 'update';
		exit(json_encode($book_arr));
	}
?>

<div class="container-fluid">

	<h1 class="main-title"><?php echo $title; ?></h1>

	<!-- <div id="buttons">
		<button id="active-button" class="btn btn-secondary" data-toggle="collapse" data-target="#active-trades" data-parent="#buttons">
			Active Trades <span id="active-trade-num"><?php echo $book_arr['num_of_active_trades']; ?></span>
		</button>
		<div id="active-trades" class="collapse">
			<?php echo $book_arr['active_trades']; ?>		
		</div>
		<button id="trade-button" class="btn btn-success" data-toggle="collapse" data-target="#trade-requests" data-parent="#buttons">
			Trade Requests <span id="trade-request-num"><?php echo $book_arr['num_of_trade_requests']; ?></span>
		</button>
		<div id="trade-requests" class="collapse">
			<?php echo $book_arr['trade_requests']; ?>
		</div>
		<button class="btn btn-primary" href="add-books.php">
			<a class="add-books" href="add-books.php">Add Books</a>
		</button>
	</div> -->
	<div id="buttons" data-children=".item">
		<div class="item">
			<button id="active-button" class="btn btn-secondary" data-toggle="collapse" data-target="#active-trades" data-parent="#buttons">
				Active Trades <span id="active-trade-num"><?php echo $book_arr['num_of_active_trades']; ?></span>
			</button>
			<div id="active-trades" class="collapse">
				<?php echo $book_arr['active_trades']; ?>		
			</div>
		</div>
		<div class="item">
			<button id="trade-button" class="btn btn-success" data-toggle="collapse" data-target="#trade-requests" data-parent="#buttons">
				Trade Requests <span id="trade-request-num"><?php echo $book_arr['num_of_trade_requests']; ?></span>
			</button>
			<div id="trade-requests" class="collapse">
				<?php echo $book_arr['trade_requests']; ?>
			</div>
		</div>
		<button class="btn btn-primary" href="add-books.php">
			<a class="add-books" href="add-books.php">Add Books</a>
		</button>
	</div>

	<div id="book-collection" class="row">

		<?php
			if ($result) {
				// Loop through each book and append to page
				foreach ($result as $book => $arr) {
					if ($title === "My Books") {
						if ($result[$book][1] === $_SESSION['id']) {
							echo "<div class='col-md-3 book-container'>
									<h2 class='book-title'>{$result[$book][2]}</h2>
									<img class='book-image' src='{$result[$book][4]}'></img>
									<p class='book-author'>{$result[$book][3]}</p>
									<button data-isbn='{$result[$book][0]}' class='btn btn-danger delete-button'>Delete Book</button>
						  		  </div>
						";
						}
						else {
							continue;
						}
					}
					else {
						echo "<div class='col-md-3 book-container'>
								<h2 class='book-title'>{$result[$book][2]}</h2>
								<img class='book-image' src='{$result[$book][4]}'></img>
								<p class='book-author'>{$result[$book][3]}</p>
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
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<script src="scripts/handle-button-click.js"></script>

<?php 
	require "footer.php";
?>