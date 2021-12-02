<?php
	class Chatmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

	    // Metodo para crear o actualizar chat
		public function insertUpdateChat($data) {
			$pdo = new Conexion();
			// Validar si existe el chat
			// -Resultado 1: El usuario actual logueado es el origen y se registran los chats como origen
			// -Resultado 2: El usuario actua logueado es el destino y se registran los chats como respuesta
			// -Resultadi 3: No existe un chat entre ambos usuarios y se registra el usuario actual como origen

			$cmd = '
				SELECT 
					CASE 
						WHEN (origin =:origin AND destiny =:destiny) THEN 1
				    	WHEN (origin =:destiny AND destiny =:origin) THEN 2
				    	ELSE 3
				    END AS metodo
				FROM chats
				WHERE origin =:origin AND destiny =:destiny

				UNION

				SELECT 
					CASE 
						WHEN (origin =:origin AND destiny =:destiny) THEN 1
				    	WHEN (origin =:destiny AND destiny =:origin) THEN 2
				    	ELSE 3
				    END AS metodo
				FROM chats
				WHERE origin =:destiny AND destiny =:origin
			';

			$parametros = array(
				':origin'	=> $data['origin'],
				':destiny'	=> $data['destiny']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			$metodo = $sql->fetch(PDO::FETCH_ASSOC);

			switch ($metodo['metodo']){
				case 1:
					$cmd = '
						UPDATE chats
						SET message = CONCAT(message, :message), unread =:destiny
						WHERE origin =:origin AND destiny =:destiny
					';

					$parametros = array(
						':message'	=> '
							<div class="alert text-white" role="alert">
	                            <figure class="mb-0">
	                                <blockquote class="blockquote">
	                                    <p class="small">'. $data['message'] .'</p>
	                                </blockquote>
	                                <figcaption class="blockquote-footer mb-0">
	                                    '. date('H:i:s') .' | '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .'
	                                </figcaption>
	                            </figure>
	                        </div>
						',
						':origin'	=> $data['origin'],
						':destiny'	=> $data['destiny']
					);

					try {
						$sql = $pdo->prepare($cmd);
						$sql->execute($parametros);

						return 1;
					} catch (PDOException $e) {
				        return 0;
				    }

					break;
				case 2:
					$cmd = '
						UPDATE chats
						SET message = CONCAT(message, :message), unread =:destiny
						WHERE origin =:destiny AND destiny =:origin
					';

					$parametros = array(
						':message'	=> '
							<figure class="text-end text-white">
	                            <blockquote class="blockquote">
	                                <p class="small">'. $data['message'] .'</p>
	                            </blockquote>
	                            <figcaption class="blockquote-footer">
	                                '. date('H:i:s') .' | '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .'
	                            </figcaption>
	                        </figure>
						',
						':origin'	=> $data['origin'],
						':destiny'	=> $data['destiny']
					);

					try {
						$sql = $pdo->prepare($cmd);
						$sql->execute($parametros);

						return 1;
					} catch (PDOException $e) {
				        return 0;
				    }

					break;
				default:
					$cmd = '
						INSERT INTO chats
							(message, origin, destiny, unread)
						VALUES
							(:message, :origin, :destiny, :destiny)
					';

					$parametros = array(
						':message'	=> '
							<div class="alert text-white" role="alert">
	                            <figure class="mb-0">
	                                <blockquote class="blockquote">
	                                    <p class="small">'. $data['message'] .'</p>
	                                </blockquote>
	                                <figcaption class="blockquote-footer mb-0">
	                                    '. date('H:i:s') .' | '. $_SESSION['authData']->name .' '. $_SESSION['authData']->last_name .'
	                                </figcaption>
	                            </figure>
	                        </div>
						',
						':origin'	=> $data['origin'],
						':destiny'	=> $data['destiny']
					);
					
					try {
						$sql = $pdo->prepare($cmd);
						$sql->execute($parametros);

						return $pdo->lastInsertId();
					} catch (PDOException $e) {
				        return 0;
				    }

					break;
			}
		}

		// Metodo para cargar el log completo de un chat al abirlo
		public function loadChatLog($data){
			$pdo = new Conexion();

			$cmd = '
				SELECT id, message, unread FROM (
					SELECT id, message, unread FROM chats WHERE origin =:origin AND destiny =:destiny
					UNION
					SELECT id, message, unread FROM chats WHERE origin =:destiny AND destiny =:origin
				) AS chatlog;
			';

			$parametros = array(
				':origin'	=> $data['origin'],
				':destiny'	=> $data['destiny']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetch(PDO::FETCH_ASSOC);
		}

		// Metodo para marcar el mensaje como leido
		public function checkChat($chatId){
			$pdo = new Conexion();

			$cmd = '
				UPDATE chats
				SET unread = 0
				WHERE id =:chatId
			';

			$parametros = array(
				':chatId' => $chatId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}

		// Metodo para monitorear mensajes nuevos y mostrar icono de alerta
		public function monitorChat($userId){
			$pdo = new Conexion();

			$cmd = '
				SELECT
					id, 
					origin, 
					(select concat(name, " ", last_name) from user where id = origin) AS originName,
					destiny,
					(select concat(name, " ", last_name) from user where id = destiny) AS destinyName
				FROM chats 
				WHERE unread =:userId
			';

			$parametros = array(
				':userId'	=> $userId
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		// Metodo para eliminar un chat
		public function deleteChat($data){
			$info = $this->loadChatLog($data);

			$pdo = new Conexion();
			$cmd = '
				DELETE FROM chats WHERE id =:id
			';

			$parametros = array(
				':id' => $info['id']
			);

			$sql = $pdo->prepare($cmd);
			$sql->execute($parametros);

			return TRUE;
		}
	}