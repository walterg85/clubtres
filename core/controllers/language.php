<?php
	header("Access-Control-Allow-Origin: *");

	$arrContextOptions = array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false
		)
	);

	$json 		= file_get_contents('../../assets/languages.json', false, stream_context_create($arrContextOptions));
	$details 	= json_decode($json, true);			

	header('HTTP/1.1 200 Ok');
	header("Content-Type: application/json; charset=UTF-8");
	exit(json_encode($details));