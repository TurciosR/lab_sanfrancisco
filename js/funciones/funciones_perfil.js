$(document).ready(function() {
  $("#nombre_examen").typeahead({
    source: function(query, process) {
      $.ajax({
        url: 'buscador_examen.php',
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
      var examen0 = selection;
      var examen = examen0.split("|");
      var id_examen = examen[0];
      var nombre = examen[1];
      add_examen(id_examen, nombre);
    }
  });
  $('#precio_perfil').numeric({
    negative: false,
    decimalPlaces: 2
  });

});
$(".may").keyup(function() {
  $(this).val($(this).val().toUpperCase());
});

$(document).on("click", "#sig", function() {
  $('#formulario').validate({
    rules: {
      nombre_perfil: {
        required: true,
      },

      precio_perfil: {
        required: true,
      },
    },
    messages: {
      nombre_perfil: "Por favor ingrese el nombre del perfil",
      precio_perfil: "Por favor ingrese el precio examen",

    },
    submitHandler: function(form) {
      senddata();
    }
  });
})

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
  $(document).on("click", "#anular", function(event) {
		estado();
	});
  // Funcion  Para validar que clase de input se utilizara


});


function add_examen(id_examen, nombre)
{
	var existe = false;
    $("#liexamen tr").each(function() {
      campo1 = $(this).find(".id_examen").text();
      if (campo1 == id_examen) {
        existe = true;
      }
    });
    if (!existe)
		{

				  var tr_add = '';
		      tr_add += "<tr>";
		      tr_add += "<td class='id_examen'>" + id_examen + "</td>";
		      tr_add += "<td class='nombre_examen'>" + nombre + '</td>';
		      tr_add += "<td class='text-center'><a class='lndelete btn'><i class='fa fa-trash'></i></a></td>";
		      tr_add += '</tr>';
					$("#liexamen").append(tr_add);

    } else {
      display_notify("Error", "El examen ya fue agregado");
    }
  }

  function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  function senddata() {
    var id_examen = "";
    var StringDatos = "";

    var error = false;
    $("#liexamen tr").each(function(index) {
      var campo1;
      campo1 = $(this).find(".id_examen").text();
      StringDatos += campo1 + "#";
      if (campo1 == "" ) {
        error = true;
      }
    })
    if (error) {
      display_notify('Error', 'Por favor agrege al menos un examen');
    } else {
        id_examen = $.trim(StringDatos);
        var nombre = $('#nombre_perfil').val();
        var precio = $('#precio_perfil').val();

        //Get the value from form if edit or insert
        var process = $('#process').val();
        if (process == 'insert') {
          var id_perfil = 0;
          var urlprocess = 'agregar_perfil.php';
          var dataString = 'process=' + process + '&nombre_perfil=' + nombre +  '&precio_perfil=' + precio + '&id_examen=' + id_examen;
        }
        if (process == 'edited') {
          var id_perfil = $('#id_perfil').val();
          var urlprocess = 'editar_perfil.php';
          var dataString = 'process=' + process + '&id_perfil=' + id_perfil + '&nombre_perfil=' + nombre +  '&precio_perfil=' + precio + '&id_examen=' + id_examen;
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
    function reload1() {
      location.href = 'admin_perfil.php';
    }
    function estado()
    {
    	var id_perfil = $('#id_perfil').val();
    	var estado = $('#estado').val();
    	var dataString = 'process=anular' + '&id_perfil=' + id_perfil+ '&estado=' + estado;
    	$.ajax({
    		type : "POST",
    		url : "estado_perfil.php",
    		data : dataString,
    		dataType : 'json',
    		success : function(datax) {
    			display_notify(datax.typeinfo, datax.msg);
    			if(datax.typeinfo == "Success")
    			{
    				setInterval("reload1();", 1500);
    				$('#deleteModal').hide();
    			}
    		}
    	});
    }
