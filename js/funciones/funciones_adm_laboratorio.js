$(document).ready(function()
{
	$(".select").select2();
	$("#logo").fileinput({'showUpload':true, 'previewFileType':'image'});
    $(".numeric").numeric({negative:false});
	$('#formulario_municipalidad').validate(
	{
	    rules:
	    {
            telefono1:
			{
                required: true,
            },
            departamento:
			{
                required: true,
            },
            municipio:
            {
                required: true,
            },
            direccion:
            {
                required: true,
            },
            nombre_lab:
            {
                required: true,
            },
        },
        messages:
        {
			telefono1: "Por favor ingrese el número de teléfono",
			departamento: "Por favor seleccione un departamento",
			municipio: "Por favor seleccione un municipio",
            direccion: "Por favor ingrese la dirección del laboratorio",
        },
		highlight: function(element)
		{
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element)
		{
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		submitHandler: function (form)
		{
    		senddata();
		}


    });
		$(document).on("ifChecked","#credito",function(){
			$("#activo").val("1");
		});
		$(document).on("ifUnchecked","#credito",function(){
			 $("#activo").val("0");
		});

		$(document).on("ifChecked","#remisiones",function(){
			$("#activo1").val("1");
		});
		$(document).on("ifUnchecked","#remisiones",function(){
			 $("#activo1").val("0");
		});

		$(document).on("ifChecked","#seguros",function(){
			$("#activo2").val("1");
		});
		$(document).on("ifUnchecked","#seguros",function(){
			 $("#activo2").val("0");
		});
		$(document).on("ifChecked","#formal",function(){
			$("#activo3").val("1");
		});
		$(document).on("ifUnchecked","#formal",function(){
			 $("#activo3").val("0");
		});
		$(document).on("ifChecked","#control_in",function(){
			$("#activo4").val("1");
		});
		$(document).on("ifUnchecked","#control_in",function(){
			 $("#activo4").val("0");
		});

//
	/*	$(document).on("ifUnchecked","#credito",function(){
			 $('#activo').val("0");
		});
		$(document).on("ifChecked","#credito",function(){
			$('#activo').val("1");
		});*/
	$("#departamento").change(function()
    {
     	$("#municipio *").remove();
     	$("#select2-municipio-container").text("");
     	var ajaxdata = { "process" : "municipio", "id_departamento": $("#departamento").val() };
        $.ajax({
          	url:"admin_laboratorio.php",
          	type: "POST",
          	data: ajaxdata,
          	success: function(opciones)
          	{
    			$("#select2-municipio-container").text("Seleccione");
    	        $("#municipio").html(opciones);
    	        $("#municipio").val("");
        	}
        })
    });
    $('.tel').on('keydown', function (event)
    {
	    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
	    {

	    }
	    else
	    {
	        inputval = $(this).val();
	        var string = inputval.replace(/[^0-9]/g, "");
	        var bloc1 = string.substring(0,4);
	        var bloc2 = string.substring(4,7);
	        var string =bloc1 + "-" + bloc2;
	        $(this).val(string);
	    }
	});
	$(".may").keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
});
$(function ()
{
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event)
	{
		deleted();
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e)
	{
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});

});

function autosave(val)
{
	var name=$('#name').val();
	if (name==''|| name.length == 0){
		var	typeinfo="Info";
		var msg="The field name is required";
		display_notify(typeinfo,msg);
		$('#name').focus();
	}
	else
	{
		senddata();
	}
}

function senddata()
{
    var form = $("#formulario_municipalidad");
    var formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    var formAction = form.attr('action');
    $.ajax({
        type        : 'POST',
        url         : 'admin_sucursal.php',
        cache       : false,
        data        : formdata ? formdata : form.serialize(),
        contentType : false,
        processData : false,
        dataType : 'json',
        success: function(data)
        {
		    display_notify(data.typeinfo,data.msg,data.process);
		    if(data.typeinfo=="Success")
		    {
		       setInterval("reload1();", 1500);
		    }
	    }
    });
}
function reload1()
{
	location.href = 'admin_sucursal.php';
}
$('#telefono').on('keydown', function (event)
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
 $('#nrc').on('keydown', function (event)
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
 $('#nit').on('keydown', function (event)
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
					 var bloc2 = string.substring(4,10);
					 var bloc3 = string.substring(10,13);
					 var bloc4 = string.substring(13,13);
					 var string = bloc1+"-"+bloc2+"-"+bloc3+"-"+bloc4;
					 $(this).val(string);
				 }
				 else
				 {
					 event.preventDefault();
				 }

		 }
 });
