<?php
	session_start();
	
	require_once '../models/business.php';
	require_once '../utils/jwt.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$is_jwt_valid 	= is_jwt_valid($_SESSION['authData']->token);

		if($is_jwt_valid){
			if($_POST['_method'] == 'POST'){

				$data = array(
					'idBusiness' => $_POST['idBusiness'],
					'inputName' => $_POST['inputName'],
					'inputDescription' => $_POST['inputDescription'],
					'inputAddress' => $_POST['inputAddress'],
					'inputNumber' => $_POST['inputNumber'],
					'inputWeb' => $_POST['inputWeb'],
					'chkActive' => $_POST['chkActive'],
					'user_id' => $_SESSION['authData']->id
				);

				$businessModel = new Businessmodel();
				$tmpResponse = $businessModel->createBusineess($data);

				if($tmpResponse[0]){
					$businessId = $tmpResponse[1];

					if (!empty($_FILES['bannerBusiness'])){
						$filename = $_FILES['bannerBusiness']['name'];
						$tempname = $_FILES['bannerBusiness']['tmp_name'];    
						$folder   = "assets/img/business/{$businessId}";

						if(file_exists("../../{$folder}/{$filename}"))
							unlink("../../{$folder}/{$filename}");
	          
	          			mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);          
						if (move_uploaded_file($tempname, "../../{$folder}/{$filename}"))
							$businessModel->updateImage($businessId, "{$folder}/{$filename}");
					}

					$response = array(
						'codeResponse' => 200,
						'id' => $businessId,
						'message' => 'Datas successfully registered.'
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
				$businessModel = new Businessmodel();
				$tmpResponse = $businessModel->getBusiness( $_SESSION['authData']->id );		

				$response = array(
					'codeResponse' => 200,
					'data' => $tmpResponse
				);

				header('HTTP/1.1 200 Ok');
				header("Content-Type: application/json; charset=UTF-8");
				
				exit(json_encode($response));
			}else if($_POST['_method'] == 'DELETE'){
				$businessModel = new Businessmodel();
				$businessModel->deleteBusiness( $_POST['idBusiness'] );

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

	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		$businessModel = new Businessmodel();
		$tmpResponse = $businessModel->getBusinessId($_GET['businessId']);

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