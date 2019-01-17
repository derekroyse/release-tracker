<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $master_array = array();  

    // Get Movie API Data
    // Todo: Handle multiple pages
    // Todo: Add Type

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.themoviedb.org/3/discover/movie?api_key=4bfcf1281ed7338461685e64a5cc0f3c&language=en-US&region=US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&page=1&primary_release_date.gte=2019-01-14",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "{}",
    ));

    $response_json = json_decode(curl_exec($curl))->results;
    
    foreach($response_json as $movie){
      $new_array = array('id' => 'mv-' . $movie -> id,
                        'title' => $movie -> title,
                        'release_date' => $movie -> release_date,
                        'type' => 'MV');
      array_push($master_array, $new_array);
    }

    $returnArray = new stdClass();
    $returnArray->data =  $response_json;
    //array_push($returnArray->data, $response_json);

    $response = json_encode($returnArray);

    // $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // Get VG API Data
    // Todo: Handle multiple pages
    // Todo: Add Type
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api-v3.igdb.com/games?fields=id,name,platforms,release_dates.*&limit=50&filter[release_dates.date][gt]=" . time(),
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

    // Error handling due to SQL exception on API side.
    // TODO: Revisit and find permanent fix.
    $vg_fixed = substr(curl_exec($curl), 0, -97);
    $vg_test = json_decode($vg_fixed);

    foreach($vg_test as $vg){
      if (isset($vg -> release_dates[0] -> date) ){
        $new_array = array('id' => 'vg-' . $vg -> id,
                        'title' => $vg -> name,
                        'release_date' => date('m/d/Y', $vg -> release_dates[0] -> date),
                        'type' => 'VG');
        array_push($master_array, $new_array);
      }
    }
    echo "<pre>";
    print_r($master_array);
    echo "</pre>";

    $returnArray = new stdClass();
    $returnArray->data = $vg_test;

    $response = json_encode($returnArray);

    // $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
    }    
?>