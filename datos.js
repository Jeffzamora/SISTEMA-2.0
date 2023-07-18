function init(){
}

$(document).ready(function() {

});

/* TODO: Script para poder modificar segun el valor de acceso Fama o Logicsa */
$(document).on("click", "#btnsoporte", function () {
    if ($('#rol_id').val()==1){
        $('#lbltitulo').html("Acceso Logicsa");
        $('#btnsoporte').html("Acceso Fama");
        $('#rol_id').val(2);
        $("#imgtipo").attr("src","public/2.png");
    }else{
        $('#lbltitulo').html("Acceso Fama");
        $('#btnsoporte').html("Acceso Logicsa");
        $('#rol_id').val(1);
        $("#imgtipo").attr("src","public/1.png");
    }
});

init();