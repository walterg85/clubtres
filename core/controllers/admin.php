<?php
    session_start();
    
    require_once '../models/admin.php';
    require_once '../utils/jwt.php';

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $adminModel  = new Adminmodel();

        if($_POST['_method'] == 'GET'){
            $response = array(
                'codeResponse'  => 200,
                'data'          => $adminModel->getReport()
            );

            header('HTTP/1.1 200 Ok');
            header("Content-Type: application/json; charset=UTF-8");
            exit(json_encode($response));
        }        
    }

    header('HTTP/1.1 400 Bad Request');
    header("Content-Type: application/json; charset=UTF-8");

    $response = array(
        'codeResponse'  => 400,
        'message'       => 'Bad Request'
    );
    exit( json_encode($response) );