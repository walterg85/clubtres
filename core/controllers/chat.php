<?php
	session_start();
	
	require_once '../models/chat.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		// se valida si el token aun esta activo y valido, para impedir consultas no deseadas
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']->token);

		if($is_jwt_valid){
			// Instanciamos el modelo
			$chatModel = new Chatmodel();

			// Validamos tipo de peticion
			if($_POST['_method'] == 'POST'){
				// Se crea una matriz para recoger los parametros enviados desde form
				$data = array(
					'message' 	=> $_POST['message'],
					'destiny' 	=> $_POST['destiny'],
					'origin' 	=> $_SESSION['authData']->id
				);

				// Se ejecuta el metodo para crear o actualizar el chat
				$success = $chatModel->insertUpdateChat($data, $_POST['chatId']);

				// Se imprime la respuesta: =0 no satiscatorio, >0 satisfactorio
				header('HTTP/1.1 200 Ok');				
				exit($success);
			} else if($_POST['_method'] == '_Getlog'){
				// Se crea una matriz para recoger los parametros enviados desde form
				$data = array(
					'destiny' 	=> $_POST['destiny'],
					'origin' 	=> $_SESSION['authData']->id
				);

				// Se ejecuta el metodo para buscar el log del chat seleccionado
				$response = array(
					'codeResponse' 	=> 200,
					'log' 			=> $chatModel->loadChatLog($data, $_POST['chatId'])
				);

				// envia la respuesta al front
				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				exit( json_encode($response) );
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