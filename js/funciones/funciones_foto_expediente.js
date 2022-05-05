$(document).ready(function() {
	$('#formulario').validate({
	    rules: {
				n_expediente:
				{
					required: true,
				},
				id_paciente:
				{
					required: true,
				},
	        },
	      messages:
	        {
				n_expediente: "Por favor ingrese el numero de expediente",
				id_paciente: "Por favor ingrese el nombre del paciente",
			},
	    submitHandler: function (form)
	    {
	        senddata();
	    }
	});

	$("#id_paciente").typeahead({
	 source: function(query, process) {
			 $.ajax({
					 url: 'buscador_expediente.php',
					 type: 'POST',
					 data: 'query = ' + query ,
					 dataType: 'JSON',
					 async: true,
					 success: function(data) {
							 process(data);
					 },

			 });
	 },
	});


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
	var n_expediente=$('#n_expediente').val();
	var id_paciente=$('#id_paciente').val();
	var explorar = id_paciente.split(" | ");
	id_paciente = explorar[0];
	var fecha_creada=$('#fecha_creada').val();
	var ultima_visita=$('#ultima_visita').val();
    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var id_expediente = 0;
		var urlprocess='agregar_expediente.php';
		var dataString='process='+process+'&n_expediente='+n_expediente+'&id_paciente='+id_paciente+'&fecha_creada='+fecha_creada+'&ultima_visita='+ultima_visita;
	}
	if(process=='edited')
	{
		var id_expediente=$('#id_expediente').val();
		var urlprocess='editar_expediente.php';
		var dataString='process='+process+'&n_expediente='+n_expediente+'&id_paciente='+id_paciente+'&fecha_creada='+fecha_creada+'&ultima_visita='+ultima_visita+'&id_expediente='+id_expediente;
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
   location.href = 'expediente.php';
}
function deleted()
{
	var id_producto = $('#id_expediente').val();
	var dataString = 'process=deleted' + '&id_expediente=' + id_expediente;
	$.ajax({
		type : "POST",
		url : "borrar_expediente.php",
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
	var id_expediente = $('#id_expediente').val();
	var dataString = 'process=ver' + '&id_expediente=' + id_expediente;
	$.ajax({
		type : "POST",
		url : "ver_expediente.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload();", 1500);
			}
		}
	});
}
