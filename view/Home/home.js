function init(){

}

$(document).ready(function(){
    var sucu_id = $('#sucu_idx').val();

    /* TODO: Llenar graficos segun rol  */
    if ( $('#rol_idx').val() == 1){
        $.post("../../controller/usuario.php?op=total", {sucu_id:sucu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        }); 

        $.post("../../controller/usuario.php?op=totalabierto", {sucu_id:sucu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalabierto').html(data.TOTAL);
        });

        $.post("../../controller/usuario.php?op=totalprocesando", {sucu_id:sucu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalprocesado').html(data.TOTAL);
        });

        $.post("../../controller/usuario.php?op=totalcerrado", {sucu_id:sucu_id}, function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrado').html(data.TOTAL);
        });

        $.post("../../controller/usuario.php?op=grafico", {sucu_id:sucu_id},function (data) {
            data = JSON.parse(data);

            new Morris.Bar({
                element: 'divgrafico',
                data: data,
                xkey: 'nom',
                ykeys: ['total'],
                labels: ['Value'],
                barColors: ["#1AB244"], 
            });
        }); 

    }else{
        $.post("../../controller/remision.php?op=total",function (data) {
            data = JSON.parse(data);
            $('#lbltotal').html(data.TOTAL);
        });

        $.post("../../controller/remision.php?op=totalabierto",function (data) {
            data = JSON.parse(data);
            $('#lbltotalabierto').html(data.TOTAL);
        });

        $.post("../../controller/remision.php?op=totalprocesado",function (data) {
            data = JSON.parse(data);
            $('#lbltotalprocesado').html(data.TOTAL);
        });

        $.post("../../controller/remision.php?op=totalcerrado", function (data) {
            data = JSON.parse(data);
            $('#lbltotalcerrado').html(data.TOTAL);
        });

        $.post("../../controller/remision.php?op=grafico",function (data) {
            data = JSON.parse(data);

            new Morris.Bar({
                element: 'divgrafico',
                data: data,
                xkey: 'nom',
                ykeys: ['total'],
                labels: ['Value'],
                barrColors: ["#1AB244"], 
            });
        });

    }
});

init();