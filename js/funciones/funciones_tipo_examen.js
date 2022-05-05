$(document).ready(function() {

	$('#formulario').validate({
	    rules: {
	            nombre:
	            {
	            	required: true,
	            },
	            descripcion:
	            {
	            	required: true,
	            },


	        },
	        messages:
	        {
				nombre: "Por favor ingrese el nombre del usuario",
				descripcion: "Por favor ingrese la descripcion",

			},
	    submitHandler: function (form)
	    {
	        senddata();
	    }
	});

    $('#tipo').select2();
  	$('#usuario').on('keyup', function(evt)
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
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		deleted();
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
    var descripcion=$('#descripcion').val();


    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var id_tipo_examen=0;
		var urlprocess='agregar_tipo_examen.php';
		var dataString='process='+process+'&nombre='+nombre+'&descripcion='+descripcion;
	}
	if(process=='edited')
	{
		var id_tipo_examen=$('#id_tipo_examen').val();
		var urlprocess='editar_tipo_examen.php';
		var dataString='process='+process+'&nombre='+nombre+'&descripcion='+descripcion+'&id_tipo_examen='+id_tipo_examen;
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
				setInterval("reload1();", 1500);
			}
		}
	});
}
function reload1()
{
   location.href = 'admin_tipo_examen.php';
}
function deleted()
{
	var id_usuario = $('#id_usuario').val();
	var dataString = 'process=deleted' + '&id_usuario=' + id_usuario;
	$.ajax({
		type : "POST",
		url : "borrar_usuario.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);
				$('#deleteModal').hide();
			}
		}
	});
}
