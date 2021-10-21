<?php
	class Leaguemodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createLeague($data) {
			$pdo = new Conexion();

			if( $data['idLeague'] == 0 ){
				$cmd = '
					INSERT INTO league
						(name, sport, register_date, status, user_id)
					VALUES
						(:name, :sport, now(), 1, :user_id)
				';

				$parametros = array(
					':name'		=> $data['name'],
					':sport'	=> $data['sport'],
					':user_id'  => $data['user_id']
				);
				
				try {
					$sql = $pdo->prepare($cmd);
					$sql->execute($parametros);

					return [TRUE, $pdo->lastInsertId()];
				} catch (PDOException $e) {
			        return [FALSE, 0];
			    }
			} else {
				$cmd = '
					UPDATE league SET name =:name, sport =:sport, status =:status WHERE id =:id
				';

				$parametros = array(
					':id'		=> $data['idLeague'],
					':name'		=> $data['name'],
					':sport'	=> $data['sport'],
					':status'	=> $data['chkActive']
				);

				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $data['idLeague']];
			}			
		}

		public function updateImage($leagueId, $image){
			$pdo = new Conexion();
			$cmd = 'UPDATE league SET image =:image WHERE id =:leagueId';

			$parametros = array(
				':image' => $image,
				':leagueId' => $leagueId			
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function getLeagueId($leagueId) {
			$pdo = new Conexion();
			$cmd = 'SELECT id, name, sport, image, status FROM league WHERE id =:leagueId';

			$parametros = array(
				':leagueId' => $leagueId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			// Buscar los equipos de la liga
			$data = $sql->fetch();
			$data->teamlist = $this->getChilds($leagueId);

			return $data;
		}

		public function getLeague( $user_id ) {
			$pdo = new Conexion();
			$cmd = '
				SELECT * FROM(SELECT id, name, sport, register_date, status, image, 1 AS type 
				FROM league 
				WHERE status = 1 AND user_id =:user_id

				UNION 

				SELECT id, name, sport, register_date, status, image, 2 AS type 
				FROM league 
				WHERE status = 1 
				AND id IN(SELECT league_id FROM team_league WHERE status = 1 AND team_id IN ( SELECT team_id FROM user_team WHERE user_id =:user_id AND status = 1)))
				AS allData GROUP BY id
			';

			$parametros = array(
				':user_id' => $user_id
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function deleteLeague($leagueId) {
			$pdo = new Conexion();
			$cmd = 'UPDATE league SET status = 0 WHERE id =:leagueId';

			$parametros = array(
				':leagueId' => $leagueId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function getChilds($leagueId){
			$pdo = new Conexion();
			$cmd = '
				-- SELECT id, name FROM team WHERE active = 1 AND id IN (SELECT DISTINCT(team_id) FROM team_league WHERE league_id =:leagueId AND STATUS = 1)
				SELECT t.id, t.name, tl.`status`, tl.register_date
				FROM team AS t
				INNER JOIN  team_league AS tl ON tl.team_id = t.id
				WHERE t.active = 1 AND tl.league_id =:leagueId
				GROUP BY t.id
			';

			$parametros = array(
				':leagueId' => $leagueId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function disableChild($data){
			$pdo = new Conexion();
			$cmd = '
				UPDATE team_league SET status = 0 WHERE team_id =:team_id AND league_id =:league_id;
			';

			$parametros = array(
				':team_id' => $data['idTeam'],
				':league_id' => $data['idLeague']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function enableChild($data){
			$pdo = new Conexion();
			$cmd = '
				UPDATE team_league SET status = 1 WHERE team_id =:team_id AND league_id =:league_id;
			';

			$parametros = array(
				':team_id' => $data['idTeam'],
				':league_id' => $data['idLeague']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function deleteChild($data){
			$pdo = new Conexion();
			$cmd = '
				DELETE FROM team_league WHERE team_id =:team_id AND league_id =:league_id;
			';

			$parametros = array(
				':team_id' => $data['idTeam'],
				':league_id' => $data['idLeague']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function disableMemberChild($idRegistro){
			$pdo = new Conexion();
			$cmd = '
				UPDATE user_team SET status = 0 WHERE id =:idRegistro;
			';

			$parametros = array(
				':idRegistro' => $idRegistro
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function enableMemberChild($idRegistro){
			$pdo = new Conexion();
			$cmd = '
				UPDATE user_team SET status = 1 WHERE id =:idRegistro;
			';

			$parametros = array(
				':idRegistro' => $idRegistro
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function addEvent($data){
			$pdo = new Conexion();
			$cmd = 'INSERT INTO games (league_id, teama_id, teamb_id, event_date, locations, registered_date, status) VALUES (:league_id, :teama_id, :teamb_id, :event_date, :locations, now(), 1)';

			$parametros = array(
				':league_id' => $data['leagueId'],
				':teama_id' => $data['team1'],
				':teamb_id' => $data['team2'],
				':event_date' => $data['date'],
				':locations' => $data['location']
			);

			try {
				$sql = $pdo->prepare($cmd);
				$sql->execute($parametros);

				return [TRUE, $pdo->lastInsertId()];
			} catch (PDOException $e) {
		        return [FALSE, 0];
		    }
		}

		public function getGames($user_id){
			$pdo = new Conexion();
			$cmd = '
				SELECT g.id, g.event_date, g.locations, g.status, l.name, l.sport, 1 AS type, (SELECT NAME FROM team WHERE id = g.teama_id) AS team1, (SELECT NAME FROM team WHERE id = g.teamb_id) AS team2
				FROM games AS g
				INNER JOIN league AS l ON g.league_id = l.id
				WHERE l.user_id =:user_id

				UNION DISTINCT

				SELECT g.id, g.event_date, g.locations, g.status, l.name, l.sport, 2 AS type, (SELECT NAME FROM team WHERE id = g.teama_id) AS team1, (SELECT NAME FROM team WHERE id = g.teamb_id) AS team2
				FROM league AS l
				INNER JOIN  games AS g ON l.id = g.league_id
				INNER JOIN user_team AS ut ON g.teama_id = ut.team_id
				WHERE ut.user_id =:user_id AND ut.status = 1 AND l.user_id !=:user_id

				UNION DISTINCT

				SELECT g.id, g.event_date, g.locations, g.status, l.name, l.sport, 2 AS type, (SELECT NAME FROM team WHERE id = g.teama_id) AS team1, (SELECT NAME FROM team WHERE id = g.teamb_id) AS team2
				FROM league AS l
				INNER JOIN  games AS g ON l.id = g.league_id
				INNER JOIN user_team AS ut ON g.teamb_id = ut.team_id
				WHERE ut.user_id =:user_id AND ut.status = 1 AND l.user_id !=:user_id
			';

			$parametros = array(
				':user_id' => $user_id
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function cancelGame($idGame){
			$pdo = new Conexion();
			$cmd = '
				UPDATE games SET status = 0 WHERE id =:idGame
			';

			$parametros = array(
				':idGame' => $idGame
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		public function deleteGame($idGame){
			$pdo = new Conexion();
			$cmd = '
				DELETE FROM games WHERE id =:idGame
			';

			$parametros = array(
				':idGame' => $idGame
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}
	}