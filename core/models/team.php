<?php
	class Teamsmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createTeam($data) {
			$pdo = new Conexion();
			if( $data['idTeam'] == 0 ){
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
							(:user_id, :team_id, 1, "Owner", now(), 1)
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
			} else {
				$cmd = '
					UPDATE team SET name =:name, active =:active WHERE id =:id
				';

				$parametros = array(
					':id'		=> $data['idTeam'],
					':name'		=> $data['name'],
					':active'	=> $data['chkActive']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $data['idTeam']];
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
			$cmd = 'SELECT id, name, image, active, (select user_id FROM user_team where team_id =:teamId AND type = 1) AS user_id FROM team WHERE id =:teamId';

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
			$cmd = '
				SELECT t.id, t.name, t.register_date, t.active, t.image, (SELECT ut.type FROM user_team AS ut WHERE ut.team_id = t.id AND ut.user_id =:user_id) AS type
				FROM team AS t
				WHERE t.id in(select team_id from user_team where user_id =:user_id) AND t.active = 1;
			';

			$parametros = array(
				':user_id' => $user_id
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function deleteTeam($teamId) {
			$pdo = new Conexion();
			$cmd = 'UPDATE team SET active = 0 WHERE id =:teamId';

			$parametros = array(
				':teamId' => $teamId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function sendInvitation($data){
			$pdo = new Conexion();

			$cmd = '
				INSERT INTO invitation
					(udestiny_id, uorigin_id, event, event_id, event_type, register_date)
				VALUES
					(:udestiny_id, :uorigin_id, :event, :event_id, :event_type, now())
			';

			$parametros = array(
				':udestiny_id'		=> $data['udestiny_id'],
				':uorigin_id'		=> $data['uorigin_id'],
				':event'		=> $data['event'],
				':event_id'		=> $data['event_id'],
				':event_type'		=> $data['event_type']
			);
			
			try {
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $pdo->lastInsertId()];
			} catch (PDOException $e) {
		        return [FALSE, 0];
		    }
		}

		public function getChilds($teamId){
			$pdo = new Conexion();
			$cmd = '
				SELECT u.id, ut.id AS registroId, CONCAT (u.name, " ", u.last_name) AS usName, ut.role, ut.type, ut.status
				FROM user AS u
				INNER JOIN user_team AS ut ON ut.user_id = u.id
				WHERE ut.team_id =:teamId;
			';

			$parametros = array(
				':teamId' => $teamId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function deleteChild($registroId){
			$pdo = new Conexion();
			$cmd = '
				DELETE FROM user_team WHERE id =:registroId;
			';

			$parametros = array(
				':registroId' => $registroId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}
	}