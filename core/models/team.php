<?php
	class Teamsmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createTeam($data) {
			$pdo = new Conexion();
			$cmd = '
				INSERT INTO team
					(name, register_date, active)
				VALUES
					(:name, now(), 1)
			';

			$parametros = array(
				':name'		=> $data['name']	
			);
			
			try {
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				$teamId = $pdo->lastInsertId();

				$cmd 	= '
					INSERT INTO user_team
						(user_id, team_id, type, role, register_date, status)
					VALUES
						(:user_id, :team_id, 1, "owner", now(), 1)
				';

				$parametros = array(
					':user_id'		=> $data['user_id'],
					':team_id'	=> $teamId		
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $teamId];
			} catch (PDOException $e) {
		        return [FALSE, 0];
		    }
		}

		public function updateImage($teamId, $image){
			$pdo = new Conexion();
			$cmd = 'UPDATE team SET image =:image WHERE id =:teamId';

			$parametros = array(
				':image' => $image,
				':teamId' => $teamId			
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function getTeamId($teamId) {
			$pdo = new Conexion();
			$cmd = 'SELECT id, name, image, active FROM team WHERE id =:teamId';

			$parametros = array(
				':teamId' => $teamId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			return $sql->fetch();
		}

		public function getTeam($user_id) {
			$pdo = new Conexion();
			$cmd = 'SELECT id, name, register_date, active FROM team WHERE id in(select team_id from user_team where user_id =:user_id);';

			$parametros = array(
				':user_id' => $user_id
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}