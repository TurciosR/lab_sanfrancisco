$(document).ready(function(){
	$("#submit1").click(function(){
		enviar();
	});
});
function reload1()
{
	location.href = 'reporte_examenes_pendientes.php';
}

function enviar() {
	var desde=$('#fecha1').val();
	var hasta=$('#fecha2').val();
  var cadena = "reporte_examenes_pendientes_pdf.php?desde="+desde+"&hasta="+hasta;

  window.open(cadena,'','');
}
