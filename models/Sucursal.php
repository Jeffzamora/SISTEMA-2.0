<?php
    class Sucursal extends Conectar{

        /* TODO: Obtener todos los registros */
        public function get_sucursal(){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_sucursal WHERE est=1;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
        /* TODO:Registro x id */
        public function get_sucu_x_id($sucu_id){
            $conectar= parent::conexion();
            parent::set_names();
            $sql="SELECT * FROM tm_sucursal WHERE sucu_id = ?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $sucu_id);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

    }