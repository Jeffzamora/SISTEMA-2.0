var tabla;
var usu_id =  $('#user_idx').val();
var rol_id =  $('#rol_idx').val();
var sucu_id =  $('#sucu_idx').val();


function init(){
    $("#ticket_form").on("submit",function(e){
        guardar(e);	
    });
}

$(document).ready(function(){

    /* TODO: Llenar Combo Sucursal */
    $.post("../../controller/sucursal.php?op=combo",function(data, status){
        $('#sucu_id').html(data);
    });

    /* TODO:LLenar Combo usuario asignar */
    $.post("../../controller/usuario.php?op=combo", function (data) {
        $('#usu_asig').html(data);
    });

    /* TODO: rol si es 1 entonces es usuario */
    if (rol_id==1){
        $('#viewuser').hide();

        tabla=$('#ticket_data').dataTable({
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            "searching": true,
            lengthChange: false,
            colReorder: true,
            buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                    ],
            "ajax":{
                url: '../../controller/remision.php?op=listar_x_sucu',
                type : "post",
                dataType : "json",
                data:data,
                error: function(e){
                    console.log(e.responseText);
                }
            },
            "ordering": false,
            "bDestroy": true,
            "responsive": true,
            "bInfo":true,
            "iDisplayLength": 10,
            "autoWidth": false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }     
        }).DataTable();
    }else{
        /* TODO: Filtro avanzado en caso de ser Logicsa */
        var remi_id = $('#remi_id').val();
        var sucu_id = $('#sucu_id').val();
        var remi_estado = $('#remi_estado').val();

        listardatatable(remi_id,sucu_id,remi_estado);
    }
});

/* TODO: Link para poder ver el detalle de Remision en otra ventana */
function ver(remi_id){
    window.open('https://fama.logicsa.net/view/ConsultarRemision/?ID='+ remi_id +'');
}

/* TODO: Mostrar datos antes de asignar */
function asignar(remi_id){
    $.post("../../controller/remision.php?op=mostrar", {remi_id : remi_id}, function (data) {
        data = JSON.parse(data);
        $('#remi_id').val(data.remi_id);

        $('#mdltitulo').html('Asignar Agente');
        $("#modalasignar").modal('show');
    });
}

/* TODO: Guardar asignacion de usuario de soporte */
function guardar(e){
    e.preventDefault();
	var formData = new FormData($("#ticket_form")[0]);
    $.ajax({
        url: "../../controller/remision.php?op=asignar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){
            var remi_id = $('#remi_id').val();
            /* TODO: enviar Email de alerta de asignacion */
            $.post("../../controller/email.php?op=remision_asignado", {remi_id : remi_id}, function (data) {

            });
            /* TODO: Alerta de confirmacion */
            swal("Correcto!", "Asignado Correctamente", "success");

            /* TODO: Ocultar Modal */
            $("#modalasignar").modal('hide');
            /* TODO:Recargar Datatable JS */
            $('#ticket_data').DataTable().ajax.reload();
        }
    });
}

/* TODO:Reabrir Remision */
function CambiarEstado(remi_id){
    swal({
        title: "Remision",
        text: "Esta seguro de Reabrir el caso?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            /* TODO: Enviar actualizacion de estado */
            $.post("../../controller/remision.php?op=reabrir", {remi_id : remi_id,usu_id : usu_id}, function (data) {

            });

            /* TODO:Recargar datatable js */
            $('#ticket_data').DataTable().ajax.reload();	

            /* TODO: Mensaje de Confirmacion */
            swal({
                title: "Remision!",
                text: "Caso Abierto.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
}

/* TODO:Filtro avanzado */
$(document).on("click","#btnfiltrar", function(){
    limpiar();

    var remi_id = $('#remi_id').val();
    var sucu_id = $('#sucu_id').val();
    var remi_estado = $('#remi_estado').val();

    listardatatable(remi_id,sucu_id,remi_estado);

});

/* TODO: Restaurar Datatable js y limpiar */
$(document).on("click","#btntodo", function(){
    limpiar();

    $('#remi_id').val('');
    $('#sucu_id').val('').trigger('change');
    $('#remi_estado').val('').trigger('change');

    listardatatable('','','');
});

/* TODO: Listar datatable con filtro avanzado */
function listardatatable(remi_id,sucu_id,remi_estado){
    tabla=$('#ticket_data').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
                ],
        "ajax":{
            url: '../../controller/remision.php?op=listar_filtro',
            type : "post",
            dataType : "json",
            data:{ remi_id:remi_id,sucu_id:sucu_id,remi_estado:remi_estado},
            error: function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "autoWidth": false,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }     
    }).DataTable().ajax.reload();
}

/* TODO: Limpiamos restructurando el html del datatable js */
function limpiar(){
    $('#table').html(
        "<table id='ticket_data' class='table table-bordered table-striped table-vcenter js-dataTable-full'>"+
            "<thead>"+
                "<tr>"+
                    "<th style='width: 5%;'>Nro.Remision</th>"+
                    "<th style='width: 15%;'>Sucursal</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 15%;'>Tipo</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 5%;'>Estado</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 10%;'>Fecha Creación</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 10%;'>Fecha Asignación</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 10%;'>Fecha Cierre</th>"+
                    "<th class='d-none d-sm-table-cell' style='width: 10%;'>Tecnico</th>"+
                    "<th class='text-center' style='width: 5%;'></th>"+
                "</tr>"+
            "</thead>"+
            "<tbody>"+

            "</tbody>"+
        "</table>"
    );
}

init();