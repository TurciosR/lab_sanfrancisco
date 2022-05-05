$(document).ready(function() {

  $('#producto').on('keyup', function(evt)
  	{
  		if(evt.keyCode == 32)
  		{
  			$(this).val($(this).val().replace(" ",""));
  		}
  		else
  		{
  			$(this).val($(this).val().toLowerCase());
  		}
  	});
	$('#admi').on('ifChecked', function(event)
	{
		if($("#process").val() =="permissions")
		{
			$('.i-checks').iCheck('check');
			$('#admin').val("1");
		}
		else
		{
			$('#admin').val("1");
		}
	});
	$('#admi').on('ifUnchecked', function(event)
	{
		if($("#process").val() =="permissions")
		{
			$('.i-checks').iCheck('uncheck');
			$('#admin').val("0");
		}
		else
		{
			$('#admin').val("0");
		}
	});
	$('#activ').on('ifChecked', function(event)
	{
		$('#activo').val("1");
	});
	$('#activ').on('ifUnchecked', function(event)
	{
		$('#activo').val("0");
	});
});

$(function ()
{
	$(document).on("click", "#btnDelete", function(event) {
		deleted();
		console.log("jjjj")
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});

});

function autosave(val){
	var name=$('#name').val();
	if (name==''|| name.length == 0){
		var	typeinfo="Info";
		var msg="The field name is required";
		display_notify(typeinfo,msg);
		$('#name').focus();
	}
	else{
		senddata();
	}
}

function senddata()
{
	var nombre=$('#nombre').val();
    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var id_tipo_empleado=0;
		var urlprocess='agregar_tipo_empleado.php';
		var dataString='process='+process+'&nombre='+nombre;
	}
	if(process=='edited')
	{
		var id_tipo_empleado=$('#id_tipo_empleado').val();
		var urlprocess='editar_tipo_empleado.php';
		var dataString='process='+process+'&nombre='+nombre+'&id_tipo_empleado='+id_tipo_empleado;
	}
	if(process=='permissions')
	{
		var id_usuario=$('#id_usuario').val();
		var urlprocess='permiso_usuario.php';
		var myCheckboxes = new Array();
        var cuantos=0;
		var chequeado=false;
		var admin = $("#admin").val();
        $("input[name='myCheckboxes']:checked").each(function(index)
        {
			var est=$('#myCheckboxes').eq(index).attr('checked');
			chequeado=true;
            myCheckboxes.push($(this).val());
            cuantos=cuantos+1;
		});
		if (cuantos==0){
			myCheckboxes='0';
		}

		var dataString='process='+process+'&admin='+admin+'&id_usuario='+id_usuario+'&myCheckboxes='+myCheckboxes+'&qty='+cuantos;
		//alert(dataString);
	}
	$.ajax({
		type:'POST',
		url:urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax)
		{
			process=datax.process;
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload();", 1500);
			}
		}
	});
}

function reload()
{
   location.href = 'admin_tipo_empleado.php';
}
function deleted()
{
	var id_tipo_empleado = $('#id_tipo_empleado').val();
	var dataString = 'process=deleted' + '&id_tipo_empleado=' + id_tipo_empleado;
	$.ajax({
		type : "POST",
		url : "borrar_tipo_empleado.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload();", 1500);
				$('#deleteModal').hide();
			}
		}
	});
}

function ver()
{
	var id_tipo_empleado = $('#id_tipo_empleado').val();
	var dataString = 'process=ver' + '&id_tipo_empleado=' + id_tipo_empleado;
	$.ajax({
		type : "POST",
		url : "ver_tipo_empleado.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload();", 1500);
				$('#verModal').hide();
			}
		}
	});
}
