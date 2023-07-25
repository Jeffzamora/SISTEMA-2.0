<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Logicsa::Nueva Caja</title>
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
						<h3>Nueva Caja</h3>
						<ol class="breadcrumb breadcrumb-simple">
							<li><a href="../Home/">Home</a></li>
							<li class="active">Nueva Caja</li>
						</ol>
					</div>
				</div>
			</div>
		</header>

		<div class="box-typical box-typical-padding">

			<h5 class="m-t-lg with-border">Ingresar Información de la caja</h5>

			<div class="row">
				<form method="post" id="caja_form">

					<input type="hidden" id="usu_id" name="usu_id" value="<?php echo $_SESSION["usu_id"]?>">
					<input type="hidden" id="sucu_id" name="sucu_id" value="<?php echo $_SESSION["sucu_id"]?>">

					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label semibold" for="caja_num">Caja Interna</label>
							<input type="text" class="form-control" id="caja_num" name="caja_num" placeholder="Ingrese Caja interna">
							<div id="caja-error" class="alert alert-danger" style="display: none;">El código de caja ya existe.</div>
						</fieldset>
					</div>

					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label semibold" for="caja_exp">Total de Expedientes</label>
							<input type="text" class="form-control" id="caja_exp" name="caja_exp" placeholder="Ingrese total Expediente">
						</fieldset>
					</div>

					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label semibold" for="caja_tipo">Tipo de expediente</label>
							<input type="text" class="form-control" id="caja_tipo" name="caja_tipo" placeholder="Tipo de expediente" value="CANCELADOS">
						</fieldset>
					</div>

					<div class="col-lg-4">
						<fieldset class="form-group">
							<label class="form-label semibold" for="caja_desde">Fecha Desde</label>
							<input type="date" class="form-control" id="caja_desde" name="caja_desde" >
						</fieldset>
					</div>

					<div class="col-lg-4">
						<fieldset class="form-group">
							<label class="form-label semibold" for="caja_hasta">Fecha Hasta</label>
							<input type="date" class="form-control" id="caja_hasta" name="caja_hasta" >
						</fieldset>
					</div>

					<div class="col-lg-12">
						<fieldset class="form-group">
							<label class="form-label semibold" for="remi_descrip">Comentarios</label>
							<div class="summernote-theme-1">
								<textarea id="caja_descrip" name="caja_descrip" class="summernote" name="name"></textarea>
							</div>
						</fieldset>
					</div>
					<div class="col-lg-12">
						<button type="submit" name="action" value="add" class="btn btn-rounded btn-inline btn-primary">Guardar</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>
	<!-- Contenido -->

	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="nuevacaja.js"></script>

	<script type="text/javascript" src="../notificacion.js"></script>

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>