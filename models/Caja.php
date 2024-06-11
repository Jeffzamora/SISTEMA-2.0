<?php
    class Caja extends Conectar{
        /* TODO: Insertar Nueva Remision */
        public function insert_caja($caja_id,$usu_id,$sucu_id,$caja_num,$caja_exp,$caja_tipo,$caja_desde,$caja_hasta,$caja_descrip){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="INSERT INTO tm_cajas(caja_id,usu_id,sucu_id,caja_num,caja_exp,caja_tipo,caja_desde,caja_hasta,caja_descrip,est)
            VALUES 
            (NULL,?,?,?,?,?,?,?,?,0);";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $caja_id);
            $sql->bindValue(2, $usu_id);
            $sql->bindValue(3, $sucu_id);
            $sql->bindValue(4, $caja_num);
            $sql->bindValue(5, $caja_exp);
            $sql->bindValue(6, $caja_tipo);
            $sql->bindValue(7, $caja_desde);
            $sql->bindValue(8, $caja_hasta);
            $sql->bindValue(9, $caja_descrip);
            $sql->execute();

            $sql1="select last_insert_id() as 'caja_id';";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        public function buscar_remision_por_caja($caja_num) {
            $conectar= parent::conexion();
            parent::set_names();
             // Preparar la consulta SQL
            $sql = "SELECT * FROM tm_cajas WHERE caja_num = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $caja_num);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            return $resultado;
            }

    }