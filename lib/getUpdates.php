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
													'type' => 'Movie',
													'platform' => 'Theatrical Release');
				array_push($master_array, $new_array);
			}
			$page_num++;
		}

		$err = curl_error($curl);
		curl_close($curl);

	// Get VG API Data
		$year = 2019;		
		$offset = 0;
		while ($offset < 701){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://www.giantbomb.com/api/games/?api_key=df05ab362e86a6c9d3bf84ce248469398d89478d&offset=" . $offset . "&format=json&filter=expected_release_year:". $year . "|2025",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "{}",
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
			));

			$vg_data = curl_exec($curl);
			$vg_decoded = json_decode($vg_data);

			// Add each title to master array.
			foreach($vg_decoded-> results as $game){	
				// For each release:					
				if (isset($game -> release_date)){
					$release_date = $game -> release_date;
				} else if (isset($game -> expected_release_day)){
					$release_date = $game -> expected_release_year . '-' . $game -> expected_release_month . '-' .  $game -> expected_release_day;
				} else if (isset($game -> expected_release_month)){
					$dateObj   = DateTime::createFromFormat('!m', $game -> expected_release_month);
					$monthName = $dateObj->format('F');
					$release_date = $monthName . ' ' .  $game -> expected_release_year;
				} else if (isset($game -> expected_release_quarter)){
					$release_date =  'Q' . $game -> expected_release_quarter . '-' .  $game -> expected_release_year;
				} else if (isset($game -> expected_release_year)){
					$release_date = $game -> expected_release_year;								
				} else {
					$release_date = 'TBD';
				}

				$platforms = '';
				if (isset($game -> platforms)){	
					foreach($game -> platforms as $platform){
						$platforms .= $platform -> name . ' ';
					}
				} else {
					$platforms = 'TBA';
				}

				$new_array = array('id' => 'vg-' . $game -> id,
												'title' => $game -> name,
												'release_date' => $release_date,
												'type' => 'Video Game',
												'platform' => $platforms);
				array_push($master_array, $new_array);
			}
			$offset += 100;
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
				release_date = VALUES(release_date),
				platform = VALUES(platform)";

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