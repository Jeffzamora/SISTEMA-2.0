<?php
    class Documento extends Conectar{
        /* TODO: Insertar registro  */
        public function insert_documento($remi_id,$doc_nom){
            $conectar= parent::conexion();
            /* consulta sql */
            $sql="INSERT INTO td_documento (doc_id,remi_id,doc_nom,fech_crea,est) VALUES (null,?,?,now(),1);";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(1,$remi_id);
            $sql->bindParam(2,$doc_nom);
            $sql->execute();
        }

        /* TODO: Obtener Documento por Remision */
        public function get_documento_x_remision($remi_id){
            $conectar= parent::conexion();
            /* consulta sql */
            $sql="SELECT * FROM td_documento WHERE remi_id=?";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(1,$remi_id);
            $sql->execute();
            return $resultado = $sql->fetchAll(pdo::FETCH_ASSOC);
        }

        /* TODO: insertar documento detalle */
        public function insert_documento_detalle($remid_id,$det_nom){
            $conectar= parent::conexion();
            /* consulta sql */
            $sql="INSERT INTO td_documento_detalle (det_id,remid_id,det_nom,est) VALUES (null,?,?,1);";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(1,$remid_id);
            $sql->bindParam(2,$det_nom);
            $sql->execute();
        }

        /* TODO: Obtener Documento x detalle */
        public function get_documento_detalle_x_remid($remid_id){
            $conectar= parent::conexion();
            /* consulta sql */
            $sql="SELECT * FROM td_documento_detalle WHERE remid_id=?";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(1,$remid_id);
            $sql->execute();
            return $resultado = $sql->fetchAll(pdo::FETCH_ASSOC);
        }
    }
