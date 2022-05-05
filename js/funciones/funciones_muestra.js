$(document).ready(function() {

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
		var urlprocess='agregar_muestra.php';
		var dataString='process='+process+'&nombre='+nombre;
	}
	if(process=='edited')
	{
		var id_muestra=$('#id_muestra').val();
		var urlprocess='editar_muestra.php';
		var dataString='process='+process+'&nombre='+nombre+'&id_muestra='+id_muestra;
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
   location.href = 'admin_muestra.php';
}
function estado()
{
	var id_muestra = $('#id_muestra').val();
	var estado = $('#estado').val();
	var dataString = 'process=anular' + '&id_muestra=' + id_muestra+ '&estado=' + estado;
	$.ajax({
		type : "POST",
		url : "estado_muestra.php",
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
