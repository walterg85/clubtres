<?php
	class Chatmodel {
		public function __construct() {
	        require_once '../dbConnection.php';
	    }

	    // Metodo para crear o actualizar chat
		public function insertUpdateChat($data, $chatId) {
			$pdo = new Conexion();

			// Si el id de chat es 0 se procede a registrar un nuevo chat
			if( $chatId == 0 ){
				$cmd = '
					INSERT INTO chats
						(message, origin, destiny, unread)
					VALUES
						(:message, :origin, :destiny, 1)
				';

				$parametros = array(
					':message'	=> '
						<div class="alert alert-info" role="alert">
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
			} else if( $chatId == 0 ){
				$cmd = '
					UPDATE chats
					SET message = CONCAT(message, :message), unread = 0
					WHERE id =:chatId
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
					':chatId'	=> $chatId
				);
				
				try {
					$sql = $pdo->prepare($cmd);
					$sql->execute($parametros);

					return $chatId;
				} catch (PDOException $e) {
			        return 0;
			    }
			}
		}
	}