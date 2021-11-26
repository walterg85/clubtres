<?php
	session_start();
	
	require_once '../models/chat.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']->token);

		if($is_jwt_valid){
			// Instanciamos el modelo
			$chatModel = new Chatmodel();

			// Validamos tipo de peticion
			if($_POST['_method'] == 'POST'){
				$data = array(
					'message' => $_POST['message'],
					'destiny' => $_POST['destiny'],
					'origin' => $_SESSION['origin']->id
				);

				$chatModel->registerChat($data, $_POST['chatId']);

				header('HTTP/1.1 200 Ok');				
				exit();
			}
		} else {
			$response = array(
				'codeResponse' => 401,
				'message' => 'Unauthorized'
			);

			header('HTTP/1.1 401 Unauthorized');
			header("Content-Type: application/json; charset=UTF-8");

			exit( json_encode($response) );
		}
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");

	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);

	exit( json_encode($response) );