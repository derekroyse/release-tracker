<?php
	// Report errors while testing.
		error_reporting(E_ALL);
		ini_set('display_errors', '1');

	// Master array that holds all media types.
		$master_array = array();  

	// Get Movie API Data
		// Array to hold movie api results.
		$response_array = array();
		// Loop through maximum size pages.
		$page_num = 1;
		while ($page_num < 51){
			$curl = curl_init();
			$today = date("Y-m-d");
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.themoviedb.org/3/discover/movie?api_key=4bfcf1281ed7338461685e64a5cc0f3c&language=en-US&region=US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&page=" . $page_num . "&primary_release_date.gte=2021-03-25",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				//CURLOPT_POSTFIELDS => "{}",
			));

			// Format and add response if valid results are received.
			$response_json = curl_exec($curl);
			if (isset(json_decode($response_json)->results)){
				$response = json_decode($response_json)->results;
				$response_array = array_merge_recursive($response_array, $response);
			}			

			// Iterate to next page.
			$page_num++;
		}		
		
		// Close curl session.
		curl_close($curl);

		print_r($response_json);

		// Add each title to master array.
		foreach($response_array as $movie){
			$temp_array = array(
				'id' => 'mv-' . $movie -> id,
				'title' => $movie -> title,
				'release_date' => $movie -> release_date . ' 00:00:00',
				'release_accuracy' => 5,
				'type' => 'Movie',
				'platform' => 'Theatrical'
			);
			array_push($master_array, $temp_array);
		}		

	// Get VG API Data
		// Array to hold game api results.
		$response_array = array();
		// Setup date limits for the next 5 years.
		$minYear = date("Y");
		$maxYear = (int)$minYear;
		$maxYear = (string)$maxYear + 5;
		// Loop through maximum size pages.
		$offset = 0;
		while ($offset < 1501){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://www.giantbomb.com/api/games/?api_key=df05ab362e86a6c9d3bf84ce248469398d89478d&offset=" . $offset . "&format=json&filter=expected_release_year:". $minYear . "|" . $maxYear,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "{}",
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
			));			

			// Format and add response if valid results are received.
			$response_json = curl_exec($curl);
			if (isset(json_decode($response_json)->results)){
				$response = json_decode($response_json)->results;
				$response_array = array_merge_recursive($response_array, $response);
			} else {
				echo "Empty result at offset #" . $offset . ' !';
			}

			$offset += 100;
		}

		// Close curl session.
		$err = curl_error($curl);
		curl_close($curl);

		// Add each title to master array.
		foreach($response_array as $game){	
			// For each release:
			// Don't include games with original_release_dates: these are already released.
			if (!isset($game -> original_release_date)) {
				// Concrete released dates.
				if (isset($game -> release_date)){
					$release_date = $game -> release_date -> date . ' 00:00:00';
					$release_accuracy = 5;
				// Estimated release dates.
				} else if (isset($game -> expected_release_day)){
						$release_date = $game -> expected_release_year . '-' . $game -> expected_release_month . '-' .  $game -> expected_release_day . ' 00:00:00';
						$release_accuracy = 5;
				// Estimated release month.
				} else if (isset($game -> expected_release_month)){
						$release_date  = $game -> expected_release_year . '-' . $game -> expected_release_month . '-31 23:59:57';
						$release_accuracy = 4;
				// Estimated release quarter.
				} else if (isset($game -> expected_release_quarter)){
						$release_accuracy = 3;
						switch($game -> expected_release_quarter) {
							case 1:
								$release_date = $game -> expected_release_year . '-3-31 23:59:58';
								break;
							case 2:
								$release_date = $game -> expected_release_year . '-6-30 23:59:58';
								break;
							case 3:
								$release_date = $game -> expected_release_year . '-9-30 23:59:58';
								break;
							case 4:
								$release_date = $game -> expected_release_year . '-12-31 23:59:58';
								break;
						}
				// Estimated release year.
				} else if (isset($game -> expected_release_year)){
					$release_date   = $game -> expected_release_year . '-12-31 23:59:59';
					$release_accuracy = 2;
				// Games with no estimated release window.
				} else {
					$release_date = '2099-12-31 23:59:59';
					$release_accuracy = 1;
				}
			// Do not include games that have already been released.
			} else {
				$release_accuracy = 0;
			}

			// Convert API's verbose platform names to simpler ones for
			// compatibility with the list badges.
			$platformArray = array(
				"Amazon Fire TV" => "FireTV",
				"Android" => "Android",
				"Apple TV" => "AppleTV",
				"Arcade" => "Arcade",				
				"Atari 2600" => "2600",
				"Browser" => "Browser",
				"Evercade" => "Evercade",
				"Google Stadia" => "Stadia",				
				"Intellivision Amico" => "Amico",				
				"iPhone" => "iPhone",
				"iPad" => "iPad",
				"Linux" => "Linux",
				"Mac" => "Mac",
				"New Nintendo 3DS"  => "3DS",
				"Nintendo 3DS" => "3DS",
				"Nintendo 3DS eShop" => "3DS",
				"Nintendo Entertainment System" => "NES",
				"Nintendo Switch" => "Switch",
				"Oculus Quest" => "Oculus",
				"PC" => "PC",
				"PlayStation 4"  => 'PS4',
				"PlayStation 5"  => 'PS5',
				"PlayStation Network (PS3)" => "PS3(PSN)",
				"PlayStation Network (Vita)" => "Vita(PSN)",
				"PlayStation Vita" => "Vita",
				"Xbox" => "Xbox",
				"Xbox 360" => "X360",
				"Xbox 360 Games Store" => "X360",
				"Xbox One" => "XboxOne",
				"Xbox Series X|S" => "Xbox Series X",
				"Wii U" => "WiiU",
				"Windows Phone" => "WindowsPhone"
			);				

			// Build Platforms string.
			$platforms = '';
			if (isset($game -> platforms)){	
				foreach($game -> platforms as $platform){
					$platforms .= $platformArray[$platform -> name] . ' ';
				}
			} else {
				$platforms = 'TBA';
			}

			// Add all games to master array.
			if ($release_accuracy > 0) {
				$temp_array = array(
					'id' => 'vg-' . $game -> id,
					'title' => $game -> name,
					'release_date' => $release_date,
					'release_accuracy' => $release_accuracy,
					'type' => 'Game',
					'platform' => trim($platforms)
				);
				array_push($master_array, $temp_array);
			}
		}

	// Save data to DB.
		// Select between Dev and Production DBs.
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

		// Build SQL statement.
		$sql = "INSERT INTO titles(id, title, release_date, platform, type, release_accuracy)
			VALUES ( ?, ?, ?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE 
				title = VALUES(title),
				release_date = VALUES(release_date),
				release_accuracy = VALUES(release_accuracy),
				type = VALUES(type),
				platform = VALUES(platform)";

		$query = $conn->prepare($sql);

		$query->bind_param('ssssss', $id, $title, $release_date, $platform, $type, $release_accuracy);

		$rows_added = 0;

		// Save all array elements to DB.
		foreach ($master_array as $row) {	  
				$id = $row['id'];
				$title = $row['title'];
				$release_date = $row['release_date'];
				$release_accuracy = $row['release_accuracy'];
				$platform = $row['platform'];
				$type = $row['type'];
				$query->execute();
				$rowid = $query->affected_rows;
				$rows_added++;
		}
		
		// Close query.
		$query = null;
		
		// Email results.
		// TODO: Doesn't work with Heroku, find solution.
		// $message = $rows_added . ' titles added successfully @' . date("D M d, Y G:i");
		// $message = wordwrap($message, 70, "\r\n");
		// mail('derekroyse@gmail.com', 'Release Tracker - Nightly Update', $message);

		// Testing output.
		echo $rows_added . ' titles added successfully!';
		echo "<pre>";
		print_r($master_array);
		echo "</pre>";

?>
