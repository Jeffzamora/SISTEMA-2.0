$(document).ready(function() {
    /* TODO: Inicializar SummerNote */
    $('#caja_descrip').summernote({
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

    // Manejador de evento para el formulario
    $("#caja_form").on("submit", function (e) {
        // Evitar el envío del formulario por defecto
        e.preventDefault();

        // Obtener el valor del campo "Caja Interna"
        var caja_num = $("#caja_num").val();

        // Realizar la validación del código de caja utilizando AJAX
        $.ajax({
            type: "POST",
            url: "../../controller/caja.php?op=validar", // Reemplaza esto con la ruta correcta a tu archivo PHP para buscar la remisión por código de caja
            data: {
                caja_num: caja_num
            },
            success: function (response) {
                if (response === "1") {
                    // Si la respuesta es "1", significa que el código de caja ya existe
                    $("#caja-error").show(); // Mostrar la alerta de error
                } else {
                    // Si la respuesta no es "1", el código de caja no existe, así que llamamos a la función para guardar y editar
                    $("#caja-error").hide(); // Ocultar la alerta de error (en caso de que esté visible)
                    guardaryeditar(); // Llamada a la función para guardar y editar el formulario
                }
            },
            error: function () {
                // Manejar el error en caso de que falle la solicitud AJAX
                alert("Error al comprobar la existencia del código de caja.");
            }
        });
    });

});

function guardaryeditar(){
    /* TODO: Array del form ticket */
    var formData = new FormData($("#caja_form")[0]);
    /* TODO: validamos si los campos tienen informacion antes de guardar */
    if ($('#caja_descrip').summernote('isEmpty') ||  $('#caja_tipo').val() == 0 || $('#caja_num').val() == 0){
        swal("Advertencia!", "Campos Vacios", "warning");

        /* TODO: Guardar Remision */
        $.ajax({
            url: "../../controller/caja.php?op=insert",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data);
                data = JSON.parse(data);
                console.log(data[0].caja_id);

                /* TODO: Envio de alerta Email de Remision Abierto */
                $.post("../../controller/email.php?op=remision_abierto", {caja_id : data[0].caja_id}, function (data) {

                });

                /* TODO: Limpiar campos */
                $('#caja_num').val('');
                $('#caja_exp').val('');
                $('#caja_tipo').val('');
                $('#caja_desde').val('');
                $('#caja_hasta').val('');
                $('#caja_descrip').summernote('reset');
                /* TODO: Alerta de Confirmacion */
                swal("Correcto!", "Registrado Correctamente", "success");
            }
        });
    }
}
