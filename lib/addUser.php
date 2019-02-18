<?php
	// Report errors.
	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');

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

	// Build and execute query.	
	$sql = "INSERT INTO users(username, email, password)
		VALUES ( ?, ?, ? )";
	$query = $conn->prepare($sql);
	$rows = null;
	if (isset($_POST['password']) && isset($_POST['username']) && isset($_POST['email'])){
		$hashed_password = crypt($_POST['password'], $hash_key);

		$query->bind_param('sss', $_POST['username'], $_POST['email'], $hashed_password);

		$query->execute();
		$rows = $query->affected_rows;
		$query = null;
	}

	// Return results for alert box.
	if(!$rows){
		echo 'An Error occured: Could not add user.';
	} else if ($rows == -1){
		echo 'Registration failed. Email already registered.';
	} else {
		echo 'User added successfully!';
	}
?>