<?php
	class Usersmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createUser($userData) {
			$pdo = new Conexion();
			$cmd = '
				INSERT INTO user
					(name, last_name, email, password, register_date, oauth_provider, active)
				VALUES
					(:name, :last_name, :email, :password, now(), "system", 1)
			';

			$parametros = array(
				':name' => $userData['name'],
				':last_name' => $userData['last_name'],
				':email' => $userData['email'],
				':password' => $userData['password']			
			);
			
			try {
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $pdo->lastInsertId()];
			} catch (PDOException $e) {
		        return [FALSE, $e->getCode()];
		    }
		}

		public function login($email) {
			$pdo = new Conexion();
			$cmd = 'SELECT id, name, last_name, email, password FROM user WHERE email =:email AND active = 1';

			$parametros = array(
				':email' => $email
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			return $sql->fetch();
		}

		public function getUserId($userId) {
			$pdo = new Conexion();
			$cmd = 'SELECT id, name, last_name, active FROM user WHERE id =:userId';

			$parametros = array(
				':userId' => $userId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			return $sql->fetch();
		}

		public function countInvitation($userId){
			$pdo = new Conexion();
			$cmd = 'SELECT COUNT(id) AS notifications FROM invitation WHERE udestiny_id =:userId;';

			$parametros = array(
				':userId' => $userId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			return $sql->fetch();
		}

		public function getInvitation($userId){
			$pdo = new Conexion();
			$cmd = 'SELECT id, uorigin_id, event, event_id, event_type, register_date, (SELECT CONCAT(NAME, " ", last_name) FROM user WHERE id = uorigin_id) AS fromName  FROM invitation WHERE udestiny_id =:userId';

			$parametros = array(
				':userId' => $userId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function updateInvitation($data, $userId){
			$pdo = new Conexion();

			$cmd = '
				DELETE FROM invitation WHERE id =:id;
			';

			$parametros = array(
				':id' => $data['invitationId']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			if($data['event_type'] == 1 && $data['action'] == 1){
				$cmd = '
					INSERT INTO user_team (user_id, team_id, type, role, register_date, status)
					VALUES (:userId, :teamId, 2, "Invited", now(), 1);
				';

				$parametros = array(
					':teamId' => $data['event_id'],
					':userId' => $userId
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
			}

			return TRUE;
		}
	}