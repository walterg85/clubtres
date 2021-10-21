<?php
	class Businessmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

		public function createBusineess($data) {
			$pdo = new Conexion();

			if( $data['idBusiness'] == 0 ){
				$cmd = '
					INSERT INTO business
						(nombre, Descripcion, telefono, Direccion, web, user_id, status)
					VALUES
						(:nombre, :Descripcion, :telefono, :Direccion, :web, :user_id, 1)
				';

				$parametros = array(
					':nombre'		=> $data['inputName'],
					':Descripcion'	=> $data['inputDescription'],
					':telefono'	=> $data['inputNumber'],
					':Direccion'	=> $data['inputAddress'],
					':web'	=> $data['inputWeb'],
					':user_id'  => $data['user_id']
				);
				
				try {
					$sql = $pdo->prepare($cmd);
					$sql->execute($parametros);

					return [TRUE, $pdo->lastInsertId()];
				} catch (PDOException $e) {
			        return [FALSE, 0];
			    }
			}		
		}

		public function updateImage($businessId, $image){
			$pdo = new Conexion();
			$cmd = 'UPDATE business SET image =:image WHERE id =:businessId';

			$parametros = array(
				':image' => $image,
				':businessId' => $businessId			
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}
	}