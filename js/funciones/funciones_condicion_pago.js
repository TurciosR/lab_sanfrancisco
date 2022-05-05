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

  /*  $('#activar').on('ifChecked', function(event)
    {

      //$('.i-checks').iCheck('check');
       $('#activo').val("1");
    });
    $('#activar').on('ifUnchecked', function(event)
    {
    //  $('.i-checks').iCheck('uncheck');
      $('#activo').val("0");
    });*/
});


$(function ()
{

  $(document).on("ifChecked","#activar",function(){

    //$("#nom tr").each(function()
    //{
      $('#activo').val("1");
    // var pr=$(this).parents().find("#id").val();
      //console.log(pr);
      //var pr=$("#id").val();

      //activo();

    //});
    $(this).parents("tr").find("#id").each(function() {
         var pr = $(this).val();
          $("#idc").val(pr);

           console.log(pr);

       });
       activo();

  });
  $(document).on("ifUnchecked","#activar",function(){

    //$("#nom tr").each(function()
    //{
    $('#activo').val("0");
    $(this).parents("tr").find("#id").each(function() {
         var pr = $(this).val();
         $("#idc").val(pr);
           console.log(pr);

       });

      //var pr=$("#id").val();
      //console.log(pr);

      activo();
  //  });

  });
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		eliminar();
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
    var descripcion=$('#abreviatura').val();


    //Get the value from form if edit or insert
	var process=$('#process').val();
	if(process=='insert')
	{
		var id_condicion_pago=0;
		var urlprocess='agregar_condicion_pago.php';
		var dataString='process='+process+'&nombre='+nombre+'&abre='+descripcion;
	}
	if(process=='edited')
	{
		var id_condicion_pago=$('#id_condicion_pago').val();
		var urlprocess='editar_condicion_pago.php';
		var dataString='process='+process+'&nombre='+nombre+'&abre='+descripcion+'&id_condicion_pago='+id_condicion_pago;
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
   location.href = 'admin_condicion_pago.php';
}
function eliminar()
{
	var id_con_pa = $('#id_con_pa').val();
	var dataString = 'process=deleted' + '&id_con_pa=' + id_con_pa;
	$.ajax({
		type : "POST",
		url : "borrar_condicion_pago.php",
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
function activo()
{

	var estado = $('#activo').val();
	var id = $('#idc').val();
  console.log(id+"hola "+estado);
	var dataString = 'process=activar' +'&id='+id+'&estado='+estado;
	$.ajax({
		type : "POST",
		url : "admin_condicion_pago.php",
		data : dataString,
		dataType : 'json',
		success : function(xdatos) {
			display_notify(xdatos.typeinfo, xdatos.msg);
			if(xdatos.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);
			}
		}
	});
}
