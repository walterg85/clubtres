<?php
	class Usersmodel
	{
		public function __construct()
	    {
	        require_once '../dbConnection.php';
	    }

		public function createUser($userData)
		{
			$pdo = new Conexion();
			$cmd = '
				INSERT INTO user
					(name, last_name, email, password, register_date, oauth_provider, active)
				VALUES
					(:name, :last_name, :email, :password, now(), "system", 1)
			';

			$parametros = array(
				':name' => $userData['owner'],
				':last_name' => $userData['email'],
				':email' => $userData['password'],
				':password' => $userData['type']			
			);
			
			try{
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $pdo->lastInsertId()];
			} catch (PDOException $e) {
		        return [FALSE, $e->getCode()];
		    }
		}
	}