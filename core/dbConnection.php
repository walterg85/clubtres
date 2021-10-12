<?php
	class Conexion extends PDO
	{
		private $host		= '127.0.0.1';
		private $database	= 'clubtres';
		private $usDb		= 'root';
		private $password	= '';

		public function __construct()
		{
			try{
				$strConect	= 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=utf8';
				$errorMode	= array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				);

				parent::__construct($strConect, $this->usDb, $this->password, $errorMode);
			}catch(PDOException $e){
				exit('Error: ' . $e->getMessage());
			}
		}
	}