$(document).ready(function(){
	$('.datepick').datepicker({
		format: 'dd-mm-yyyy',
	});
	$("#cliente").typeahead({
    source: function(query, process) {
      $.ajax({
        type: 'POST',
        url: 'doctor_autocomplete.php',
        data: 'query=' + query,
        dataType: 'JSON',
        async: true,
        success: function(data)
        {
          process(data);
        }
      });
    },
    updater: function(selection) {
      var prod0 = selection;
      var prod = prod0.split("|");
      var id_cliente = prod[0];
      var nombre = prod[1];
      $("#id_cliente").val(id_cliente);
      $("#mcliente").text(nombre);
      // agregar_producto_lista(id_prod, descrip, isbarcode);
    }
  });

	$("#enviar").click(function(){
		enviar();
	});
});
function enviar() {
  var desde=$('#fecha1').val();
  var hasta=$('#fecha2').val();
  var id_doctor=$('#id_cliente').val();
  var cadena = "reporte_doctor_pdf.php?desde="+desde+"&hasta="+hasta+"&id_doctor="+id_doctor;

  window.open(cadena,'','');
}
