<?php
    class Usersmodel {
        public function __construct() {
            require_once '../dbConnection.php';
        }
        
        // Metodo para registrar un nuevo usurio
        public function createUser($userData){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO user
                    (name, last_name, email, password, register_date, oauth_provider, active)
                VALUES
                    (:name, :last_name, :email, :password, now(), "system", 1)
            ';

            $parametros = array(
                ':name'         => $userData['name'],
                ':last_name'    => $userData['last_name'],
                ':email'        => $userData['email'],
                ':password'     => $userData['password']            
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return [TRUE, $pdo->lastInsertId()];
            } catch (PDOException $e) {
                return [FALSE, $e->getCode()];
            }
        }

        // Metodo auxiliar para obtener la iunformacion de inicio de session de un usuario
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

        // Metodo para obtener la informacion de un usuario especifico
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

        // Metodo para conteo de invitaciones y marcar en el menu lateral como advertencia
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

        // Metodo para obtener las invitaciones que he enviado, para cancelarlas
        public function getInvitation($userId){
            $pdo = new Conexion();
            $cmd = 'SELECT id, uorigin_id, event, event_id, event_type, register_date, (SELECT CONCAT(NAME, " ", last_name) FROM user WHERE id = uorigin_id) AS fromName, comodin  FROM invitation WHERE udestiny_id =:userId';

            $parametros = array(
                ':userId' => $userId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Metodo para aceptar solictudes
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

            // Un jugador acepto la invitacion para unirse a un equipo
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
            } else if($data['event_type'] > 0 && $data['action'] == 1){ // Un equipo acepto la invitacion para unirse a una liga
                $cmd = '
                    INSERT INTO team_league (team_id, league_id, register_date, status)
                    VALUES (:team_id, :league_id, now(), 1);
                ';

                $parametros = array(
                    ':league_id'    => $data['event_id'],
                    ':team_id'      => $data['event_type']
                );

                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);
            } else if($data['event_type'] == -1 && $data['action'] == 1){ // Un jugador acepto la solicitud de amistad de otro jugador
                $cmd = '
                    INSERT INTO user_friends (user_id, friend_id, register_date)
                    VALUES (:receptor, :emisor, now()), (:emisor, :receptor, now());
                ';

                $parametros = array(
                    ':receptor' => $userId,
                    ':emisor'   => $data['uorigin_id']
                );

                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);
            } else if($data['event_type'] == -2 && $data['action'] == 1){ // Un equipo aceptola solicitud de un jugador para unirse al equipo
                $cmd = '
                    INSERT INTO user_team (user_id, team_id, type, role, register_date, status)
                    VALUES (:userId, :team_id, 2, "Invited", now(), 1);
                ';

                $parametros = array(
                    ':team_id'  => $data['event_id'],
                    ':userId'   => $data['uorigin_id']
                );

                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);
            } else if($data['event_type'] == -3 && $data['action'] == 1){ // Una liga acepto la solicitud de un equipo para unirse a la liga
                $cmd = '
                    INSERT INTO team_league (team_id, league_id, register_date, status)
                    VALUES (:team_id, :league_id, now(), 1);
                ';

                $parametros = array(
                    ':team_id'      => $data['comodin'],
                    ':league_id'    => $data['event_id']
                );

                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);
            }

            return TRUE;
        }

        // Metodo para la busqueda general (Barra de busquedas)
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

        // Metodo para actualizacion de datos de usuario
        public function updateData($data){
            $pdo = new Conexion();

            $updatePass = '';
            if($data['password'] != '')
                $updatePass = ', password ="' . $data['password'] .'"';

            $cmd = 'UPDATE user SET name =:name, last_name =:last_name'. $updatePass .' WHERE id =:userId ';

            $parametros = array(
                ':name'         => $data['name'],
                ':last_name'    => $data['lastName'],
                ':userId'       => $data['userId']
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return TRUE;
        }

        // Metodo para mostrar todas las invitaciones del usuario logueado
        public function showInvitations($userId){
            $pdo = new Conexion();

            $cmd = '
                SELECT 
                    i.id, 
                    i.udestiny_id,
                    i.event_type, 
                    i.register_date, 
                    (SELECT CONCAT(NAME, " ", last_name) FROM user WHERE id = i.udestiny_id) AS toName,
                    CASE
                        WHEN i.event_type = 0 THEN (Select name from team where id = i.event_id)
                        WHEN i.event_type > 0 THEN (Select name from league where id = i.event_id)
                    END AS eventName,
                    CASE
                        WHEN i.event_type > 0 THEN (Select name from team where id = i.event_type)
                        ELSE ""
                    END AS TeamName
                FROM invitation AS i
                WHERE uorigin_id =:userId
            ';

            $parametros = array(
                ':userId' => $userId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Metodo para registrar la invitacion del usuario
        public function inviteFriend($data){
            $pdo = new Conexion();
            $cmd = 'SELECT COUNT(id) AS invitado FROM invitation WHERE udestiny_id =:udestiny_id AND uorigin_id =:uorigin_id AND event_id =:event_id AND event_type =:event_type';

            $parametros = array(
                ':udestiny_id'  => $data['udestiny_id'],
                ':event_id'     => $data['event_id'],
                ':event_type'   => $data['event_type'],
                ':uorigin_id'   => $data['uorigin_id']
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);
            $sql->setFetchMode(PDO::FETCH_OBJ);
            $response = $sql->fetch();

            if($response->invitado > 0)
                return [FALSE, 'You cannot send another friend invitation to the same user, they still have a pending invitation'];

            $cmd = '
                INSERT INTO invitation
                    (udestiny_id, uorigin_id, event, event_id, event_type, register_date)
                VALUES
                    (:udestiny_id, :uorigin_id, :event, :event_id, :event_type, now())
            ';

            $parametros = array(
                ':udestiny_id'  => $data['udestiny_id'],
                ':uorigin_id'   => $data['uorigin_id'],
                ':event'        => $data['event'],
                ':event_id'     => $data['event_id'],
                ':event_type'   => $data['event_type']
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return [TRUE, $pdo->lastInsertId()];
            } catch (PDOException $e) {
                return [FALSE, 'Invitation not sent'];
            }
        }

        // Validar al usuario actual, si ya envio una solicitud de amistad previa o si ya son amigos
        public function reviewFriendRequest($data){
            $pdo        = new Conexion();
            $parametros = array(
                ':uorigin_id'   => $data['uorigin_id'],
                ':udestiny_id'  => $data['udestiny_id'],
                ':event_type'   => $data['event_type']
            );

            // Verificar si se envio solicitud a un usuario
            $cmd = '
                SELECT COUNT(id) AS invitacion
                FROM invitation 
                WHERE udestiny_id =:udestiny_id 
                    AND uorigin_id =:uorigin_id
                    AND event_type =:event_type
            ';          

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);
            $sql->setFetchMode(PDO::FETCH_OBJ);
            $response = $sql->fetch();

            if($response->invitacion > 0)
                return [1, 0];

            // Verificar si se recibio solicitud de un usuario
            $cmd = '
                SELECT COUNT(id) AS invitado
                FROM invitation 
                WHERE udestiny_id =:uorigin_id 
                    AND uorigin_id =:udestiny_id
                    AND event_type =:event_type
            ';          

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);
            $sql->setFetchMode(PDO::FETCH_OBJ);
            $response = $sql->fetch();

            if($response->invitado > 0)
                return [2, 0];

            // Verificar si ya son amigos
            $cmd = '
                SELECT COUNT(id) AS amigos, register_date
                FROM user_friends 
                WHERE user_id =:user_id 
                    AND friend_id =:friend_id
            ';

            $parametros = array(
                ':friend_id'    => $data['uorigin_id'],
                ':user_id'      => $data['udestiny_id']
            );      

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);
            $sql->setFetchMode(PDO::FETCH_OBJ);
            $response = $sql->fetch();

            if($response->amigos > 0)
                return [3, $response->register_date];

            return [0, 0];
        }

        // Metodo para listar todos los amigos de un usuario
        public function getFriends($userId){
            $pdo = new Conexion();

            $cmd = '
                SELECT 
                    uf.friend_id,
                    CONCAT(u.name, " ", u.last_name) AS name,
                    uf.register_date
                FROM 
                    user_friends AS uf 
                INNER JOIN user AS u ON uf.friend_id = u.id
                WHERE uf.user_id =:userId
            ';

            $parametros = array(
                ':userId' => $userId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Metodo para eliminar el registro de amistad
        public function deleteFriend($data){
            $pdo = new Conexion();

            $parametros = array(
                ':user_id'      => $data['user_id'],
                ':friend_id'    => $data['friend_id']
            );

            // Elimina ambos registros de cada usuario
            $cmd = '
                DELETE  FROM user_friends WHERE user_id =:user_id AND friend_id =:friend_id;
                DELETE  FROM user_friends WHERE user_id =:friend_id AND friend_id =:user_id;
            ';

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return TRUE;
        }
    }