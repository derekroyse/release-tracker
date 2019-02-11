<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

// Connect to DB.
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	if (array_key_exists("host", $url)){
		$server = $url["host"];
		$username = $url["user"];
		$password = $url["pass"];
		$db = substr($url["path"], 1);
		$conn = new mysqli($server, $username, $password, $db);
	} else {
		$servername = "localhost";
		$username = "RTAdmin";
		$password = "rogers001";
		$db = "releasetracker";
		$conn = new mysqli($servername, $username, $password, $db);
	}

// Generate SQL based on list being retrieved.
	$today = date("Y-m-d");
	if (isset($_POST['listType'])){
		if ($_POST['listType'] == 'master'){
			$sql = "SELECT * FROM titles
					WHERE release_date >= ?";
			$query = $conn->prepare($sql);
			$query->bind_param('s', $today);
		} else if($_POST['listType'] == 'user'){
			$sql = "SELECT * FROM titles
					LEFT JOIN users_titles ON titles.id = users_titles.title_id
					WHERE users_titles.user_id = ?
					AND release_date >= ?";
			$query = $conn->prepare($sql);
			$query->bind_param('is', $_POST['currentUserID'], $today);
		} else if($_POST['listType'] == 'released'){
			$sql = "SELECT * FROM titles
					LEFT JOIN users_titles ON titles.id = users_titles.title_id
					WHERE users_titles.user_id = ?
					AND release_date < ?";
			$query = $conn->prepare($sql);
			$query->bind_param('is', $_POST['currentUserID'], $today);
		}
	}
	
// Execute query.
	$query->execute();
	$rowid = $query->affected_rows;
	$result = $query->get_result();

// Format and return results.
	$data = [];
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$data[] = $row;
	}
		
	$returnArray = new stdClass();
	$returnArray->data = $data;
	$response = json_encode($returnArray);
	$query = null;
	echo $response;
?>