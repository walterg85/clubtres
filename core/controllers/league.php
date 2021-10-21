<?php
	session_start();
	
	require_once '../models/league.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']->token);

		if($is_jwt_valid){
			// parse_str(file_get_contents("php://input"), $put_vars);
			if($_POST['_method'] == 'POST'){

				$data = array(
					'name' => $_POST['inputNameL'],
					'sport' => $_POST['sportType'],
					'idLeague' => $_POST['idLeague'],
					'chkActive' => $_POST['chkActive'],
					'user_id' => $_SESSION['authData']->id
				);

				$leagueModel = new Leaguemodel();
				$tmpResponse = $leagueModel->createLeague($data);

				if($tmpResponse[0]){
					$leagueId = $tmpResponse[1];

					if (!empty($_FILES['imageleague'])){
						$filename = $_FILES['imageleague']['name'];
						$tempname = $_FILES['imageleague']['tmp_name'];    
						$folder   = "assets/img/leagues/{$leagueId}";

						if(file_exists("../../{$folder}/{$filename}"))
							unlink("../../{$folder}/{$filename}");
	          
	          			mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);          
						if (move_uploaded_file($tempname, "../../{$folder}/{$filename}"))
							$leagueModel->updateImage($leagueId, "{$folder}/{$filename}");
					}

					$response = array(
						'codeResponse' => 200,
						'id' => $leagueId,
						'message' => 'League successfully registered.'
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
				$leagueModel = new Leaguemodel();
				$tmpResponse = $leagueModel->getLeague( $_SESSION['authData']->id );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'DELETE'){
				$leagueModel = new Leaguemodel();
				$leagueModel->deleteLeague( $_POST['idLeague'] );

				header('HTTP/1.1 200 Ok');				
				exit();
			}else if($_POST['_method'] == 'PUT'){
				require_once '../models/team.php';

				$data = array(
					'uorigin_id' => $_SESSION['authData']->id,
					'udestiny_id' => $_POST['idUser'],
					'event' => 'The '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .' user invites you to be part of the '. $_POST["nameLeague"] .' league, respond soon, await your response.',
					'event_type' => $_POST['idTeam'],
					'event_id' => $_POST['idLeague']
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
						'message' => $tmpResponse[1]
					);
				}

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'getChilds'){
				$leagueModel = new Leaguemodel();
				$tmpResponse = $leagueModel->getChilds( $_POST['leagueId'] );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'disableChild'){
				$leagueModel = new Leaguemodel();
				$leagueModel->disableChild( $_POST );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'Team suspended for the league'
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'enableChild'){
				$leagueModel = new Leaguemodel();
				$leagueModel->enableChild( $_POST );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'Team reactivated'
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'deleteChild'){
				$leagueModel = new Leaguemodel();
				$leagueModel->deleteChild( $_POST );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'Team eliminated from league'
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'disableMemberChild'){
				$leagueModel = new Leaguemodel();
				$leagueModel->disableMemberChild( $_POST['idRegistro'] );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'Suspended member'
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'enableMemberChild'){
				$leagueModel = new Leaguemodel();
				$leagueModel->enableMemberChild( $_POST['idRegistro'] );		

				$response = array(
					'codeResponse' => 200,
					'message' => 'Enabled member'
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'addEvent'){
				$leagueModel = new Leaguemodel();
				$tmpResponse = $leagueModel->addEvent($_POST);

				if($tmpResponse[0]){
					$response = array(
						'codeResponse' => 200,
						'message' => 'Registered event'
					);
				}else{
					$response = array(
						'codeResponse' => 0,
						'message' => 'Unregistered event'
					);
				}

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'getGames'){
				$leagueModel = new Leaguemodel();
				$tmpResponse = $leagueModel->getGames( $_POST['user_id'] );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
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
		$leagueModel = new Leaguemodel();
		$tmpResponse = $leagueModel->getLeagueId($_GET['leagueId']);

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