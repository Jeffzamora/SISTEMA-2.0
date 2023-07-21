<?php
    /* TODO:Cadena de Conexion */
    require_once("../config/conexion.php");
    /* TODO:Clases Necesarias */
    require_once("../models/Remision.php");
    $remision = new Remision();
    require_once("../models/Usuario.php");
    $usuario = new Usuario();
    require_once("../models/Documento.php");
    $documento = new Documento();

    /*TODO: opciones del controlador Remision*/
    switch($_GET["op"]){

        /* TODO: Insertar nueva Remision */
        case "insert":
            $datos=$remision->insert_remision($_POST["usu_id"],$_POST["sucu_id"],$_POST["remi_caja"],$_POST["remi_exp"],$_POST["remi_cancel"],$_POST["remi_desde"],$_POST["remi_hasta"],$_POST["remi_descrip"]);
            /* TODO: Obtener el ID del ultimo registro insertado */
            if (is_array($datos)==true and count($datos)>0){
                foreach ($datos as $row){
                    $output["remi_id"] = $row["remi_id"];

                    /* TODO: Validamos si vienen archivos desde la Vista */
                    if (empty($_FILES['files']['name'])){

                    }else{
                        /* TODO:Contar Cantidad de Archivos desde la Vista */
                        $countfiles = count($_FILES['files']['name']);
                        /* TODO: Generamos ruta segun el ID del ultimo registro insertado */
                        $ruta = "../public/document/".$output["remi_id"]."/";
                        $files_arr = array();

                        /* TODO: Preguntamos si la ruta existe, en caso no exista la creamos */
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }

                        /* TODO:Recorremos los archivos, y insertamos tantos detalles como documentos vinieron desde la vista */
                        for ($index = 0; $index < $countfiles; $index++) {
                            $doc1 = $_FILES['files']['tmp_name'][$index];
                            $destino = $ruta.$_FILES['files']['name'][$index];

                            /* TODO: Insertamos Documentos */
                            $documento->insert_documento( $output["remi_id"],$_FILES['files']['name'][$index]);

                            /* TODO: Movemos los archivos hacia la carpeta creada */
                            move_uploaded_file($doc1,$destino);

                            /* TODO: Establecer permisos de archivo a 777 */
                           // chmod($destino, 0777);
                        }
                    }
                }
            }
            echo json_encode($datos);
            break;

        /* TODO: Actualizamos la Remision a cerrado y adicionamos una linea adicional */
        case "update":
            $remision->update_remision($_POST["remi_id"]);
            $remision->insert_remisiondetalle_cerrar($_POST["remi_id"],$_POST["usu_id"]);
            break;

        /* TODO: Reabrimos la remision y adicionamos una linea adicional */
        case "reabrir":
            $remision->reabrir_remision($_POST["remi_id"]);
            $remision->insert_remisiondetalle_reabrir($_POST["remi_id"],$_POST["usu_id"]);
            break;

        /* TODO: Asignamos la Remision  */
        case "asignar":
            $remision->update_remision_asignacion($_POST["remi_id"],$_POST["usu_asig"]);
            break;

        /* TODO: Listado de Remisiones segun Sucursal,formato json para Datatable JS */
        case "listar_x_sucu":
            $datos=$remision->listar_remi_x_sucu();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["remi_id"];
                $sub_array[] = $row["sucu_nom"];

                $sub_array[] = $row["remi_cancel"];

                if ($row["remi_estado"]=="Abierto"){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }elseif  ($row["remi_estado"]=="Procesando"){
                    $sub_array[] = '<span class="label label-pill label-warning">Procesando</span>';
                }else{
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-warning">Sin Asignar</span>';
                }else{
                    $datos1=$usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1 as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="ver('.$row["remi_id"].');"  id="'.$row["remi_id"].'" class="btn btn-inline btn-primary btn-sm ladda-button"><i class="fa fa-eye"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        case "listar_x_sucu_0":
            $datos=$remision->listar_remi_x_sucu_0();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["remi_id"];
                $sub_array[] = $row["sucu_nom"];

                $sub_array[] = $row["remi_cancel"];

                if ($row["remi_estado"]=="Abierto"){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }elseif  ($row["remi_estado"]=="Procesando"){
                    $sub_array[] = '<span class="label label-pill label-warning">Procesando</span>';
                }else{
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-warning">Sin Asignar</span>';
                }else{
                    $datos1=$usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1 as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="enviar('.$row["remi_id"].');"  id="'.$row["remi_id"].'" class="btn btn-inline btn-success" btn-sm ladda-button"><i class="fa fa-check"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Listado de Remisiones,formato json para Datatable JS */
        case "listar":
            $datos=$remision->listar_remision();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["remi_id"];
                $sub_array[] = $row["sucu_nom"];

                $sub_array[] = $row["remi_cancel"];

                if ($row["remi_estado"]=="Abierto"){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }elseif  ($row["remi_estado"]=="Procesando"){
                    $sub_array[] = '<span class="label label-pill label-warning">Procesando</span>';
                }else{
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<a onClick="asignar('.$row["remi_id"].');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
                }else{
                    $datos1=$usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1 as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="ver('.$row["remi_id"].');"  id="'.$row["remi_id"].'" class="btn btn-inline btn-primary btn-sm ladda-button"><i class="fa fa-eye"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Listado de Remisiones,formato json para Datatable JS, filtro avanzado*/
        case "listar_filtro":
            $datos=$ticket->filtrar_ticket($_POST["remi_id"],$_POST["sucu_id"],$_POST["remi_estado"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["remi_id"];
                $sub_array[] = $row["sucu_nom"];

                $sub_array[] = $row["remi_cancel"];

                if ($row["remi_estado"]=="Abierto"){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }else{
                    $sub_array[] = '<a onClick="CambiarEstado('.$row["remi_id"].')"><span class="label label-pill label-danger">Cerrado</span><a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<a onClick="asignar('.$row["remi_id"].');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
                }else{
                    $datos1=$usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1 as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="ver('.$row["remi_id"].');"  id="'.$row["remi_id"].'" class="btn btn-inline btn-primary btn-sm ladda-button"><i class="fa fa-eye"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        case "listar_filtro_0":
            $datos=$remision->filtrar_remision0($_POST["remi_id"],$_POST["sucu_id"],$_POST["remi_estado"]);
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["remi_id"];
                $sub_array[] = $row["sucu_nom"];

                $sub_array[] = $row["remi_cancel"];

                if ($row["remi_estado"]=="Abierto"){
                    $sub_array[] = '<span class="label label-pill label-success">Abierto</span>';
                }elseif  ($row["remi_estado"]=="Procesando"){
                    $sub_array[] = '<span class="label label-pill label-warning">Procesando</span>';
                }else{
                    $sub_array[] = '<span class="label label-pill label-danger">Cerrado</span></a>';
                }

                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));

                if($row["fech_asig"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_asig"]));
                }

                if($row["fech_cierre"]==null){
                    $sub_array[] = '<span class="label label-pill label-default">Sin Cerrar</span>';
                }else{
                    $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                }

                if($row["usu_asig"]==null){
                    $sub_array[] = '<a onClick="asignar('.$row["remi_id"].');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
                }else{
                    $datos1=$usuario->get_usuario_x_id($row["usu_asig"]);
                    foreach($datos1 as $row1){
                        $sub_array[] = '<span class="label label-pill label-success">'. $row1["usu_nom"].'</span>';
                    }
                }

                $sub_array[] = '<button type="button" onClick="enviar('.$row["remi_id"].');"  id="'.$row["remi_id"].'" class="btn btn-inline btn-success btn-sm ladda-button"><i class="fa fa-check"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        /* TODO: Formato HTML para mostrar detalle de Remision con comentarios */
        case "listardetalle":
            /* TODO: Listar todo el detalle segun remi_id */
            $datos=$remision->listar_remisiondetalle_x_remision($_POST["remi_id"]);
            ?>
                <?php
                    /* TODO: Repetir tantas veces se obtenga de la varible datos$ */
                    foreach($datos as $row){
                        ?>
                            <article class="activity-line-item box-typical">
                                <div class="activity-line-date">
                                    <!-- TODO: Formato de fecha creacion -->
                                    <?php echo date("d/m/Y", strtotime($row["fech_crea"]));?>
                                </div>
                                <header class="activity-line-item-header">
                                    <div class="activity-line-item-user">
                                        <div class="activity-line-item-user-photo">
                                            <a href="#">
                                                <img src="../../public/<?php echo $row['rol_id'] ?>.jpg" alt="">
                                            </a>
                                        </div>
                                        <div class="activity-line-item-user-name"><?php echo $row['usu_nom'].' '.$row['usu_ape'];?></div>
                                        <div class="activity-line-item-user-status">
                                            <!-- TODO: Mostrar perfil del usuario segun rol -->
                                            <?php
                                                if ($row['rol_id']==1){
                                                    echo 'Fama';
                                                }else{
                                                    echo 'Logicsa';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </header>
                                <div class="activity-line-action-list">
                                    <section class="activity-line-action">
                                        <div class="time"><?php echo date("H:i:s", strtotime($row["fech_crea"]));?></div>
                                        <div class="cont">
                                            <div class="cont-in">
                                                <p>
                                                    <?php echo $row["remid_descrip"];?>
                                                </p>

                                                <br>

                                                <!-- TODO: Mostrar documentos adjunto en el detalle de ticket -->
                                                <?php
                                                    $datos_det=$documento->get_documento_detalle_x_remid($row["remid_id"]);
                                                    if(is_array($datos_det)==true and count($datos_det)>0){
                                                        ?>
                                                            <p><strong>Documentos</strong></p>

                                                            <p>
                                                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 60%;">Archivo</th>
                                                                        <th style="width: 40%;"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <!-- TODO: Mostrar tantos documentos tenga la Remision detalle -->
                                                                        <?php
                                                                            foreach ($datos_det as $row_det){ 
                                                                        ?>
                                                                            <tr>
                                                                                <td><?php echo $row_det["det_nom"]; ?></td>
                                                                                <td>
                                                                                    <a href="../../public/document_detalle/<?php echo $row_det["remid_id"]; ?>/<?php echo $row_det["det_nom"]; ?>" target="_blank" class="btn btn-inline btn-primary btn-sm">Ver</a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                </tbody>
                                                            </table>

                                                            </p>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </article>
                        <?php
                    }
                ?>
            <?php
            break;

        /* TODO: Mostrar informacion de la Remision en formato JSON para la vista */
        case "mostrar";
            $datos=$remision->listar_remision_x_id($_POST["remi_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["remi_id"] = $row["remi_id"];
                    $output["usu_id"] = $row["usu_id"];
                    $output["sucu_id"] = $row["sucu_id"];

                    $output["remi_descrip"] = $row["remi_descrip"];

                    if ($row["remi_estado"]=="Abierto"){
                        $output["remi_estado"] = '<span class="label label-pill label-success">Abierto</span>';
                    }elseif ($row["remi_estado"]=="Procesando"){
                        $output["remi_estado"] = '<span class="label label-pill label-warning">Procesando</span>';
                    }else{
                        $output["remi_estado"] = '<span class="label label-pill label-danger">Cerrado</span>';
                    }

                    $output["remi_estado_texto"] = $row["remi_estado"];

                    $output["fech_crea"] = date("d/m/Y H:i:s", strtotime($row["fech_crea"]));
                    $output["fech_cierre"] = date("d/m/Y H:i:s", strtotime($row["fech_cierre"]));
                    $output["sucu_nom"] = $row["sucu_nom"];
                    $output["usu_ape"] = $row["usu_ape"];
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["remi_caja"] = $row["remi_caja"];
                    $output["remi_exp"] = $row["remi_exp"];
                    $output["remi_cancel"] = $row["remi_cancel"];
                    $output["remi_desde"] = date("d/m/Y", strtotime($row["remi_desde"]));
                    $output["remi_hasta"] = date("d/m/Y", strtotime($row["remi_hasta"]));
                    $output["remi_estre"] = $row["remi_estre"];
                    $output["remi_coment"] = $row["remi_coment"];
                }
                echo json_encode($output);
            }
            break;

        case "insertdetalle":
            $datos=$remision->insert_remisiondetalle($_POST["remi_id"],$_POST["usu_id"],$_POST["remid_descrip"]);
            if (is_array($datos)==true and count($datos)>0){
                foreach ($datos as $row){
                    /* TODO: Obtener remid_id de $datos */
                    $output["remid_id"] = $row["remid_id"];
                    /* TODO: Consultamos si vienen archivos desde la vista */
                    if (empty($_FILES['files']['name'])){

                    }else{
                        /* TODO:Contar registros */
                        $countfiles = count($_FILES['files']['name']);
                        /* TODO:Ruta de los documentos */
                        $ruta = "../public/document_detalle/".$output["remid_id"]."/";
                        /* TODO: Array de archivos */
                        $files_arr = array();
                        /* TODO: Consultar si la ruta existe en caso no exista la creamos */
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }

                        /* TODO:recorrer todos los registros */
                        for ($index = 0; $index < $countfiles; $index++) {
                            $doc1 = $_FILES['files']['tmp_name'][$index];
                            $destino = $ruta.$_FILES['files']['name'][$index];

                            $documento->insert_documento_detalle($output["remid_id"],$_FILES['files']['name'][$index]);

                            move_uploaded_file($doc1,$destino);
                        }
                    }
                }
            }
            echo json_encode($datos);
            break;

        /* TODO: Total de Remision para vista de soporte */
        case "total";
            $datos=$remision->get_remision_total();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Total de Remisiones Abierto para vista de soporte */
        case "totalabierto";
            $datos=$remision->get_remision_totalabierto();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Total de Remisiones Cerrados para vista de soporte */
        case "totalcerrado";
            $datos=$remision->get_remision_totalcerrado();  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        /* TODO: Formato Json para grafico de soporte */
        case "grafico";
            $datos=$remision->get_remision_grafico();  
            echo json_encode($datos);
            break;

        case "actualizar";
            $datos= $remision->update_remi_estado($_POST["remi_id"]);
            echo json_encode($datos);
            break;

    }
