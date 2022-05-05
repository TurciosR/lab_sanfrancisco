$(document).ready(function() {
  generar();
  generar1();
  /*$("#submit").click(function(){
    enviar();
  })*/
});
$(function ()
{
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});

});

$(document).on("click", "#btn_agregar_ft", function(event) {
  var form = $("#form");
  var formdata = false;

  if(window.FormData)
  {
      formdata = new FormData(form[0]);
  }
  var formAction = form.attr('action');

  var dataString = 'process=upload_s';
    $.ajax({
      type : "POST",
      url : "foto_expediente.php",
      data : formdata ? formdata : form.serialize(),
      dataType : 'json',
      cache       : false,
      contentType : false,
      processData : false,
      success : function(datax) {
        display_notify(datax.typeinfo, datax.msg);
        if(datax.typeinfo == "Success")
        {
          setInterval("reload();", 1500);
        }
      }
    });
  });

function reload()
  {
     location.reload();
  }

function generar(){
	id_expediente=$("#id_expediente").val();
	desde=$("#desde").val();
	hasta=$("#hasta").val();
	dataTable = $('#editable2').DataTable().destroy()
	dataTable = $('#editable2').DataTable( {
			"pageLength": 10,
			"order":[[ 1, 'desc' ], [ 0, 'desc' ]],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"ver_expediente_dt.php?desde="+desde+"&hasta="+hasta+"&id_expediente="+id_expediente,
					error: function(){
						$(".editable2-error").html("");
						$("#editable2").append('<tbody class="editable22_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable2_processing").css("display","none");
						$( ".editable2-error" ).remove();
						}
					}
				} );

		dataTable.ajax.reload()
}
$("#desde").change(function(event) {
	generar();
});
$("#hasta").change(function(event) {
	generar();
});
function generar1(){
	id_expediente1=$("#id_expediente1").val();
	desde1=$("#desde1").val();
	hasta1=$("#hasta1").val();
	dataTable = $('#editable3').DataTable().destroy()
	dataTable = $('#editable3').DataTable( {
			"pageLength": 10,
			"order":[[ 1, 'desc' ], [ 0, 'desc' ]],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"tabla_comparativa_dt.php?desde="+desde1+"&hasta="+hasta1+"&id_expediente="+id_expediente1,
					error: function(){
						$(".editable3-error").html("");
						$("#editable3").append('<tbody class="editable22_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable3_processing").css("display","none");
						$( ".editable3-error" ).remove();
						}
					}
				} );

		dataTable.ajax.reload()
}
$("#desde1").change(function(event) {
	generar1();
});
$("#hasta1").change(function(event) {
	generar1();
});

function enviar() {
  var desde=$('#desde').val();
  var hasta=$('#hasta').val();
  var id=$('#id_expediente').val();

  var cadena = "reporte_expediente.php?id_expediente="+id+"&desde="+desde+"&hasta="+hasta;

  window.open(cadena,'','');
}
