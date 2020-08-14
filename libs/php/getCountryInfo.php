<?php

	// mircrotime(true) is time in ms from start of internet epoch. true means float not string
	$executionStartTime = microtime(true) / 1000;

	// $_REQUEST variable checks both post and get for keys
	$url='http://api.geonames.org/countryInfoJSON?formatted=true&lang=' . $_REQUEST['lang'] . '&country=' . $_REQUEST['country'] . '&username=flightltd&style=full';

	// NOTE: NEED TO ENABLE CURL ON PHP 
	// sudo apt install libapache2-mod-php php-curl
	// sudo service restart apache2
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);

	$result=curl_exec($ch);

	curl_close($ch);

	$decode = json_decode($result,true);	
	$endtime = microtime(true) / 1000;
	
	$output['status']['code'] = "200";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "mission saved";
	$output['status']['returnedIn'] = ($endtime - $executionStartTime);
	$output['data'] = $decode['geonames'];
	
	header('Content-Type: application/json; charset=UTF-8');

	echo json_encode($output); 

?>
