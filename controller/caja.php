<?php
    /* TODO:Cadena de Conexion */
    require_once("../config/conexion.php");
    /* TODO:Clases Necesarias */
    require_once("../models/Caja.php");
    $caja = new Caja();
    require_once("../models/Usuario.php");
    $usuario = new Usuario();
    require_once("../models/Documento.php");
    $documento = new Documento();

    /*TODO: opciones del controlador Remision*/
    switch($_GET["op"]){

        case "validar":
             // Validar si el campo "caja_num" ya existe en la base de datos
            $caja_existe = $caja->buscar_remision_por_caja($_POST["caja_num"]);
            if (!empty($caja_existe)) {
                // Generar la alerta indicando que el código de caja ya existe
                $output["caja-error"] = "El código de caja ya existe en la base de datos.";
                echo json_encode($output);
                break;
            } 
        /* TODO: Insertar nueva Remision */
        case "insert":   
            $datos = $caja->insert_caja($_POST["caja_id"],$_POST["usu_id"],$_POST["sucu_id"],$_POST["caja_num"],$_POST["caja_exp"],$_POST["caja_tipo"],$_POST["caja_desde"],$_POST["caja_hasta"],$_POST["caja_descrip"]);
            echo json_encode($datos);
            break;
    }