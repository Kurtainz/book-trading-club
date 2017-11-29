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

	$bookArr = [
		'num_of_active_trades' => 0,
		'num_of_trade_requests' => 0,
		'trade_requests' => '',
		'active_trades' => ''
	];

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
		if (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][5])) || $result[$book][5] === $_SESSION['id']) {
			$bookArr['num_of_trade_requests'] += 1;
			$bookArr['trade_requests'] .= $new_book_elements;
		}
		elseif (($result[$book][1] === $_SESSION['id'] && !is_null($result[$book][6])) || $result[$book][6] === $_SESSION['id']) {
			$bookArr['num_of_active_trades'] += 1;
			$bookArr['active_trades'] .= $new_book_elements;
		}
	}

	if (isset($_POST['update'])) {
		$bookArr['type'] = 'update';
		exit(json_encode($bookArr));
	}
?>

<div class="container-fluid">
	<h1><?php echo $title; ?></h1>
	<div>
		<button id="active-button" class="btn btn-secondary" href="">Active Trades <?php echo $bookArr['num_of_active_trades']; ?></button>
		<div id="active-trades"><?php echo $bookArr['active_trades']; ?></div>
		<button id="trade-button" class="btn btn-success" href="">Trade Requests <?php echo $bookArr['num_of_trade_requests']; ?></button>
		<div id="trade-requests">
			<?php echo $bookArr['trade_requests']; ?>
		</div>
	</div>
	<div>
		<a class="btn btn-primary btn-lg" href="add-books.php">Add Books</a>
	</div>

	<button id="smoke">Smoke</button>

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
								$innerText = "Requested";
							}
							echo "
									<button $disabled data-isbn='{$result[$book][0]}' class='btn btn-success request-button'>$innerText</button>
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