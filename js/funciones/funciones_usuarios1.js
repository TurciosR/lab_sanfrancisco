$(document).ready(function() {

	$('#formulario').validate({
	    rules: {
	            nombre:
	            {
	            	required: true,
	            },
	            usuario:
	            {
	            	required: true,
	            },
	            clave:
	            {
	            	required: true,
	            },
	        },
	        messages:
	        {
				nombre: "Por favor ingrese el nombre del usuario",
				usuario: "Por favor ingrese el usuario",
				clave: "Por favor ingrese la clave",
			},
	    submitHandler: function (form)
	    {
	        senddata();
	    }
	});

    $('.select').select2();
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
var exis = 0;
var data = "";
$(document).on("click","#submit1", function()
{
	var nombre = $("#nombre").val();
	var tamanio = $("#tamanio").val();
	var tipo = $("#tipo").val();
	data += nombre+"|"+tamanio+"|"+tipo+"#";
	var tip = $("#tipo option:selected").text();
	var tr  = "<tr>";
			tr += "<td>1</td>";
			tr += "<td>"+ucfirst(nombre)+"</td>";
			tr += "<td>"+tamanio+"</td>";
			tr += "<td>"+tip+"</td>";
			tr += "<td></td>";
			tr += "<td><input type='checkbox' class='form-control'></td>";
			tr += "<td><a><i class='fa fa-trash'></i></a></td>";
			tr += "</tr>";
	switch (tamanio)
	{
		case '12':
			exis = 1;
			break;
		case '6':
			exis += 0.5;
			break;
		case '3':
			exis += 0.34;
			break;
		case '4':
			exis += 0.25;
			break;
	}
	var div = "";
	if(exis == 0)
	{
	 		div += "<div class='row'>";
	}
	div += "<div class='col-lg-"+tamanio+" form-group'>";
	console.log(div);
	if(tipo == 1)
	{
		var input  = "<label>"+ucfirst(nombre)+"</label><input readonly type='text' class='form-control' name='"+nombre.toLowerCase()+"' id='"+nombre.toLowerCase()+"'>";
	}
	div += input;
	div += "</div>";
	console.log(div);
	if(exis >= 1)
	{
		div += "</div>";
		exis  = 0;
	}
	console.log(div);
	//$("#prev").append(div);
	$("#appd").append(tr);
	console.log(data);
});
function ucfirst(string)
{
	 return string.charAt(0).toUpperCase() + string.slice(1);
}
function senddata()
{
    var nombre=$('#nombre').val();
    var usuario=$('#usuario').val();
    var clave=$('#clave').val();
    var admin=$('#admin').val();
    var activo=$('#activo').val();
    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var id_usuario=0;
		var urlprocess='agregar_usuario.php';
		var dataString='process='+process+'&nombre='+nombre+'&usuario='+usuario+'&clave='+clave+'&admin='+admin+'&activo='+activo;
	}
	if(process=='edited')
	{
		var id_usuario=$('#id_usuario').val();
		var urlprocess='editar_usuario.php';
		var dataString='process='+process+'&nombre='+nombre+'&usuario='+usuario+'&clave='+clave+'&admin='+admin+'&id_usuario='+id_usuario+'&activo='+activo;
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
   location.href = 'admin_usuario.php';
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
