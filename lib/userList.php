<?php
		// Return data (testing)...
		// $returnArray = new stdClass();
		// $returnArray->data = $master_array;
		// $response = json_encode($returnArray);

		// $err = curl_error($curl);
		// curl_close($curl);

		// if ($err) {
		// 	echo "cURL Error #:" . $err;
		// } else {
		// 	echo $response;
		// }

		//... or save data to DB (production)
		// In the variables section below, replace user and password with your own MySQL credentials as created on your server
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

		$sql = "SELECT * FROM titles
				LEFT JOIN users_titles ON titles.id = users_titles.title_id
				WHERE users_titles.user_id = ?";

		$query = $conn->prepare($sql);
		$query->bind_param('i', $_POST['currentUserID']);
		$query->execute();
		$rowid = $query->affected_rows;
		$result = $query->get_result();

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