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

			if($data['event_type'] == 0 && $data['action'] == 1){
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
			} else if($data['event_type'] > 0 && $data['action'] == 1){
				$cmd = '
					INSERT INTO team_league (team_id, league_id, register_date, status)
					VALUES (:team_id, :league_id, now(), 1);
				';

				$parametros = array(
					':league_id' => $data['event_id'],
					':team_id' => $data['event_type']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
			}

			return TRUE;
		}

		public function search($query) {
			$pdo = new Conexion();

			$cmd = '
				SELECT * FROM (
					SELECT
						id, nombre, image, "Business" AS tipo
					FROM 
						business
					WHERE status = 1	
					
						UNION 
					
					SELECT
						l.id, l.name AS nombre, l.image, "League" AS tipo
					FROM 
						league AS l
					WHERE l.status = 1
					
					
						UNION 
					
					SELECT
						t.id, t.name AS nombre, t.image, "Team" AS tipo
					FROM 
						team AS t
					WHERE t.active = 1
						
					
						UNION 
					
					SELECT
						u.id, CONCAT(u.name, " ", u.last_name ) AS nombre, null AS image, "User" AS tipo
					FROM 
						user AS u
					WHERE u.active = 1
				) AS Universe
				WHERE nombre LIKE "%'. str_replace(' ', '%', $query) .'%"
				ORDER BY tipo
			';

			$sql = $pdo->prepare($cmd);
			$sql->execute();

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function updateData($data){
			$pdo = new Conexion();

			$updatePass = '';
	    	if($data['password'] != '')
	    		$updatePass = ', password ="' . $data['password'] .'"';

			$cmd = 'UPDATE user SET name =:name, last_name =:last_name'. $updatePass .' WHERE id =:userId ';

			$parametros = array(
				':name' => $data['name'],
				':last_name' => $data['lastName'],
				':userId' => $data['userId']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function showInvitations($userId){
			$pdo = new Conexion();

			$cmd = '
				SELECT 
					id, 
					udestiny_id,
					event_id, 
					event_type, 
					register_date, 
					(SELECT CONCAT(NAME, " ", last_name) FROM user WHERE id = udestiny_id) AS toName  
				FROM invitation 
				WHERE uorigin_id =:userId
			';

			$parametros = array(
				':userId' => $userId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}