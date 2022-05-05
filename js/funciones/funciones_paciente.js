$(document).ready(function() {

 $('.select').select2();
	$("#foto").fileinput({'showUpload':true, 'previewFileType':'image'});
	$('#formulario').validate({
	    rules: {
	            nombre:
	            {
	            	required: true,
	            },
	            apellido:
	            {
	            	required: true,
	            },
              sexo:
	            {
	            	required: true,
	            },
              fecha_nacimiento:
	            {
	            	required: true,
	            },


	        },
	        messages:
	        {
				nombre: "Por favor ingrese el nombre del usuario",
				apellido: "Por favor ingrese el apellido",
        sexo: "Por favor ingrese el genero",
        fecha_nacimiento: "Por favor ingrese la fecha de nacimiento",

			},

	    submitHandler: function (form)
	    {
	        senddata();
	    }

	});
  $(".may").keyup(function() {
    $(this).val($(this).val().toUpperCase());
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
  $(document).on("click", "#anular", function(event) {
		estado();
	 });
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

function senddata()
{
  var form = $("#formulario");
  var formdata = false;
  if(window.FormData)
  {
      formdata = new FormData(form[0]);
  }
  var formAction = form.attr('action');

    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var urlprocess='agregar_paciente.php';
	}
	if(process=='edit')
	{
		var urlprocess='editar_paciente.php';
	}
		//alert(dataString);

	$.ajax({
		type:'POST',
    url: urlprocess,
    data : formdata ? formdata : form.serialize(),
		dataType: 'json',
    cache       : false,
    contentType : false,
    processData : false,
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
   location.href = 'admin_paciente.php';
}
function estado()
{
	var id_paciente = $('#id_paciente').val();
	var estado = $('#estado').val();
	var dataString = 'process=anular' + '&id_paciente=' + id_paciente+ '&estado=' + estado;
	$.ajax({
		type : "POST",
		url : "estado_paciente.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);
				$('#verModal').hide();
			}
		}
	});
}

function deleted()
{
	var id_paciente = $('#id_paciente').val();
	var dataString = 'process=deleted' + '&id_paciente=' + id_paciente;
	$.ajax({
		type : "POST",
		url : "borrar_paciente.php",
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
$('#dui').on('keydown', function (event)
    {
	    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
	    {

	    }
	    else
	    {
	        if((event.keyCode>47 && event.keyCode<60 ) || (event.keyCode>95 && event.keyCode<106 ))
	        {
	        	inputval = $(this).val();
	        	var string = inputval.replace(/[^0-9]/g, "");
		        var bloc1 = string.substring(0,8);
		        var bloc2 = string.substring(8,8);
		        var string =bloc1 + "-" + bloc2;
		        $(this).val(string);
	        }
	        else
	        {
	        	event.preventDefault();
	        }

	    }
	});
$('.telefono').on('keydown', function (event)
    {
	    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
	    {

	    }
	    else
	    {
	        if((event.keyCode>47 && event.keyCode<60 ) || (event.keyCode>95 && event.keyCode<106 ))
	        {
	        	inputval = $(this).val();
	        	var string = inputval.replace(/[^0-9]/g, "");
		        var bloc1 = string.substring(0,4);
		        var bloc2 = string.substring(4,7);
		        var string =bloc1 + "-" + bloc2;
		        $(this).val(string);
	        }
	        else
	        {
	        	event.preventDefault();
	        }

	    }
	});
