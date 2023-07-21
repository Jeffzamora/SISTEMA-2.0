<?php
require_once("../../config/conexion.php");

// Comprobamos si el usuario ha iniciado sesión o no
$sesion_iniciada = isset($_SESSION["usu_id"]);

// Redireccionar al inicio de sesión si no ha iniciado sesión
if (!$sesion_iniciada) {
    header("Location: " . Conectar::ruta() . "index.php");
    exit; // Asegurarse de que el script se detenga después de la redirección
}
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
    <title>Logicsa: Usuarios</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/nav.php");?>

    <!-- Contenido -->
    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Mantenimiento Usuario</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="..\Home\">Home</a></li>
                                <li class="active">Mantenimiento Usuarios</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <button type="button" id="btnnuevo" class="btn btn-inline btn-primary">Nuevo Registro</button>
                <table id="usuario_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Nombre</th>
                            <th style="width: 10%;">Apellido</th>
                            <th class="d-none d-sm-table-cell" style="width: 40%;">Correo</th>
                            <th class="d-none d-sm-table-cell" style="width: 5%;">Sucursal</th>
                            <th class="d-none d-sm-table-cell" style="width: 5%;">Rol</th>
                            <th class="text-center" style="width: 5%;"></th>
                            <th class="text-center" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se colocaría el contenido de la tabla si el usuario está autenticado -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- Contenido -->

    <?php require_once("modalmantenimiento.php");?>

    <?php require_once("../MainJs/js.php");?>
    
    <?php if ($sesion_iniciada): ?>
        <script type="text/javascript" src="mntusuario.js"></script>
        <script type="text/javascript" src="../notificacion.js"></script>
    <?php endif; ?>
</body>
</html>
