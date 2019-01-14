<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');     

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

    $returnArray = new stdClass();
    $returnArray->data =  $response_json;
    //array_push($returnArray->data, $response_json);

    $response = json_encode($returnArray);

    // $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }    
?>