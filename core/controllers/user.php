<?php
	session_start();
	
	require_once '../models/user.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		// parse_str(file_get_contents("php://input"), $put_vars);
		$put_vars = ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);

		if($put_vars['_method'] == 'POST'){
			$userModel 				= new Usersmodel();
			$put_vars['password'] 	= encryptPass( $put_vars['password'] );

			$tmpResponse = $userModel->createUser($put_vars);

			if($tmpResponse[0]){
				$response = array(
					'codeResponse' 	=> 200,
					'id' 			=> $tmpResponse[1],
					'message' 		=> 'User account successfully registered.'
				);
			}else{
				$message = '';
				switch ($tmpResponse[1]) {
					case '23000':
						$message = 'Email is registered, try another email.';
						break;				
					default:
						$message = 'general error in the database.';
						break;
				}

				$response = array(
					'codeResponse' 	=> 0,
					'message' 		=> $message
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'VALIDATE'){
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->login($put_vars['email']);

			$response = array(
				'codeResponse' 	=> 0,
				'message' 		=> 'Username or password incorrect'
			);

			if($tmpResponse){
				if (password_verify($put_vars['password'], $tmpResponse->password)){
					unset($tmpResponse->password);

					$headers = array('alg' => 'HS256', 'typ' => 'JWT');
					$payload = array('username' => $put_vars['email'], 'exp' => (time() + 86400));
					$tmpResponse->token = generate_jwt($headers, $payload);

					$response = array(
						'codeResponse' 	=> 200,
						'data' 			=> $tmpResponse,
						'message' 		=> 'Welcome'
					);

					// Primero se setea la foto por defecto del usuario, se valida si existe el archivo de fotografia con su ID, si existe se cambia la ruta de la foto.
					$fotografia = "assets/img/user/default.jpg";
					if( is_file(dirname(__FILE__, 3) . "/assets/img/user/". $tmpResponse->id . ".jpg" ) )
						$fotografia = "assets/img/user/". $tmpResponse->id . ".jpg";

					$_SESSION['login'] 				= TRUE;
					$_SESSION['authData'] 			= $tmpResponse;
					$_SESSION['authData']->image 	= $fotografia;
				}
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'GET') {
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->getUserId($put_vars['userId']);

			if($tmpResponse){
				// Primero se setea la foto por defecto del usuario, se valida si existe el archivo de fotografia con su ID, si existe se cambia la ruta de la foto.
				$fotografia = "assets/img/user/default.jpg";
				if( is_file(dirname(__FILE__, 3) . "/assets/img/user/". $tmpResponse->id . ".jpg" ) )
					$fotografia = "assets/img/user/". $tmpResponse->id . ".jpg";

				$tmpResponse->image = $fotografia;
				$tmpResponse->id 	= str_pad($tmpResponse->id, 5, '0', STR_PAD_LEFT);
			}

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $tmpResponse
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'countInvitation') {
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->countInvitation( $_SESSION['authData']->id );	

			$response = array(
				'codeResponse' 	=> 200,
				'count' 		=> $tmpResponse->notifications
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'getInvitation') {
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->getInvitation( $_SESSION['authData']->id );		

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $tmpResponse
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'updateInvitation'){
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->updateInvitation( $put_vars, $_SESSION['authData']->id );		

			$response = array(
				'codeResponse' 	=> 200,
				'message' 		=> 'Updated invitation'
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'search'){
			$usersModel 	= new Usersmodel();
			$data 			= $usersModel->search($put_vars['strQuery']);

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $data,
				'message' 		=> 'Ok'
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'authData'){
			$_SESSION['authData']->avatar = null;
			$fileStr = dirname(__FILE__, 3) . "/assets/img/user/" . $_SESSION['authData']->id .".jpg";
			if (file_exists($fileStr))
				$_SESSION['authData']->avatar = "assets/img/user/" . $_SESSION['authData']->id .".jpg";

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($_SESSION['authData']));
		} else if($put_vars['_method'] == 'updateData'){
			$usersModel 	= new Usersmodel();
			$usData = array(
				'userId' 	=> $put_vars['userId'],
				'name' 		=> $put_vars['inputName'],
				'lastName'	=> $put_vars['inputLastName']
			);
			
			$usersModel->updateData($usData);

			$folder = "assets/img/user";
			if (!empty($_FILES['userPhoto'])){
				$filename = $_FILES['userPhoto']['name'];
				$tempname = $_FILES['userPhoto']['tmp_name'];
				       
				move_uploaded_file($tempname, "../../{$folder}/{$filename}");
				$_SESSION['authData']->image = $folder . '/' . $put_vars['userId'] . '.jpg';
			}

			$_SESSION['authData']->name 		= $put_vars['inputName'];
			$_SESSION['authData']->last_name 	= $put_vars['inputLastName'];

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $_SESSION['authData']->image
			);			

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'showInvitations') {
			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->showInvitations( $_SESSION['authData']->id );		

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $tmpResponse
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'inviteFriend'){
			$data = array(
				'uorigin_id' 	=> $_SESSION['authData']->id,
				'udestiny_id' 	=> $put_vars['userId'],
				'event' 		=> 'Hello, '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .' has sent you a friend request, respond soon, await your response.',
				'event_type' 	=> '-1',
				'event_id' 		=> 0
			);

			$userModel 		= new Usersmodel();
			$tmpResponse 	= $userModel->inviteFriend($data);

			if($tmpResponse[0]){
				$response = array(
					'codeResponse' => 200
				);
			}else{
				$response = array(
					'codeResponse' 	=> 0,
					'message' 		=> $tmpResponse[1]
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'reviewFriendRequest'){
			if( $put_vars['userId'] == $_SESSION['authData']->id){
				$response  = array(
					'codeResponse' 	=> 200,
					'status'		=> 4
				);
			} else {
				$data = array(
					'uorigin_id' 	=> $_SESSION['authData']->id,
					'udestiny_id' 	=> $put_vars['userId'],
					'event_type' 	=> '-1'
				);

				$userModel = new Usersmodel();
				$response  = array(
					'codeResponse' 	=> 200,
					'status'		=> $userModel->reviewFriendRequest($data)
				);
			}
			

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'getFriends'){
			$userModel = new Usersmodel();
			$tmpData   = $userModel->getFriends($_SESSION['authData']->id);

			$data = [];
			foreach ($tmpData as $key => $value) {
				$value['avatar'] = 'assets/img/user/default.jpg';

				if( is_file(dirname(__FILE__, 3) . '/assets/img/user/'. $value['friend_id'] .'.jpg' ) )
					$value['avatar'] = 'assets/img/user/'. $value['friend_id'] .'.jpg';

				$data[] = $value;
			}

			$response = array(
				'codeResponse' 	=> 200,
				'data'			=> $data
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'getPeople'){
			$userModel = new Usersmodel();
			
			$response = array(
				'codeResponse' 	=> 200,
				'data'			=> $userModel->getPeople()
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'deleteFriend'){
			$data = array(
				'user_id' 	=> $_SESSION['authData']->id,
				'friend_id'	=> $put_vars['friend_id']
			);
			
			$userModel = new Usersmodel();
			$userModel->deleteFriend( $data );

			$response = array(
				'codeResponse' 	=> 200
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_RestorePassword'){
			$userModel = new Usersmodel();
			$data = $userModel->getTorestore($put_vars['email']);

			// Generar token para recuperacion de contraseña
			// Al tiempo actual se le suman 900 Segundos (15 min), para que al paso de ello no sea valido el token
			$headers = array('alg' => 'HS256', 'typ' => 'JWT');
			$payload = array('usEmail' => $put_vars['email'], 'exp' => (time() + 10));
			$token = generate_jwt($headers, $payload);
			//==============================================

			if($data->existe > 0){
				//Se bypasea por que no tengo activo el mailserver, se debe activar ya en hosting
				// $to      = $put_vars['email'];
				//    $subject = 'Restore de password';
				//    $message = '
				//    	Recover your account and reset your password in the following link: http://localhost/clubtres/user/recoverypassword.php?restore='. $data->id .'&token='. $token.'
				//    ';
				//    $headers = 'From: webmaster@clubtres.com'       . "\r\n" .
				//               'Reply-To: webmaster@clubtres.com' . "\r\n" .
				//               'X-Mailer: PHP/' . phpversion();
				//    mail($to, $subject, $message, $headers);

				// Esto es solo para completar el proceso sin interuppciones, se debe borrar
				$data->url = 'http://localhost/clubtres/user/recoverypassword.php?restore='. $data->id .'&token='. $token;
			}
			
			$response = array(
				'codeResponse' 	=> 200,
				'data'			=> $data
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'updatePassword'){
			$bearer_token 	= get_bearer_token();
			$is_jwt_valid 	= is_jwt_valid($bearer_token);

			if($is_jwt_valid){
				$usersModel 	= new Usersmodel();
				$newPassword 	= encryptPass($put_vars['newPassword']);

				$usData = array(
					'userId' 	=> $put_vars['userId'],
					'password'	=> $newPassword
				);
				
				$usersModel->updatePassword($usData);

				$response = array(
					'codeResponse' 	=> 200
				);			

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				exit(json_encode($response));
			} else{
				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");

				$response = array(
					'codeResponse' => 401,
					'message' => 'Unauthorized'
				);

				exit( json_encode($response) );
			}
		} else if($put_vars['_method'] == 'updatePasswordConfig'){
			$usersModel 	= new Usersmodel();
			$newPassword 	= encryptPass($put_vars['newPassword']);

			$usData = array(
				'userId' 	=> $put_vars['userId'],
				'password'	=> $newPassword
			);
			
			$usersModel->updatePassword($usData);

			$response = array(
				'codeResponse' 	=> 200
			);			

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");
			exit(json_encode($response));			
		}
	}

	function encryptPass($strPassword) {
		$options = [
		    'cost' => 12
		];

		return password_hash($strPassword, PASSWORD_BCRYPT, $options);
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );