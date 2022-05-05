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
		if(id_prod!=0){
		 addProductList(id_prod);
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
$("#id_cliente").change(function() {
	$("#tipo_impresion *").remove();
	$("#select2-tipo_impresion-container").text("");
	var ajaxdata = {
		"process": "tipoimpre",
		"id_cliente": $("#id_cliente").val()
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
$(".decimal").numeric({negative:false,decimalPlaces:2});
/*$('#id_cliente').select2({
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
	$('#id_cliente').select2({
	    allowClear: true,
	    escapeMarkup: function(markup) {
	      return markup;
	    },
	    placeholder: "Buscar Cliente",
	    language: {
	      noResults: function() {
	        var modalcliente = "<a href='ClienteModal.php' data-toggle='modal' data-target='#clienteModal'>";
	        modalcliente += "Agregar Cliente</a>";
	        return modalcliente;
	        $('#id_cliente').select2('close');
	      }
	    }
	  });

		$('#paciente').select2({
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

});

$(document).on("click", ".xa", function(event) {
	$('#doctor').select2('close');
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
  $(this).parents("tr").remove();
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
  var precio_sin_iva = parseFloat(tr.find('#precio_sin_iva').val());
  var tipo_impresion = $('#tipo_impresion').val();
  if (tipo_impresion!='CCF') {
    var cantidad = tr.find('#cant').val();
    if (isNaN(cantidad) || cantidad == "") {
      cantidad = 0;
    }
    var precio = parseFloat(tr.find("#precio_venta_inicial").val());
    var precio_oculto = parseFloat(tr.find("#precio_venta_inicial").val());

    if (isNaN(precio) || precio == "") {
      precio = 0;
    }
    var subtotal = subt(cantidad, precio);
    var subt_mostrar = round(subtotal,2);
    tr.find("#subtotal_fin").val(subt_mostrar);
    tr.find("#subtotal_mostrar").val(subt_mostrar);

		console.log("hola"+subt_mostrar+" can"+cantidad+"pre"+precio);
    totales();
  }
  else {
    var cantidad = tr.find('#cant').val();
    if (isNaN(cantidad) || cantidad == "") {
      cantidad = 0;
    }
    var precio = tr.find('#precio_sin_iva').val();

    if (isNaN(precio) || precio == "") {
      precio = 0;
    }
    var subtotal = subt(cantidad, precio);
    var subt_mostrar = subtotal.toFixed(4);

    tr.find("#subtotal_fin").val(subt_mostrar);
    var subt_mostrar = round(subtotal,2);
    tr.find("#subtotal_mostrar").val(subt_mostrar);
    totales();
  }

}

function totales() {
  //impuestos
  var iva = $('#porc_iva').val();
  var porc_percepcion = $("#porc_percepcion").val();
  var porc_retencion1 = $("#porc_retencion1").val();
  var porc_retencion10 = $("#porc_retencion10").val();

  var id_tipodoc = $("#tipo_impresion option:selected").val();
  var monto_retencion1 = parseFloat($('#monto_retencion1').val());
  var monto_retencion10 = parseFloat($('#monto_retencion10').val());
  var monto_percepcion = $('#monto_percepcion').val();
  var porcentaje_descuento = parseFloat($("#porcentaje_descuento").val());

  var total_sin_iva = 0;
  //fin impuestos

  var tipo_impresion = $('#tipo_impresion').val();

  var urlprocess = $('#urlprocess').val();
  var i = 0, total = 0;
  totalcantidad = 0;

  var total_gravado = 0;

  var total_exento = 0;

  var subt_gravado = 0;

  var subt_exento = 0;

  var subtotal = 0;

  var total_descto = 0;
  var total_sin_descto = 0;
  var subt_descto = 0;
  var total_final = 0;
  var subtotal_sin_iva = 0;
  var StringDatos = '';
  var filas = 0;
  var total_iva = 0;
  if (tipo_impresion=="CCF") {

    $("#inventable tr").each(function() {
      subt_cant = $(this).find("#cant").val();
      ex = parseInt($(this).find('#exento').val());

      if (isNaN(subt_cant) || subt_cant == "") {
        subt_cant = 0;
      }
      subt_gravado=0;
      subt_exento=0;

      if (ex==0) {
        subt_gravado= $(this).find("#subtotal_fin").val();
      }
      else {
        subt_exento=$(this).find("#subtotal_fin").val();
      }

      totalcantidad += parseFloat(subt_cant);

      total_gravado += parseFloat(subt_gravado);

      total_exento += parseFloat(subt_exento);

      subtotal+= parseFloat(subt_exento) + parseFloat(subt_gravado);;

      filas += 1;
    });

    total_gravado = round(total_gravado, 4);
    //descuento
    var total_descuento = 0;
    if (porcentaje_descuento > 0.0) {
      total_descuento = (porcentaje_descuento / 100) * total_final;
    } else {
      total_descuento = 0;
    }
    var total_descuento_mostrar = total_descuento.toFixed(2);
    var total_mostrar = subtotal.toFixed(2);
    totcant_mostrar = totalcantidad.toFixed(2);

   console.log(subt_gravado);
    $('#totcant').text(totcant_mostrar);


    var total_sin_iva_mostrar = total_gravado.toFixed(2);
    $('#total_gravado_sin_iva').html(total_sin_iva_mostrar);
    txt_war = "class='text-danger'"
    $('#total_gravado').html(total_mostrar);
    $('#total_exenta').html(total_exento.toFixed(2));
    var total_iva_mostrar = 0.00;
    total_iva=total_gravado*(parseFloat(iva));
    total_iva=round(total_iva, 2)
    total_gravado_iva=  total_gravado+total_iva;
    total_gravado_iva_mostrar = total_gravado_iva.toFixed(2);
    $('#total_gravado_iva').html(total_gravado_iva_mostrar); //total gravado con iva
    $('#total_iva').html(total_iva.toFixed(2));
    var total_retencion1 = 0
    var total_retencion10 = 0
    var total_percepcion = 0
    if (total_gravado >= monto_retencion1)
      total_retencion1 = total_gravado * porc_retencion1;
    if (total_gravado >= monto_retencion10)
      total_retencion10 = total_gravado * porc_retencion10;
    var total_final = (total_gravado - total_descuento + total_percepcion) - (total_retencion1 + total_retencion10) + total_iva + total_exento;
    total_final_mostrar = total_final.toFixed(2);
    $('#total_percepcion').html(0);
    total_retencion1_mostrar = total_retencion1.toFixed(2);
    total_retencion10_mostrar = total_retencion10.toFixed(2);
    $('#total_retencion').html('0.00');
    if (parseFloat(total_retencion1) > 0.0)
      $('#total_retencion').html(total_retencion1_mostrar);
    if (parseFloat(total_retencion10) > 0.0)
      $('#total_retencion').html(total_retencion10_mostrar);
    //total final
    $('#total_final').html(total_descuento_mostrar);
    $('#totalfactura').val(total_final_mostrar);

    $('#totcant').html(totcant_mostrar);
    $('#items').val(filas);
    $('#totaltexto').load(urlprocess, {
      'process': 'total_texto',
      'total': total_final_mostrar
    });
    $('#monto_pago').html(total_final_mostrar);

    $('#totalfactura').val(total_final_mostrar);

  }
  else
  {
    $("#inventable tr").each(function() {
      subt_cant = $(this).find("#cant").val();
      ex = parseInt($(this).find('#exento').val());

      if (isNaN(subt_cant) || subt_cant == "") {
        subt_cant = 0;
      }
      subt_gravado=0;
      subt_exento=0;

      if (ex==0) {
        subt_gravado= $(this).find("#subtotal_fin").val();
      }
      else {
        subt_exento=$(this).find("#subtotal_fin").val();
      }
      totalcantidad += parseFloat(subt_cant);

      total_gravado += parseFloat(subt_gravado);

      total_exento += parseFloat(subt_exento);

      subtotal+= parseFloat(subt_exento) + parseFloat(subt_gravado);;

      filas += 1;
    });

    total_gravado = round(total_gravado, 4);
    //descuento
    var total_descuento = 0;
    if (porcentaje_descuento > 0.0) {
      total_descuento = (porcentaje_descuento / 100) * total_final
    } else {
      total_descuento = 0;
    }
    var total_descuento_mostrar = total_descuento.toFixed(2)
    var total_mostrar = subtotal.toFixed(2)
    totcant_mostrar = totalcantidad.toFixed(2)

    //console.log(subt_gravado);
    $('#totcant').text(totcant_mostrar);


    var total_sin_iva_mostrar = total_gravado.toFixed(2);
    $('#total_gravado_sin_iva').html(total_sin_iva_mostrar);
    txt_war = "class='text-danger'"


    $('#total_gravado').html(total_mostrar);
    $('#total_exenta').html(total_exento.toFixed(2));

    var total_iva_mostrar = 0.00;

    total_iva=0;
    total_iva=round(total_iva, 2)
    total_gravado_iva=  total_gravado+total_iva;


    total_gravado_iva_mostrar = total_gravado_iva.toFixed(2);
    $('#total_gravado_iva').html(total_gravado_iva_mostrar); //total gravado con iva
    $('#total_iva').html(total_iva.toFixed(2));

    var total_retencion1 = 0
    var total_retencion10 = 0
    var total_percepcion = 0
    if (total_gravado >= monto_retencion1)
      total_retencion1 = total_gravado * porc_retencion1;
    if (total_gravado >= monto_retencion10)
      total_retencion10 = total_gravado * porc_retencion10;
    var total_final = (total_gravado - total_descuento + total_percepcion) - (total_retencion1 + total_retencion10) + total_iva + total_exento;

    total_final_mostrar = total_final.toFixed(2);
    $('#total_percepcion').html(0);
    total_retencion1_mostrar = total_retencion1.toFixed(2);
    total_retencion10_mostrar = total_retencion10.toFixed(2);
    $('#total_retencion').html('0.00');
    if (parseFloat(total_retencion1) > 0.0)
      $('#total_retencion').html(total_retencion1_mostrar);
    if (parseFloat(total_retencion10) > 0.0)
      $('#total_retencion').html(total_retencion10_mostrar);
    //total final
    $('#total_final').html(total_descuento_mostrar);
    $('#totalfactura').val(total_final_mostrar);

    $('#totcant').html(totcant_mostrar);
    $('#items').val(filas);
    $('#totaltexto').load(urlprocess, {
      'process': 'total_texto',
      'total': total_final_mostrar
    });
    $('#monto_pago').html(total_final_mostrar);

    $('#totalfactura').val(total_final_mostrar);
  }

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
	 	var facturado= totalfinal.toFixed(2);
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
  var id_exa = $("#id_prod").val();
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
  var id_vendedor = $("#vendedor").val();
	var id_apertura =$('#id_apertura').val();
	var turno =$('#turno').val();
	var caja =$('#caja').val();
	var credito=$('#tipo_pago').val();

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
  $("#inventable tr").each(function(index) {
      var id_detalle = $(this).attr("id_detalle");
      if(id_detalle == undefined)
      {
        id_detalle = "";
      }
      var id = $(this).find("td:eq(0)").text();
      var id_paciente = $(this).find('.sel').val();
      var precio_venta = $(this).find("#precio_venta").text();
      var cantidad = $(this).find("#cant").val();
      var unidades = $(this).find("#unidades").val();
      var descripcion = $(this).find("#desc").text();
      var exento = $(this).find("#exento").val();
      var subtotal = $(this).find("#subtotal_fin").val();
			var fecha_re =$(this).find(".fecha").val();
			var hora_re =$(this).find('.hora').val();
      if (cantidad && precio_venta) {
        var obj = new Object();
        obj.id_detalle = id_detalle;
        obj.id = id_exa;
				obj.descripcion=descripcion;
        obj.precio = precio_venta;
        obj.cantidad = cantidad;
        obj.unidades = unidades;
        obj.subtotal = subtotal;
        obj.fecha = fecha_re;
        obj.hora = hora_re;
        obj.id_paciente = id_paciente;
        obj.exento = exento;
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
	dataString += '&turno=' + turno;
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


  var sel_vendedor = 1;
	if (credito == "") {
    msg = 'No a seleccionado un tipo de pago!';
    sel_vendedor = 0;
  }

  if (id_cliente == "") {
    msg = 'No hay un Cliente!';
    sel_vendedor = 0;
  }

  if (tipo_impresion == "") {
    msg = 'No hay un tipo de impresion seleccionada!';
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
 					 	$("#efectivov").focus();
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
			imprimev();
			display_notify("Success", "Factura realizada con exito!");
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
/*
function activa_modal(numfact,numdoc,id_cliente){
	urlprocess=$('#urlprocess').val();
	$('#num_doc_fact').numeric({negative:false,decimal:false});
	$('#viewModal').modal({backdrop: 'static',keyboard: false});
	var totalfinal=parseFloat($('#total_dinero').text());
	var tipo_impresion=$('#tipo_impresion').val();
	if (tipo_impresion=="TIK"){
		$('#fact_cf').hide();
	}
	else{
		$('#fact_cf').show();
	}
	if (tipo_impresion=="CCF"){
		$('#ccf').show();

		//para traer datos de cliente si existe
		var id_client = $('#id_cliente').val();
		var dataString = 'process=mostrar_datos_cliente' + '&id_client=' + id_client;
		$.ajax({
			type: 'POST',
			url: urlprocess,
			data: dataString,
			dataType: 'json',
			success: function(data) {
				nit = data.nit;
				registro = data.registro;
				nombreape= data.nombreape;
				$('#nit').val(nit);
				$('#nrc').val(registro);
				$('#nombreape').val(nombreape);
			}
		});

	}
	else{
		$('#ccf').hide();
	}
	var facturado= parseFloat($('#totalfactura').val()).toFixed(2);
  $(".modal-body #facturado").val(facturado);

  $(".modal-body #fact_num").html(numdoc);
	$(".modal-body #efectivo").focus();
}
*/
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

  var id_factura=$("#id_factura").val();
	if (tipo_impresion=="TIK"){
			numero_factura_consumidor='';
	}
	else{
		var numero_factura_consumidor = $("#numdoc").val();
	}
	var dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&tipo_impresion=' + tipo_impresion + '&id_cobro=' + id_factura+'&numero_factura_consumidor='+numero_factura_consumidor;

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
	  });*/
	//	location.reload();
    }
  });
}


 function reload1(){
	location.href = 'venta.php';
}



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

$(document).on('change', '#tipo_impresion', function(event) {
  $('#inventable tr').each(function(index) {
    var tr = $(this);
    actualiza_subtotal(tr);
  });
});


function addProductList(id_prod) {
  $('#inventable').find('tr#filainicial').remove();
  id_prod = $.trim(id_prod);
	id_factura= parseInt($('#id_factura').val());

	if(isNaN(id_factura))
	{
		id_factura=0;
	}
//	var fila=1;
  urlprocess = $('#urlprocess').val();
  var dataString = 'process=consultar_stock' + '&id_producto=' + id_prod+ '&id_factura=' + id_factura;
  $.ajax({
    type: "POST",
    url: urlprocess,
    data: dataString,
    dataType: 'json',
    success: function(data) {
      var precio_venta = data.precio_venta;
      var unidades = data.unidades;
      var existencias = data.stock;
      var perecedero = data.perecedero;
      var descrip_only = data.descripcion;
      var fecha_fin_oferta = data.fecha_fin_oferta;
      var exento = data.exento;
			var categoria=data.categoria;
			var select_rank=data.select_rank;
      var preciop_s_iva = parseFloat(data.preciop/1.13);
			var tipo_impresion=$('#tipo_impresion').val();
      //var filas = parseInt($("#filas").val());

			var fila=1;
			var filas = 1;
      var id_previo = new Array();
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
          filas = filas + 1;
        } //if index>0
      });
			var subtotal = subt(data.preciop, 1);
			subt_mostrar = subtotal.toFixed(2);
			console.log(subtotal);
      var cantidades = "<td  hidden class='cell100 column8 text-success'><div class='col-xs-2'><input type='hidden'  class='txt_box decimal2 ' cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></div></td>";
			var exento ="<input type='hidden' id='exento' name='exento' value='"+exento+"'>";
		 //<input type='text'  class='form-control decimal' readOnly id='precio_venta' name='precio_venta' value='" + data.preciop + "'>*/
			tr_add = '';
      tr_add += "<tr  class='row100 head' id='" + filas + "'>";
			tr_add += "<td  class='cell100 column5 text-success id_pps'>" +filas + "</td>";
			tr_add += "<td class='cell100 column20 text-success descp' id='desc'>" + data.descripcionp + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";
			tr_add += "<td class='cell100 column20 text-success preccs'>" + data.select + "</td>";
			tr_add += "<td class='cell100 column16 text-success precs'>" + data.select1 + "</td>";
			tr_add += "<td class='cell100 column12 text-success dia'>" + data.fecha + "</td>";
			tr_add += "<td class='cell100 column14 text-success hor'> " + data.horas + "</td>";
			tr_add += "<td class='cell100 column6 text-success' id='precio_venta'><input type='hidden'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" +data.preciop + "'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + data.preciop + "'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva+ "'>" + data.preciop + "</td>";
			tr_add += cantidades;
		//	tr_add += "<td hidden class='cell100 column10 text-success'></td>";

			if(tipo_impresion=="CCF")
      {
        tr_add += "<td   class='ccell100 column7'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+preciop_s_iva+"'>" + "<input type='hidden'  class='decimal form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + preciop_s_iva.toFixed(2) + "'style='width:55px;'readOnly></td>";

      }
      else
      {
        tr_add += "<td  class='ccell100 column7'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+subtotal+"'>" + "<input type='hidden'  class='decimal txt_box' id='subtotal_mostrar' name='subtotal_mostrar'  value='" +subt_mostrar + "'style='width:55px;'readOnly></td>";

      }
			tr_add += '<td class="cell100 column7 Delete text-center"><input id="delprod" type="button" class="btn btn-danger fa"  value="&#xf1f8;"></td>';
      tr_add += '</tr>';
			$("#inventable").append(tr_add);
      $(".decimal2").numeric({negative:false,decimal:false});
			$(".86").numeric({negative:false,decimalPlaces:4});
      $('#items').val(filas);
			$(".sel").select2();
			$(".sel1").select2({
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
			$(".datepicker").datepicker({
				format: 'd/m/yy',
				//startDate: '-3d'
			});
			$(".datetime").datetimepicker({
				   format: 'LT'
			});
			$(".sel_r").select2();
			$('#inventable #'+filas).find(".sel").select2("open");
			//numero de filas
      filas++;
		totales();
    scrolltable();
    }
  });
  totales();
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

$(document).on('select2:close', '.sel', function(evt)
{
	var tr =$(this).parents("tr");

		tr.find('.sel1').select2('open');


});
$(document).on('select2:close', '.sel1', function()
{
		var tr =$(this).parents("tr");
		tr.find(".fecha").focus();

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
	$(this).datetimepicker('hide');
	tr.find("#producto_buscar").focus();
});


/*
$(document).on('select2:close', '.sel', function(event)
{
	var tr = $(this).parents("tr");
	//var cantid = tr.find("#cant").val();
  //var id_presentacion = $(this).val();
  //var a = $(this);
  //console.log(id_presentacion);
  /*$.ajax({
    url: 'preventa.php',
    type: 'POST',
    dataType: 'json',
    data: 'process=getpresentacion'+"&id_presentacion="+id_presentacion+"&cant="+cantid,
    success: function(data) {
      a.closest('tr').find('.descp').html(data.descripcion);
      a.closest('tr').find('#precio_venta').val(data.precio);
      a.closest('tr').find('#unidades').val(data.unidad);
      a.closest('tr').find('#precio_sin_iva').val(data.preciop_s_iva);
			a.closest('tr').find(".rank_s").html(data.select_rank);
      fila = a.closest('tr');
      id_producto = fila.find('.id_pps').text();
      existencia = parseFloat(fila.find('#cant_stock').text());
			existencia=round(existencia,4);
      a_cant=parseFloat(fila.find('#cant').val());
      unidad= parseInt(fila.find('#unidades').val());
			a_cant=parseFloat(a_cant*data.unidad);
      a_cant=round(a_cant, 4);
			$(".sel_r").select2();
			a.closest('tr').find('.sel_r').select2("open");
      //console.log(a_cant);
      //console.log(id_producto);
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
      //console.log(existencia);
      //console.log(a_asignar);

      if(a_asignar>existencia)
      {
          val = existencia-(a_asignar-a_cant);
          val = val/unidad;
          val=Math.trunc(val);
          val =parseInt(val);
          fila.find('#cant').val(val);
      }

      var tr = a.closest('tr');
      actualiza_subtotal(tr);
    }
  });
  setTimeout(function() {
    totales();
  }, 300);


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
      $("#id_cliente").append("<option value='" + id_client + "' selected>" + nombress + " " + apellidos + "</option>");
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
/*$(document).on('change', '.sel_r', function(event) {
	var a = $(this).closest('tr');
	precio=parseFloat($(this).val());
	a.find('#precio_venta').val(precio);
	a.find("#precio_sin_iva").val(precio/1.13);
	actualiza_subtotal(a);
});
*/
