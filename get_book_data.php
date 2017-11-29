<?php 
	require "scripts/database.php";

	function get_book_data() {
		$db = getDBConnection();
		$query = sprintf("SELECT * FROM books");
		return mysqli_fetch_all(makeQuery($db, $query));
	}

	function generate_book_HTML($data) {
		global $_SESSION;
		$bookArr = [
			'num_of_active_trades' => 0,
			'num_of_trade_requests' => 0,
			'trade_requests' => '',
			'active_trades' => ''
		];

		// Loop through all books to populate trade request data
		foreach ($data as $book => $arr) {
			$new_book_elements = "
				<div data-isbn='{$data[$book][0]}' data-owner='{$data[$book][1]}' data-requestedBy='{$data[$book][5]}' class='col-md-3'>
					<h2 class='book-title'>{$data[$book][2]}</h2>
					<img src='{$data[$book][4]}'></img>
					<p>{$data[$book][3]}</p>
					<a class='fa fa-check confirm'></i></a>
					<a class='fa fa-times cancel'></a>
				</div>
			";
			if (($data[$book][1] === $_SESSION['id'] && !is_null($data[$book][5])) || $data[$book][5] === $_SESSION['id']) {
				$bookArr['num_of_trade_requests'] += 1;
				$bookArr['trade_requests'] .= $new_book_elements;
			}
			elseif (($data[$book][1] === $_SESSION['id'] && !is_null($data[$book][6])) || $data[$book][6] === $_SESSION['id']) {
				$bookArr['num_of_active_trades'] += 1;
				$bookArr['active_trades'] .= $new_book_elements;
			}
		}	
		return $bookArr;
	}

	if (isset($_POST['update'])) {
		$bookData = get_book_data();
		echo json_encode(generate_book_HTML($bookData));
	}