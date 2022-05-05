$(document).ready(function(){
	$("#enviar").click(function(){
		enviar();
	});
});
function enviar() {
  var desde=$('#fecha1').val();
  var hasta=$('#fecha2').val();
  var cadena = "reporte_egreso_pdf.php?desde="+desde+"&hasta="+hasta;

  window.open(cadena,'','');
}
