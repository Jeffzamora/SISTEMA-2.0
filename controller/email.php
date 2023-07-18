<?php
/* Llamada a las clases necesarias */
require_once("../config/conexion.php");
require_once("../models/Email.php");
$email = new Email();

/* Opciones del controlador */
$op = $_GET["op"] ?? "";

switch ($op) {
    /* Enviar remisión abierto según el ID */
    case "remision_abierto":
        $remiId = $_POST["remi_id"] ?? "";
        if (!empty($remiId)) {
            $email->remision_abierto($remiId);
        }
        break;

    /* Enviar Remisión Reabrir según el ID */
    case "remision_re_abrir":
        $remiId = $_POST["remi_id"] ?? "";
        if (!empty($remiId)) {
            $email->remision_re_abrir($remiId);
        }
        break;

    /* Enviar Remisión Cerrado según el ID */
    case "remision_cerrado":
        $remiId = $_POST["remi_id"] ?? "";
        if (!empty($remiId)) {
            $email->remision_cerrado($remiId);
        }
        break;

    /* Enviar remisión asignado según el ID */
    case "remision_asignado":
        $remiId = $_POST["remi_id"] ?? "";
        if (!empty($remiId)) {
            $email->remision_asignado($remiId);
        }
        break;
}