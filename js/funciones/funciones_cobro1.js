$(document).ready(function() {
  $(".select").select2();
//  $("#doctor").select2();
  //$("#con_pag").select2();
  //$("#tipo_doc").select2();

  /*$(".datapicker").datepicker({
    format: 'yyyy-mm-dd',
    language:'es',
  });*/
  $('#formulario').validate({
    rules: {
      paciente:
      {
        required: true,
      },
      fecha:
      {
        required: true,
      },
      tipo_doc:
      {
        required: true,
      },
      con_pag:
      {
        required: true,
      },
    },
    messages:
    {
      paciente: "Por favor ingrese el nombre de paciente",
      fecha: "Por favor ingrese la direccion de empleado",
      tipo_doc: "Por favor ingrese tipo de documento",
      con_pag: "Por favor ingrese condicion de cobro",
    },
    submitHandler: function (form)
    {
      var tot = parseFloat($("#tot").text());
      if(tot>0)
      {
        senddata();
      }
      else{
        display_notify("Error", "Aun no hay detalles agregados a esta factura, o no estan completos");
      }
    }


  });
  $(function ()
  {

    /*$(document).on("click", "#Cerrar", function(event) {
      reload1();
    });*/
    $(document).on("click", "#btnPrint", function(event) {
        imprimev();
    });
    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
      var target = $(e.target);
      target.removeData('bs.modal').find(".modal-content").html('');
    });

  });


  //$("#exam").typeahead({
  /*  source: function(query, process) {
      $.ajax({
        url: 'detalle_auto.php',
        type: 'POST',
        data: 'query=' + query ,
        dataType: 'JSON',
        async: true,
        success: function(data) {
          process(data);
        }
      });
    },*/
  /*  updater: function(selection){
      var prod0=selection;
      var prod= prod0.split("|");
      var id_prod = prod[0];
      var descrip = prod[1];
      //var descrip1 = prod[2];
      var doctor=$("#doctor").val().split(",");
      var doctor1=doctor[1]+" "+doctor[2];
      var paciente=$("#paciente").val().split(",");
      var paciente1=paciente[1]+" "+paciente[2];
      var precio=parseFloat(prod[2]);
      //var paciente =$("#paciente").val();
      var descu1=0;
      var cantidad=1;
    //  console.log(descu1+" - "+cantidad1);
      //if(!id_existente(id_prod))
      //{
        var fila = "<tr style='' class='desc' value="+id_prod+"><td   ><input type='hidden' id='desc' value='"+id_prod+"'>"+id_prod+"</td>";
        fila += "<td><label class='desc1'>"+descrip+ "</label><input type='hidden' class=' cantidad form-control text-center ' value="+cantidad+"></td>";
        fila += "<td><label class='id_paciente'>"+paciente1+ "</label></td>";
        fila += "<td><label class='id_doctor'>"+doctor1+ "</label></td>";
        fila += "<td class='text-center'><input type='text' class='form-control text-center precio' value="+precio+"></td>";
        fila += "<td class='text-center'> <input type='text' class=' form-control descuento' value="+descu1+"></td>";
        fila += "<td class='text-center'> <input readonly type='text' class='des form-control'></td>";
        fila += "<td class='text-center'> <input readonly type='text' class='subt form-control'></td>";
        fila += "<td><a id='ac' class='ac'><i class=\"fa fa-trash\"></i></a></td></tr>";
        $("#nom").append(fila);
      //}
      /*$(".cantidad").numeric({
        negative:false,
        decimal:false
      });
      $(".descuento").numeric({
        negative:false,
        decimal:false
      });
      $(".precio").numeric({
        negative:false,
      });*/

    //  calculo();
    //}

  //});
  $("#app").click(function(){
    if($("#examen").val()!="")
    {
            if($("#paciete").val()!="")
            {

              /*swal({
                title: "Realizar Factura?",
                text: "Realizar facturacion!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Si, realizar siguiente cobro!",
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: true,
                closeOnCancel: false
              },
              function(isConfirm) {
                if (isConfirm) {*/
                  agregar();
                  //swal("Exito", "Turno iniciado con exito", "error");
                /*} else {
                  swal("Cancelado", "La facturacion a sido cancelada", "error");
                }
              });*/
            }
            else
            {
              display_notify('Warning', 'Debe Selecionar nombre del paciente');
            }
    }
    else
    {
      display_notify('Warning', 'Debe Selecionar descripcion de examen');
    }

  });
})
//$(document).on("click", "#agregar", function()
function agregar()
{
  var doctor=$("#doctor").val().split(",");
  var doctor1=doctor[1]+" "+doctor[2];
  var paciente=$("#paciente").val().split(",");
  var paciente1=paciente[1]+" "+paciente[2];
  var examen=$("#examen").val().split(",");
  var descrip=examen[2];
  var id_prod=examen[0];
  var id_doc=doctor[0];
  var id_exa=paciente[0];
  var precio=parseFloat(examen[1]);
  //var paciente =$("#paciente").val();
  var descu1=0;
  var cantidad=1;
// console.log(id_exa);
  if(!id_existente(id_prod,id_exa))
  {
    var fila = "<tr style='' class='desc' value="+id_prod+"><td   ><input type='hidden' id='desc' value='"+id_prod+"'><input type='hidden' id='doc' value='"+id_doc+"'> <input type='hidden' id='desc2' value='"+id_exa+"'>"+id_prod+"</td>";
    fila += "<td><label class='desc1'>"+descrip+ "</label><input type='hidden' class=' cantidad form-control text-center ' value="+cantidad+"></td>";
    fila += "<td><label class='id_paciente'>"+paciente1+ "</label></td>";
    fila += "<td><label class='id_doctor'>"+doctor1+ "</label></td>";
    fila += "<td class='text-center'><input readonly type='text' class='form-control text-center precio' value="+precio+"></td>";
    fila += "<td class='text-center'> <input type='text' class=' form-control descuento' value="+descu1+"></td>";
    fila += "<td class='text-center'> <input readonly type='text' class='des form-control'></td>";
    fila += "<td class='text-center'> <input readonly type='text' class='subt form-control'></td>";
    fila += "<td><a id='ac' class='ac'><i class=\"fa fa-trash\"></i></a></td></tr>";
    $("#nom").append(fila);
  }
  $(".cantidad").numeric({
    negative:false,
    decimal:false
  });
  $(".descuento").numeric({
    negative:false,
    decimal:false
  });
  $(".precio").numeric({
    negative:false,
  });

  calculo();


}


//)

/*
$(document).on("click", "#submit1", function()
{
senddata();
})*/
//function to round 2 decimal places
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
function senddata(){
  var cuantos=0;
  var data="";
  var fallos =0;
  var paciente=$('#responsable').val();
  var fecha=$('#fecha').val();
  var hora=$('#hora').val();
  var tot = $("#tot").text();
  var tot_sub = $("#tot_sub").text();
  var tot_des1 = $("#tot_des").text();
  var tot_des=tot_des1[1];
  var tot_can = $("#tot_can").text()
  var num_dia=$("#dias").val();
  var num_doc=$("#n_doc").val();
  var con_pago=$("#con_pag").val();
  var tipo_doc=$("#tipo_doc").val();

  var total = tot[1];
  var process=$('#process').val();

  if(process=='insert'){
    var id_paciente=0;
    var urlprocess='agregar_cobro.php';
  }

  $("#nom tr").each(function()
  {
    var cantidad = parseFloat($(this).find(".cantidad").val());
    var precio = parseFloat($(this).find(".precio").val());
    var subtt = parseFloat($(this).find(".subt").val());
    var descuento = parseFloat($(this).find(".descuento").val());
    var val_des= parseFloat($(this).find(".des").val());
    var descripcion = $(this).find(".desc1").text();
    var id_paciente=parseFloat($(this).find("#desc2").val());
    var id_examen=parseFloat($(this).find("#desc").val());
    var id_doctor=parseFloat($(this).find("#doc").val());
    if(isNaN(precio)!=true  && isNaN(cantidad)!=true  && (cantidad > 0))
    {
      data = data+descripcion+","+cantidad+","+precio+","+descuento+","+val_des+","+subtt+","+id_paciente+","+id_examen+","+id_doctor+"|";
      cuantos = cuantos + 1;
    }
  });
  var dataString='process='+process+"&paciente="+paciente+'&data='+data+"&fecha="+fecha+"&total="+tot+"&cuantos="+cuantos+"&num_dia="+num_dia+"&num_doc="+num_doc+"&tipo_doc="+tipo_doc+"&con_pago="+con_pago+"&total_des="+tot_des1+"&hora="+hora;

  if(fallos > 0)
  {

    display_notify("Warning","Por favor verifique que todos los datos del detalle (precio, cantidad) esten completos");
  }
  else
  {
    if (cuantos!=0) {
      $.ajax({
        type:'POST',
        url:urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax){
          process=datax.process;
          //var maxid=datax.max_id;
          display_notify(datax.typeinfo,datax.msg);
          if(datax.typeinfo =="success" || datax.typeinfo=="Success")
          {
           setInterval("reload1();", 1000);
          }
        }
      });

    }


  }

}
//funcion para esconder y aparecer un div
$("#div_con").hide();
$("#con_pag").change(function(){
  var val = $(this).val();
  if(val == 1){
    $("#div_con").show();
  }else if(val == "" || val ==0){
    $("#div_con").hide();
  }
});
//funcion de redirecionamiento si se guarda corretamente
function reload1()
{
  location.href = 'admin_cobro.php';
}

//funcion para remover una fila de una tabla
$(document).on("click", "#ac", function()
{
  var parent = $(this).parents("tr").get(0);
  $(parent).remove();
  calculo();
})

//funcion que identifica cundo el id es identico y suma la cantidad
function id_existente(id,ide)
{
  var dato =false;
  $("#nom tr").each(function()
  {
    var id1 = $(this).find("#desc").val();
    var id2 = $(this).find("#desc2").val();
    if(id == id1 && ide==id2)
    {
      dato = true;
      //var a = parseInt($(this).find(".cantidad").val());
      //a=a+1;
      //$(this).find(".cantidad").val(a);
    }
    console.log(dato);
  });
  return dato;

}

//funcion para validar que el campo total tenga alguna descriocion
function validar_datos()
{


}

//funcion para calculo de total y subtotales
function calculo()
{
  var precio_total =0;
  var descuento_total=0;

  $("#nom").each(function()
  {
    var cantidad_tot=0;
    if($(this).children("tr").length >0)
    {
      $(this).children("tr").each(function()
      {
        var precio_tota =0;
        var descuento_tota=0;
        var cantidad = parseFloat($(this).find(".cantidad").val());
        var precio= parseFloat($(this).find(".precio").val());
        var descuento= parseFloat($(this).find(".descuento").val());
      //  console.log(descuento);
        if((isNaN(cantidad) == false) && (cantidad > 0) && (isNaN(precio) == false) )
        {
          precio_tota= precio * cantidad;
          cantidad_tot+= cantidad;
          descuento_tota=precio_tota*(descuento/100);
          precio_tota -= descuento_tota;
          descuento_total+=descuento_tota;
          precio_total+=precio_tota;

          $(this).find(".subt").val(round(precio_tota,2));
          $(this).find(".des").val(round(descuento_tota,2));
          $("#tot").html(round(precio_total,2));
          $("#tot_can").html(cantidad_tot);
          $("#tot_des").html(round(descuento_total,2));
        }
        else
        {
          $(this).find(".subt").html("");
          $("#tot").html(precio_total);
          $("#tot_can").html("")
        }
      })
    }
    else
    {
      $("#tot").html("0.00");
      $("#tot_can").html("0");
    }
  })
}
//funcion para actualizar en vivo las cantidades, total y subtotal
$( "#nom" ).delegate( ".cantidad", "keyup", function()
{
  calculo();
});
$( "#nom" ).delegate( ".precio", "keyup", function()
{
  calculo();
});

$(document).on("keyup",".descuento", function()
{
  if($(this).val()!="")
  {
    if(parseInt($(this).val())<100)
    {
      calculo();
    }
    else {
      $(this).val(100);
    }
  }
});

function imprimev(){
  var print = 'imprimir_fact';
	var id_factura = $('#id_detalle_cobro').val();

	var   efectivo_fin=0
	var	 cambio_fin=0
	var num_fact_print=  $('#num_fact_print').val();
		var dataString = 'process=imprimir_fact' + '&id_factura=' + id_factura+'&num_fact_print='+num_fact_print;
  $.ajax({
    type: 'POST',
    url: 'ver_detalle_cobro.php',
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
			  //setInterval("reload1();", 500);

    }
  });
}

function ver()
{
	var id_detalle_cobro = $('#id_detalle_cobro').val();
	var dataString = 'process=ver' + '&id_detalle_cobro=' + id_detalle_cobro;
	$.ajax({
		type : "POST",
		url : "admin_cobro.php",
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
