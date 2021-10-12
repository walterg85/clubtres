<?php
	session_start();
	
	require_once '../models/team.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']['token']);

		if($is_jwt_valid){
			parse_str(file_get_contents("php://input"), $put_vars);

			if($put_vars['_method'] == 'POST'){
				$teamModel = new Teamsmodel();

				print_r($_SESSION['authData']);

				exit();


				$tmpResponse = $teamModel->createTeam($put_vars);

				if($tmpResponse[0]){
					$response = array(
						'codeResponse' => 200,
						'id' => $tmpResponse[1],
						'message' => 'Team successfully registered.'
					);
				}else{
					$response = array(
						'codeResponse' => 0,
						'message' => 'general error in the database.'
					);
				}

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
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