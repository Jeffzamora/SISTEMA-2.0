<?php
/*TODO: librerias necesarias para que el proyecto pueda enviar emails */
require('class.phpmailer.php');
include("class.smtp.php");

/*TODO: llamada de las clases necesarias que se usaran en el envio del mail */
require_once("../config/conexion.php");
require_once("../models/Remision.php");

class Email extends PHPMailer{

    //TODO: variable que contiene el correo del destinatario
    protected $cCorreo = 'solicitudes@logicsa.net';
    //TODO: variable que contiene la contraseña del destinatario
    protected $gCorreo = 'no-reply@logicsa.net';
    protected $gContrasena = 'Soporte02';
    protected $tu_nombre = 'LOGICSA';
    /* TODO:Alertar al momento de generar una Remision */
    public function remision_abierto($remi_id){
        $remision = new Remision();
        $datos = $remision->listar_remision_x_id($remi_id);
        foreach ($datos as $row){
            $id = $row["remi_id"];
            $usu = $row["usu_nom"];
            $titulo = $row["remi_caja"];
            $categoria = $row["sucu_nom"];
            $correo = $row["usu_correo"];
        }

        //TODO: IGual//
        $this->IsSMTP();
        $this->Host = 'mail.ideay.info';//Aqui el server
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->From = $this->gCorreo;
        $this->cc = $this->cCorreo;
        $this->SMTPSecure = 'ssl';
        $this->FromName = $this->tu_nombre = "Remision Abierta".$id;
        $this->CharSet = 'UTF8';
        $this->addAddress($correo);
        $this->addAddress($_SESSION["usu_email"]);
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = "Remision Abierta";
        //Igual//
        $cuerpo = file_get_contents('../public/NuevoTicket.html'); /*TODO:  Ruta del template en formato HTML */
        /*TODO: parametros del template a remplazar */
        $cuerpo = str_replace("xnroticket", $id, $cuerpo);
        $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
        $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
        $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Remision Abierta");
        return $this->Send();
    }
    /* TODO:Alertar al momento de Cerrar una Remision */
    public function remision_cerrado($remi_id){
        $remision = new Remision();
        $datos = $remision->listar_remision_x_id($remi_id);
        foreach ($datos as $row){
            $id = $row["remi_id"];
            $usu = $row["usu_nom"];
            $titulo = $row["remi_caja"];
            $categoria = $row["sucu_nom"];
            $correo = $row["usu_correo"];
        }

        //IGual//
        $this->IsSMTP();
        $this->Host = 'smtp.office365.com';//Aqui el server
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->From = $this->gCorreo;
        $this->SMTPSecure = 'tls';
        $this->FromName = $this->tu_nombre = "Ticket Cerrado ".$id;
        $this->CharSet = 'UTF8';
        $this->addAddress($correo);
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = "Ticket Cerrado";
        //Igual//
        $cuerpo = file_get_contents('../public/CerradoTicket.html'); /*TODO:  Ruta del template en formato HTML */
        /*TODO:  parametros del template a remplazar */
        $cuerpo = str_replace("xnroticket", $id, $cuerpo);
        $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
        $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
        $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Ticket Cerrado");
        return $this->Send();
    }
    /* TODO:Alertar al momento de Re-abrir un ticket */
    public function remision_re_abrir($remi_id){
        $remision = new Remision();
        $datos = $remision->listar_remision_x_id($remi_id);
        foreach ($datos as $row){
            $id = $row["remi_id"];
            $usu = $row["usu_nom"];
            $titulo = $row["remi_caja"];
            $categoria = $row["sucu_nom"];
            $correo = $row["usu_correo"];
        }

        //IGual//
        $this->IsSMTP();
        $this->Host = 'mail.ideay.info';//Aqui el server
        $this->Port = 465;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->From = $this->gCorreo;
        $this->cc = $this->cCorreo;
        $this->SMTPSecure = 'tls';
        $this->FromName = $this->tu_nombre = "Ticket Cerrado ".$id;
        $this->CharSet = 'UTF8';
        $this->addAddress($correo);
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = "Ticket Cerrado";
        //Igual//
        $cuerpo = file_get_contents('../public/CerradoTicket.html'); /*TODO:  Ruta del template en formato HTML */
        /*TODO:  parametros del template a remplazar */
        $cuerpo = str_replace("xnroticket", $id, $cuerpo);
        $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
        $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
        $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Ticket Cerrado");
        return $this->Send();
    }
    /* TODO:Alertar al momento de Asignar un Remision */
    public function remision_asignado($remi_id){
        $remision = new Remision();
        $datos = $remision->listar_remision_x_id($remi_id);
        foreach ($datos as $row){
            $id = $row["remi_id"];
            $usu = $row["usu_nom"];
            $titulo = $row["remi_caja"];
            $categoria = $row["sucu_nom"];
            $correo = $row["usu_correo"];
        }

        //IGual//
        $this->IsSMTP();
        $this->Host = 'mail.ideay.info';//Aqui el server
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->From = $this->gCorreo;
        $this->cc = $this->cCorreo;
        $this->SMTPSecure = 'tls';
        $this->FromName = $this->tu_nombre = "Remision Asignada".$id;
        $this->CharSet = 'UTF8';
        $this->addAddress($correo);
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = "Remision Asignada";
        //Igual//
        $cuerpo = file_get_contents('../public/AsignarTicket.html'); /*TODO:  Ruta del template en formato HTML */
        /*TODO:  parametros del template a remplazar */
        $cuerpo = str_replace("xnroticket", $id, $cuerpo);
        $cuerpo = str_replace("lblNomUsu", $usu, $cuerpo);
        $cuerpo = str_replace("lblTitu", $titulo, $cuerpo);
        $cuerpo = str_replace("lblCate", $categoria, $cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Remision Asignada");
        return $this->Send();
    }

}