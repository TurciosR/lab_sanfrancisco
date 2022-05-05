var subtotal = 0;
$(function() {
  $(document).on('click', '.check_can', function(event) {
    //var valor = parseInt($(this).val());
    var tr = $(this).parents("tr");
    var precio = parseFloat(tr.find(".precioss").text());
    if($(this).is(':checked'))
    {
      subtotal+=round(precio, 2);
    }
    else
    {
        subtotal-=round(precio, 2);
    }
    $('#totcant').text(subtotal.toFixed(2));
  });

  $(document).on('click', '#btnGuardar', function(event) {
    if($("#tabla .check_can:checked").length > 0)
    {
      if($("#dui").val()!="")
      {
        senddata();
      }
      else
      {
          display_notify("Error","Por favor ingrese el numero de DUI");
      }
    }
    else
    {
        display_notify("Error","Seleccione por lo menos un item a devolver");
    }
  });
});
function senddata() {
  var total = $("#totcant").text();
  var id_s = "";
  var cuantos = 0;
  var dui = $("#dui").val();
  var comentario = $("#comentario").val();
  var id_cobro = $("#id_cobro").val();
  var doc_ap = $("#doc_ap").text();
  var cliente = $("#clii").text();
  var id_apertura = $("#id_apertura").val();
  var caja = $("#caja").val();
  var turno = $("#turno").val();
  var id_cliente = $("#id_cliente").val();
  var tipo = $('#tipo').val();

  $('#tabla .check_can:checked').each(function()
  {
    var tr = $(this).parents("tr");
    id_s+=tr.attr("id")+","+tr.attr("id_detalle")+","+tr.find(".n_precio").val()+","+tr.find(".precioss").text()+","+tr.find(".nexa").text()+"|";
    cuantos++;
  });
  var dataString = 'process=insert&id_cobro='+id_cobro+'&total='+total+'&id_s='+id_s+'&id_cliente='+id_cliente;
      dataString += '&id_apertura='+id_apertura+'&caja='+caja+'&turno='+turno+'&cuantos='+cuantos+'&doc_ap='+doc_ap;
      dataString += '&dui='+dui+'&comentario='+comentario+'&cliente='+cliente+'&tipo='+tipo;
  $.ajax({
    type: 'POST',
    url: 'devolucion.php',
    data: dataString,
    dataType: 'json',
    success: function(datax)
    {
      display_notify(datax.typeinfo, datax.msg);
      if(datax.typeinfo == "Success")
      {
        factura=datax.id_factura;
        numdoc=datax.numero_doc;

        if(tipo=="CCF")
        {
          activa_modal(factura,numdoc);
        }
        else
        {
          imprime2(factura,numdoc)
          //setInterval("reload1();", 1500);
        }

      }
    }
  });
}

function activa_modal(factura,numdoc){
	urlprocess=$('#urlprocess').val();
	$('#viewModal').modal({backdrop: 'static',keyboard: false});
    $(".modal-body #id_factura_n").val(factura);
    var dev= $('#montodev').text();
     var cant=$('#totcant').text();
     var totalfinal=parseFloat(dev);
     var tipo_impresion="DEV";
     var facturado= totalfinal.toFixed(2);
      $(".modal-body #facturado").val(cant);
      $(".modal-body #fact_num").html(numdoc);
}
$(document).on("click", "#btnEsc", function(event)
{
  var numero = $(".modal-body #numero").val();
  var id_factura = $(".modal-body #id_factura_n").val();

  if(numero!=""&&numero!=null)
  {
    $.ajax({
      url: 'devolucion.php',
      type: 'POST',
      dataType: 'json',
      data: "process=act"+"&id_factura="+id_factura+"&numero="+numero,
      success: function(datax)
      {
        reload1();
      }
    });
  }
  else
  {
    display_notify('Error','Ingrese numero de documento');
  }



});
$(document).on("click", "#btnPrintFact", function(event)
{
  imprime1();
});

function imprime1(){
  var numero_doc = $(".modal-body #fact_num").html();
  var print = 'imprimir_fact';
  var pass = true;
  var numero_factura_imprimir=$(".modal-body #numero").val();
  var tipo_impresion = 'DEV';
  var id_factura=$(".modal-body #id_factura_n").val();

	var dataString = 'process=' + print + '&numero_doc=' + numero_doc + '&num_doc_fact=' + id_factura;
  if(numero_factura_imprimir==""){
    pass = false;
  }
  dataString +='&numero_factura_imprimir='+numero_factura_imprimir
  if(pass){
    imprime2(id_factura,numero_factura_imprimir);
  }
  else{
      display_notify("Error", "Por favor complete los datos de facturacion");
  }
}
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
function reload1()
{
  location.href = "admin_cobro.php";
}

function imprime2(id_fact,numero_doc){
  var print = 'imprimir_fact';
	var id_factura = id_fact;

	var num_fact_print=  numero_doc;
		var dataString = 'process=imprimir_fact' + '&id_factura=' + id_factura+'&num_fact_print='+num_fact_print;
  $.ajax({
    type: 'POST',
    url: 'devolucion.php',
    data: dataString,
    dataType: 'json',
    success: function(datos) {
			var sist_ope = datos.sist_ope;
      var dir_print=datos.dir_print;
      var tipo_impresion= datos.tipo_impresion;
      var shared_printer_win=datos.shared_printer_win;
			var shared_printer_pos=datos.shared_printer_pos;
			var headers=datos.headers;
			var footers=datos.footers;

      //esta opcion es para generar recibo en  printer local y validar si es win o linux
      if (tipo_impresion == 'COF') {
        if (sist_ope == 'win') {
          $.post("http://"+dir_print+"printfactwin1.php", {
						datosventa: datos.facturar,
						efectivo: "",
						cambio: "",
						shared_printer_win:shared_printer_win
          })
        } else {
          $.post("http://"+dir_print+"printfact1.php", {
            datosventa: datos.facturar,
            efectivo: "",
            cambio: ""
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
						efectivo: "",
						cambio: "",
						shared_printer_win:shared_printer_win
          })
        } else {
          $.post("http://"+dir_print+"printenv1.php", {
            datosventa: datos.facturar,
            efectivo: "",
            cambio: ""
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
						efectivo: "",
						cambio: "",
						shared_printer_pos:shared_printer_pos,
						headers:headers,
						footers:footers,
          })
        } else {
          $.post("http://"+dir_print+"printpos1.php", {
            datosventa: datos.facturar,
            efectivo: "",
            cambio: "",
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
						efectivo: "",
						cambio: "",
						shared_printer_win:shared_printer_win
          })
        } else {
          $.post("http://"+dir_print+"printcf1.php", {
            datosventa: datos.facturar,
            efectivo: "",
            cambio: ""
          }, function(data, status) {

            if (status != 'success') {
              //alert("No Se envio la impresión " + data);
            }

          });
        }
      }
			  setInterval("reload1();", 500);

    }
  });
}
