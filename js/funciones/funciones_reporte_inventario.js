
$(document).ready(function(){
	$("#submit1").click(function(){
		enviar();
	});
});
function reload1()
{
	location.href = 'reporte_inventario.php';
}

function enviar() {
  var cadena = "reporte_inventario_pdf.php";

  window.open(cadena,'','');
}
