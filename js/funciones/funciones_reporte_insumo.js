$(document).ready(function(){
	generar1();

	$("#enviar").click(function(){
		enviar();
	});
});
$('.select').select2();
function reload1()
{
	location.href = 'reporte_insumo.php';
}
function generar1(){
	fecha=$("#fecha1").val();
	fecha2=$("#fecha2").val();
	$.ajax({
    type: 'POST',
    url: 'reporte_insumo.php',
    data: {
      process: 'traerdatos',
      desde: fecha,
      hasta: fecha2
    },

    success: function(html) {
      $('#traer').html(html);

    }
  });
}


$("#fecha1").change(function(event) {
	generar1();
});

$("#fecha2").change(function(event) {
	generar1();
});



function enviar() {
  var desde=$('#fecha1').val();
  var hasta=$('#fecha2').val();
  var cadena = "reporte_insumos_pdf.php?desde="+desde+"&hasta="+hasta;

  window.open(cadena,'','');
}
