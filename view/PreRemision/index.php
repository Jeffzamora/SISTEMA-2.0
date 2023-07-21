<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Logicsa: :Remision en Sucursal</title>
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
							<h3>Remision en Sucursal</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="..\Home\">Home</a></li>
								<li class="active">Remision en Sucursal</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				
				<div class="row" id="viewuser">
					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label" for="remi_codigo">Remision</label>
							<input type="text" class="form-control" id="remi_id" name="remi_id" placeholder="Numero de Remision" required>
						</fieldset>
					</div>

					<div class="col-lg-3">
						<fieldset class="form-group">
							<label class="form-label" for="sucu_id">sucursal</label>
							<select class="select2" id="sucu_id" name="sucu_id" data-placeholder="Seleccionar">
								<option label="Seleccionar"></option>

							</select>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="remi_estado">Estado</label>
							<select class="select2" id="remi_estado" name="remi_estado" data-placeholder="Seleccionar">
								<option value="" selected></option>
								<option value="Abierto">Abierto</option>
  								<option value="Procesando">Procesando</option>
  								<option value="Cerrado">Cerrado</option>
							</select>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="btnfiltrar">&nbsp;</label>
							<button type="submit" class="btn btn-rounded btn-primary btn-block" id="btnfiltrar">Filtrar</button>
						</fieldset>
					</div>

					<div class="col-lg-2">
						<fieldset class="form-group">
							<label class="form-label" for="btntodo">&nbsp;</label>
							<button class="btn btn-rounded btn-primary btn-block" id="btntodo">Ver Todo</button>
						</fieldset>
					</div>
				</div>

				<div class="box-typical box-typical-padding" id="table">
					<table id="ticket_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th style="width: 5%;">N° Caja</th>
								<th style="width: 15%;">Sucursal</th>
								<th class="d-none d-sm-table-cell" style="width: 15%;">Tipo</th>
								<th class="d-none d-sm-table-cell" style="width: 5%;">Estado</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Fecha Creación</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Fecha Asignación</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Fecha Cierre</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">TECNICO</th>
								<th class="text-center" style="width: 5%;"></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div>

		</div>
	</div>
	<!-- Contenido -->
	<?php require_once("../MainJs/js.php");?>

	<script type="text/javascript" src="preremision.js"></script>

	<script type="text/javascript" src="../notificacion.js"></script>

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>