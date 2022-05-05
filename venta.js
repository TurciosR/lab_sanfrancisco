var urlprocess='';
$(document).ready(function() {

	$('#num_doc_fact').numeric({negative:false,decimal:false});
	$('html,body').animate({
		scrollTop: $(".focuss").offset().top
	}, 1500);
	$(".select").select2({
		placeholder: {
			id: '',
			text: 'Seleccione',
		},
		allowClear: true,
	});
	$("#producto_buscar").typeahead({
		source: function(query, process) {
			$.ajax({
				type: 'POST',
				url: 'facturacion_autocomplete1.php',
				data: 'query=' + query,
				dataType: 'JSON',
				async: true,
				success: function(data) {
					process(data);
				}
			});
		},
		updater: function(selection) {
			var prod0 = selection;
			var prod = prod0.split("|");
			var id_prod = prod[0];
			var descrip = prod[1];
			var tipo = prod[2];
			if(id_prod!=0){
				addProductList(id_prod, tipo, descrip);
				$('input#producto_buscar').val("");
				// $('.sel').focus().select2("open");

			}
			else{
				$('input#producto_buscar').focus();
				$('input#producto_buscar').val("");
			}
			// agregar_producto_lista(id_prod, descrip, isbarcode);
		}
	});
	var urlprocess=$('#urlprocess').val();
	$('#formulario').validate({
		rules: {
			descripcion: {
				required: true,
			},
			precio1: {
				required: true,
				number: true,
			},
		},
		submitHandler: function (form) {
			senddata();
		}
	});
	// Clean the modal form
	$("#n_ref").keypress(function(e) {
		if(e.which == 13) {
			cargar_ref();

			$("#n_ref").val("");
		}
	});
	$("#banco").change(function() {
		$("#numcuenta *").remove();
		$("#select2-numcuenta-container").text("");
		var ajaxdata = {
			"process": "cuenta",
			"id_banco": $("#banco").val()
		};
		$.ajax({
			url: "venta.php",
			type: "POST",
			data: ajaxdata,
			success: function(opciones) {
				$("#select2-numcuenta-container").text("Seleccione");
				$("#numcuenta").html(opciones);
				$("#numcuenta").val("");
			}
		})
	});
	/*
	$("#id_cliente").change(function() {
	$("#tipo_impresion *").remove();
	$("#select2-tipo_impresion-container").text("");
	var ajaxdata = {
	"process": "tipoimpre",
	"id_cliente": $("#id_cliente").val(),
	"tip_impre": $("#tip_impre").val()
};
$.ajax({
url: "venta.php",
type: "POST",
data: ajaxdata,
success: function(opciones) {
$("#select2-tipo_impresion-container").text("Seleccione");
$("#tipo_impresion").html(opciones);
$("#tipo_impresion").val("");
}
})
});

$("#id_cliente").change(function() {
$("#tipo_pago *").remove();
$("#select2-tipo_pago-container").text("");
var ajaxdata = {
"process": "tipopago",
"id_cliente": $("#id_cliente").val()
};
$.ajax({
url: "venta.php",
type: "POST",
data: ajaxdata,
success: function(opciones) {
$("#select2-tipo_pago-container").text("Seleccione");
$("#tipo_pago").html(opciones);
$("#tipo_pago").val("");
}
})
});
*/
$(".decimal").numeric({negative:false,decimalPlaces:2});
/*$('#paciente').select2({
allowClear: true,
escapeMarkup: function (markup) { return markup; },
placeholder: "Buscar Cliente",
language: {
noResults: function () {
var modalcliente="<a href='modal_cliente.php' data-toggle='modal' data-target='#clienteModal'>";
modalcliente+="Agregar Cliente</a>";
return modalcliente;
}
}
});*/

$("#doctor").select2({
	allowClear: true,
	escapeMarkup: function(markup) {
		return markup;
	},
	placeholder: "Buscar Doctor",
	language: {
		noResults: function() {
			var modalDoctor = "<a class='xa' href='DoctorModal.php' data-toggle='modal' data-target='#doctorModal'>";
			modalDoctor += "Agregar Doctor</a>";
			return modalDoctor;
			$('#doctor').select2('close');
		}
	}
});
$("#id_procedencia").select2({
	allowClear: true,
	escapeMarkup: function(markup) {
		return markup;
	},
	placeholder: "Buscar Procedencia",
	language: {
		noResults: function() {
			var modalDoctor = "<a class='xp' href='ProcedenciaModal.php' data-toggle='modal' data-target='#procedenciaModal'>";
			modalDoctor += "Agregar Procedencia</a>";
			return modalDoctor;
			$('#id_procedencia').select2('close');
		}
	}
});
$("#paciente").select2({
	allowClear: true,
	escapeMarkup: function(markup) {
		return markup;
	},
	placeholder: "Buscar Paciente",
	language: {
		noResults: function() {
			var modalcliente = "<a class='xb' href='ClienteModal.php' data-toggle='modal' data-target='#clienteModal'>";
			modalcliente += "Agregar Paciente</a>";
			return modalcliente;
			$('#paciente').select2('close');
		}
	}
});
$("#id_cliente").select2({
	allowClear: true,
	escapeMarkup: function(markup) {
		return markup;
	},
	placeholder: "Buscar Cliente",
	language: {
		noResults: function() {
			var modalcliente = "<a class='xc' href='ClienteModal1.php' data-toggle='modal' data-target='#cliente1Modal'>";
			modalcliente += "Agregar Cliente</a>";
			return modalcliente;
			$('#id_cliente').select2('close');
		}
	}
});
$(document).keydown(function(e){
	if(e.which == 113){ //F2 Guardar
		e.stopPropagation();
		senddata();
	}
	if(e.which == 115){ //F2 Guardar
		e.stopPropagation();
		location.replace('dashboard.php');
	}
	if(e.which == 119) {//F8 Imprimir
		//$('#busca_descrip_activo').prop("checked", false);
		//activar_busqueda()
		//PENDIENTE
	}
	if(e.which == 120) { //F9 Salir
		//PENDIENTE
	}

	if ((e.metaKey || e.ctrlKey) && ( String.fromCharCode(e.which).toLowerCase() === 'e') ) {

		$('#doctor').select2('close');
		$('#tipo_impresion').select2('close');
		$('#id_cliente').select2('close')
		$('input#producto_buscar').focus();;
	}
	if ((e.metaKey || e.ctrlKey) && ( String.fromCharCode(e.which).toLowerCase() === 'c') ) {


		$('#id_cliente').select2('open');
		$('#tipo_impresion').select2('close');
		$('#doctor').select2('close');
	}
	if ((e.metaKey || e.ctrlKey) && ( String.fromCharCode(e.which).toLowerCase() === 'x') ) {

		$('#doctor').select2('open');
		$('#tipo_impresion').select2('close');
		$('#id_cliente').select2('close');

	}
	if ((e.metaKey || e.ctrlKey) && ( String.fromCharCode(e.which).toLowerCase() === 'a') ) {


		$('#tipo_impresion').select2('open');
		$('#doctor').select2('close');
		$('#id_cliente').select2('close');
	}

});

$('#form_fact_consumidor').hide();
$('#form_fact_ccfiscal').hide();

//Boton de imprimir deshabilitado hasta que se guarde la factura
$('#print1').prop('disabled', true);
$('#submit1').prop('disabled', false);
//$('#print1').prop('disabled', false);

//$('#buscador').hide();
$("#producto_buscar").typeahead({
	source: function(query, process) {
		//var textVal=$("#producto_buscar").val();
		$.ajax({
			url: 'autocomplete_producto.php',
			type: 'POST',
			data: 'query=' + query ,
			dataType: 'JSON',
			async: true,
			success: function(data) {
				process(data);

			}
		});
	},
	updater: function(selection){
		var prod0=selection;
		var prod= prod0.split("|");
		var id_prod = prod[0];
		var descrip = prod[1];

		agregar_producto_lista(id_prod, descrip);
		// $('.sel').select2('oper');



	}
});
$("#producto_buscar").focus();


/*	$('#paciente').select2({
allowClear: true,
escapeMarkup: function(markup) {
return markup;
},
placeholder: "Buscar Cliente",
language: {
noResults: function() {
var modalcliente = "<a href='ClienteModal.php' data-toggle='modal' data-target='#clienteModal'>";
modalcliente += "Agregar Paciente</a>";
return modalcliente;
$('#paciente').select2('close');
}
}
});
*/
});

$(document).on("click", ".xa", function(event) {
	$("#doctor").select2('close');

});
$(document).on("click", ".xb", function(event) {
	$("#paciente").select2('close');
});
$(document).on("click", ".xp", function(event) {
	$("#id_procedencia").select2('close');
});
$(document).on("click", ".xc", function(event) {
	$("#id_cliente").select2('close');
});
$(document).on('hidden.bs.modal', function(e) {
	var target = $(e.target);
	target.removeData('bs.modal').find(".modal-content").html('');
});

//function to round 2 decimal places
function round(value, decimals) {
	return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
$(function (){
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

// Evento para seleccionar una opcion y mostrar datos en un div
$(document).on("change","#tipo_entrada", function (){
	$( ".datepick2" ).datepicker();
	$('#id_proveedor').select2();

	var id=$("select#tipo_entrada option:selected").val(); //get the value
	if(id!='0'){
		$('#buscador').show();
	}
	else
	$('#buscador').hide();

	if (id=='1')
	$('#form_fact_consumidor').show();
	else
	$('#form_fact_consumidor').hide();


	if (id=='2')
	$('#form_fact_ccfiscal').show();
	else
	$('#form_fact_ccfiscal').hide();

});

// Seleccionar el tipo de factura
$(document).on("change","#tipo_entrada", function(){
	var id=$("select#tipo_entrada option:selected").val(); //get the value
	////alert(id);
	$('#mostrar_numero_doc').load('editar_factura.php?'+'process=mostrar_numfact'+'&id='+id);
});

// Agregar productos a la lista del inventario
function cargar_empleados(){
	$('#inventable>tbody>tr').find("#select_empleado").each(function(){
		$(this).load('editar_factura.php?'+'process=cargar_empleados');
		totales();
	});
}

// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", ".Delete", function() {
	var tr = $(this).parents("tr");
	var tipo = tr.hasClass("P");
	var idp = tr.find("#id_prod").val();
	if(tipo)
	{
		$(".P"+idp).remove();
	}
	tr.remove();
	totales();
});

/*$(document).on('keyup', '#cant', function() {
fila = $(this).parents('tr');
id_producto = fila.find('.id_pps').text();
//existencia = parseFloat(fila.find('#cant_stock').text());
//existencia=round(existencia, 4);
a_cant=$(this).val();
unidad= parseInt(fila.find('#unidades').val());
a_cant=parseFloat(a_cant*unidad);
a_cant=round(a_cant, 4);

//console.log(a_cant);
a_asignar=0;

$('#inventable tr').each(function(index) {

if($(this).find('.id_pps').text()==id_producto)
{
t_cant=parseFloat($(this).find('#cant').val());
t_cant=round(t_cant, 4);
if(isNaN(t_cant))
{
t_cant=0;
}
t_unidad=parseInt($(this).find('#unidades').val());
if(isNaN(t_unidad))
{
t_unidad=0;
}
t_cant=parseFloat((t_cant*t_unidad));
a_asignar=a_asignar+t_cant;
a_asignar=round(a_asignar,4);
}
});

totales();
var tr = $(this).parents("tr");
setTimeout(function(){ actualiza_subtotal(tr); }, 300);
});*/

$(document).on("keyup", "#precio_venta", function() {
	var tr = $(this).parents("tr");
	if($(this).val()!=""){
		actualiza_subtotal(tr);
	}
});

function actualiza_subtotal(tr) {
	var iva = parseFloat($('#porc_iva').val());
	//var precio_sin_iva = parseFloat(tr.find('#precio_sin_iva').val());
	var tipo_impresion = $('#tipo_impresion').val();
	if (tipo_impresion!='CCF') {
		var cantidad = tr.find('#cant').val();
		if (isNaN(cantidad) || cantidad == "") {
			cantidad = 0;
		}
		var precio = tr.find("#precio_venta").val();
		if(precio != "")
		{
			precio = parseFloat(precio);
		}
		else
		{
			precio  = 0;
		}
		if (isNaN(precio) || precio == "") {
			precio = 0;
		}
		var subtotal = subt(cantidad, precio);
		var subt_mostrar = round(subtotal,2);
		tr.find("#subtotal_fin").val(subt_mostrar);
		tr.find("#subtotal_mostrar").val(subt_mostrar);

		//console.log("hola"+subt_mostrar+" can"+cantidad+"pre"+precio);
		totales();
	}

}

function totales() {
	//impuestos
	var porcentaje_descuento = parseFloat($("#porcentaje_descuento").val());


	var urlprocess = $('#urlprocess').val();

	var i = 0, total = 0;

	totalcantidad = 0;
	var subtotal = 0;
	var subt_cant = 0;
	var total_descto = 0;
	var subt_descto = 0;
	var total_final = 0;
	var StringDatos = '';
	var filas = 0;
	var items2 = 0;
	var total_iva = 0;

		$("#inventable tr").each(function() {
			if(!$(this).hasClass("EP"))
			{
				subt_cant = $(this).find("#cant").val();
				totalcantidad += parseInt(subt_cant);
				subtotal += parseFloat($(this).find("#subtotal_fin").val());
				filas += 1;
			}
		});
		items2=$("#idco").val();
		subtotal = round(subtotal, 4);
		//descuento
		var total_descuento = 0;
		if (porcentaje_descuento > 0.0)
		{
			total_descuento = (porcentaje_descuento / 100) * subtotal
		}
		else
		{
			total_descuento = 0;
		}
		var total_descuento_mostrar = round(total_descuento,2);
		var total_mostrar = (subtotal -total_descuento).toFixed(2);
		totcant_mostrar = round(totalcantidad,2).toFixed(2);
		$('#totcant').text(totcant_mostrar);

		$('#total_gravado').html(total_mostrar);
		$('#subtotal').html(subtotal.toFixed(2));
		$('#pordescuento').html(porcentaje_descuento);
		$('#valdescuento').html(total_descuento.toFixed(2));

		$('#totcant').html(totcant_mostrar);
		$('#items').val(items2);
		$('#totaltexto').load(urlprocess, {
			'process': 'total_texto',
			'total': total_mostrar
		});
		$('#monto_pago').html(total_mostrar);

		$('#totalfactura').val(total_mostrar);


}

function totalFact(){
	var TableData = new Array();
	var i = 0;
	var total = 0;
	var StringDatos = '';
	$("#inventable>tbody  tr").each(function(index) {
		if (index >= 0) {
			var subtotal = 0;
			$(this).children("td").each(function(index2) {
				switch (index2) {
					case 7:
					var isVisible = false
					isVisible = $(this).filter(":visible").length > 0;
					if (isVisible == true) {
						subtotal = parseFloat($(this).text());
						if (isNaN(subtotal)) {
							subtotal = 0;
						}
					} else {
						subtotal = 0;
					}
					break;
				}
			});
			total += subtotal;
		}
	});
	total = round(total, 2);
	total_dinero = total.toFixed(2);
	$('#total_dinero').html("<strong>" + total_dinero + "</strong>");
	$('#totaltexto').load('venta.php?' + 'process=total_texto&total=' + total_dinero);
	//console.log('total:' + total_dinero);
}
// actualize table data to server
$(document).on("click","#submit1",function(){
	senddata();
});
$(document).on("click", "#btnEsc", function (event) {
	reload1();
});

$(document).on("click", ".print1", function () {
	var totalfinal=parseFloat($('#totalfactura').val());
	var facturado= totalfinal.tosenddFixed(2);
	$(".modal-body #facturado").val(facturado);
});
$(document).on("click", "#btnPrintFact", function (event) {
	imprime1();
});

$(document).on("click","#print2",function(){
	imprime2();
});


function senddata() {
	//Obtener los valores a guardar de cada item facturado
	var procces = $("#process").val();
	var i = 0;
	var StringDatos = "";
	var id = '1';
	var id_empleado = 0;
	var id_cliente = $("#id_cliente").val();
	var id_procedencia = $("#id_procedencia").val();
	var items = $("#items").val();
	var numche = $("#numche").val();
	var banco = $("#banco").val();
	var num_trans = $("#numtrans").val();
	var num_cuenta = $("#numcuent").val();

	var msg = "";
	//IMPUESTOS
	error=false;


	var total_percepcion = $('#total_percepcion').text();
	var id_factura =$('#id_factura').val();
	var subtotal = $('#total_gravado_iva').text();/*total gravado mas iva subtotal*/
	var suma_gravada= $('#total_gravado_sin_iva').text();/*total sumas sin iva*/
	var sumas= $('#total_gravado').text();/*total sumas sin iva + exentos*/
	var iva = $('#total_iva').text(); /*porcentaje de iva de la factura*/
	var retencion = $('#total_retencion').text();/*total retencion cuando un cliente retiene 1 o 10 %*/
	var venta_exenta =$('#total_exenta').text();/*total venta exenta*/
	var total = $('#totalfactura').val();
	var tipo_pago=$('#tipo_pago').val();
	var con_pago=$('#con_pago').val();
	var tipo_rem=$('#tipo_rem').val();
	var doctor =$("#doctor").val();
	var variosE =$("#cuanto").val();
	var id =$("#id").val();
	var id_vendedor = $("#vendedor").val();
	var id_apertura =$('#id_apertura').val();
	var turno =$('#turno').val();
	var caja =$('#caja').val();
	var credito=$('#tipo_pago').val();
	var id_descuento=$('#id_descuento').val();
	var porcentaje_descuento = $('#porcentaje_descuento').val();
	var id_pacie = $('.sel').val();
	var tipo_impresion= $('#tipo_impresion').val();


	var fecha_movimiento = $("#fecha").val();
	var id_prod = 0;
	if (fecha_movimiento == '' || fecha_movimiento == undefined) {
		var typeinfo = 'Warning';
		msg = 'Seleccione una Fecha!';
		display_notify(typeinfo, msg);
	}
	var verificaempleado = 'noverificar';
	var verifica = [];
	var array_json = new Array();
	var id_paciente="";
	$("#inventable tr").each(function(index)
	{
		var id_detalle = $(this).attr("id_detalle");
		if(id_detalle == undefined)
		{
			id_detalle = "";
		}
		var tipo = "E";
		if($(this).hasClass("EP"))
		{
			 tipo = "P";
		}
		var perfil = "E";
		if($(this).hasClass("P"))
		{
			perfil = "P";
		}
		var id_exa = $(this).find("#id_prod").val();
		var id = $(this).find("td:eq(0)").text();
		id_paciente = $('.sel').val();
		var n_precio = $(this).find("#precio_venta").val();
		var precio_venta = $(this).find("#precio_venta").text();
		var cantidad = $(this).find("#cant").val();
		var unidades = $(this).find("#unidades").val();
		var descripcion = $(this).find("#desc").text();
		var exento = $(this).find("#exento").val();
		var subtotal = $(this).find("#subtotal_fin").val();
		var fecha_re =$(this).find(".fecha").val();
		var hora_re =$(this).find('.hora').val();
		var cortesia =$(this).find('#cortesia').val();
		if (cantidad && precio_venta) {
			var obj = new Object();
			obj.id_detalle = id_detalle;
			obj.id = id_exa;
			obj.descripcion=descripcion;
			obj.precio = precio_venta;
			obj.nprecio = n_precio;
			obj.cantidad = cantidad;
			obj.varios = variosE;
			obj.corte = cortesia;
			obj.unidades = unidades;
			obj.subtotal = subtotal;
			obj.fecha = fecha_re;
			obj.hora = hora_re;
			obj.id_paciente = id_paciente;
			obj.perfil = perfil;
			obj.tipo = tipo;
			//convert object to json string
			text = JSON.stringify(obj);
			array_json.push(text);
			i = i + 1;
		}
		else
		{
			error=true
		}
	});
	json_arr = '[' + array_json + ']';
	if(procces == "insert")
	{
		var urlprocess = "preventa.php";
		var id_cotizacion = "";
	}

	if (i==0) {
		error=true
	}

	var dataString = 'process=insert' + '&cuantos=' + i + '&fecha_movimiento=' + fecha_movimiento;
	dataString += '&id_cliente=' + id_cliente + '&total=' + total;
	dataString += '&id_vendedor=' + id_vendedor + '&json_arr=' + json_arr;
	dataString += '&retencion=' + retencion;
	dataString += '&total_percepcion=' + total_percepcion;
	dataString += '&iva=' + iva;
	dataString += '&items=' + items;
	dataString += '&subtotal=' + subtotal;
	dataString += '&sumas=' + sumas;
	dataString += '&venta_exenta=' + venta_exenta;
	dataString += '&suma_gravada=' + suma_gravada;
	dataString += '&tipo_impresion=' + tipo_impresion;
	dataString += '&id_factura=' + id_factura;
	dataString += '&id_apertura=' + id_apertura;
	dataString += '&procedencia=' + id_procedencia;
	dataString += '&turno=' + turno;
	dataString += '&id_pacie=' + id_pacie;
	dataString += '&caja=' + caja;
	dataString += '&credito=' + credito;
	dataString += '&doctor=' + doctor;
	dataString += '&tipo_pag=' + tipo_pago;
	dataString += '&con_pag=' + con_pago;
	dataString += '&tipo_rem=' + tipo_rem;
	dataString += '&num_che=' + numche;
	dataString += '&banco=' + banco;
	dataString += '&monto_che=' + total;
	dataString += '&num_trans=' + num_trans;
	dataString += '&cuenta_banco=' + num_cuenta;
	dataString += '&monto_trans=' + total;
	dataString += '&id_descuento=' + id_descuento;
	dataString += '&porcentaje_descuento=' + porcentaje_descuento;


	var sel_vendedor = 1;
	if (credito == "") {
		msg = 'No a seleccionado un tipo de pago!';
		sel_vendedor = 0;
	}

	if (id_cliente == "") {
		msg = 'No hay un Cliente!';
		sel_vendedor = 0;
	}
	if (id_paciente == "") {
		msg = 'No hay un Paciente Seleccionado!';
		sel_vendedor = 0;
	}


	if (tipo_pago == "") {
		msg = 'No hay un tipo de pago seleccionada!';
		sel_vendedor = 0;
	}

	if (i == 0) {
		msg = 'Seleccione al menos un producto !';
		sel_vendedor = 0;
	}

	if (sel_vendedor == 1) {
		$("#inventable tr").remove();
		$.ajax({
			type: 'POST',
			url: urlprocess,
			data: dataString,
			dataType: 'json',
			success: function(datax) {
				if (datax.typeinfo == "Success")
				{

					$(".usage").attr("disabled", true);
					if(tipo_impresion == "CCF" || tipo_impresion == "COF")
					{
						if(tipo_impresion == "CCF")
						{
							$("#nitcli").attr('readOnly', false);
							$("#nrccli").attr('readOnly', false);
							$("#nrccli").val(datax.ncr);
							$("#nitcli").val(datax.nit);

						}
						$("#nomcli").attr('readOnly', false);
						$("#nomcli").val(datax.cliente);
						$("#numdoc").attr('readOnly', false);
						$("#numdoc").focus();
					}
					else
					{
						if($("#con_pago").val()=='CHE'){
							$("#numche").focus();
						}else if ($("#con_pago").val()=='TRA') {
							$(".banco").select2('open');
						}else if ($("#con_pago").val()=='TAR') {
							$("#numtarj").focus();
						}	else {
							$("#efectivov").focus();
						}
						$('#numdoc').val(datax.ultimo);

					}
					$("#tot_fdo").val(total);
					$('#id_factura').val(datax.id_cobro);
					ultimo=parseInt(datax.ultimo);
					if(ultimo!=0)
					{
						$('#num_doc_fact').val(ultimo);
					}
					$('#corr_in').val(datax.numdoc);

					if (con_pago=="CHE") {
						$("#numche").attr('readOnly',false);
						$("#emisor").attr('readOnly',false);
					}else if (con_pago=="TRA") {
						$("#banco").attr('disabled',false);
						$("#numtrans").attr('readOnly',false);
						$("#numcuenta").attr('disabled',false);

					}else if (con_pago=="TAR") {
						$("#numtarj").attr('readOnly',false);
						$("#emisor").attr('readOnly',false);
						$("#voucher").attr('readOnly',false);
					}

				}
				else {
					display_notify(datax.typeinfo, datax.msg);
				}
			}
		});
	} else {
		display_notify('Warning', msg);
	}
}

$(document).on("keyup","#efectivo",function(){
	total_efectivo();
});
$(document).on("change","#con_pago",function(){
	//$(".usage").attr("disabled", true);
	if ($(this).val()=="CHE") {


		$("#vernumche").attr("hidden",false);
		$("#veremisor").attr("hidden",false);
		$("#verefectivo").attr("hidden",false);
		$("#verntarj").attr("hidden",true);
		$("#vervouch").attr("hidden",true);
		$("#verbanco").attr("hidden",true);
		$("#vernumcue").attr("hidden",true);
		$("#vertrans").attr("hidden",true);

	}else if ($(this).val()=="TRA") {

		$("#veremisor").attr("hidden",true);
		$("#vernumche").attr("hidden",true);
		$("#verefectivo").attr("hidden",false);
		$("#verntarj").attr("hidden",true);
		$("#verbanco").attr("hidden",false);
		$("#vervouch").attr("hidden",true);
		$("#vertrans").attr("hidden",false);
		$("#vernumcue").attr("hidden",false);


	}else if ($(this).val()=="EFE") {

		$("#veremisor").attr("hidden",true);
		$("#vernumche").attr("hidden",true);
		$("#verefectivo").attr("hidden",false);
		$("#verntarj").attr("hidden",true);
		$("#verbanco").attr("hidden",true);
		$("#vervouch").attr("hidden",true);
		$("#vertrans").attr("hidden",true);
		$("#vernumcue").attr("hidden",true);
	}else if ($(this).val()=="TAR") {
		$("#veremisor").attr("hidden",false);
		$("#vernumche").attr("hidden",true);
		$("#verefectivo").attr("hidden",false);
		$("#verntarj").attr("hidden",false);
		$("#verbanco").attr("hidden",true);
		$("#vervouch").attr("hidden",false);
		$("#vertrans").attr("hidden",true);
		$("#vernumcue").attr("hidden",true);
	}

});
$(document).on("change","#tipo_pago",function(){
	if($(this).val()=="CON")
	{
		$("#mostrarcon").attr("hidden",false);
		$("#mostrarrem").attr("hidden",true);
	}else if ($(this).val()=="REM") {
		$("#mostrarrem").attr("hidden",false);
		$("#mostrarcon").attr("hidden",true);
	}
	else {
		$("#mostrarcon").attr("hidden",true);
		$("#mostrarrem").attr("hidden",true);
	}
});

$(document).on("keyup","#efectivov",function(evt){
	if(evt.keyCode !=13)
	{
		total_efectivov();
	}
	else
	{
		if(parseFloat($("#cambiov").val()) >=0)
		{
			display_notify("Success", "Factura realizada con exito!");
			imprimev();
			//setInterval("reload1();", 500);
		}
		else {
			display_notify("Warning", "Ingrese un valor mayor o igual al total facturado");
		}
	}
});

$(document).on("keyup","#numdoc",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#nomcli").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero del documento a imprimir');
		}
	}
});
$(document).on("keyup","#nomcli",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			if($("#tipo_impresion").val() == 'CCF')
			{
				$("#nitcli").focus();
			}
			else {
				if($("#con_pago").val()=='CHE'){
					$("#numche").focus();
				}else if ($("#con_pago").val()=='TRA') {
					$(".banco").select2('open');
				}else if ($("#con_pago").val()=='TAR') {
					$("#numtarj").focus();
				}

				else {
					$("#efectivov").focus();
				}

			}
		}
		else {
			display_notify('Warning','Ingrese el nombre del cliente');
		}
	}
});
$(document).on("keyup","#numche",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#emisor").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero de cheque!');
		}
	}
});
$(document).on("keyup","#numtarj",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#emisor").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero de tarjeta!');
		}
	}
});

$(document).on("keyup","#emisor",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			if ($("#con_pago").val()=='TAR') {
				$("#voucher").focus();
			}

			else {
				$("#efectivov").focus();
			}
		}
		else {
			display_notify('Warning','Ingrese el nombre de banco!');
		}
	}
});
$(document).on('select2:close', '.banco', function(evt)
{
	$('.cuentaba').select2('open');
});
$(document).on('select2:close', '.cuentaba', function(evt)
{
	$("#numtrans").focus();

});
$(document).on("keyup","#numtrans",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#efectivov").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero de Transferencia!');
		}
	}
});

$(document).on("keyup","#voucher",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#efectivov").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero de Voucher!');
		}
	}
});
$(document).on("keyup","#nitcli",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			$("#nrccli").focus();
		}
		else {
			display_notify('Warning','Ingrese el numero de NIT del cliente');
		}
	}
});
$(document).on("keyup","#nrccli",function(evt){
	if(evt.keyCode == 13)
	{
		if($(this).val()!="")
		{
			if($("#con_pago").val()=='CHE'){
				$("#numche").focus();
			}else if ($("#con_pago").val()=='TRA') {
				$(".banco").select2('open');
			}else if ($("#con_pago").val()=='TAR') {
				$("#numtarj").focus();
			}	else {
				$("#efectivov").focus();
			}
		}
		else {
			display_notify('Warning','Ingrese el numero de registro del cliente');
		}
	}
});

function total_efectivo(){
	var efectivo=parseFloat($('#efectivo').val());
	var totalfinal=parseFloat($('#totalfactura').val());
	var facturado= totalfinal.toFixed(2);
	$('#facturado').val(facturado);
	if (isNaN(parseFloat(efectivo))){
		efectivo=0;
	}
	if (isNaN(parseFloat(totalfinal))){
		totalfinal=0;
	}
	var cambio=efectivo-totalfinal;
	var cambio=round(cambio, 2);
	var	cambio_mostrar=cambio.toFixed(2);
	$('#cambio').val(cambio_mostrar);
}
function total_efectivov(){
	var efectivo=parseFloat($('#efectivov').val());
	var totalfinal=parseFloat($('#tot_fdo').val());
	var facturado= totalfinal.toFixed(2);
	$('#facturadov').val(facturado);
	if (isNaN(parseFloat(efectivo))){
		efectivo=0;
	}
	if (isNaN(parseFloat(totalfinal))){
		totalfinal=0;
	}
	var cambio=efectivo-totalfinal;
	var cambio=round(cambio, 2);
	var	cambio_mostrar=cambio.toFixed(2);
	$('#cambiov').val(cambio_mostrar);
}
function imprime1(){
	var numero_doc = $(".modal-body #fact_num").html();
	var print = 'imprimir_fact';
	var tipo_impresion = $("#tipo_impresion").val();

	var id_factura=$("#id_factura").val();
	if (tipo_impresion=="TIK"){
		var num_doc_fact = '';
		numero_factura_consumidor='';
	}
	else{
		var numero_factura_consumidor = $(".modal-body #num_doc_fact").val();
		var num_doc_fact = $(".modal-body #num_doc_fact").val();
	}
	var dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion + '&num_doc_fact=' + id_factura+'&numero_factura_consumidor='+numero_factura_consumidor;

	if (tipo_impresion=="CCF"){
		nit=$('.modal-body #nit').val();
		nrc=$('.modal-body #nrc').val();
		nombreape=$('.modal-body #nombreape').val();
		dataString +='&nit=' + nit+ '&nrc=' + nrc+'&nombreape=' + nombreape;
	}
	$.ajax({
		type: 'POST',
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datos) {
			var sist_ope = datos.sist_ope;
			var dir_print=datos.dir_print;
			var shared_printer_win=datos.shared_printer_win;
			var shared_printer_pos=datos.shared_printer_pos;
			var headers=datos.headers;
			var footers=datos.footers;
			var efectivo_fin = parseFloat($('#efectivo').val());
			var cambio_fin = parseFloat($('#cambio').val());

			//esta opcion es para generar recibo en  printer local y validar si es win o linux
			if (tipo_impresion == 'COF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printfactwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printfact1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			if (tipo_impresion == 'ENV') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printenvwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printenv1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			if (tipo_impresion == 'TIK') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printposwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_pos:shared_printer_pos,
						headers:headers,
						footers:footers,
					})
				} else {
					$.post("http://"+dir_print+"printpos1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						headers:headers,
						footers:footers,
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			if (tipo_impresion == 'CCF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printcfwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printcf1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			//  setInterval("reload1();", 500);
		}
	});
}
/*function imprimev(){
var numero_doc = $("#numdoc").val();
var print = 'actua_fac';
var tipo_impresion = $("#tipo_impresion").val();
var tipo_pago = $("#tipo_pago").val();

var id_factura=$("#id_factura").val();
if (tipo_impresion=="TIK"){
numero_factura_consumidor='';
}
else{
var numero_factura_consumidor = $("#numdoc").val();
}
var dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion+ '&tipo_pag=' + tipo_pago + '&id_cobro=' + id_factura+'&numero_factura_consumidor='+numero_factura_consumidor;

if (tipo_impresion=="CCF"){
nit=$('#nitcli').val();
nrc=$(' #nrccli').val();
nombreape=$('#nomcli').val();
dataString +='&nit=' + nit+ '&nrc=' + nrc+'&nombreape=' + nombreape;
}
$.ajax({
type: 'POST',
url: urlprocess,
data: dataString,
dataType: 'json',
success: function(datos) {
/*	var sist_ope = datos.sist_ope;
var dir_print=datos.dir_print;
var shared_printer_win=datos.shared_printer_win;
var shared_printer_pos=datos.shared_printer_pos;
var headers=datos.headers;
var footers=datos.footers;
var efectivo_fin = parseFloat($('#efectivo').val());
var cambio_fin = parseFloat($('#cambio').val());

//esta opcion es para generar recibo en  printer local y validar si es win o linux
if (tipo_impresion == 'COF') {
if (sist_ope == 'win') {
$.post("http://"+dir_print+"printfactwin1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin,
shared_printer_win:shared_printer_win
})
} else {
$.post("http://"+dir_print+"printfact1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin
}, function(data, status) {

if (status != 'success')
{
//alert("No Se envio la impresión " + data);
}
});
}
}
if (tipo_impresion == 'ENV') {
if (sist_ope == 'win') {
$.post("http://"+dir_print+"printenvwin1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin,
shared_printer_win:shared_printer_win
})
} else {
$.post("http://"+dir_print+"printenv1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin
}, function(data, status) {

if (status != 'success') {
//alert("No Se envio la impresión " + data);
}

});
}
}
if (tipo_impresion == 'TIK') {
if (sist_ope == 'win') {
$.post("http://"+dir_print+"printposwin1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin,
shared_printer_pos:shared_printer_pos,
headers:headers,
footers:footers,
})
} else {
$.post("http://"+dir_print+"printpos1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin,
headers:headers,
footers:footers,
}, function(data, status) {

if (status != 'success') {
//alert("No Se envio la impresión " + data);
}

});
}
}
if (tipo_impresion == 'CCF') {
if (sist_ope == 'win') {
$.post("http://"+dir_print+"printcfwin1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin,
shared_printer_win:shared_printer_win
})
} else {
$.post("http://"+dir_print+"printcf1.php", {
datosventa: datos.facturar,
efectivo: efectivo_fin,
cambio: cambio_fin
}, function(data, status) {

if (status != 'success') {
//alert("No Se envio la impresión " + data);
}

});
}
}
//  setInterval("reload1();", 500);

/*swal({
title: "Impresion correcta?",
text: "",
type: "warning",
showCancelButton: true,
confirmButtonClass: "btn-success",
cancelButtonClass: "btn-info",
confirmButtonText: "Si, Continuar",
cancelButtonText: "No, Reimprimir",
closeOnConfirm: true,
closeOnCancel: true
},
function(isConfirm) {
if (isConfirm)
{

}
else
{
imprimev();
}
});
//location.reload();
}
});
}
*/
function imprime2(){
	//Utilizar la libreria esc pos php
	//Calcular los valores a guardar de cad item del inventario
	var i=0;
	var precio_venta,precio_venta, cantidad,id_prod,id_empleado;
	var elem1 = '';
	var descripcion='';
	var tipoprodserv = '';  tipoprod = '';
	var  StringDatos="";
	var id=$("select#tipo_entrada option:selected").val(); //get the value

	var id_cliente=$("select#id_cliente option:selected").val(); //get the value
	if (id=='0'){
		$('#tipo_entrada').focus();
	}
	var numero_doc=$("#numero_doc").val();
	var numero_doc2=$("#numero_doc2").val();
	var total_ventas=$('#total_dinero').text();
	var fecha_movimiento=$("#fecha").val();
	var fecha_movimiento2=$("#fecha2").val();

	if (numero_doc==undefined || numero_doc==''){
		numero_doc=0;
	}
	var verificaempleado;
	var verifica=[];
	$("#inventable>tbody tr ").each(function (index) {
		if (index>=0){
			//verificaempleado=false;
			var campo0,campo1, campo2, campo3, campo4, campo5, campo6;
			$(this).children("td").each(function (index2) {
				switch (index2){
					case 0:
					campo0 = $(this).text();
					if (campo0==undefined){
						campo0='';
					}
					break;
					case 1:
					campo1 = $(this).text();
					elem1 = campo1.split('(');
					descripcion=elem1[0];
					var tipoprodserv1 = elem1[1];
					var ln= tipoprodserv1.length-1;
					tipoprodserv = tipoprodserv1 .substring(0,ln);

					break;
					case 2:
					campo2 = $(this).text();
					break;
					case 4:
					campo3= $(this).find("#precio_venta").val();
					if (isNaN(campo3)==false){
						precio_venta=parseFloat(campo3);
					}
					break;
					case 5:
					campo4= $(this).find("#cant").val();
					if (isNaN(campo4)==false){
						cantidad=parseFloat(campo4);
					}
					break;
					case 6:
					campo5 = $(this).text();

				}


			});

			if(campo0!=""|| campo0==undefined || isNaN(campo0)==false ){
				//StringDatos+=campo0+"|"+tipoprodserv+"|"+precio_venta+"|"+cantidad+"|"+id_empleado+"|"+verificaempleado+"#";
				StringDatos+=campo0+"|"+descripcion+"|"+tipoprodserv+"|"+precio_venta+"|"+cantidad+"|"+id_empleado+"#";
				verifica.push(verificaempleado);
				i=i+1;
			}
		}

	});
	verifica.forEach(function (item, index, array) {
		if (item=='verificar'){
			verificaempleado='verificar';
		}
	});
	var id=$("select#tipo_entrada option:selected").val(); //get the value
	if (id=='1'){
		var dataString='process=print2'+'&stringdatos='+StringDatos+'&cuantos='+i+'&id='+id+'&numero_doc='+numero_doc+'&fecha_movimiento='+fecha_movimiento+'&id_cliente='+id_cliente;
		dataString+='&total_ventas='+total_ventas+'&verificaempleado='+verificaempleado;
	}
	if (id=='2'){
		var dataString='process=print2'+'&stringdatos='+StringDatos+'&cuantos='+i+'&id='+id+'&numero_doc='+numero_doc2+'&fecha_movimiento='+fecha_movimiento2+'&id_cliente='+id_cliente;
		dataString+='&total_ventas='+total_ventas+'&verificaempleado='+verificaempleado;
	}
	if (verificaempleado=='noverificar'){
		$.ajax({
			type:'POST',
			url:'editar_factura.php',
			data: dataString,
			dataType: 'json',
			success: function(datos){
				sist_ope=datos.sist_ope;
				//esta opcion es para generar recibo en  printer local y validar si es win o linux
				if (sist_ope=='win'){
					$.post("http://localhost:8080/variedades/printpos2.php",{datosventa:datos.facturar})
				}
				else {
					$.post("http://localhost/variedades/printpos2.php",{datosventa:datos.facturar})
				}
			}
		});
	}
	else{
		var typeinfo='Warning';
		var msg='Falta seleccionar Empleado que brinda algun servicio en Factura !';
		display_notify(typeinfo,msg);
	}

}
function imprimev(){
	var numero_doc = $("#numdoc").val();
	var print = 'imprimir_fact';
	var tipo_impresion = $("#tipo_impresion").val();
	var con_pago1 = $("#con_pago").val();
	var monto = $("#tot_fdo").val();
	var id_apertura =$('#id_apertura').val();
	var turno =$('#turno').val();
	var caja =$('#caja').val();
	var id_factura=$("#id_factura").val();
	if (tipo_impresion=="TIK" || tipo_impresion=="COB"){
		numero_factura_consumidor='';
	}
	else{
		var numero_factura_consumidor = $("#numdoc").val();
	}
	var dataString = 'process=' + print +'&con_pago1='+con_pago1+'&monto='+monto+'&turno=' + turno+ '&id_apertura=' + id_apertura+ '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion+'&id_cobro=' + id_factura+'&numero_factura_consumidor='+numero_factura_consumidor;

	if (tipo_impresion=="CCF" ||tipo_impresion=="COF"){
		nit=$('#nitcli').val();
		nrc=$('#nrccli').val();
		nombreape=$('#nomcli').val();
		dataString +='&nit=' + nit+ '&nrc=' + nrc+'&nombreape=' + nombreape;
	}
	if (con_pago1=="CHE"){
		numche=$('#numche').val();
		emisor=$('#emisor').val();
		dataString +='&numche=' + numche+ '&emisor=' + emisor;
	}
	if (con_pago1=="TRA"){
		banco=$('#banco').val();
		numcuenta=$('#numcuenta').val();
		numtrans=$('#numtrans').val();
		dataString +='&banco=' + banco+ '&numcuenta=' + numcuenta+ '&numtrans=' + numtrans;
	}
	if (con_pago1=="TAR"){
		numtarj=$('#numtarj').val();
		emisor=$('#emisor').val();
		voucher=$('#voucher').val();
		dataString +='&numtarj=' + numtarj+ '&emisor=' + emisor+ '&voucher=' + voucher;
	}
	$.ajax({
		type: 'POST',
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datos) {
			var sist_ope = datos.sist_ope;
			var dir_print=datos.dir_print;
			var shared_printer_win=datos.shared_printer_win;
			var shared_printer_pos=datos.shared_printer_pos;
			var headers=datos.headers;
			var footers=datos.footers;
			var efectivo_fin = parseFloat($('#efectivo').val());
			var cambio_fin = parseFloat($('#cambio').val());

			//esta opcion es para generar recibo en  printer local y validar si es win o linux
			if (tipo_impresion == 'COF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printfactwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printfact1_lab.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success')
						{
							//alert("No Se envio la impresión " + data);
						}
					});
				}
			}
			if (tipo_impresion == 'ENV') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printenvwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printenv1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			if (tipo_impresion == 'TIK' ||tipo_impresion=='COB') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printposwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_pos:shared_printer_pos,
						headers:headers,
						footers:footers,
					})
				} else {
					$.post("http://"+dir_print+"printpos1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						headers:headers,
						footers:footers,
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			if (tipo_impresion == 'CCF') {
				if (sist_ope == 'win') {
					$.post("http://"+dir_print+"printcfwin1.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin,
						shared_printer_win:shared_printer_win
					})
				} else {
					$.post("http://"+dir_print+"printcf1_lab.php", {
						datosventa: datos.facturar,
						efectivo: efectivo_fin,
						cambio: cambio_fin
					}, function(data, status) {

						if (status != 'success') {
							//alert("No Se envio la impresión " + data);
						}

					});
				}
			}
			setInterval("reload1();", 800);

		}
	});
}


function reload1(){
	location.href = 'venta.php';
}


/*
$(document).on("click", "#btnAddClient", function(event) {
agregarcliente();
});
function agregarcliente() {
urlprocess=$('#urlprocess').val();
var nombress = $(".modal-body #nombress").val();
var duii = $(".modal-body #duii").val();
var tel1 = $(".modal-body #tel1").val();
var tel2 = $(".modal-body #tel2").val();
var dataString = 'process=agregar_cliente' + '&nombress=' + nombress;
dataString += '&dui=' + duii + '&tel1=' + tel1 + '&tel2=' + tel2;
$.ajax({
type: "POST",
url: urlprocess,
data: dataString,
dataType: 'json',
success: function(datax) {
var process = datax.process;
var id_client = datax.id_client;
// Agragar datos a select2
//var nombreape = nombress + " " + apellidoss;
$("#id_cliente").append("<option value='" + id_client + "' selected>" + nombress + "</option>");
$("#id_cliente").trigger('change');

//Cerrar Modal
$('#clienteModal').modal('hide');
//Agregar NRC y NIT al form de Credito Fiscal
display_notify(datax.typeinfo, datax.msg);
$(document).on('hidden.bs.modal', function(e) {
var target = $(e.target);
target.removeData('bs.modal').find(".modal-content").html('');
});
}
});
}
$(document).on("click", "#btnEsc2", function (event) {
$('#clienteModal').modal('hide');
//reload1();
});
*/
$(document).on('change', '#tipo_impresion', function(event) {
	$('#inventable tr').each(function(index) {
		var tr = $(this);
		actualiza_subtotal(tr);
	});
});


function addProductList(id_prod, tipo, descr) {
	$('#inventable').find('tr#filainicial').remove();
	id_prod = $.trim(id_prod);
	id_factura= parseInt($('#id_factura').val());

	if(isNaN(id_factura))
	{
		id_factura=0;
	}
	//	var fila=1;
	urlprocess = $('#urlprocess').val();
	var dataString = 'process=consultar_stock'+'&id_producto=' + id_prod+ '&tipo=' + tipo;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(data)
		{
			var id_previo = new Array();
			if(tipo == "P")
			{
				var precio_p = data.precio_p;
				var cortesia_p = data.cortesia_p;
				tr_add = '';
				var fila=1;
				var filas = 1;
				$("#inventable  tr").each(function(index) {
					if (index >= 0) {
						var campo0 = "";
						$(this).children("td").each(function(index2) {
							switch (index2) {
								case 0:
								campo0 = $(this).text();
								if (campo0 != undefined || campo0 != '') {
									id_previo.push(campo0);
								}
								break;
							}
						});
						if(campo0 !="")
						{
							filas = filas + 1;
						}
					} //if index>0
				});
				tr_add += "<tr class='row100 head "+tipo+"' id='" + id_prod + "'>";
				tr_add += "<td class='cell100 column20 text-success id_pps'>" +filas + "<input type='hidden'  class='txt_box decimal2  cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";
				tr_add += "<td class='cell100 column50 text-success descp' id='desc'>" +descr + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";
				tr_add += "<td class='cell100 column13 text-success' id='precio_venta'><input type='hidden'  class='form-control decimal' id='precio_venta' name='precio_venta' value='"+precio_p+"'>"+precio_p+"</td>";
				tr_add += "<td class='cell100 column9 text-success'>"+"<input type='hidden'  id='cortesia' name='cortesia' value='0'>"+"<input type='hidden'  id='idco' name='idco' value='"+filas+"'>"+cortesia_p+"</td>";
				tr_add += "<td class='ccell100 column7'><input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+precio_p+"'>" + "<input type='hidden'  class='decimal txt_box' id='subtotal_mostrar' name='subtotal_mostrar'  value='"+precio_p+"'style='width:55px;'readOnly></td>";
				tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
				tr_add += '</tr>';
				if(!id_existente(id_prod, tipo))
				{
					$("#inventable").append(tr_add);

				}
			}
			var descripcionps= data.descripcionp;
			var cortesias = data.cortesia;
			var select2s = data.select2;
			var cuantos=data.cuantos;
			var id_prods=data.id_prods;
			var descrip = descripcionps.split("|");
			var cortes = cortesias.split("|");
			var selec = select2s.split("|");
			var idp = id_prods.split("|");
			for(jk=0; jk<cuantos; jk++)
			{
				tr_add = '';
				var fila=1;
				var filas = 1;
				var descripcionp = descrip[jk];
				var cortesia = cortes[jk];
				var select2 = selec[jk];
				var id_prodd = idp[jk];
				if(tipo == "E")
				{
					$("#inventable  tr").each(function(index) {
						if (index >= 0) {
							var campo0 = "";
							$(this).children("td").each(function(index2) {
								switch (index2) {
									case 0:
									campo0 = $(this).text();
									if (campo0 != undefined || campo0 != '') {
										id_previo.push(campo0);
									}
									break;
								}
							});
							if(campo0 !="")
							{
								filas = filas + 1;
							}
						} //if index>0
					});
				}
				var pert = "";
				if(tipo == "P")
				{
					pert = "EP P"+id_prod;
					filas = "";
				}
				tr_add += "<tr class='row100 head E "+pert+"' id='"+id_prodd+"'>";
				tr_add += "<td class='cell100 column20 text-success id_pps'>"+filas+"<input type='hidden'  class='txt_box' id='cuanto' name='cuanto' value='"+cuantos+"'><input type='hidden'  class='txt_box decimal2 ' cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";
				tr_add += "<td class='cell100 column50 text-success descp' id='desc'>" +descripcionp + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prodd + "'></td>";
				tr_add += "<td class='cell100 column13 text-success' id='precio_venta'><input type='hidden'  class='form-control decimal' id='precio_venta' name='precio_venta' value='"+select2+"'>"+select2+"</td>";
				tr_add += "<td class='cell100 column9 text-success'>"+"<input type='hidden'  id='cortesia' name='cortesia' vatr_addlue='0'>"+"<input type='hidden'  id='idco' name='idco' value='"+filas+"'>"+cortesia+"</td>";
				tr_add += "<td class='ccell100 column7'><input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+selec+"'>" + "<input type='hidden'  class='decimal txt_box' id='subtotal_mostrar' name='subtotal_mostrar'  value='"+selec+"'style='width:55px;'readOnly></td>";
				if(tipo == "E")
				{
					tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
				}
				else
				{
						tr_add += '<td class="cell100 column8"></td>';
				}
				tr_add += '</tr>';
				if(!id_existente(id_prodd, 'E'))
				{
					$("#inventable").append(tr_add);
				}
				if(tipo == "E")
				{
					filas++;
				}
			}
			scrolltable();
			totales();

		}
	});

}
/*$(document).on('keyup', '.cant', function(evt){
var tr = $(this).parents("tr");
if(evt.keyCode == 13)
{
tr.find('.sel').select2("open");
}
});*/
/*$(document).on('select2:close', '.sel', function()
{
var tr = $(this).parents("tr");
if(evt.keyCode == 13)
{
tr.find('.sel1').select2("open");
}
});*/
/*$(document).on('keydown','.fecha', function (evt)
{
if (evt.keyCode == 8 || evt.keyCode == 9 || evt.keyCode == 13 || evt.keyCode == 37 || evt.keyCode == 39)
{

}
else
{
if((evt.keyCode>47 && evt.keyCode<60 ) || (evt.keyCode>95 && evt.keyCode<106 ))
{
inputval = $(this).val();
var string = inputval.replace(/[^0-8]/g, "");
var bloc1 = string.substring(0,2);
var bloc2 = string.substring(2,4);
var bloc3 = string.substring(4,7);
if(bloc2>12){
bloc2=12;
}
if(bloc1>31){
bloc1=31;
}
var string =bloc1 + "-" + bloc2+ "-" + bloc3;
$(this).val(string);
}
else
{
evt.preventDefault();
}

}
});*/

$(document).on('select2:close', '.sel2', function(evt)
{
	$("#producto_buscar").focus();

});
/*
$(document).on('select2:close', '.sel1', function()
{
var tr =$(this).parents("tr");
//tr.find(".fecha").focus();

tr.find(".sel2").select2('open');
});
$(document).on('change', '.fecha', function(e)
{
var tr = $(this).parents("tr");
$(this).datepicker('hide');
tr.find(".hora").focus();
});
$(document).on('change', '.hora', function(e)
{
var tr = $(this).parents("tr");
if(e.keyCode == 13)
{
tr.find(".sel2").select2('open');
}
});*/

$(document).on("click", "#btnAddDoctor", function(event) {
	$(document).ready(function(){
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
				especialidad:
				{
					required: true,
				},
			},
			messages:
			{
				nombre: "Por favor ingrese el nombre del doctor",
				apellido: "Por favor ingrese el apellido del doctor",
				especialidad: "Por favor ingrese la especialidad del doctor"
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {
				agregardoctor();

			}
		});

	});
	$(".may").keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});

});
$(document).on("click", "#btnAddProcedencia", function(event) {
	$(document).ready(function(){
		$('#formulario').validate({
			rules: {
				nombre:
				{
					required: true,
				},
			},
			messages:
			{
				nombre: "Por favor ingrese el nombre del Procedencia",
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {
				agregar_procedencia();

			}
		});

	});
	$(".may").keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});

});

$(document).on("click", "#btnAddClient", function(event) {
	$(document).ready(function(){
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
				sexo: "Por favor ingrese el sexo",
				fecha_nacimiento: "Por favor ingrese la fecha de nacimiento",
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {
				agregarcliente();

			}
		});
		$(".may").keyup(function() {
			$(this).val($(this).val().toUpperCase());
		});
	});
});

function agregardoctor() {
	urlprocess = $('#urlprocess').val();
	var nombress = $(".modal-body #nombre").val();
	var apellidos = $(".modal-body #apellido").val();
	var especialidad = $(".modal-body #especialidad").val();
	var dataString = 'process=agregar_doctor' + '&nombre=' + nombress + '&apellido=' + apellidos;
	dataString += '&especialidad=' + especialidad;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax) {
			var process = datax.process;
			var id_doctor = datax.id_doct;
			//var nombreape = nombress + " " + apellidoss;
			$("#doctor").append("<option value='" + id_doctor + "' selected>" + nombress + " " + apellidos + "</option>");
			$("#doctor").trigger('change');
			$('.sel2').select2('open');


			//Cerrar Modal
			$('#doctorModal').modal('hide');
			//Agregar NRC y NIT al form de Credito Fiscal
			display_notify(datax.typeinfo, datax.msg);
			$(document).on('hidden.bs.modal', function(e) {
				var target = $(e.target);
				target.removeData('bs.modal').find(".modal-content").html('');
			});
		}
	});
}
function agregarcliente() {
	urlprocess = $('#urlprocess').val();
	var nombress = $(".modal-body #nombre").val();
	var apellidos = $(".modal-body #apellido").val();
	var sexo = $(".modal-body #sexo").val();
	var naci = $(".modal-body #naci").val();
	var dataString = 'process=agregar_cliente' + '&nombre=' + nombress + '&apellido=' + apellidos;
	dataString += '&sexo=' + sexo +'&naci=' + naci;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax) {
			var process = datax.process;
			var id_client = datax.id_client;
			//var nombreape = nombress + " " + apellidoss;
			$("#paciente").append("<option value='" + id_client + "' selected>" + nombress + " " + apellidos + "</option>");
			$("#paciente").trigger('change');
			$('.sel1').select2('open');


			//Cerrar Modal
			$('#clienteModal').modal('hide');
			//Agregar NRC y NIT al form de Credito Fiscal
			display_notify(datax.typeinfo, datax.msg);
			$(document).on('hidden.bs.modal', function(e) {
				var target = $(e.target);
				target.removeData('bs.modal').find(".modal-content").html('');
			});
		}
	});
}
function agregar_procedencia() {
	urlprocess = $('#urlprocess').val();
	var nombress = $(".modal-body #nombre").val();
	var apellidos = $(".modal-body #descripcion").val();
	var especialidad = $(".modal-body #telefono").val();
	var dataString = 'process=agregar_procedencia'+ '&nombre=' + nombress + '&descripcion=' + apellidos;
	dataString += '&telefono=' + especialidad;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax) {
			var process = datax.process;
			var id_doctor = datax.id_doct;
			//var nombreape = nombress + " " + apellidoss;
			$("#id_procedencia").append("<option value='" + id_doctor + "' selected>" + nombress + "</option>");
			$("#id_procedencia").trigger('change');

			//Cerrar Modal
			$('#procedenciaModal').modal('hide');
			//Agregar NRC y NIT al form de Credito Fiscal
			display_notify(datax.typeinfo, datax.msg);
			$(document).on('hidden.bs.modal', function(e) {
				var target = $(e.target);
				target.removeData('bs.modal').find(".modal-content").html('');
			});
		}
	});
}
function agregarcliente1() {
	urlprocess = $('#urlprocess').val();
	var nombress = $(".modal-body #nombre").val();
	var apellidos = $(".modal-body #apellido").val();
	var sexo = $(".modal-body #sexo").val();
	var dataString = 'process=agregar_cliente1' + '&nombre=' + nombress + '&apellido=' + apellidos;
	dataString += '&sexo=' + sexo ;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(datax) {
			var process = datax.process;
			var id_client = datax.id_client2;
			//var nombreape = nombress + " " + apellidoss;
			$("#id_cliente").append("<option value='" + id_client + "' selected>" + nombress + "</option>");
			$("#id_cliente").trigger('change');


			//Cerrar Modal
			$('#cliente1Modal').modal('hide');
			//Agregar NRC y NIT al form de Credito Fiscal
			display_notify(datax.typeinfo, datax.msg);
			$(document).on('hidden.bs.modal', function(e) {
				var target = $(e.target);
				target.removeData('bs.modal').find(".modal-content").html('');
			});
		}
	});
}
function id_existente(id, tipoa)
{
	var dato =false;
	$("#inventable tr").each(function()
	{
		var tipo = $(this).hasClass(tipoa);
		var id1 = $(this).attr("id");
		//  var id2 = $(this).find("#desc2").val();
		if(id == id1 && tipo)
		{
			dato = true;
			//var a = parseInt($(this).find(".cantidad").val());
			//a=a+1;
			//$(this).find(".cantidad").val(a);
		}
		//console.log(id);
		console.log(id1);
		//console.log(tipo);
		//console.log(tipoa);
	});
	return dato;

}
/*$(document).on('select2:close', '.sel2', function(event) {
var a = $(this).closest('tr');
precio=parseFloat($(this).val());
a.find('#precio_venta').val(precio);
a.find("#precio_sin_iva").val(precio);
actualiza_subtotal(a);
});*/
$("#descto").keyup(function(event) {
	if (event.keyCode == 13) {
		if ($(this).val() != "") {
			aplicar_descuento($(this).val());
		}
	}
});
$(document).on('change','.cort',function(){
	if ($(this).is(':checked') ) {
		$(this).parents("tr").find("#idco").each(function() {
			var tr = $(this).parents("tr");
			precio=0;
			tr.find("#precio_venta").val(precio);
			tr.find("#cortesia").val(1);
			tr.find("#precio_sin_iva").val(precio);
			actualiza_subtotal(tr);
		});
	} else {
		$(this).parents("tr").find("#idco").each(function() {
			var tr = $(this).parents("tr");
			precio=parseFloat(tr.find("#precio_venta").text());
			tr.find("#precio_venta").val(precio);
			tr.find("#cortesia").val(0);
			tr.find("#precio_sin_iva").val(precio);
			actualiza_subtotal(tr);
		});
	}
});
/*$(document).on('keyup', '#precio_venta', function(event) {
		var a = $(this).closest('tr');
	  precio=parseFloat($(this).text());
	//cortesia=a.find("#cortesia").val();
	/*if(cortesia==0){
		a.find('#precio_venta').text(precio);
		a.find("#precio_sin_iva").text(precio);
		actualiza_subtotal(a);
	}*/
	//a.find('#precio_venta').text(precio);
/*	console.log(a);
	actualiza_subtotal();
});*/
function aplicar_descuento(hash) {
	$("#id_descuento").val("");
	$("#porcentaje_descuento").val("0");
	$.ajax({
		type: 'POST',
		url: 'venta.php',
		data: 'process=pin&hash=' + hash,
		dataType: 'JSON',
		success: function(datax) {
			$("#descto").val("");
			if (datax.typeinfo == "Ok") {
				$("#porcentaje_descuento").val(datax.porcentaje);
				$("#id_descuento").val(datax.id_descuento);
				totales();
			} else if(datax.typeinfo == "Ap") {
				display_notify("Warning", "El codigo ya fue aplicado");
			} else {
				display_notify("Error", "Codigo no valido");
			}
		}
	});
}
