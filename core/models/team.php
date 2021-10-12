<?php
	class Teamsmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createTeam($data) {
			$pdo = new Conexion();
			$cmd = '
				INSERT INTO team
					(name, image, register_date, active)
				VALUES
					(:name, :image, now(), 1)
			';

			$parametros = array(
				':name'		=> $data['name'],
				':image'	=> $data['image']		
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
	}