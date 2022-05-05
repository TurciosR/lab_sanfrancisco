$(document).ready(function() {
  $('.select').select2();
   $("#producto_buscar").typeahead({
     source: function(query, process) {
       var id_sucursal = $('#id_sucursal').val();
       $.ajax({
         url: 'buscador_producto_inventario.php',
         type: 'POST',
         data: 'query=' + query + "&id_sucursal=" + id_sucursal,
         dataType: 'JSON',
         async: true,
         success: function(data) {
           process(data);
         }
       });
     },
     updater: function(selection) {
       var existe = false;
       var insu0 = selection;
       var insu = insu0.split("|");
       var id_producto = insu[0];
       var nombre = insu[1];
       agregar_descargo(id_producto,nombre);
       totales();

     }
   });

});

function agregar_descargo(id_producto,descripcion)
{
	var existe = false;
    $("table tr").each(function() {
      campo1 = $(this).find(".id_producto").val();
      console.log(campo1);
      if (campo1 == id_producto) {
        var cantidad = $(this).find(".cant").val();

        if (cantidad == "")
        {
          cantidad = 0;
          console.log(cantidad);

        }
        var cant = parseInt(cantidad)+1;
        $(this).find(".cant").val(cant);
        existe = true;
      }
    });
    if (!existe)
		{
			$.ajax({
		    type: "POST",
		    url: 'descargo_inventario.php',
		    data: "process=traerdatos&id_producto="+id_producto +'&descripcion='+descripcion,
		    dataType: 'json',
		    success: function(data)
				{
          var id_producto = data.id_producto;
          var unidadp= data.unidadp;
          var descripcion= data.descripcion;
          var select= data.select;
          var descripcionp= data.descripcionp;
          var costop= data.costop;
          var preciop= data.preciop;
          var existencia= data.existencia;
          var input= data.input;
          var tr ="";
          tr += "<tr>"
          tr +=   "<td class='col-lg-5' class='descripcion'> "+descripcion+" <input type='hidden' class='id_producto' name='' value="+id_producto+"> <input type='hidden' class='unidad' value="+unidadp+"></td>";
          tr +=   "<td class='col-lg-1 text-center'>"+select+"</td>";
          tr +=   "<td class='col-lg-1 text-center descp'>"+descripcionp+"</td>";
          tr +=   "<td class='col-lg-1 text-center precio_compra'>"+costop+"</td>";
          tr +=   "<td class='col-lg-1 text-center precio_venta'>"+preciop+"</td>";
          tr +=   "<td class='col-lg-1 text-center exis'>"+existencia+"</td>";
          tr +=   "<td class='col-lg-1 text-center'>"+input+"</td>";
          tr +=   "<td class='trash text-center'><a ><i class='fa fa-trash'></i></a></td>";
          tr += "</tr>";

					$("#mostrardatos").append(tr);
					$(".sel").select2();
		     }
		  });
    } else {

      //display_notify("Error", "El producto agregado");
    }
  }
//  $("#rangosF").hide();
  $("#tipoD").change(function(){
    var val = $(this).val();
    if(val == "DESCARGA" || val ==""){
      $("#buscador").attr("hidden",false);
      $("#rangosF").attr("hidden",true);
      $("#tipo").val("descargar");
      $("#mostrardatos tr").remove();
    }else if (val=="AJUSTE INVENTARIO"){
      $("#buscador").attr("hidden",true);
      $("#rangosF").attr("hidden",false);
      cargar_descargo1();
    }
  });

    function cargar_descargo1(){

      var hasta =$("#hasta").val();
      urlprocess = "descargo_inventario.php";
      $.ajax({
        type: 'POST',
        url: urlprocess,
        data: {
          process: 'recibir',
          hasta: hasta
        },
        success: function(html)
        {
            $('#mostrardatos').html(html);
            totales();
          }
      });

    }


$(document).on("click", ".trash", function()
  {
    $(this).parents("tr").remove();
    totales();
  });
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });

  /*$(document).on("click", "#btnAceptar", function(event) {
    cargar_descargo();
  });*/
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });

});

// Agregar productos a la lista del inventario
function agregar_producto(id_prod, descrip) {
  var dataString = 'process=consultar_stock' + '&id_producto=' + id_prod;
  $.ajax({
    type: "POST",
    url: 'ingreso_inventario.php',
    data: dataString,
    dataType: 'json',
    success: function(data)
    {
      var cp = data.costop;
      var perecedero = data.perecedero;
      var select = data.select;
      var preciop = data.preciop;
      var unidadp = data.unidadp;
      var descripcionp = data.descripcionp;
      if (perecedero == 1)
      {
        caduca = "<div class='form-group'><input type='text' class='datepicker form-control vence' value=''></div>";
      }
      else
      {
        caduca = "<input type='hidden' class='vence' value='NULL'>";
      }
      var unit = "<input type='hidden' class='unidad' value='" + unidadp + "'>";
      var tr_add = "";
      tr_add += '<tr>';
      tr_add += '<td class="id_p">' + id_prod + '</td>';
      tr_add += '<td>' + descrip + '</td>';
      tr_add += '<td>' + select + '</td>';
      tr_add += '<td class="descp">' + descripcionp + '</td>';
      tr_add += "<td><div class='col-xs-1'>" + unit + "<input type='text'  class='form-control precio_compra' value='" + cp + "' style='width:80px;'></div></td>";
      tr_add += "<td><div class='col-xs-1'><input type='text'  class='form-control precio_venta' value='" + preciop + "' style='width:80px;'></div></td>";
      tr_add += "<td><div class='col-xs-1'><input type='text'  class='form-control cant' style='width:60px;'></div></td>";
      tr_add += "<td class='col-xs-2'>" + caduca + '</td>';
      tr_add += "<td class='Delete text-center'><a href='#'><i class='fa fa-trash'></i></a></td>";
      tr_add += '</tr>';
      if (id_prod != "")
      {
        $("#inventable").prepend(tr_add);
        $(".sel").select2();

        /*que no se vayan letras*/
        $(".precio_compra").numeric(
          {
            negative:false,
            decimalPlaces:2,
          });

        $(".precio_venta").numeric(
          {
            negative:false,
            decimalPlaces:2,
          });

        $(".cant").numeric(
          {
            decimal:false,
            negative:false,
            decimalPlaces:2,
          });
      }
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '1d'
      });
    }
  });
  totales();
}
//Evento que se activa al perder el foco en precio de venta y cantidad:
$(document).on("blur", "#inventable", function() {
  totales();
});
$(document).on("keyup", ".precio_compra, .precio_venta", function() {
  totales();
});

// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", ".Delete", function()
{
  $(this).parents("tr").remove();
  totales();
});
$(document).on("click", ".cheke", function()
{
  var tr = $(this).parents("tr");
  if($(this).is(":checked"))
  {
    tr.find(".cant").attr("readOnly", false);
  }
  else
  {
    tr.find(".cant").attr("readOnly", true);
  }
  totales();
});
//Calcular Totales del grid
function totales()
{
  var subtotal = 0;
  var total = 0;
  var totalcantidad = 0;
  var subcantidad = 0;
  var total_dinero = 0;
  var total_cantidad = 0;
  var faltante=0;
  $("#loadtable>tbody tr").each(function()
  {
      var compra = $(this).find(".precio_compra").text();
      var unidad = $(this).find(".unidad").val();
      var venta = $(this).find(".precio_venta").text();
      var cantidad = parseInt($(this).find(".cant").val());
      var existencia = parseInt($(this).find(".exis").text());
      var cantidad1=0;
      var cantidad2=0;

      if (isNaN(cantidad) == true)
      {
        cantidad = 0;
      }
      if (isNaN(existencia) == true)
      {
        existencia = 0;
      }
      if(cantidad>existencia ){

        cantidad1=cantidad -existencia;
        cantidad2= cantidad-cantidad1;

      }else {
        cantidad2= cantidad;
      }
      console.log(cantidad2+" "+cantidad);


      subtotal = compra * cantidad2;

      totalcantidad += cantidad2;

      if (isNaN(subtotal) == true)
      {
        subtotal = 0;
      }
      total += subtotal;

  });
  if (isNaN(total) == true)
  {
    total = 0;
  }
  total_dinero = round(total,2);
  total_cantidad = round(totalcantidad,2);
  total_dinero = round(total,2);
  total_cantidad = round(totalcantidad,2);



  $('#total_dinero').html("<strong>" + total_dinero + "</strong>");
  $('#totcant').html(total_cantidad);

}
// actualize table
$(document).on("click", "#submit1", function()
{
  senddata();
});

function senddata()
{
  $('#submit1').prop('disabled', true);
  //Calcular los valores a guardar de cada item del inventario
  var i = 0;
  var error  = false;
  var datos = "";
  var origen =$('#origen').val();
  var iden = $("select#tipo option:selected").val(); //get the value

  $("#loadtable>tbody tr").each(function()
  {
      var id_prod = $(this).find(".id_producto").val();
      var id_presentacion = $(this).find(".sel").val();
      var compra = parseFloat($(this).find(".precio_compra").text());
      var unidad = $(this).find(".unidad").val();
      var venta = parseFloat($(this).find(".precio_venta").text());
      var cant = parseInt($(this).find(".cant").val());
      var exis = parseInt($(this).find(".exis").text());
      var cantN=0;
      var canfa=0;

      if(cant>exis){
        canfa=cant-exis;
        cantN=cant-canfa;
      }
      else{
        cantN=cant;
      }
      var vence = "";
      if (cant > 0 && parseInt(cantN)>0){
        datos += id_prod + "|" + compra + "|" + venta + "|" + cantN + "|" + unidad + "|" + vence + "|" + id_presentacion +"|" + canfa+ "#";
        i = i + 1;
      }
      else {
        error = true;
      }

  });

  console.log(error);
  var total = $('#total_dinero').text();
  var concepto = $('#concepto').val();
  var fecha1 = $('#fecha1').val();

  var dataString =
  {
    'process': "insert",
    'datos': datos,
    'cuantos': i,
    'total': total,
    'fecha': fecha1,
    'concepto': concepto,
    'origen': origen,
    'iden': iden
  }
  if (!error&&i>0)
  {
    $.ajax({
      type: 'POST',
      url: "descargo_inventario.php",
      data: dataString,
      dataType: 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if(datax.typeinfo == "Success")
        {
          setInterval("reload1();", 1000);

        }
      }
    });
  }
  else
  {
    display_notify('Warning', 'Falta completar algun valor de cantidad !');
    $('#submit1').prop('disabled', "");
  }
}

$(document).on('keyup', '.cant', function(event){
  fila = $(this).closest('tr');
  id_producto = fila.find('.id_producto').val();
  existencia = parseInt(fila.find('.exis').text());
  a_cant=$(this).val();
  unidad= parseInt(fila.find('.unidad').val());
  a_cant=parseInt(a_cant*unidad);

  console.log(unidad);
  a_asignar=0;

  $('table tr').each(function(index) {

    if($(this).find('.id_producto').val()==id_producto)
    {
      t_cant=parseInt($(this).find('.cant').val());
      if(isNaN(t_cant))
      {
        t_cant=0;
      }
      t_unidad=parseInt($(this).find('.unidad').val());
      if(isNaN(t_unidad))
      {
        t_unidad=0;
      }
      t_cant=parseInt((t_cant*t_unidad));
      a_asignar=a_asignar+t_cant;
      a_asignar=parseInt(a_asignar);
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
      $(this).val(val);
      setTimeout(function() {totales();}, 1000);
  }
  else
  {
    totales();
  }

});

function reload1()
{
  location.href = "descargo_inventario.php";
}
$(document).on('change', '.sel', function(event)
{
  var id_presentacion = $(this).val();
  var a = $(this).parents("tr");
  $.ajax({
    url: 'descargo_inventario.php',
    type: 'POST',
    dataType: 'json',
    data: 'process=getpresentacion' + "&id_presentacion=" + id_presentacion,
    success: function(data)
    {
      a.find('.descp').html(data.descripcion);
      a.find('.precio_venta').text(data.precio);
      a.find('.unidad').val(data.unidad);
      a.find('.precio_compra').text(data.costo);

      fila = a;
      id_producto = fila.find('.id_producto').val();
      existencia = parseInt(fila.find('.exis').text());
      a_cant=parseInt(fila.find('.cant').val());
      unidad= parseInt(fila.find('.unidad').val());

      a_cant=parseInt(a_cant*data.unidad);
      console.log(unidad);

      a_asignar=0;

      $('table tr').each(function(index) {

        if($(this).find('.id_producto').val()==id_producto)
        {
          t_cant=parseInt($(this).find('.cant').val());
          if(isNaN(t_cant))
          {
            t_cant=0;
          }
          t_unidad=parseInt($(this).find('.unidad').val());
          if(isNaN(t_unidad))
          {
            t_unidad=0;
          }
          t_cant=parseInt((t_cant*t_unidad));
          a_asignar=a_asignar+t_cant;
          a_asignar=parseInt(a_asignar);
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
          fila.find('.cant').val(val);
      }

    }
  });
  setTimeout(function() {
    totales();
  }, 1000);
});
function round(value, decimals)
{
  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
/*$("#desde").change(function(event) {
	generar1();
});

$("#hasta").change(function(event) {
	generar1();
});*/
$("#hasta").change(function(event) {
 cargar_descargo1();
 totales();
});
