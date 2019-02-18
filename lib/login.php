<?php	
	// Report errors while testing.
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();

	// Get Heroku values on production.
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	$hash_key = getenv("HASH_KEY");

	// Test for Heroku values. If they don't exist, use dev DB.
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

	// Use dummy hash on production.
	if (!$hash_key){
		$hash_key = 'test';
	}

	echo $hash_key;

	// Build and execute query.
	$sql = "SELECT * FROM users
		WHERE email = ?
		AND password = ?";
	$query = $conn->prepare($sql);
	$hashed_password = crypt($_POST['password'], $hash_key);
	$query->bind_param('ss', $_POST['email'], $hashed_password);
	$query->execute();

	$rows = null;
	$result = $query->get_result();
	$rows = $result->num_rows;	
	$query = null;

	// Return results.
	if($rows < 1){
		echo 'false';
	} else {
		$row = $result->fetch_array(MYSQLI_NUM);
		// Set Session variables for user session
		$_SESSION['userID'] = $row[0];
		$_SESSION['logged_in'] = 1;
		$_SESSION['username'] = $row[1];
		$_SESSION['email'] = $row[2];	
		echo 'true';
	}	
?>