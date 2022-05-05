//funcion de redirecionamiento si se guarda corretamente
function reload1()
{
  location.href = 'admin_movimiento.php';
}
function ver()
{
	var id_detalle_cobro = $('#id_detalle').val();
	var dataString = 'process=ver' + '&id_detalle=' + id_detalle;
	$.ajax({
		type : "POST",
		url : "admin_movimiento.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);
			}
		}
	});
}
$(function ()
{
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});

});
