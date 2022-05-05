$(document).ready(function(){

	$('#hora_de_muestra').timepicker({ 'step': 1 });
	$('#hora_de_reporte').timepicker({ 'step': 1 });
	var fecha = $("#fecha").val();
	generar(fecha);
	var fecha1 = $("#fecha1").val();
	generar1(fecha1);
	generar2();
});
var formulario="";
$(document).on("click", "#sig", function()
{
	$('#formulario').validate({
		rules: {
			realizado:
			{
				required: true,
			},
			muestra:
			{
				required: true,
			},


		},
		messages:
		{
			realizado: "Por favor seleccione el empleado",
			muestra: "Por favor seleccione la muestra",

		},
		submitHandler: function (form)
		{
			var  StringDatos="";
			var error = false;
			$("#exa tr").each(function (index)
			{
				var campo1, campo2, campo3, campo4,campo_seccion;
				campo_seccion = $(this).find(".seccion").text();
				campo1 = $(this).find(".parametro").text();
				campo2 = $(this).find(".resultado").text().replace(/\+/g,"@");
				campo3 = $(this).find(".unidades").text();
				campo4 = $(this).find(".valores_referencia").text();

				if($(this).hasClass('s')){
					StringDatos += campo_seccion + "| | | |s#";
	        if (campo_seccion== "") {
	          console.log("AQUI seccion" + index);
	          error = true;
	        }

				}else{
					StringDatos+=campo1+"|"+campo2+"|"+campo3+"|"+campo4+"|p#";
					/*if(campo1 == "" || campo2 == "" )
					{
						error = true;
					}*/

				}
			})
			if(error)
			{
				display_notify('Error','Por favor agrege al menos un campo');
			}
			else
			{
				formulario=StringDatos;
				console.log(formulario);
				senddata();
			}
		}
	});

})
$(function ()
{
	/////////////////GUARDAR ID DE EXAMEN PACIENTE PARA REALIZAR  ////
	$(document).on("click", "#procesar", function() {
		var paciente = $("#id_paciente").val();
		var cobro = $("#id_cobro").val();
		window.open("agregar_examen_paciente_pen.php?id_paciente="+paciente+"&id_cobro="+cobro+"", '_blank');
	});

	/////////////////GUARDAR ID DE EXAMEN PACIENTE PARA IMPRIMIR  ////
	 $(document).on("click", "#imprimir", function() {
		 var cobro = $("#id_cobro").val();
		 window.open("impresion_examen.php?id_cobro="+cobro+"", '_blank');
	 });
	 ////////////PROCESAR/////////////
 	$(document).on("click", "#procesar_imprimir", function() {
 		var chequeado=false;
 		var cuantos=0;
 		var myCheckboxes = new Array();
 		$("#tabla1>tbody tr").each(function()
 		{
 			var estado= $(this).find(".estado").text();
 			var id_examen_paciente= $(this).find(".id_examen_paciente").val();

 			if(estado=="Procesado"){
 				console.log(estado);
 				myCheckboxes.push(id_examen_paciente);
 				cuantos=cuantos+1;
 			}


 		});

 		if(cuantos==0){
 			display_notify("Error", "No hay examenes pendientes de imprimir");
 		}else{

 			var dataString = 'process=impreso' + '&id_examen_paciente=' + myCheckboxes;
 			$.ajax({
 				type : "POST",
 				url : "examen_pendiente_imprimir.php",
 				data : dataString,
 				dataType : 'json',
 				success : function(datax) {
 					display_notify(datax.typeinfo, datax.msg);
 					if(datax.typeinfo == "Success")
 					{
 						$("#cerrar").click();
 							setInterval('location.reload();', 1500);
 					}


 				}
 			});
 		}


 	});

	///procesar imprimir individual
	$(document).on("click", "#impreso", function(event) {
		var fila=$(this).parents("tr");
		var id_examen_paciente=fila.find(".id_examen_paciente").val();
		console.log(id_examen_paciente);
		var dataString = 'process=impreso_individual' + '&id_examen_paciente=' + id_examen_paciente;
		$.ajax({
			type : "POST",
			url : "examen_pendiente_imprimir.php",
			data : dataString,
			dataType : 'json',
			success : function(datax) {
				display_notify(datax.typeinfo, datax.msg);
				if(datax.typeinfo == "Success")
				{
				tmodal_pediente_impr();
				}


			}
		});
	});
	//binding event click for button in modal form
	$(document).on("click", "#anular", function(event) {
		anular_examen_paciente();
		console.log("anular")
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});

});

$('.select').select2();


$(function() {

	$('html').click(function() {
		/* Aqui se esconden los menus que esten visibles (input)*/
		var fila=$('#value').parents("tr");
	  if($('#value').val()!="")
	  {
			var valor_referencia=fila.find(".valores_referencia").text();
			var valores= valor_referencia.split("-");
			var valor0= valores[0];
			var valor1= valores[1];
	    if(parseInt($('#value').val())> parseInt(valor1)  || parseInt($('#value').val())< parseInt(valor0) )
	    {
	      display_notify('Warning','Verificar el campo');
				console.log("hey");
	    }

	  }
		var number=$('#value').val();
		var a = $('#value').closest('td');
		var idtransace=a.closest('tr').attr('class');
		a.html(number);



	});


	// Funcion  Para validar que clase de input se utilizara
	function campos(td,valores,col,row)
	{

	  if ($(td).hasClass('ed')) {

	      var av = $(td).html();
	      $(td).html('');
	      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="" autocomplete="off">');
	      $('#value').val(av);
	      $('#value').focus();
	      $('#value').numeric({
	        negative: false,
	        decimalPlaces: 2
	      });
	    }
	    if ($(td).hasClass('vr') || $(td).hasClass('vr_hidden')) {
	      console.log(valores);

				$("#modal").attr("href", "valores_referencia.php?row="+row+"&col="+col+"&valores="+escape(valores)+"");
				$("#modal").click();
	    }
	    if ($(td).hasClass('sel')) {
	      var av = $(td).html();
	      $(td).html('');
	      $(td).html('<select class="form-control select in" name="value" id="value" value=""><option value="" >SELECCIONAR</option><option value="UNISEX" >UNISEX</option><option value="MASCULINO" >MASCULINO</option><option value="FEMENINO" >FEMENINO</option></select>');
	      $('#value').val(av);
	      $('#value').focus();
	    }
	    if ($(td).hasClass('nm')) {
	      var av = $(td).html();
	      $(td).html('');
	      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="" autocomplete="off">');
	      $('#value').val(av);
	      $('#value').focus();
	      $('#value').numeric({
	        negative: false,
	        decimal: false
	      });

	    }
	    if ($(td).hasClass('tex')){

	      var av = $(td).html();
	      $(td).html('');
	      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="" autocomplete="off">');
	      $('#value').val(av);
	      $('#value').focus();
	    }

	}
	$(document).on('click', '#exa .resultado', function(e) {
      campos($(this),"",0,0);

  });

$(document).on('keydown', '.in', function(event) {

		if(event.key=='Enter')
		{
			$('html').click();
		}
		//Abajo
		if(event.key=='ArrowDown'){
			var fila_campo=0;
			var fila=0;
			var tr = $(this).parents('tr');
			var nada=$(this);
			fila   = nada.parents('tr').index();
			$('html').click();
			//console.log(fila);
			var salto=0;
			$("#exa tr").each(function(inde) {
					if((inde-fila)==1){
						if($(this).hasClass('p')) {
							salto=1;
						}
						else {
							salto=2;
						}

					}

			})
				$("#exa tr").each(function(index) {
						if((index-fila)==salto){
							if(tr.hasClass('p')) {
							campos($(this).find("td:eq(1)"),"",0,0);
							}
							else{

							}
						}

				})
	  }
		if(event.key=='ArrowUp'){
			var fila_campo=0;
			var fila=0;
			var tr = $(this).parents('tr');
			var nada=$(this);
			fila   = nada.parents('tr').index();
			$('html').click();
			//console.log(fila);
			var salto=0;
			$("#exa tr").each(function(inde) {
					if((inde-fila)==-1){
						if($(this).hasClass('p')) {
							salto=-1;
							console.log($(this));
						}
						else {
							salto=-2;
						}

					}

			})

				$("#exa tr").each(function(index) {

						if((index-fila)==salto){
							if(tr.hasClass('p')) {
							campos($(this).find("td:eq(1)"),"",0,0);
							}

						}

				})
	  }
	});


	function validar()
	{
		var fila=$("#value").parents("tr");
	  if($(this).val()!="")
	  {
			var valor_referencia=fila.find(".valores_referencia").text();
			var valores= valor_referencia.split("-");
			var valor0= valores[0];
			var valor1= valores[1];
			console.log(valor1);
	    if(parseInt($(this).val())> parseInt(valor1)  || parseInt($(this).val())< parseInt(valor0) )
	    {
	      display_notify('Warning','Verificar el campo');
				console.log("hey");
	    }

	  }
	}


});

function senddata()
{
	var id_empleado=$('#realizado').val();
	var id_doctor=$('#referido').val();
	var id_muestra=$('#muestra').val();
	var fecha_cobro=$('#fecha').val();
	var hora_cobro=$('#hora').val();
	var id_paciente=$("#id_paciente").val();
	var hora_de_muestra = $("#hora_de_muestra").val();
	var hora_de_reporte = $("#hora_de_reporte").val();
	var id_examen_evaluar = $("#id_examen_evaluar").val();
	var error_avanzar =0;
	var process=$('#process').val();
	//agregar examen paciente pendientes de imprimir
	if(id_examen_evaluar == 698 || id_examen_evaluar == 698 || id_examen_evaluar == 698 ){
		if(hora_de_muestra =="" || hora_de_muestra == ""){
			error_avanzar++;
		}
	}
	if(process=='Guardar' || process=='Siguiente' )
	{
		var id_examen_paciente=$('#id_examen_paciente').val();
		var id_cobro=$('#id_cobro').val();
		var id_paciente=$('#id_paci').val();
		var urlprocess='agregar_examen_paciente_pen.php';
		var dataString='process='+process+'&id_examen_paciente='+id_examen_paciente+'&id_paciente='+id_paciente+'&id_empleado='+id_empleado+'&id_doctor='+id_doctor+'&formulario='+formulario+'&fecha_cobro='+fecha_cobro+'&hora_cobro='+hora_cobro+'&id_muestra='+id_muestra+'&id_cobro='+id_cobro+'&edad=' + edad +"&paciente="+paciente+'&hora_de_muestra='+hora_de_muestra+'&hora_de_reporte='+hora_de_reporte+'&id_examen_evaluar='+id_examen_evaluar;
	}
  //agregar examen paciente en el modal pendientes de procesar
	if(process=='edited_modal')
	{
		console.log("edited_modal");
		var id_examen_paciente=$('#id_examen_paciente').val();
		var id_paciente=$('#id_paci').val();
		var urlprocess='agregar_examen_paciente.php';
		var dataString='process='+process+'&id_examen_paciente='+id_examen_paciente+'&id_paciente='+id_paciente+'&id_empleado='+id_empleado+'&id_doctor='+id_doctor+'&formulario='+formulario+'&fecha_cobro='+fecha_cobro+'&hora_cobro='+hora_cobro+'&id_muestra='+id_muestra+'&hora_de_muestra='+hora_de_muestra+'&hora_de_reporte='+hora_de_reporte+'&id_examen_evaluar='+id_examen_evaluar;
	}

//agregar examen paciente admin examne paciente
	if(process=='edited_admin')
	{
		var id_examen_paciente=$('#id_examen_paciente').val();
		var id_paciente=$('#id_paci').val();
		var urlprocess='agregar_examen_paciente.php';
		var dataString='process='+process+'&id_examen_paciente='+id_examen_paciente+'&id_paciente='+id_paciente+'&id_empleado='+id_empleado+'&id_doctor='+id_doctor+'&formulario='+formulario+'&fecha_cobro='+fecha_cobro+'&hora_cobro='+hora_cobro+'&id_muestra='+id_muestra+'&hora_de_muestra='+hora_de_muestra+'&hora_de_reporte='+hora_de_reporte+'&id_examen_evaluar='+id_examen_evaluar;
	}
  //editar examen paciente en admin examen paciente
	if(process=='edited1')
	{
		var id_paciente=$('#id_paci').val();
		var id_examen=$('#id_examen').val();
		var id_examen_paciente=$('#id_examen_paciente').val();
		var urlprocess='editar_examen_paciente.php';
		var dataString='process='+process+'&id_examen_paciente='+id_examen_paciente+'&id_empleado='+id_empleado+'&id_examen='+id_examen+'&id_paciente='+id_paciente+'&id_doctor='+id_doctor+'&formulario='+formulario+'&fecha_cobro='+fecha_cobro+'&hora_cobro='+hora_cobro+'&id_muestra='+id_muestra+'&hora_de_muestra='+hora_de_muestra+'&hora_de_reporte='+hora_de_reporte+'&id_examen_evaluar='+id_examen_evaluar;
	}
	//editar examen paciente en pendientes de imprimir
	if(process=='edited_imprimir')
	{
		var id_paciente=$('#id_paci').val();
		var id_examen=$('#id_examen').val();
		var id_examen_paciente=$('#id_examen_paciente').val();
		var urlprocess='editar_examen_paciente.php';
		var dataString='process='+process+'&id_examen_paciente='+id_examen_paciente+'&id_empleado='+id_empleado+'&id_examen='+id_examen+'&id_paciente='+id_paciente+'&id_doctor='+id_doctor+'&formulario='+formulario+'&fecha_cobro='+fecha_cobro+'&hora_cobro='+hora_cobro+'&id_muestra='+id_muestra+'&hora_de_muestra='+hora_de_muestra+'&hora_de_reporte='+hora_de_reporte+'&id_examen_evaluar='+id_examen_evaluar;
	}
	if(error_avanzar == 0){
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
					if(datax.hey=="edited1" || datax.hey=="edited_admin"){
						setInterval("reload2();", 1000);
					}
					 if(datax.hey=="edited_imprimir") {
						 setInterval("reload3();", 1000);
					 }
					 if(datax.hey=="Guardar" || datax.hey=="edited_modal") {
							 setInterval("reload1();", 1000);
						 }
					 if(datax.hey=="Siguiente") {
							 setInterval("reload4("+datax.paciente+","+datax.cobro+");", 1000);
						 }
	
				}
			}
		});
	}
	else{
		display_notify("Warning","Falta completar los datos de fecha y hora de muestra o reporte");
	}
	
}
function reload1()
{
	location.href = 'examen_pendiente.php';
}
function reload2()
{
	location.href = 'admin_examen_paciente.php';
}
function reload3()
{
	location.href = 'examen_pendiente_imprimir.php';
}
function reload4(paciente,cobro)
{
	location.href = 'agregar_examen_paciente_pen.php?id_paciente='+paciente+'&id_cobro='+cobro+'';
}


function generar(consulta){
	var order;
	if(consulta=="1"){
		order=1;
	}else{
		order=4;
	}
	console.log(order);
	dataTable = $('#editable2').DataTable().destroy()
	dataTable = $('#editable2').DataTable( {
			"pageLength": 50,
			"order":[[ order, 'asc' ]],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"examen_pendiente_dt.php?consulta="+consulta,
					error: function(){
						$(".editable2-error").html("");
						$("#editable2").append('<tbody class="editable22_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable2_processing").css("display","none");
						$( ".editable2-error" ).remove();
						}
					}
				} );

		dataTable.ajax.reload()
}
setInterval('generar("1")', 300000);


function generar1(consulta){
	dataTable = $('#editable3').DataTable().destroy()
	dataTable = $('#editable3').DataTable( {
			"pageLength": 50,
			"order":[ 4, 'asc' ],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"examen_pendiente_imprimir_dt.php?fecha="+consulta,
					error: function(){
						$(".editable3-error").html("");
						$("#editable3").append('<tbody class="editable3_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable3_processing").css("display","none");
						$( ".editable3-error" ).remove();
						}
					}
				} );

		dataTable.ajax.reload()
}
$("#fecha1").change(function(event) {
	generar1($(this).val());
});
$(document).on("click", "#cargar1", function(event) {
	generar1("1");
});


$("#fecha").change(function(event) {
	var consulta_fecha=$(this).val();
	generar(consulta_fecha);

});
$(document).on("click", "#cargar", function(event) {
	generar("1");
});




function generar2(){
	fechai=$("#fecha_inicial").val();
	fechaf=$("#fecha_final").val();
	dataTable = $('#editable4').DataTable().destroy()
	dataTable = $('#editable4').DataTable( {
			"pageLength": 50,
			"order":[ 0, 'asc' ],
			"processing": true,
			"serverSide": true,
			"ajax":{
					url :"admin_examen_paciente_dt.php?fechai="+fechai+"&fechaf="+fechaf, // json datasource
					//url :"admin_factura_rangos_dt.php", // json datasource
					//type: "post",  // method  , by default get
					error: function(){  // error handling
						$(".editable4-error").html("");
						$("#editable4").append('<tbody class="editable2_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
						$("#editable4_processing").css("display","none");
						$( ".editable4-error" ).remove();
						}
					}
				} );

		dataTable.ajax.reload()
	//}
}
$(document).on("click", "#btnMostrar", function(event) {
	generar2();
});

function anular_examen_paciente()
{
	var id_examen_paciente = $('#id_examen_paciente').val();
	var dataString = 'process=deleted' + '&id_examen_paciente=' + id_examen_paciente;
	$.ajax({
		type : "POST",
		url : "anular_examen_paciente.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload2();", 1500);
				$('#deleteModal').hide();
			}
		}
	});
}


///enviar individual
$(document).on('click', '#sendc', function()
{
    if($("#correo").val()!="")
    {
        var ide = $("#ide").val();
        var correo = $("#correo").val();
        var doctor = $("#nombre_doctor").val();
				var examen = $("#nombre_examen").val();
				var paciente = $("#nombre_paciente").val();
        $.ajax({
            type: 'POST',
            url: 'enviar_examen.php',
            data: 'process=sendc&ide='+ide+"&correo="+correo+"&doctor="+doctor+"&examen="+examen+"&paciente="+paciente,
            dataType: 'JSON',
            success: function(datax)
            {
                display_notify(datax.typeinfo, datax.msg);
                if(datax.typeinfo == "Success")
                {
                    $("#salir").click();
                    setInterval('location.reload();', 1500);
                }
            }
        });
    }
    else
    {
        display_notify("Error", "Ingrese un correo");
    }
});

//enviar todos
$(document).on('click', '#sendc_i', function()
{

    if($("#correo").val()!="")
    {
        var cobro = $("#id_cobro").val();
        var correo = $("#correo").val();
        var doctor = $("#nombre_doctor").val();
				var examen = $("#nombre_examen").val();
				var paciente = $("#nombre_paciente").val();
        $.ajax({
            type: 'POST',
            url: 'modal_imprimir_examen.php',
            data: 'process=sendc_i&ide='+cobro+"&correo="+correo+"&doctor="+doctor+"&examen="+examen+"&paciente="+paciente,
            dataType: 'JSON',
            success: function(datax)
            {
                display_notify(datax.typeinfo, datax.msg);
                if(datax.typeinfo == "Success")
                {
                    $("#salir").click();
                    setTimeout('location.reload();', 1500);
                }
            }
        });
    }
    else
    {
			console.log("enviar")
        display_notify("Error", "Ingrese un correo");
    }
});

function tmodal_pediente_impr()
{
	var id_cobro = $('#id_cobro').val();
	var url='modal_imprimir_examen.php'
	var dataString = 'process=tabla_modal&id_cobro=' + id_cobro ;

	$.ajax({
		type : "POST",
		url : url,
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			$("#print_e").html(datax.tr);
			if(datax.tr==""){
				setInterval("reload3();", 1000);
			}

		}
	});

}
