function init(){

}

$(document).ready(function(){
    var remi_id = getUrlParameter('ID');

    listardetalle(remi_id);

    /* TODO: Inicializamos summernotejs */
    $('#remid_descripusu').summernote({
        height: 100,
        lang: "es-ES",
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });  

    $('#remid_descripusu').summernote('disable');

    /* TODO: Listamos documentos en caso hubieran */
    tabla=$('#documentos_data').dataTable({
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
            url: '../../controller/documento.php?op=listar',
            type : "post",
            data : {remi_id:remi_id},
            dataType : "json",
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
    }).DataTable();

});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

$(document).on("click","#btnenviar", function(){
    var remi_id = getUrlParameter('ID');
    var usu_id = $('#user_idx').val();
    var remid_descrip = $('#remid_descrip').val();

    /* TODO:Validamos si el summernote esta vacio antes de guardar */
    if ($('#remid_descrip').summernote('isEmpty')){
        swal("Advertencia!", "Falta Descripción", "warning");
    }else{
        var formData = new FormData();
        formData.append('remi_id',remi_id);
        formData.append('usu_id',usu_id);
        formData.append('remid_descrip',remid_descrip);
        var totalfiles = $('#fileElem').val().length;
        /* TODO:Agregamos los documentos adjuntos en caso hubiera */
        for (var i = 0; i < totalfiles; i++) {
            formData.append("files[]", $('#fileElem')[0].files[i]);
        }

        /* TODO:Insertar detalle */
        $.ajax({
            url: "../../controller/remision.php?op=insertdetalle",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data);
                listardetalle(remi_id);
                /* TODO: Limpiar inputfile */
                $('#fileElem').val('');
                $('#remid_descrip').summernote('reset');
                swal("Correcto!", "Registrado Correctamente", "success");
            }
        });
    }
});

$(document).on("click","#btncerrarticket", function(){
    /* TODO: Preguntamos antes de cerrar la Remision */
    swal({
        title: "Remision",
        text: "Esta seguro de Cerrar la Remision?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
    function(isConfirm) {
        if (isConfirm) {
            var remi_id = getUrlParameter('ID');
            var usu_id = $('#user_idx').val();
            /* TODO: Actualizamos la remision  */
            $.post("../../controller/remision.php?op=update", { remi_id : remi_id,usu_id : usu_id }, function (data) {

            });

            /* TODO:Alerta de Remision cerrado via email */
            $.post("../../controller/email.php?op=remision_cerrado", {remi_id : remi_id}, function (data) {

            });

            /* TODO:Llamamos a funcion listardetalle */
            listardetalle(remi_id);

            /* TODO: Alerta de confirmacion */
            swal({
                title: "Remision!",
                text: "Remision Cerrado correctamente.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
});


function listardetalle(remi_id){
    /* TODO: Mostramos informacion de detalle de Remision */
    $.post("../../controller/remision.php?op=listardetalle", { remi_id : remi_id }, function (data) {
        $('#lbldetalle').html(data);
    }); 

    /* TODO: Mostramos informacion de la Remision en inputs */
    $.post("../../controller/remision.php?op=mostrar", { remi_id : remi_id }, function (data) {
        data = JSON.parse(data);
        $('#lblestado').html(data.remi_estado);
        $('#lblnomusuario').html(data.usu_nom +' '+data.usu_ape);
        $('#lblfechcrea').html(data.fech_crea);

        $('#lblnomidticket').html("Detalle Ticket - "+data.remi_id);

        $('#sucu_id').val(data.sucu_nom);
        $('#remi_id').val(data.remi_id);
        $('#remi_caja').val(data.remi_caja);
        $('#remi_exp').val(data.remi_exp);
        $('#remi_cancel').val(data.remi_cancel);
        $('#remi_desde').val(data.remi_desde);
        $('#remi_hasta').val(data.remi_hasta);
        $('#tickd_descripusu').summernote ('code',data.remi_descrip);

        if (data.remi_estado_texto == "Cerrado"){
            /* TODO: Ocultamos panel de detalle */
            $('#pnldetalle').hide();
        }
    });
}

init();
