$(document).ready(function() {
  $('.sel').select2();
  $("#nombre_insumo").typeahead({
    source: function(query, process) {
      $.ajax({
        url: 'buscador_insumo.php',
        type: 'POST',
        data: 'query=' + query,
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
      var id_insumo = insu[0];
      var nombre = insu[1];
      add_insumo(id_insumo, nombre);
    }
  });
  $('.select').select2();
  $('#precio_examen').numeric({
    negative: false,
    decimalPlaces: 2
  });

});
$(".may").keyup(function() {
  $(this).val($(this).val().toUpperCase());
});
//Validaciones de los campos
// Funcion utizada para la funcion de la tabla
var col = 0;
var row = 0;
var vr = "";
$(function() {
  $('html').click(function() {
    /* Aqui se esconden los menus que esten visibles (input)*/
    var number = $('#value').val();
    var a = $('#value').closest('td');
    var idtransace = a.closest('tr').attr('class');
    a.html(number);
  });
  //funcio para remover la columna con el icono de basura
  $("#tsb").on('keypress', '.in', function(event) {
    var suma=colref+add;
    if (event.key == 'Tab') {
      var tr = $(this).parents('tr');
      var nada=$(this);
      var row   = nada.parents('tr').index();
      var val = nada.text().trim();
      if (tr.find("td:eq(" + suma + ")")) {
        $('html').click();
        coll=suma+1;
        var tdd = tr.find("td:eq(" + coll + ")");
        if(suma>=1){
          $("#tsb tr").each(function(index) {
            if(index==row){
              val = $(this).find(".vr").text().trim();

            }
          })
        }
        campos(tdd,val,coll, row);
        add+=1;
    }


    }
    if (event.key == 'Enter') {
      var a = $(this).parents('tr');
      var app = "<tr style='height:35px;'><td class='tex param'></td><td class='tex unidad'></td> <td class='vr'></td><td class='text-center'><a class=' lndelete' type='button' name='button'> <span class='fa fa-trash'></span> </a></td></tr>";
      a.after(app);
      $('html').click();
    }
  });
});
$(document).on("click", "#sig", function() {
  $('#formulario').validate({
    rules: {
      id_categoria: {
        required: true,
      },
      nombre_examen: {
        required: true,
      },
      precio_examen: {
        required: true,
      },
    },
    messages: {
      id_categoria: "Por favor selecione la categoria",
      nombre_examen: "Por favor ingrese el nombre examen",
      precio_examen: "Por favor ingrese el precio examen",

    },
    submitHandler: function(form) {
      senddata();
    }
  });
})
var rowref=0;
var colref=0;
var add=0;
$(document).on('keypress', '#tabla1 .in', function(event) {
  console.log(event.key);

  var suma=colref+add;

  if (event.key == 'Tab') {
      var tr = $(this).parents('tr');
      var nada=$(this);
      var row   = nada.parents('tr').index();
      console.log(row);
      //console.log(row);
      if (tr.find("td:eq(" + suma + ")")) {
        $('html').click();
        coll=suma+1;
        var tdd = tr.find("td:eq(" + coll + ")");
        campos(tdd,"",0,0);

        if(suma>2){
          $("#ref tr").each(function(index) {
          console.log(row);
            if((index-row)==1){
              campos($(this).find("td:eq(0)"),valores,col,row);
              add=-1;
              colref=0;
            }
          })
        }
        add+=1;
    }

  }
  if (event.key == 'Enter') {
    var a = $(this).parents('tr');
    var app = "<tr style='height:35px;'><td class='sel sexo'></td><td class='nm edad_inicio'></td><td class='nm edad_fin'></td> <td class='tex valores'></td><td class='text-center'><a class=' lndelete' type='button' name='button'> <span class='fa fa-trash'></span> </a></td></tr>";
    a.after(app);
    $('html').click();
  }
});
$(document).on("click", "#sig1", function() {
  var modal = "<a data-toggle='modal' href='ver_examen.php' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-trash\"></i> Ver</a>";
  window.location = 'valores_referencia.php';
});
$(document).on("click", "#valores", function() {
  var valores = "";
  var eee = false;
  var row = $("#row").val();
  var col = $("#col").val();
  $("#ref tr").each(function(index) {
    var campo1, campo2, campo3, campo4;
    campo1 = $(this).find(".sexo").text();
    campo2 = $(this).find(".edad_inicio").text();
    campo3 = $(this).find(".edad_fin").text();
    campo4 = $(this).find(".valores").text();
    valores += campo1 + ":" + campo2 + ":" + campo3 + ":" + campo4 + ";<br>";
    if (campo1 == "" || campo2 == "" || campo3 == "" || campo4 == "") {
      eee = true;
      console.log("Error llenar campos");
    }
  })
  //$(".vr").val(valores);\
  if (!eee) {
    $("#cerrar").click();
    $("#tsb tr").each(function(index) {
      var tr = $(this);
      if (index == row) {
        var td = tr.find("td:eq(" + col + ")");
        td.html(valores);
      }
    });
  } else {
    display_notify("Error", "Verifique todos los campos");
  }
  console.log(valores);
})
var contador = 0;
$(function() {
  //binding event click for button in modal form
  $(document).on("click", "#btnDelete", function(event) {
    deleted();
  });
  // Clean the modal form
  $(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
  });
  $(document).on('click', '.lndelete', function(e) {
    $(this).closest('tr').remove();
  });
  // Funcion  Para validar que clase de input se utilizara
  $(document).on('click', 'td', function(e) {

    var tdd = $(this);
    var valores  = "";
    valores = tdd.text().trim();
    var col   = tdd.index();
    colref   = tdd.index();
    rowref   = tdd.parents('tr').index();
    add=0;
    var row   = tdd.parents('tr').index();
    campos($(this),valores,col,row);
    //console.log();


  });
  $(document).on('keypress', '.in', function(event) {

    if (event.key == 'Enter') {
      $('html').click();
    }
  });
});

function campos(td,valores,col,row)
{

  if ($(td).hasClass('ed')) {

      var av = $(td).html();
      $(td).html('');
      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="">');
      $('#value').val(av);
      $('#value').focus();
      $('#value').numeric({
        negative: false,
        decimalPlaces: 2
      });
    }
    if ($(td).hasClass('vr')) {

			$("#modal").attr("href", "valores_referencia.php?row="+row+"&col="+col+"&valores="+ valores +"");
			$("#modal").click();
    }
    if ($(td).hasClass('sel')) {
      var av = $(td).html();
      $(td).html('');
      $(td).html('<select class="form-control select in" name="value" id="value" value=""><option value="" >Seleccionar</option><option value="MASCULINO" >MASCULINO</option><option value="FEMENINO" >FEMENINO</option></select>');
      $('#value').val(av);
      $('#value').focus();
    }
    if ($(td).hasClass('nm')) {
      var av = $(td).html();
      $(td).html('');
      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="">');
      $('#value').val(av);
      $('#value').focus();
      $('#value').numeric({
        negative: false,
        decimal: false
      });

    }
    if ($(td).hasClass('tex')) {

      var av = $(td).html();
      $(td).html('');
      $(td).html('<input class="form-control in" type="text" id="value" name="value" value="">');
      $('#value').val(av);
      $('#value').focus();
    }

}
function add_insumo(id_insumo, nombre)
{
	var existe = false;
    $("#linsumo tr").each(function() {
      campo1 = $(this).find(".id_insumo").text();
      if (campo1 == id_insumo) {
        existe = true;
      }
    });
    if (!existe)
		{
			$.ajax({
		    type: "POST",
		    url: 'agregar_examen.php',
		    data: "process=getpresentacion&id_producto="+id_insumo,
		    dataType: 'json',
		    success: function(data)
				{
				  var select = data.select;
				  var tr_add = '';
		      tr_add += "<tr>";
		      tr_add += "<td class='id_insumo'>" + id_insumo + "</td>";
		      tr_add += "<td class='nombre_insumo'>" + nombre + '</td>';
					tr_add += "<td class='present'>" + select + "</td>";
		      tr_add += "<td><input type='text' class='cantidad_insumo numeric' value='1'></td>";
		      tr_add += "<td class='text-center'><a class='lndelete btn'><i class='fa fa-trash'></i></a></td>";
		      tr_add += '</tr>';
					$("#linsumo").append(tr_add);
					$(".sel").select2();
					$(".numeric").numeric({negative:false, decimal:false});
		     }
		  });
    } else {
      display_notify("Error", "El insumo ya fue agregado");
    }
  }

  function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  function senddata() {
    var formulario = "";
    var insumos = "";
    var StringDatos = "";
		var datos = "";

    var error = false;
    $("#tsb tr").each(function(index) {
      var campo1, campo2, campo3S, campo3;
      campo1 = $(this).find(".param").text();
      campo2 = $(this).find(".unidad").text();
      campo3S = $(this).find(".vr").text();
      campo3 = $.trim(campo3S);
      StringDatos += campo1 + "|" + campo2 + "|" + campo3 + "#";
      if (campo1 == "") {
        console.log("AQUI" + index);
        error = true;
      }
    })
    if (error) {
      display_notify('Error', 'Por favor agrege al parametro');
    } else {
      var error2 = false;
      $("#linsumo tr").each(function() {
        var id, cantidad;
        id = $(this).find(".id_insumo").text();
        cantidad = $(this).find(".cantidad_insumo").val();
        presentacion = $(this).find(".sel").val();
        datos += id+"|"+cantidad+"|"+presentacion+"#";
        if (id == "" || cantidad == "") {
          error2 = true;
        }
      })
      if (error2) {
        display_notify('Error', 'Por favor agrege una cantidad');
      } else {
        console.log(datos);
        formulario = $.trim(StringDatos);
        insumos = datos;

        var id_categoria = $('#id_categoria').val();
        var nombre = $('#nombre_examen').val();
        var precio = $('#precio_examen').val();
        //Get the value from form if edit or insert
        var process = $('#process').val();
        if (process == 'insert') {
          var id_examen = 0;
          var urlprocess = 'agregar_examen.php';
          var dataString = 'process=' + process + '&id_categoria=' + id_categoria + '&nombre_examen=' + nombre + '&precio_examen=' + precio + '&formulario=' + formulario + '&insumos=' + insumos;
        }
        if (process == 'edited') {
          var id_examen = $('#id_examen').val();
          var urlprocess = 'editar_examen.php';
          var dataString = 'process=' + process + '&id_categoria=' + id_categoria + '&nombre_examen=' + nombre + '&precio_examen=' + precio + '&formulario=' + formulario + '&id_examen=' + id_examen + '&insumos=' + insumos;
        }
        $.ajax({
          type: 'POST',
          url: urlprocess,
          data: dataString,
          dataType: 'json',
          success: function(datax) {
            process = datax.process;
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
              setInterval("reload1();", 1500);
            }
          }
        });
      }
    }
	}
    function reload1() {
      location.href = 'admin_examen.php';
    }
    function deleted() {
      var id_usuario = $('#id_usuario').val();
      var dataString = 'process=deleted' + '&id_usuario=' + id_usuario;
      $.ajax({
        type: "POST",
        url: "borrar_usuario.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
          display_notify(datax.typeinfo, datax.msg);
          if (datax.typeinfo == "Success") {
            setInterval("reload1();", 1500);
            $('#deleteModal').hide();
          }
        }
      });
    }
