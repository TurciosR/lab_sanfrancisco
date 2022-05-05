$(document).ready(function() {



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
	$(document).on("click", "#anular", function(event) {
		estado();
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
		var urlprocess='agregar_categoria.php';
		var dataString='process='+process+'&nombre='+nombre+'&descripcion='+descripcion;
	}
	if(process=='edited')
	{
		var id_categoria=$('#id_categoria').val();
		var urlprocess='editar_categoria.php';
		var dataString='process='+process+'&nombre='+nombre+'&descripcion='+descripcion+'&id_categoria='+id_categoria;
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
   location.href = 'admin_categoria.php';
}
function estado()
{
	var id_categoria = $('#id_categoria').val();
	var estado = $('#estado').val();
	var dataString = 'process=anular' + '&id_categoria=' + id_categoria+ '&estado=' + estado;
	$.ajax({
		type : "POST",
		url : "estado_categoria.php",
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
