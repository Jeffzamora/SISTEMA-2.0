<?php
    // Cadena de Conexión
    require_once(__DIR__ . "/../config/conexion.php");

    // Modelo Usuario
    require_once(__DIR__ . "/../models/Usuario.php");
    $usuarioModelo = new Usuario();

    // Opciones del controlador Usuario
    $opcion = isset($_GET["op"]) ? $_GET["op"] : "";

    switch ($opcion) {
        case "guardaryeditar":
            $usuId = isset($_POST["usu_id"]) ? $_POST["usu_id"] : "";
            $usuNom = $_POST["usu_nom"];
            $usuApe = $_POST["usu_ape"];
            $usuCorreo = $_POST["usu_correo"];
            $usuPass = $_POST["usu_pass"];
            $rolId = $_POST["rol_id"];
            $sucuId = $_POST["sucu_id"];
            $usuTelf = $_POST["usu_telf"];

            if (empty($usuId)) {
                $usuarioModelo->insert_usuario($usuNom, $usuApe, $usuCorreo, $usuPass, $rolId, $sucuId, $usuTelf);
            } else {
                $usuarioModelo->update_usuario($usuId, $usuNom, $usuApe, $usuCorreo, $usuPass, $rolId, $sucuId, $usuTelf);
            }
            break;

        case "listar":
            $datos = $usuarioModelo->get_usuario();
            $data = array();
            foreach ($datos as $row) {
                $sub_array = array(
                    $row["usu_nom"],
                    $row["usu_ape"],
                    $row["usu_correo"],
                    $row["sucu_nom"],
                    $row["rol_id"] == "1" ? '<span class="label label-pill label-info">FAMA</span>' : '<span class="label label-pill label-success">LOGICSA</span>',
                    '<button type="button" onClick="editar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>',
                    '<button type="button" onClick="eliminar('.$row["usu_id"].');"  id="'.$row["usu_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>'
                );
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

        case "eliminar":
            $usuId = $_POST["usu_id"];
            $usuarioModelo->delete_usuario($usuId);
            break;

        case "mostrar":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_x_id($usuId);
            $output = array();

            if (is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                $output["usu_id"] = $row["usu_id"];
                $output["usu_nom"] = $row["usu_nom"];
                $output["usu_ape"] = $row["usu_ape"];
                $output["usu_correo"] = $row["usu_correo"];
                $output["usu_pass"] = $row["usu_pass"];
                $output["rol_id"] = $row["rol_id"];
                $output["sucu_id"] = $row["sucu_id"];
                $output["usu_telf"] = $row["usu_telf"];
            }
            echo json_encode($output);
            break;

        case "total":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_total_x_id($usuId);
            $output = array();

            if (is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
            break;

        case "totalabierto":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_totalabierto_x_id($usuId);
            $output = array();

            if (is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
            break;

        case "totalprocesando":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_totalprocesando_x_id($usuId);
            $output = array();

            if (is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
            break;

        case "totalcerrado":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_totalcerrado_x_id($usuId);
            $output = array();

            if (is_array($datos) && count($datos) > 0) {
                $row = $datos[0];
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
            break;

        case "grafico":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_usuario_grafico($usuId);
            echo json_encode($datos);
            break;

        case "grafico_sucursal":
            $sucuId = $_POST["sucu_id"];
            $datos = $usuarioModelo->get_sucursal_grafico($sucuId);
            echo json_encode($datos);
            break;

        case "combo":
            $datos = $usuarioModelo->get_usuario_x_rol();
            $html = "<option label='Seleccionar'></option>";

            if (is_array($datos) && count($datos) > 0) {
                foreach ($datos as $row) {
                    $html .= "<option value='".$row['usu_id']."'>".$row['usu_nom']."</option>";
                }
            }
            echo $html;
            break;

        case "password":
            $usuId = $_POST["usu_id"];
            $usuPass = $_POST["usu_pass"];
            $usuarioModelo->update_usuario_pass($usuId, $usuPass);
            break;

        case "sucursal":
            $usuId = $_POST["usu_id"];
            $datos = $usuarioModelo->get_sucursal_id($usuId);
            $html = "<option label='Seleccionar'></option>";

            if (is_array($datos) && count($datos) > 0) {
                foreach ($datos as $row) {
                    $html .= "<option value='".$row['sucu_id']."'>".$row['sucu_nom']."</option>";
                }
            }
            echo $html;
            break;

        default:
            echo "Opción inválida";
            break;
    }
