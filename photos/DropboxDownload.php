<?php

	$path = $_GET['path'];

	require_once "dropbox/access.php";

  	$ch = curl_init('https://content.dropboxapi.com/2/files/download');
	curl_setopt_array($ch, array(
	    CURLOPT_POST => TRUE,
	    CURLOPT_RETURNTRANSFER => TRUE,
	    CURLOPT_HTTPHEADER => array(
	        'Authorization: Bearer ' . $accessToken,
	        'Dropbox-API-Arg: {"path":"'. $path .'"}',
	        'Dropbox-API-Select-User: ',
	        'Content-Type: text/plain'
	    )	));

	// Send the request
	$response = curl_exec($ch);

	// Check for errors
	if($response === FALSE){
	    die(curl_error($ch));
	}

	print($response);

//	header("Content-Type: image/jpeg");
//	imagejpeg(imagecreatefromstring($response));

?>