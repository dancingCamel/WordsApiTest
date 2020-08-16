<?php
	// ini_set('display_errors', 'On');
	// error_reporting(E_ALL);
	require("./env.php");
	$executionStartTime = microtime(true) / 1000;

	$ch = curl_init();
	$apiKey = getenv("APIKEY");

	$url = "https://wordsapiv1.p.rapidapi.com/words/" . $_REQUEST['word'];
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"x-rapidapi-host: wordsapiv1.p.rapidapi.com",
			"x-rapidapi-key: ".$apiKey
		),
	));

	$result= curl_exec($ch);
	$err = curl_error($ch);

	curl_close($ch);

	$decode = json_decode($result,true);	
	$endtime = microtime(true) / 1000;

	if ($err) {
		$output['status']['name'] = "error";
		$output['status']['code'] = "500";
		$output['message'] = $err;
		
	} 
	else {
		if (array_key_exists('success', $decode)){
			$output['status']['code'] = "404";
			$output['status']['name'] = "error";
			$output['message'] = $decode['message'];
		
		}
		else {
			$partsOfSpeech = array("adverb", "adjective", "noun", "pronoun", "verb");
			$temp = array();

			foreach($partsOfSpeech as $part){
				foreach($decode['results'] as $entry){
					if ($entry['partOfSpeech'] == $part) {
						if (!array_key_exists($part, $temp)){
							$temp[$part] = array();
						}
						array_push($temp[$part], $entry['definition']);
					}
				}
				
			}

			$output['status']['code'] = "200";
			$output['status']['name'] = "ok";
			$output['status']['description'] = "success";
			$output['status']['returnedIn'] = ($endtime - $executionStartTime);
			$output['word'] = $decode['word'];
			$output['data'] = $temp;
			$output['key'] = $apiKey;

		}
		
	}
	header('Content-Type: application/json; charset=UTF-8');
	echo json_encode($output); 