<?php
	// Report errors while testing.
		// error_reporting(E_ALL);
		// ini_set('display_errors', '1');

	// Master array that holds all media types
		$master_array = array();  

	// Get Movie API Data
		$page_num = 1;
		while ($page_num < 51){
			$curl = curl_init();
			$today = date("Y-m-d");
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.themoviedb.org/3/discover/movie?api_key=4bfcf1281ed7338461685e64a5cc0f3c&language=en-US&region=US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&page=" . $page_num . "&primary_release_date.gte=2019-02-08",
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
													'release_date' => $movie -> release_date . ' 00:00:00',
													'release_accuracy' => 5,
													'type' => 'Movie',
													'platform' => 'Theatrical');
				array_push($master_array, $new_array);
			}
			$page_num++;
		}

		$err = curl_error($curl);
		curl_close($curl);

	// // Get VG API Data
		$minYear = date("Y");
		$maxYear = (int)$minYear;
		$maxYear = (string)$maxYear + 5;
		$offset = 0;
		while ($offset < 701){
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

			$vg_data = curl_exec($curl);
			$vg_decoded = json_decode($vg_data);

			// Add each title to master array.
			foreach($vg_decoded-> results as $game){	
				// For each release:					
				//if (!isset($game -> original_release_date)) {
					if (isset($game -> release_date)){
						$release_date = $game -> release_date -> date . ' 00:00:00';
						$release_accuracy = 5;
					} else if (isset($game -> expected_release_day)){
							$release_date = $game -> expected_release_year . '-' . $game -> expected_release_month . '-' .  $game -> expected_release_day . ' 00:00:00';
							$release_accuracy = 5;
					} else if (isset($game -> expected_release_month)){
							$release_date  = $game -> expected_release_year . '-' . $game -> expected_release_month . '-31 23:59:57';
							$release_accuracy = 4;
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
					} else if (isset($game -> expected_release_year)){
						$release_date   = $game -> expected_release_year . '-12-31 23:59:59';
						$release_accuracy = 2;
					} else {
						$release_date = '2099-12-31 23:59:59';
						$release_accuracy = 1;
					}
				//} else {
				//	$release_accuracy = 0;
				//}

				$platformArray = array(
		    	"PlayStation 4"  => 'PS4',
		    	"Xbox One" => "Xbox",
		    	"Nintendo Switch" => "Switch",
		    	"iPhone" => "iPhone",
		    	"iPad" => "iPad",
		    	"Wii U" => "WiiU",
		    	"Nintendo 3DS" => "3DS",
		    	"Nintendo 3DS eShop" => "3DS",
		    	"PlayStation Vita" => "Vita",
		    	"PlayStation Network (Vita)" => "Vita(PSN)",
		    	"Mac" => "Mac",
		    	"PC" => "PC",
		    	"Browser" => "Browser",
		    	"Android" => "Android",
		    	"Linux" => "Linux",
		    	"Xbox 360 Games Store" => "360",
		    	"Windows Phone" => "WindowsPhone",
		    	"Atari 2600" => "2600",
		    	"Arcade" => "Arcade",
		    	"New Nintendo 3DS"  => "3DS",
		    	"PlayStation Network (PS3)" => "PS3(PSN)",
		    	"Amazon Fire TV" => "FireTV",
		    	"Nintendo Entertainment System" => "NES"
				);				

				$platforms = '';
				if (isset($game -> platforms)){	
					foreach($game -> platforms as $platform){
						$platforms .= $platformArray[$platform -> name] . ' ';
					}
				} else {
					$platforms = 'TBA';
				}

				if ($release_accuracy > 0) {
					$new_array = array('id' => 'vg-' . $game -> id,
													'title' => $game -> name,
													'release_date' => $release_date,
													'release_accuracy' => $release_accuracy,
													'type' => 'Game',
													'platform' => $platforms);
					array_push($master_array, $new_array);
				}
			}
			$offset += 100;
		}

		$err = curl_error($curl);
		curl_close($curl);

	//Return data (testing)...
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
		
		$query = null;
		
		// Email results.
		// $message = $rows_added . ' titles added successfully @' . date("D M d, Y G:i");
		// $message = wordwrap($message, 70, "\r\n");
		// mail('derekroyse@gmail.com', 'Release Tracker - Nightly Update', $message);

		echo $rows_added . ' titles added successfully!';

		echo "<pre>";
    	print_r($master_array);
    	echo "</pre>";

?>