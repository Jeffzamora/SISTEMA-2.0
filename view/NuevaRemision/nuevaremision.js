
function init(){
    $("#ticket_form").on("submit",function(e){
        guardaryeditar(e);
    });
}

$(document).ready(function() {
    /* TODO: Inicializar SummerNote */
    $('#remi_descrip').summernote({
        height: 100,
        lang: "es-ES",
        popover: {
            image: [],
            link: [],
            air: []
        },
        callbacks: {
            onImageUpload: function(image) {
                console.log("Image detect...");
                myimagetreat(image[0]);
            },
            onPaste: function (e) {
                console.log("Text detect...");
            }
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });

});

function guardaryeditar(e){
    e.preventDefault();
    /* TODO: Array del form ticket */
    var formData = new FormData($("#ticket_form")[0]);
    /* TODO: validamos si los campos tienen informacion antes de guardar */
    if ($('#remi_descrip').summernote('isEmpty') ||  $('#remi_exp').val() == 0 || $('#remi_caja').val() == 0){
        swal("Advertencia!", "Campos Vacios", "warning");
    }else{
        var totalfiles = $('#fileElem').val().length;
        for (var i = 0; i < totalfiles; i++) {
            formData.append("files[]", $('#fileElem')[0].files[i]);
        }

        /* TODO: Guardar Remision */
        $.ajax({
            url: "../../controller/remision.php?op=insert",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data);
                data = JSON.parse(data);
                console.log(data[0].remi_id);

                /* TODO: Envio de alerta Email de Remision Abierto */
                $.post("../../controller/email.php?op=remision_abierto", {remi_id : data[0].remi_id}, function (data) {

                });

                /* TODO: Limpiar campos */
                $('#remi_codigo').val('');
                $('#remi_caja').val('');
                $('#remi_exp').val('');
                $('#remi_cancel').val('');
                $('#remi_desde').val('');
                $('#remi_hasta').val('');
                $('#fileElem').val('');
                $('#remi_descrip').summernote('reset');
                /* TODO: Alerta de Confirmacion */
                swal("Correcto!", "Registrado Correctamente", "success");
            }
        });
    }
}

init();