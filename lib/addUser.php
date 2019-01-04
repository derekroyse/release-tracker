<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

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

	$sql = "INSERT INTO users(username, email, password)
		VALUES ( ?, ?, ? )";

	$query = $conn->prepare($sql);

	$query->bind_param('sss', $_POST['username'], $_POST['email'], $_POST['password']);

	$query->execute();
	$rowid = $query->affected_rows;
	$query = null;

	if(!$rowid){
		echo 'Error: Could not add User.';
	} else {
		echo 'User added successfully!';
	}
?>