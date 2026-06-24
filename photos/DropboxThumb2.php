<?php

	$path = "/Photos/" . $_GET['path'];

	require_once($_SERVER['DOCUMENT_ROOT'] . "/www/dropbox/access.php");
//	require_once($_SERVER['DOCUMENT_ROOT'] . "../../dropbox/access.php");

	// Setup cURL
	$ch = curl_init('https://content.dropboxapi.com/2/files/get_thumbnail');
	curl_setopt_array($ch, array(
	    CURLOPT_POST => TRUE,
	    CURLOPT_RETURNTRANSFER => TRUE,
	    CURLOPT_HTTPHEADER => array(
	        'Authorization: Bearer ' . $accessToken,
	        'Dropbox-API-Arg: {"path":"'. $path .'","format":{".tag":"jpeg"},"size":{".tag":"w640h480"}}',
	        'Dropbox-API-Select-User: ',
	        'Content-Type: text/plain'
	    ),
	));

	// Send the request
	$response = curl_exec($ch);

	// Check for errors
	if($response === FALSE){
	    die(curl_error($ch));
	}

//	echo($response);


	header("Content-Type: image/jpeg");
	imagejpeg(imagecreatefromstring($response));

?>