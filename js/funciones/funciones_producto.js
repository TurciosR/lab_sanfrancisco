$(document).ready(function() {
  generar();
  //setInterval("validar_btn();", 1000);
  $("#skardex").click(function() {
    kardex();
  });
  $("#hkardex").click(function() {
    kardex();
  });
  $("#pkardex").click(function() {
    var fini = $("#fini").val();
    var fin = $("#fin").val();
    var id_producto = $("#id_producto").val();
    var sucursal = $("#sucursal").val();
    window.open('kardex.php?id_producto='+id_producto+'&fini='+fini+'&fin='+fin, '', '');

  });
  $("#srotacion").click(function() {
    rotacion();
  });
  $("#hrotacion").click(function() {
    rotacion();
  });
  $("#logo").fileinput({'previewFileType':'image'});
  $('#formulario').validate({
    rules: {
      descripcion: {
        required: true,
      },
      proveedor: {
        required: true,
      },
    },
    messages: {
      descripcion: "Ingrese una descripcion",
      proveedor: "Seleccione proveedor",
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

  $("#btnGimg").click(function()
  {
    $("#cerrar_ven").click();
  });
  $(".bb").click(function()
  {
    $(".close").click();
  });
  $('#minimo').numeric({
    negative: false,
    decimal: false
  });
  $('#unidad_pre').numeric({
    negative: false,
    decimal: false
  });
  $('#precio_pre').numeric({
    negative: false,
    decimalPlaces: 4
  });
  $('#costo_pre').numeric({
    negative: false,
    decimalPlaces: 5
  });
  $('#id_categoria').select2();
  $('#id_proveedor').select2();
  $(".select2").select2({
    placeholder: {
      id: '',
      text: 'Seleccione',
    },
    allowClear: true,
  });
  //datepicker active
  //$( ".datepick" ).datepicker();
  $(".may").keyup(function() {
    $(this).val($(this).val().toUpperCase());
  });

});
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
  // Clean the modal form
  /*$(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
*/
  $(document).on("click", ".img_bbt", function()
  {
    $('#viewModal').modal({backdrop: 'static',keyboard: false});
  });

});

function generar() {
  dataTable = $('#editable2').DataTable().destroy()
  dataTable = $('#editable2').DataTable({
    "pageLength": 50,
    "order": [
      [2, 'asc']
    ],
    "processing": true,
    "serverSide": true,
    "ajax": {
      url: "admin_producto_dt.php",

      error: function() { // error handling
        //$(".editable2-error").html("");
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
    }]
  });
  dataTable.ajax.reload()
}

function senddata() {
  //var name=$('#name').val();
  if ($("#presentacion_table tr").length > 0) {
    var minimo = $('#minimo').val();
    var descripcion = $('#descripcion').val();
    var barcode = $('#barcode').val();
    var proveedor = $('#proveedor').val();
    var marca = $('#marca').val();
    var id_categoria = $('#id_categoria').val();
    var lista = "";
    var cuantos = 0;
    //Get the value from form if edit or insert
    var process = $('#process').val();
    var perecedero = $('#perecedero:checked').val();
    var exento = $('#exento:checked').val();

    if (process == 'insert') {
      var id_producto = 0;
      var urlprocess = 'agregar_producto.php';
    }
    if (process == 'edited') {
      var estado = $('#activo:checked').val();
      if (estado == undefined) {
        estado = 0;
      } else {
        estado = 1;
      }
      var id_producto = $('#id_producto').val();
      var urlprocess = 'editar_producto.php';
    }
    $("#presentacion_table tr").each(function() {
			var exis = $(this).attr("class");
      var id_pp = $(this).find(".presentacion").val();
      var des = $(this).find(".descripcion_p").html();
      var unidad_p = $(this).find(".unidad_p").html();
      var precio_p = $(this).find(".precio_p").html();
      var costo = $(this).find(".costo").html();
			if(exis == 'exis')
		 	{
		 		var id_prp = $(this).find(".id_pres_prod").val();
		 	}
		 	else
		 	{
		 		var id_prp = 0;
		 	}
      lista += id_pp + "," + des + "," + unidad_p + "," + precio_p + "," + id_prp + "," + costo + "|";
      cuantos += 1;
    });
    var dataString = 'process=' + process + '&id_producto=' + id_producto + '&barcode=' + barcode + '&descripcion=' + descripcion;
    dataString += '&exento=' + exento + '&proveedor=' + proveedor + '&id_categoria=' + id_categoria + '&perecedero=' + perecedero + '&lista=' + lista;
    dataString += '&marca=' + marca + '&minimo=' + minimo + '&cuantos=' + cuantos + '&estado=' + estado;

    $.ajax({
      type: 'POST',
      url: urlprocess,
      data: dataString,
      dataType: 'json',
      success: function(datax) {
        process = datax.process;
        id_producto2 = datax.id_producto;
        //var maxid=datax.max_id;


        if (datax.typeinfo == "Success") {
          //setInterval("reload1();", 1000);
          $("#id_id_p").val(id_producto2);
          img();
        }
        else {
          display_notify(datax.typeinfo, datax.msg);
        }
      }
    });
  } else {
    display_notify("Warning", "Debe ingresar al menos una presentacion");
  }
}

function reload1() {
  location.href = 'admin_producto.php';
}
$(document).on("click", ".deactive", function(){
	var id = $(this).attr("id");
	var td = $(this).parents("td");
	var tr = $(this).parents("tr");
	var fila = "<a class='activate' id='"+id+"'><i class='fa fa-check'></i></a>";
	$.ajax({
		type: 'POST',
		url: 'editar_producto.php',
		data: 'process=deactive&id_pres='+id,
		dataType: 'JSON',
		success : function(datax)
		{
			if(datax.typeinfo == "Success")
			{
				tr.css('background',  '#fcf8e3');
				td.html(fila);
			}
			else
			{
				display_notify("Error", "Ocurrio un error inesperado, intente nuevamente");
			}
		}
	});
});

$(document).on("click", ".activate", function(){
	var id = $(this).attr("id");
	var tr = $(this).parents("tr");
	var td = $(this).parents("td");
	var fila = "<a class='deactive' id='"+id+"'><i class='fa fa-times'></i></a>";
	$.ajax({
		type: 'POST',
		url: 'editar_producto.php',
		data: 'process=active&id_pres='+id,
		dataType: 'JSON',
		success : function(datax)
		{
			if(datax.typeinfo == "Success")
			{
				tr.css('background', '#fff');
				td.html(fila);
			}
			else
			{
				display_notify("Error", "Ocurrio un error inesperado, intente nuevamente");
			}
		}
	});
});
function deleted() {
  var id_producto = $('#id_producto').val();
  var dataString = 'process=deleted' + '&id_producto=' + id_producto;
  $.ajax({
    type: "POST",
    url: "borrar_producto.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      setInterval("location.reload();", 1000);
      $('#deleteModal').hide();
    }
  });
}
$(document).on("click", "#btnAgregar", function() {
  $.ajax({
    type: "POST",
    url: "agregar_producto.php",
    data: "process=lista",
    dataType: 'json',
    success: function(datax) {

    }
  });
})
$(document).on("click", ".Delete", function() {
  $(this).parents("tr").remove();
});

$(document).on("click", "#add_pre", function() {

  var id_presentacion = $("#id_presentacion").val();
  var desc_pre = $("#desc_pre").val();
  var unidad_pre = $("#unidad_pre").val();
  var precio_pre = $("#precio_pre").val();
  var costo_p = $("#costo_pre").val();
  var valor = $("#id_presentacion").val();
  if (id_presentacion != "" && desc_pre != "" && unidad_pre != "" && valor != "") {
    var exis = false;
    $("#presentacion_table tr").each(function() {
      var id_pp = $(this).find(".presentacion").val();
      if (id_pp == valor) {
        if (unidad_pre == $(this).find(".unidad_p").val()) {
          exis = true;
        }
      }

    });
    if (exis)
		{
      display_notify("Warning", "Ya agrego una presentacion con estas caracteristicas");
    } else {
      var text_select = $("#id_presentacion option:selected").html();
      var fila = "<tr>";
      fila += "<td><input type='hidden' class='presentacion' value='" + valor + "'>" + text_select + "</td>";
      fila += "<td class='descripcion_p'>" + desc_pre + "</td>";
      fila += "<td class='unidad_p'>" + unidad_pre + "</td>";
      fila += "<td class='costo'>" + costo_p + "</td>";
      fila += "<td class='precio_p'>" + precio_pre + "</td>";
      fila += "<td class='delete text-center'><a class='btn Delete'><i class='fa fa-trash'></i></a></td>";
      $("#presentacion_table").append(fila);
      $(".clear").val("");
      $("#id_presentacion").val("");
      $("#id_presentacion").trigger('change');
    }
  } else {
    display_notify("Error", "Por favor complete todos los campos");
  }
});
$('html').click(function() {
  /* Aqui se esconden los menus que esten visibles*/
  var number = $('#value').val();
  var a = $('#value').closest('td');

  var idtransace = a.closest('tr').attr('class');
  a.html(number);
});

$(document).on('dblclick', 'td', function(e) {
  if ($(this).hasClass('ed')) {
    var av = $(this).html();
    $(this).html('');
    $(this).html('<input class="form-control in" type="text" id="value" name="value" value="">');
    $('#value').val(av);
    $('#value').focus();
    $('#value').numeric({
      negative: false,
      decimalPlaces: 4
    });
    e.stopPropagation();
  }
  if ($(this).hasClass('ed2')) {
    var av = $(this).html();
    $(this).html('');
    $(this).html('<input class="form-control in" type="text" id="value" name="value" value="">');
    $('#value').val(av);
    $('#value').focus();
    $('#value').numeric({
      negative: false,
      decimalPlaces: 2
    });
    e.stopPropagation();
  }

  if ($(this).hasClass('nm')) {
    var av = $(this).html();
    $(this).html('');
    $(this).html('<input class="form-control in" type="text" id="value" name="value" value="">');
    $('#value').val(av);
    $('#value').focus();
    $('#value').numeric({
      negative: false,
      decimal: false
    });
    e.stopPropagation();
  }
});

function img()
{
  var form = $("#formulario_pro");
  var formdata = false;
  if(window.FormData)
  {
      formdata = new FormData(form[0]);
  }
  var formAction = form.attr('action');
  $.ajax({
      type        : 'POST',
      url         : "agregar_producto.php",
      cache       : false,
      data        : formdata ? formdata : form.serialize(),
      contentType : false,
      processData : false,
      dataType : 'json',
      success: function(datax)
      {
        display_notify(datax.typeinfo, datax.msg);
        if (datax.typeinfo == "Success")
        {
          setInterval("reload1();", 1000);
        }
      }
  });
}

function kardex() {
  $("#res").attr("style", "display: none;");
  $("#divh").attr("style", "display: block;");
  var fini = $("#fini").val();
  var fin = $("#fin").val();
  var id_producto = $("#id_producto").val();
  $.ajax({
    type: 'POST',
    url: 'editar_producto.php',
    data: 'process=kardex&fini='+fini+'&fin='+fin+'&id_producto='+id_producto,
    dataType: 'JSON',
    success: function(datax) {
      if (datax.typeinfo == "Success") {
        $("#no-data").hide();
        $("#res").show();
        $("#resultado").html(datax.table);
        $("#res").attr("style", "display: block;");
        $("#divh").attr("style", "display: none;");

      } else {
        $("#res").hide();
        $("#no-data").show();
        $("#resultado").html("");
        $("#divh").attr("style", "display: none;");

      }
    }
  });
}

function rotacion() {
  $("#res1").attr("style", "display: none;");
  $("#divh1").attr("style", "display: block;");
  var fini = $("#fini1").val();
  var fin = $("#fin1").val();
  var id_producto = $("#id_producto").val();
  $.ajax({
    type: 'POST',
    url: 'editar_producto.php',
    data: 'process=rotacion&fini='+fini+'&fin='+fin+'&id_producto='+id_producto,
    dataType: 'JSON',
    success: function(datax) {
      if (datax.typeinfo == "Success") {
        $("#no-data1").hide();
        $("#res1").show();
        $("#resultado1").html(datax.table1);
        $("#resultado2").html(datax.table2);
        $("#res1").attr("style", "display: block;");
        $("#divh1").attr("style", "display: none;");

      } else {
        $("#res1").hide();
        $("#no-data1").show();
        $("#resultado1").html("");
        $("#divh1").attr("style", "display: none;");
      }
    }
  });
}
