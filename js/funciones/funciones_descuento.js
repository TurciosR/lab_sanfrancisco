var dataTable = "";
$(document).ready(function() {
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
  generar();
});
function generar_pin()
{
$.ajax({
  type : "POST",
  url : "agregar_descuento.php",
  data : "process=pin",
  dataType : 'JSON',
  success : function(datax)
  {
    $("#pin").val(datax.pin);
  }
});
}
function generar() {
  fini = $("#fini").val();
  fin = $("#fin").val();
  dataTable = $('#editable2').DataTable().destroy()
  dataTable = $('#editable2').DataTable({
    "pageLength": 50,
    "order": [
      [1, 'desc'],
      [0, 'desc']
    ],
    "processing": true,
    "serverSide": true,
    "ajax": {
      url: "admin_descuento_dt.php?fini="+fini+"&fin="+fin, // json datasource
      error: function() { // error handling
        $(".editable2-error").html("");
        $("#editable2").append('<tbody class="editable2_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
        $("#editable2_processing").css("display", "none");
        $(".editable2-error").remove();
      }
    },
    "columnDefs": [{
      "targets": 1, //index of column starting from 0
      "render": function(data, type, full, meta) {
        if (data != null)
          return '<p class="text-success"><strong>' + data + '</strong></p>';
        else
          return '';
      }
    }],
    "language": {
      "url": "js/Spanish.json"
    }
  });
  dataTable.ajax.reload()
  //}
}
$(function()
{
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
  $(document).on("click", "#btn_savea", function(event) {
    if($("#porcentaje").val() !="")
    {
      send();
      valida();
    }
    else
    {
        display_notify("Error", "Ingrese un porcentaje");
    }
  });
  $(document).on("click", "#btnMostrar", function(event) {
    generar();
  });
});

function reload1() {
  location.href = 'admin_descuento.php';
}

function send()
{
  var process = $('#process').val();
  var porcentaje = $('#porcentaje').val();
  var pin = $('#pin').val();
  if(process == "insert_d")
  {
    var urlp = "agregar_descuento.php";
    var id_descuento = 0;
  }
  else
  {
    var urlp = "editar_descuento.php";
    var id_descuento = $("#id_descuento").val();
  }
  var dataString = "process="+process+"&porcentaje="+porcentaje+"&pin="+pin+"&id_descuento="+id_descuento;
  $.ajax({
    type: "POST",
    url: urlp,
    data: dataString,
    dataType: 'JSON',
    success: function(datax)
    {
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo == "Success")
      {
        setInterval("reload1();", 1000);
        $('#btn_clos').click();
      }
    }
  });
}
function deleted() {
  var id_descuento = $('#id_descuento').val();
  var dataString='process=deleted'+'&id_descuento='+id_descuento;
  $.ajax({
    type: "POST",
    url: "borrar_descuento.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      if(datax.typeinfo == "Success")
      {
        setInterval("reload1();", 1000);
        $('#btn_clos').click();
      }
    }
  });
}
