<?php
	// Report errors while testing.
		// TODO: Remove this.
		error_reporting(E_ALL);
		ini_set('display_errors', '1');

		// Master array that holds all media types
		$master_array = array();  

	// Get Movie API Data
		$page_num = 1;
		while ($page_num < 51){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.themoviedb.org/3/discover/movie?api_key=4bfcf1281ed7338461685e64a5cc0f3c&language=en-US&region=US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&page=" . $page_num . "&primary_release_date.gte=2019-01-14",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "{}",
			));

			$response = curl_exec($curl);
			if (isset(json_decode($response)->results)){
				$response_json = json_decode($response)->results;
			} else {
				break;
			}
			
			// Add each title to master array.
			foreach($response_json as $movie){
				$new_array = array('id' => 'mv-' . $movie -> id,
													'title' => $movie -> title,
													'release_date' => $movie -> release_date,
													'type' => 'Movie (Theatrical Release)',
													'platform' => '');
				array_push($master_array, $new_array);
			}
			$page_num++;
		}

		$err = curl_error($curl);
		curl_close($curl);

	// Get VG API Data
		$offset = 0;
		while ($offset < 2501){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api-v3.igdb.com/games?fields=id,name,platforms,release_dates.*&limit=50&filter[release_dates.date][gt]=" . time() . "&offset=" . $offset,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "",
				CURLOPT_HTTPHEADER => array(
					"Accept: application/json",
					"Postman-Token: d2c754b0-78a4-452e-9ac7-e18e03bed008",
					"cache-control: no-cache",
					"user-key: c219b1070d2cdfcef8a9cf7bebeca311"
				),
			));

			$vg_data = curl_exec($curl);
			$vg_decoded = json_decode($vg_data);

			// Add each title to master array.
			foreach($vg_decoded as $vg){
				$counter = 1;				
				// For each release date:
				if (isset($vg -> release_dates)){
					foreach ($vg -> release_dates as $date ){
						
						if (isset($date -> date)){
							$release_date = date('Y-m-d', $date -> date);
						} else if (isset($date-> human)){
							$release_date = $date -> human;
						} else {
							$release_date = 'TBD';
						}

						$new_array = array('id' => 'vg-' . $vg -> id . '-' . $counter,
														'title' => $vg -> name,
														'release_date' => $release_date,
														'type' => 'Video Game',
														'platform' => $date -> platform);
						array_push($master_array, $new_array);
						$counter++;
					}
				}
			}

			$offset += 50;
		}

		$err = curl_error($curl);
		curl_close($curl);

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

		$sql = "INSERT INTO titles(id, title, release_date, platform, type)
			VALUES ( ?, ?, ?, ?, ? )
			ON DUPLICATE KEY UPDATE 
				title = VALUES(title),
				release_date = VALUES(release_date)";

		$query = $conn->prepare($sql);

		$query->bind_param('sssss', $id, $title, $release_date, $platform, $type);

		$rows_added = 0;

		foreach ($master_array as $row) {	  
				$id = $row['id'];
				$title = $row['title'];
				$release_date = $row['release_date'];
				$platform = $row['platform'];
				$type = $row['type'];
				$query->execute();
				$rowid = $query->affected_rows;
				$rows_added++;
		}
		
		$query = null;

		if(!$rowid){
			echo 'Error: Could not add titles.';
		} else {
			echo $rows_added . ' titles added successfully!';
		}	

		echo "<pre>";
    print_r($master_array);
    echo "</pre>";

?>