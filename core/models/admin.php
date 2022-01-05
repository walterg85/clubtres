<?php
	class Adminmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function getReport() {
			$pdo = new Conexion();
			$cmd = '
				SELECT
					(SELECT COUNT(id) FROM `team`) AS total_team,
				    (SELECT COUNT(id) FROM `team` WHERE active = 1) AS active_team,
				    (SELECT COUNT(id) FROM `team` WHERE active = 0) AS inactive_team,
					(SELECT COUNT(id) FROM `user`) AS total_user,
					(SELECT COUNT(id) FROM `user` WHERE active = 1) AS active_user,
					(SELECT COUNT(id) FROM `user` WHERE active = 0) AS inactive_user,
					(SELECT COUNT(id) FROM `league`) AS total_league,
					(SELECT COUNT(id) FROM `league` WHERE status = 1) AS active_league,
					(SELECT COUNT(id) FROM `league` WHERE status = 0) AS inactive_league,
					(SELECT COUNT(id) FROM `business`) AS total_business,
					(SELECT COUNT(id) FROM `business` WHERE status = 1) AS active_business,
					(SELECT COUNT(id) FROM `business` WHERE status = 0) AS inactive_business,
					(SELECT COUNT(id) FROM `games`) AS total_games,
					(SELECT COUNT(id) FROM `games` WHERE event_date > CURRENT_DATE) AS pending_games,
					(SELECT COUNT(id) FROM `games` WHERE event_date < CURRENT_DATE) AS passed_games
			';

			$sql = $pdo->prepare($cmd);
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_OBJ);
			
			return $sql->fetch();
		}

		public function getChart1($data){
			$pdo = new Conexion();

	    	$cmd = '
	    		SELECT COUNT(id) AS user_count FROM `user` WHERE register_date LIKE :fecha
	    	';

	    	$parametros = array(
	    		':fecha' => $data . '%'
	    	);

	    	$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);
			$sql->setFetchMode(PDO::FETCH_OBJ);

			return $sql->fetch();
		}
	}