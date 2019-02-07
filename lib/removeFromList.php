<?php
	// Report errors while testing.
		// TODO: Remove this.
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

	$sql = "DELETE FROM users_titles
		WHERE user_id = ?
		AND title_id = ?";

	$query = $conn->prepare($sql);
	$query->bind_param('is', $user_id, $title_id);
	$rows_added = 0;

	foreach ($_POST['selectedArray'] as $title_id) {	  
		$user_id = $_POST['currentUserID'];
		$title_id = $title_id;
		$query->execute();
		$rowid = $query->affected_rows;
		$rows_added++;
	}		

	$query = null;

	if(!$rowid){
		echo 'Error: Could not add titles.';
	} else {
		echo 'Successfully removed ' . $rows_added . ' title(s)!';
	}
?>