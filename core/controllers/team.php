<?php
	session_start();
	
	require_once '../models/team.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']->token);

		if($is_jwt_valid){
			// parse_str(file_get_contents("php://input"), $put_vars);
			if($_POST['_method'] == 'POST'){

				$data = array(
					'name' => $_POST['inputName'],
					'user_id' => $_SESSION['authData']->id,
					'idTeam' => $_POST['idTeam'],
					'chkActive' => $_POST['chkActive']
				);

				$teamModel = new Teamsmodel();
				$tmpResponse = $teamModel->createTeam($data);

				if($tmpResponse[0]){
					$teamId = $tmpResponse[1];

					if (!empty($_FILES['imageteam'])){
						$filename = $_FILES['imageteam']['name'];
						$tempname = $_FILES['imageteam']['tmp_name'];    
						$folder   = "assets/img/teams/{$teamId}";

						unlink("../../{$folder}/{$filename}"); 
	          
	          			mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);          
						if (move_uploaded_file($tempname, "../../{$folder}/{$filename}"))
							$teamModel->updateImage($teamId, "{$folder}/{$filename}");
					}

					$response = array(
						'codeResponse' => 200,
						'id' => $teamId,
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
			}else if($_POST['_method'] == 'GET'){
				$teamsModel = new Teamsmodel();
				$tmpResponse = $teamsModel->getTeam( $_SESSION['authData']->id );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'DELETE'){
				$teamsModel = new Teamsmodel();
				$teamsModel->deleteTeam( $_POST['idTeam'] );

				header('HTTP/1.1 200 Ok');				
				exit();
			}else if($_POST['_method'] == 'PUT'){

				$data = array(
					'uorigin_id' => $_SESSION['authData']->id,
					'udestiny_id' => $_POST['idUser'],
					'event' => 'The '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .' user invites you to be part of the '. $_POST["nameTeam"] .' team, respond soon, await your response.',
					'event_type' => 0,
					'event_id' => $_POST['idTeam']
				);

				$teamModel = new Teamsmodel();
				$tmpResponse = $teamModel->sendInvitation($data);

				if($tmpResponse[0]){
					$response = array(
						'codeResponse' => 200,
						'message' => 'Invitation sent, wait for the user to respond.'
					);
				}else{
					$response = array(
						'codeResponse' => 0,
						'message' => 'Invitation not sent'
					);
				}

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			} else if($_POST['_method'] == 'getChilds'){
				$teamsModel = new Teamsmodel();
				$tmpResponse = $teamsModel->getChilds( $_POST['teamId'] );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			} else if($_POST['_method'] == 'deleteChild'){
				$teamsModel = new Teamsmodel();
				$teamsModel->deleteChild( $_POST['idRegistro'] );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'User removed from team'
				);

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

	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		$teamsModel = new Teamsmodel();
		$tmpResponse = $teamsModel->getTeamId($_GET['teamId']);

		if($tmpResponse)
			$tmpResponse->id = str_pad($tmpResponse->id, 5, '0', STR_PAD_LEFT);			

		$response = array(
			'codeResponse' => 200,
			'data' => $tmpResponse
		);

		header('HTTP/1.1 200 Ok');
		header("Content-Type: application/json; charset=UTF-8");
		
		exit(json_encode($response));
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");

	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);

	exit( json_encode($response) );