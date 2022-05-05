$(document).ready(function() {
  $('#formulario').validate({
    rules: {
      id_cliente: {
        required: true,
      },
      fecha: {
        required: true,
      },
      dias: {
        required: true,
      },
      monto: {
        required: true,
      },
    },
    highlight: function(element) {
      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
    },
    submitHandler: function(form) {
      senddata();
    }
  });
  $("#cliente").typeahead({
    source: function(query, process) {
      $.ajax({
        type: 'POST',
        url: 'cliente_autocomplete.php',
        data: 'query=' + query,
        dataType: 'JSON',
        async: true,
        success: function(data)
        {
          process(data);
        }
      });
    },
    updater: function(selection) {
      var prod0 = selection;
      var prod = prod0.split("|");
      var id_cliente = prod[0];
      var nombre = prod[1];
      $("#id_cliente").val(id_cliente);
      $("#mcliente").text(nombre);
      // agregar_producto_lista(id_prod, descrip, isbarcode);
    }
  });
  $('.numeric').numeric({
    negative: false,
    decimal: false
  });
  $('.decimal').numeric({
    negative: false,
    decimalPlaces: 4
  });
  $("#submit1").click(function(){
    $("#formulario").submit();
  });
});
function senddata() {
  //var name=$('#name').val();
  var lista = "";
  var cuantos = 0;
  if ($("#append tr").length > 0)
  {
    $("#append tr").each(function() {
    var fecha_a = $(this).find(".fecha_t").text();
    var tipo_doca = $(this).find(".tipo_doct").text();
    var numero_doca = $(this).find(".numero_doct").text();
    var abono = $(this).find(".abono").text();
    lista += fecha_a+","+tipo_doca+","+numero_doca+","+abono+"|";
    cuantos += 1;
  });
  }
    var id_cliente = $('#id_cliente').val();
    var fecha = $('#fecha').val();
    var dias = $('#dias').val();
    var monto = $('#monto').val();
    var tipo_doc = $('#tipo_doc').val();
    var numero_doc = $('#numero_doc').val();
    //Get the value from form if edit or insert
    var process = $('#process').val();

      var urlprocess = 'agregar_credito.php';

    var dataString = 'process=' + process + '&id_cliente=' + id_cliente + '&fecha=' + fecha + '&dias=' + dias;
    dataString += '&monto=' + monto + '&tipo_doc=' + tipo_doc + '&numero_doc=' + numero_doc + '&lista=' + lista;
    dataString += '&cuantos=' + cuantos;

    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success")
        {
          setInterval("location.reload();",1000);
        }
      }
    });
}

$(document).on("click", ".Delete", function(){
	$(this).parents("tr").remove();
});

$(document).on("keyup", "#abono", function(even)
{
  if(even.keyCode == 13)
  {
    var fecha_a= $("#fecha_a").val();
    var tipo_doca = $("#tipo_doca").val();
    var numero_doca = $("#numero_doca").val();
    var abono = $("#abono").val();
    if (fecha_a != "" && abono != "")
    {
        var text_select = $("#id_presentacion option:selected").html();
        var fila = "<tr>";
        fila += "<td class='fecha_t'>" + fecha_a + "</td>";
        fila += "<td class='tipo_doct'>" + tipo_doca + "</td>";
        fila += "<td class='numero_doct'>" + numero_doca + "</td>";
        fila += "<td class='abono'>" + abono + "</td>";
        fila += "<td class='delete text-center'><a class='btn Delete'><i class='fa fa-trash'></i></a></td>";
        $("#append").append(fila);
        $(".clear").val("");
        $("#fecha_a").focus();
    } else
    {
      display_notify("Error", "Por favor complete los campos de fecha y abono");
    }
  }
});
