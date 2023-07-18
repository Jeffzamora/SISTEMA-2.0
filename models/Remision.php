<?php
    class Remision extends Conectar{
        /* TODO: Insertar Nueva Remision */
        public function insert_remision($usu_id,$sucu_id,$remi_caja,$remi_exp,$remi_cancel,$remi_desde,$remi_hasta,$remi_descrip){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_remision(usu_id,sucu_id,remi_caja,remi_exp,remi_cancel,remi_desde,remi_hasta,remi_descrip, remi_estado,fech_crea,est)
            VALUES 
            (?,?,?,?,?,?,?,?,'Abierto',now(),0);";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_id);
            $sql->bindValue(2, $sucu_id);
            $sql->bindValue(3, $remi_caja);
            $sql->bindValue(4, $remi_exp);
            $sql->bindValue(5, $remi_cancel);
            $sql->bindValue(6, $remi_desde);
            $sql->bindValue(7, $remi_hasta);
            $sql->bindValue(8, $remi_descrip);
            $sql->execute();

            $sql1="select last_insert_id() as 'remi_id';";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        /* TODO: Listar Remision segun Sucursal de usuario */
        public function listar_remi_x_sucu(){
            $conectar= parent::conexion();
            parent::set_names();
            $sucu_id= $_SESSION["sucu_id"];
            $sql="SELECT 
            tm_remision.remi_id,
            tm_remision.usu_id,
            tm_remision.sucu_id,
            tm_remision.remi_cancel,
            tm_remision.remi_estado,
            tm_remision.fech_crea,
            tm_remision.fech_cierre,
            tm_remision.usu_asig,
            tm_remision.fech_asig,
            tm_usuario.usu_nom,
            tm_usuario.usu_ape,
            tm_sucursal.sucu_nom
            FROM 
            tm_remision
            INNER join tm_sucursal on tm_remision.sucu_id = tm_sucursal.sucu_id
            INNER join tm_usuario on tm_remision.usu_id = tm_usuario.usu_id
            WHERE
            tm_remision.est = 1
            AND tm_remision.sucu_id = '$sucu_id'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* TODO: Mostrar Remision segun id de Remision */
        public function listar_remision_x_id($remi_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT 
            tm_remision.remi_id,
            tm_remision.usu_id,
            tm_remision.sucu_id,
            tm_remision.remi_caja,
            tm_remision.remi_exp,
            tm_remision.remi_cancel,
            tm_remision.remi_desde,
            tm_remision.remi_hasta,
            tm_remision.remi_descrip,
            tm_remision.remi_estado,
            tm_remision.fech_crea,
            tm_remision.fech_cierre,
            tm_remision.remi_estre,
            tm_remision.remi_coment,
            tm_remision.usu_asig,
            tm_usuario.usu_nom,
            tm_usuario.usu_ape,
            tm_usuario.usu_correo,
            tm_usuario.usu_telf,
            tm_sucursal.sucu_nom
            FROM 
            tm_remision
            INNER join tm_sucursal on tm_remision.sucu_id = tm_sucursal.sucu_id
            INNER join tm_usuario on tm_remision.usu_id = tm_usuario.usu_id
            AND tm_remision.remi_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $remi_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Mostrar todos las Remisiones */
        public function listar_remision(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT
                tm_remision.remi_id,
                tm_remision.usu_id,
                tm_remision.sucu_id,
                tm_remision.remi_exp,
                tm_remision.remi_descrip,
                tm_remision.remi_estado,
                tm_remision.fech_crea,
                tm_remision.fech_cierre,
                tm_remision.usu_asig,
                tm_remision.fech_asig,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_sucursal.sucu_nom
                FROM 
                tm_remision
                INNER join tm_sucursal on tm_remision.sucu_id = tm_sucursal.sucu_id
                INNER join tm_usuario on tm_remision.usu_id = tm_usuario.usu_id
                WHERE
                tm_remision.est = 1";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Mostrar detalle de Remision por id de Remision */
        public function listar_remisiondetalle_x_remision($remi_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT
                td_remisiondetalle.remid_id,
                td_remisiondetalle.remid_descrip,
                td_remisiondetalle.fech_crea,
                tm_usuario.usu_nom,
                tm_usuario.usu_ape,
                tm_usuario.rol_id
                FROM 
                td_remisiondetalle
                INNER join tm_usuario on td_remisiondetalle.usu_id = tm_usuario.usu_id
                WHERE 
                remi_id =?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $remi_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Insert Remision detalle */
        public function insert_ticketdetalle($remi_id,$usu_id,$remid_descrip){
            $conectar= parent::conexion();
            parent::set_names();

            /* TODO:Obtener usuario asignado del remi_id */
            $remision = new Remision();
            $datos = $remision->listar_remision_x_id($remi_id);
            foreach ($datos as $row){
                $usu_asig = $row["usu_asig"];
                $usu_crea = $row["usu_id"];
            }

            /* TODO: si Rol es 1 = Usuario insertar alerta para usuario soporte */
            if ($_SESSION["rol_id"]==1){
                /* TODO: Guardar Notificacion de nuevo Comentario */
                $sql0="INSERT INTO tm_notificacion (not_id,usu_id,not_mensaje,tick_id,est) VALUES (null, $usu_asig ,'Tiene una nueva respuesta del usuario con nro de ticket : ',$tick_id,2)";
                $sql0=$conectar->prepare($sql0);
                $sql0->execute();
            /* TODO: Else Rol es = 2 Soporte Insertar alerta para usuario que genero la Remision */
            }else{
                /* TODO: Guardar Notificacion de nuevo Comentario */
                $sql0="INSERT INTO tm_notificacion (not_id,usu_id,not_mensaje,tick_id,est) VALUES (null,$usu_crea,'Tiene una nueva respuesta de soporte del ticket Nro : ',$tick_id,2)";
                $sql0=$conectar->prepare($sql0);
                $sql0->execute();
            }

            $sql="INSERT INTO td_ticketdetalle (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) VALUES (NULL,?,?,?,now(),'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->bindValue(3, $tickd_descrip);
            $sql->execute();

            /* TODO: Devuelve el ultimo ID (Identty) ingresado */
            $sql1="select last_insert_id() as 'tickd_id';";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        /* TODO: Insertar linea adicional de detalle al cerrar el ticket */
        public function insert_ticketdetalle_cerrar($tick_id,$usu_id){
            $conectar= parent::conexion();
            parent::set_names();
                $sql="call sp_i_ticketdetalle_01(?,?)";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Insertar linea adicional al reabrir el ticket */
        public function insert_ticketdetalle_reabrir($tick_id,$usu_id){
            $conectar= parent::conexion();
            parent::set_names();
                $sql="	INSERT INTO td_ticketdetalle 
                    (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) 
                    VALUES 
                    (NULL,?,?,'Ticket Re-Abierto...',now(),'1');";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->bindValue(2, $usu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: actualizar ticket */
        public function update_ticket($tick_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="update tm_ticket 
                set	
                    tick_estado = 'Cerrado',
                    fech_cierre = now()
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Cambiar estado del ticket al reabrir */
        public function reabrir_ticket($tick_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="update tm_ticket 
                set	
                    tick_estado = 'Abierto'
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO:Actualizar usu_asig con usuario de soporte asignado */
        public function update_ticket_asignacion($tick_id,$usu_asig){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="update tm_ticket 
                set	
                    usu_asig = ?,
                    fech_asig = now()
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $usu_asig);
            $sql->bindValue(2, $tick_id);
            $sql->execute();

            /* TODO: Guardar Notificacion en la tabla */
            $sql1="INSERT INTO tm_notificacion (not_id,usu_id,not_mensaje,tick_id,est) VALUES (null,?,'Se le ha asignado el ticket Nro : ',?,2)";
            $sql1=$conectar->prepare($sql1);
            $sql1->bindValue(1, $usu_asig);
            $sql1->bindValue(2, $tick_id);
            $sql1->execute();

            return $resultado=$sql->fetchAll();
        }

        /* TODO: Obtener total de tickets */
        public function get_ticket_total(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Total de ticket Abiertos */
        public function get_ticket_totalabierto(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where tick_estado='Abierto'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Total de ticket Cerrados */
        public function get_ticket_totalcerrado(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT COUNT(*) as TOTAL FROM tm_ticket where tick_estado='Cerrado'";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        } 

        /* TODO:Total de ticket por categoria */
        public function get_ticket_grafico(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT tm_categoria.cat_nom as nom,COUNT(*) AS total
                FROM   tm_ticket  JOIN  
                    tm_categoria ON tm_ticket.cat_id = tm_categoria.cat_id  
                WHERE    
                tm_ticket.est = 1
                GROUP BY 
                tm_categoria.cat_nom 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Actualizar valor de estrellas de encuesta */
        public function insert_encuesta($tick_id,$tick_estre,$tick_comment){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="update tm_ticket 
                set	
                    tick_estre = ?,
                    tick_coment = ?
                where
                    tick_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $tick_estre);
            $sql->bindValue(2, $tick_comment);
            $sql->bindValue(3, $tick_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Filtro Avanzado de ticket */
        public function filtrar_ticket($tick_titulo,$cat_id,$prio_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="call filtrar_ticket (?,?,?)";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, "%".$tick_titulo."%");
            $sql->bindValue(2, $cat_id);
            $sql->bindValue(3, $prio_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();

        }

    }
?>