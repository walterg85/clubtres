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
						(name, register_date, active, country, city, receive_requests)
					VALUES
						(:name, now(), 1, :country, :city, :visible)
				';

				$parametros = array(
					':name'		=> $data['name'],
					':country'	=> $data['country'],
					':city'		=> $data['city'],
					':visible'	=> $data['visible']
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
					UPDATE team SET name =:name, active =:active, country =:country, city =:city, receive_requests =:visible WHERE id =:id
				';

				$parametros = array(
					':id'		=> $data['idTeam'],
					':name'		=> $data['name'],
					':active'	=> $data['chkActive'],
					':country'	=> $data['country'],
					':city'		=> $data['city'],
					':visible'	=> $data['visible']
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
			$cmd = 'SELECT id, name, image, active, (select user_id FROM user_team where team_id =:teamId AND type = 1) AS user_id, country, city, receive_requests FROM team WHERE id =:teamId';

			$parametros = array(
				':teamId' => $teamId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			// Buscar los miembros de este equipo
			$data = $sql->fetch();
			if($data)
				$data->teamlist = $this->getChilds($teamId);

			return $data;
		}

		public function getTeam($user_id) {
			$pdo = new Conexion();
			$cmd = '
				SELECT t.id, t.name, t.register_date, t.active, t.image, (SELECT ut.type FROM user_team AS ut WHERE ut.team_id = t.id AND ut.user_id =:user_id) AS type, t.country, t.city, t.receive_requests
				FROM team AS t
				WHERE t.id in (select team_id from user_team where user_id =:user_id) AND t.active = 1;
			';

			$parametros = array(
				':user_id' => $user_id
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getTeamLeague($user_id, $league_id) {
			$pdo = new Conexion();
			$cmd = '
				SELECT t.id, t.name, t.register_date, t.active, t.image, ut.type, ut.register_date
				FROM user_team ut
				INNER JOIN team t ON ut.team_id = t.id
				WHERE ut.user_id =:user_id 
					AND t.active = 1
					AND ut.type = 1
					AND t.id NOT IN (select tl.team_id FROM team_league tl where tl.league_id =:league_id)
					AND t.id NOT IN (select comodin FROM invitation i where i.event_id =:league_id and i.uorigin_id =:user_id)
			';

			$parametros = array(
				':user_id'	=> $user_id,
				'league_id' => $league_id
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

			if($data['event_type'] == 0){
				$cmd = '
					SELECT COUNT(id) AS existe, role FROM user_team WHERE user_id =:user_id AND team_id =:team_id
				';

				$parametros = array(
					':user_id'		=> $data['udestiny_id'],
					':team_id'		=> $data['event_id']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$response = $sql->fetch();

				if($response->existe == 1){
					 return [FALSE, 'They cannot add this user, he is already registered as an ' . $response->role];
				}else{
					$cmd = 'SELECT COUNT(id) AS invitado FROM invitation WHERE udestiny_id =:udestiny_id AND event_id =:event_id AND event_type =:event_type';

					$parametros = array(
						':udestiny_id'	=> $data['udestiny_id'],
						':event_id'		=> $data['event_id'],
						':event_type'	=> $data['event_type']
					);

					$sql = $pdo->prepare($cmd);
					$sql->execute($parametros);
					$sql->setFetchMode(PDO::FETCH_OBJ);
					$response = $sql->fetch();

					if($response->invitado == 1)
						return [FALSE, 'You cannot send another invitation to the same user, they still have a pending invitation to join the same team'];
				}
			}else if($data['event_type'] > 0){
				$cmd = '
					SELECT COUNT(id) AS existe FROM team_league WHERE team_id =:team_id AND league_id =:league_id
				';

				$parametros = array(
					':league_id'	=> $data['event_id'],
					':team_id'		=> $data['event_type']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$response = $sql->fetch();

				if($response->existe == 1){
					 return [FALSE, 'You cannot invite the team in this league, because it is already registered'];
				}else{
					$cmd = 'SELECT COUNT(id) AS invitado FROM invitation WHERE udestiny_id =:udestiny_id AND event_id =:event_id AND event_type =:event_type';

					$parametros = array(
						':udestiny_id'	=> $data['udestiny_id'],
						':event_id'		=> $data['event_id'],
						':event_type'	=> $data['event_type']
					);

					$sql = $pdo->prepare($cmd);
					$sql->execute($parametros);
					$sql->setFetchMode(PDO::FETCH_OBJ);
					$response = $sql->fetch();

					if($response->invitado == 1)
						return [FALSE, 'You cannot send another invitation to the team to join the league, they still have a pending invitation to join this league.'];
				}
			}

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
		        return [FALSE, 'Invitation not sent'];
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

		public function reviewRequest($data){
			$pdo = new Conexion();
			// Buscar si el usuario ya esta registrado en el equipo
			$cmd = '
				SELECT COUNT(id) AS existe, role, register_date FROM user_team WHERE user_id =:user_id AND team_id =:team_id
			';

			$parametros = array(
				':user_id'		=> $data['userId'],
				':team_id'		=> $data['teamId']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			$response = $sql->fetch();

			if($response->existe == 1){
				return [1, $response->register_date];
			}else{
				// Localizar al dueño del equipo
				$cmd = 'SELECT `user_id` FROM `user_team` where team_id =:team_id and type = 1;';
				$parametros = array(
					':team_id' => $data['teamId']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$owner = $sql->fetch();

				// Verificar si se envio solicitud al equipo
				$cmd = '
					SELECT COUNT(id) AS invitacion
					FROM invitation 
					WHERE udestiny_id =:udestiny_id 
						AND uorigin_id =:uorigin_id
						AND event_id =:event_id
						AND event_type =:event_type
				';

				$parametros = array(
					':udestiny_id'	=> $owner->user_id,
					':uorigin_id'	=> $data['userId'],
					':event_id'		=> $data['teamId'],
					':event_type'	=> $data['event_type']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);
				$sql->setFetchMode(PDO::FETCH_OBJ);
				$response = $sql->fetch();

				if($response->invitacion > 0)
					return [2, 0];

				return [0, 0];
			}
		}

		public function sendAdmision($data){
			$pdo = new Conexion();

			// Localizar al dueño del equipo
			$cmd = 'SELECT `user_id` FROM `user_team` where team_id =:team_id and type = 1;';
			$parametros = array(
				':team_id' => $data['event_id']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);
			$owner = $sql->fetch();

			$cmd = '
				INSERT INTO invitation
					(udestiny_id, uorigin_id, event, event_id, event_type, register_date)
				VALUES
					(:udestiny_id, :uorigin_id, :event, :event_id, :event_type, now())
			';

			$parametros = array(
				':udestiny_id'	=> $owner->user_id,
				':uorigin_id'	=> $data['uorigin_id'],
				':event'		=> $data['event'],
				':event_id'		=> $data['event_id'],
				':event_type'	=> $data['event_type']
			);
			
			try {
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $pdo->lastInsertId()];
			} catch (PDOException $e) {
		        return [FALSE, 'Invitation not sent'];
		    }
		}

		public function searchByLocation($miCiuadad) {
			$pdo = new Conexion();
			$cmd = '
				SELECT 
					id, 
					name, 
					image, 
					active, 
					(select concat(name, " ", last_name) FROM user where id = (select user_id FROM user_team where team_id = team.id AND type = 1)) AS owner, 
					country, 
					city 
				FROM 
					team 
				WHERE 
					'. $miCiuadad .'
					AND receive_requests = 1
			';

			$sql = $pdo->prepare($cmd);
			$sql->execute();

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getCitys(){
			$pdo = new Conexion();
			$cmd = '
				SELECT DISTINCT(city) ciudades FROM team
			';

			$sql = $pdo->prepare($cmd);
			$sql->execute();

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getCountry(){
			$pdo = new Conexion();
			$cmd = '
				SELECT DISTINCT(country) AS pais FROM team WHERE receive_requests = 1;
			';

			$sql = $pdo->prepare($cmd);
			$sql->execute();

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}