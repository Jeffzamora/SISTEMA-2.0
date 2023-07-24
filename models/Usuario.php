<?php
    class Usuario extends Conectar{

        /* TODO: Funcion de login y generacion de session */
        public function login() {
            $conectar = parent::conexion();
            parent::set_names();
        
            if (isset($_POST["enviar"])) {
                $correo = $_POST["usu_correo"];
                $pass = $_POST["usu_pass"];
                $rol = $_POST["rol_id"];
        
                if (empty($correo) || empty($pass)) {
                    header("Location:".conectar::ruta()."index.php?m=2");
                    exit();
                } else {
                    // Verificar si el usuario ha sido bloqueado
                    $sql_bloqueo = "SELECT bloqueado FROM tm_login_attempts WHERE usu_correo=?";
                    $stmt_bloqueo = $conectar->prepare($sql_bloqueo);
                    $stmt_bloqueo->bindValue(1, $correo);
                    $stmt_bloqueo->execute();
                    $resultado_bloqueo = $stmt_bloqueo->fetch();
        
                    if ($resultado_bloqueo && $resultado_bloqueo["bloqueado"] == 1) {
                        header("Location:".conectar::ruta()."index.php?m=3");
                        exit();
                    }
        
                    // Verificar las credenciales del usuario
                    $sql = "SELECT usu_pass FROM tm_usuario WHERE usu_correo=? AND rol_id=? AND est=1";
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $correo);
                    $stmt->bindValue(2, $rol);
                    $stmt->execute();
                    $resultado = $stmt->fetch();
        
                    if ($resultado && password_verify($pass, $resultado["usu_pass"])) {
                        // Restablecer el contador de intentos fallidos y desbloquear al usuario
                        $sql_reset = "UPDATE tm_login_attempts SET intentos_fallidos=0, bloqueado=0 WHERE usu_correo=?";
                        $stmt_reset = $conectar->prepare($sql_reset);
                        $stmt_reset->bindValue(1, $correo);
                        $stmt_reset->execute();
        
                        // Almacenar los datos del usuario en la sesión
                        $_SESSION["usu_id"] = $resultado["usu_id"];
                        $_SESSION["usu_nom"] = $resultado["usu_nom"];
                        $_SESSION["usu_ape"] = $resultado["usu_ape"];
                        $_SESSION["rol_id"] = $resultado["rol_id"];
                        $_SESSION["sucu_id"] = $resultado["sucu_id"];
        
                        header("Location:".Conectar::ruta()."view/Home/");
                        exit();
                    } else {
                        // Incrementar el contador de intentos fallidos o insertar un registro si no existe
                        $sql_attempts = "INSERT INTO tm_login_attempts (usu_correo, intentos_fallidos, ultimo_intento) VALUES (?, 1, NOW()) ON DUPLICATE KEY UPDATE intentos_fallidos=intentos_fallidos+1, ultimo_intento=NOW()";
                        $stmt_attempts = $conectar->prepare($sql_attempts);
                        $stmt_attempts->bindValue(1, $correo);
                        $stmt_attempts->execute();
        
                        // Verificar si el usuario debe ser bloqueado
                        $sql_check_attempts = "SELECT intentos_fallidos FROM tm_login_attempts WHERE usu_correo=?";
                        $stmt_check_attempts = $conectar->prepare($sql_check_attempts);
                        $stmt_check_attempts->bindValue(1, $correo);
                        $stmt_check_attempts->execute();
                        $resultado_check_attempts = $stmt_check_attempts->fetch();
        
                        if ($resultado_check_attempts && $resultado_check_attempts["intentos_fallidos"] >= 3) {
                            // Bloquear al usuario
                            $sql_block = "UPDATE tm_login_attempts SET bloqueado=1 WHERE usu_correo=?";
                            $stmt_block = $conectar->prepare($sql_block);
                            $stmt_block->bindValue(1, $correo);
                            $stmt_block->execute();
        
                            header("Location:".conectar::ruta()."index.php?m=3");
                            exit();
                        } else {
                            header("Location:".conectar::ruta()."index.php?m=1");
                            exit();
                        }
                    }
                }
            }
        }
        

        /* TODO:Insert */
        public function insert_usuario($usu_nom,$usu_ape,$usu_correo,$usu_pass,$rol_id,$sucu_id,$usu_telf){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_usuario (usu_id, usu_nom, usu_ape, usu_correo, usu_pass, rol_id, sucu_id, usu_telf, fech_crea, fech_modi, fech_elim, est) VALUES (NULL,?,?,?,MD5(?),?,?,?,now(), NULL, NULL, '1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_ape);
            $sql->bindValue(3, $usu_correo);
            $sql->bindValue(4, $usu_pass);
            $sql->bindValue(5, $rol_id);
            $sql->bindValue(6, $sucu_id);
            $sql->bindValue(7, $usu_telf);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Update */
        public function update_usuario($usu_id,$usu_nom,$usu_ape,$usu_correo,$usu_pass,$rol_id,$sucu_id,$usu_telf){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_usuario set
                usu_nom = ?,
                usu_ape = ?,
                usu_correo = ?,
                usu_pass = ?,
                rol_id = ?,
                sucu_id = ?,
                usu_telf = ?
                WHERE
                usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_nom);
            $sql->bindValue(2, $usu_ape);
            $sql->bindValue(3, $usu_correo);
            $sql->bindValue(4, $usu_pass);
            $sql->bindValue(5, $rol_id);
            $sql->bindValue(6, $sucu_id);
            $sql->bindValue(7, $usu_telf);
            $sql->bindValue(8, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Delete */
        public function delete_usuario($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="call sp_d_usuario_01(?)";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Todos los registros */
        public function get_usuario(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="call sp_l_usuario_01()";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Obtener registros de usuarios segun rol 2 */
        public function get_usuario_x_rol(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario where est=1 and rol_id=2";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Registro x id */
        public function get_usuario_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_usuario WHERE usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Total de registros segun usu_id */
        public function get_usuario_total_x_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_remision where sucu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Total de Remision Abiertos por usu_id */
        public function get_usuario_totalabierto_x_id($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_remision where sucu_id = ? and remi_estado='Abierto'";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
         /* TODO: Total de Remisiones Procesadas por usu_id */
        public function get_usuario_totalprocesando_x_id($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_remision where sucu_id = ? and remi_estado='Procesando'";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* TODO: Total de Remisiones Cerrado por usu_id */
        public function get_usuario_totalcerrado_x_id($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_remision where sucu_id = ? and remi_estado='Cerrado'";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Total de Remisiones por sucursal segun usuario */
        public function get_usuario_grafico($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT tm_sucursal.sucu_nom as nom,COUNT(*) AS total
                FROM   tm_remision  JOIN  
                    tm_sucursal ON tm_remision.sucu_id = tm_sucursal.sucu_id  
                WHERE    
                tm_remision.sucu_id = ?
                GROUP BY 
                tm_sucursal.sucu_nom 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* TODO: Total de Remisiones por usuario segun sucursal*/
        public function get_sucursal_grafico($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
            tm_usuario.usu_nom as nom,COUNT(*) AS total
            FROM tm_remision
            JOIN tm_usuario ON tm_remision.usu_id = tm_usuario.usu_id  
            WHERE
            tm_remision.est = 0 and tm_remision.sucu_id = ?
            GROUP BY
            tm_usuario.usu_nom
            ORDER BY total DESC;";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* TODO: Actualizar contraseña del usuario */
        public function update_usuario_pass($usu_id,$usu_pass){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="UPDATE tm_usuario
                SET
                    usu_pass = MD5(?)
                WHERE
                    usu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_pass);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        public function get_sucursal_id($usu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
            tm_usuario.sucu_id,
            tm_sucursal.sucu_nom 
            FROM tm_usuario 
            INNER join tm_sucursal on tm_usuario.sucu_id = tm_sucursal.sucu_id
            WHERE tm_usuario.usu_id=? ;";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

    }
