<?php
    session_start();
    
    require_once '../models/admin.php';
    require_once '../utils/jwt.php';

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $adminModel  = new Adminmodel();

        if($_POST['_method'] == 'GET'){

            $currentYear    = date("Y");
            $chart1         = [];

            for ($i=1; $i < 13; $i++) { 
                $result     = $adminModel->getChart1( $currentYear . '-' . str_pad($i, 2, '0', STR_PAD_LEFT) );
                $userCount  = ($result->user_count) ? intval($result->user_count) : 0;
                $chart1[]   = $userCount;
            }

            $response = array(
                'codeResponse'  => 200,
                'data'          => $adminModel->getReport(),
                'chart1'        => $chart1
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