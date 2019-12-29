<?php

	$path = "/Photos/" . $_GET['path'];

	//require_once "../../dropbox-sdk/Dropbox/autoload.php";
	require_once "../../dropbox/access.php";

/*	use \Dropbox as dbx;

	$appInfo = dbx\AppInfo::loadFromJsonFile("../../dropbox/app-info.json");
	$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

	$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");

	list($metadata, $data) = $dbxClient->getThumbnail($path, "jpeg", "m");
*/

	// The data to send to the API
	//$postData = array();

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