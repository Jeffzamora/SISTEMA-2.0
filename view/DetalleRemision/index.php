<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
  <!DOCTYPE html>
  <html>
  <?php require_once("../MainHead/head.php"); ?>
  <title>Logicsa: :Detalle Remision</title>
  </head>

  <body class="with-side-menu">

    <?php require_once("../MainHeader/header.php"); ?>

    <div class="mobile-menu-left-overlay"></div>

    <?php require_once("../MainNav/nav.php"); ?>

    <!-- Contenido -->
    <div class="page-content">
      <div class="container-fluid">

        <header class="section-header">
          <div class="tbl">
            <div class="tbl-row">
              <div class="tbl-cell">
                <h3 id="lblnomidticket">Detalle Remision - 1</h3>
                <div id="lblestado"></div>
                <span class="label label-pill label-primary" id="lblnomusuario"></span>
                <span class="label label-pill label-default" id="lblfechcrea"></span>
                <ol class="breadcrumb breadcrumb-simple">
                  <li><a href="../Home/">Home</a></li>
                  <li class="active">Detalle Remision</li>
                </ol>
              </div>
            </div>
          </div>
        </header>

        <div class="box-typical box-typical-padding">
          <div class="row">

              <div class="col-lg-3">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="sucu_id">Sucursal</label>
                  <input type="text" class="form-control" id="sucu_id" name="sucu_id" readonly>
                </fieldset>
              </div>

              <div class="col-lg-3">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="remi_codigo">REMISION</label>
                  <input type="text" class="form-control" id="remi_id" name="remi_id" readonly>
                </fieldset>
              </div>

              <div class="col-lg-3">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="remi_caja">CAJA INTERNA</label>
                  <input type="text" class="form-control" id="remi_caja" name="remi_caja" readonly>
                </fieldset>
              </div>

              <div class="col-lg-3">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="remi_exp">TOTAL DE EXPEDIENTE</label>
                  <input type="text" class="form-control" id="remi_exp" name="remi_exp" readonly>
                </fieldset>
              </div>

              <div class="col-lg-3">
							  <fieldset class="form-group">
								  <label class="form-label semibold" for="remi_cancel">Tipo de expediente</label>
								  <input type="text" class="form-control" id="remi_cancel" name="remi_cancel" readonly>
							  </fieldset>
						  </div>

              <div class="col-lg-4">
							  <fieldset class="form-group">
								  <label class="form-label semibold" for="remi_desde">Fecha Desde</label>
								  <input type="text" class="form-control" id="remi_desde" name="remi_desde" readonly>
							  </fieldset>
						  </div>

						<div class="col-lg-4">
							<fieldset class="form-group">
								<label class="form-label semibold" for="remi_hasta">Fecha Hasta</label>
								<input type="text" class="form-control" id="remi_hasta" name="remi_hasta" readonly>
							</fieldset>
						</div>

              <div class="col-lg-12">
                <fieldset class="form-group">
                  <table id="documentos_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                      <tr>
                        <th style="width: 90%;">Archivo</th>
                        <th class="text-center" style="width: 10%;">Descarga</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
                </fieldset>
              </div>

              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="tickd_descripusu">Comentarios</label>
                  <div class="summernote-theme-1">
                    <textarea id="tickd_descripusu" name="tickd_descripusu" class="summernote" name="name"></textarea>
                  </div>

                </fieldset>
              </div>

          </div>
        </div>

        <section class="activity-line" id="lbldetalle">

        </section>

        <div class="box-typical box-typical-padding" id="pnldetalle">
          <p>
            Ingrese su duda o consulta
          </p>
          <div class="row">
              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="remid_descrip">Escribir</label>
                  <div class="summernote-theme-1">
                    <textarea id="remid_descrip" name="remid_descrip" class="summernote" name="name"></textarea>
                  </div>
                </fieldset>
              </div>

              <!-- TODO: Agregar archivos adjuntos -->
              <div class="col-lg-12">
                <fieldset class="form-group">
                  <label class="form-label semibold" for="fileElem">Documentos Adicionales</label>
                  <input type="file" name="fileElem" id="fileElem" class="form-control" multiple>
                </fieldset>
              </div>

              <div class="col-lg-12">
                <button type="button" id="btnenviar" class="btn btn-rounded btn-inline btn-primary">Enviar</button>
                <?php
                  if($_SESSION["rol_id"]==2){
                    ?>
                       <button type="button" id="btncerrarticket" class="btn btn-rounded btn-inline btn-warning">Cerrar Remision</button>
                    <?php
                  }
                ?>
              </div>
          </div>
			  </div>

      </div>
    </div>
    <!-- Contenido -->

    <?php require_once("../MainJs/js.php"); ?>

    <script type="text/javascript" src="detalleremision.js"></script>

    <script type="text/javascript" src="../notificacion.js"></script>

  </body>

  </html>
<?php
} else {
  header("Location:" . Conectar::ruta() . "index.php");
}
?>