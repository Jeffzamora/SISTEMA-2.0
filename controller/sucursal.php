<?php
/* TODO: Cadena de Conexion */
require_once("../config/conexion.php");
/* TODO: Modelo Sucursal */
require_once("../models/Sucursal.php");

$sucursal = new Sucursal();

/* TODO: Opciones del controlador Sucursal */
switch ($_GET["op"]) {
    case "listar":
        $datos = $sucursal->get_sucursal();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["sucu_id"];
            $sub_array[] = $row["sucu_num"];
            $sub_array[] = $row["sucu_nom"];
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case "mostrar":
        $datos = $sucursal->get_sucu_x_id($_POST["sucu_id"]);

        if (is_array($datos) == true && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["sucu_id"] = $row["sucu_id"];
                $output["sucu_num"] = $row["sucu_num"];
                $output["sucu_nom"] = $row["sucu_nom"];
            }
            echo json_encode($output);
        }
        break;

    case "combo":
        $datos = $sucursal->get_sucursal();
        $html = "<option value=''>Seleccionar</option>";

        if (is_array($datos) == true && count($datos) > 0) {
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['sucu_id'] . "'>" . $row['sucu_nom'] . "</option>";
            }
            echo $html;
        }
        break;
}

