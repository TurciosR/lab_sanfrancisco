$(document).ready(function()
{

  $('#formulario').validate({
  	    rules: {
            name_caja: {
            required: true,
             },

            serie: {
            required: true,
             },
            resolucion: {
             required: true,
              },

            fecha_resolucion: {
            required: true,

             },
            hasta: {
            required: true,
             },

         },
        messages: {
						name_caja: "Por favor ingrese el nombre de caja",
						serie: "Por favor ingrese la serie",
            resolucion: "Por favor ingrese la resolucion",
            fecha_resolucion: "Por favor ingrese la fecha resolucion",
						desde: "Por favor ingrese el valor inicial",
						hasta: "Por favor ingrese el valor final",

					},

        submitHandler: function (form) {
            senddata();
        }
      });
      $('.select').select2();
      $(".numeric").numeric({
        negative:false,
      });
});

function senddata()
{
  var nombre_caja = $("#name_caja").val();
  var serie = $("#serie").val();
  var resolucion = $("#resolucion").val();
  var fecha_resolucion = $("#fecha_resolucion").val();
  var desde = $("#desde").val();
  var hasta = $("#hasta").val();
  var process = $("#process").val();
  var id_sucursal = $("#id_sucursal").val();


  var datos = "";
  if(process == 'agregar')
  {
    var url = "agregar_caja.php";
    datos += "process="+process+"&nombre_caja="+nombre_caja+"&serie="+serie+"&desde="+desde+"&hasta="+hasta+"&id_sucursal="+id_sucursal;
  }
  if(process == 'editar')
  {
    var url = "editar_caja.php";
    var id_caja = $("#id_caja").val();
    datos += "process="+process+"&nombre_caja="+nombre_caja+"&serie="+serie+"&desde="+desde+"&hasta="+hasta+"&id_caja="+id_caja+"&id_sucursal="+id_sucursal+"&resolucion="+resolucion+"&fecha_resolucion="+fecha_resolucion;
  }
  if(id_sucursal != "" && id_sucursal != 0)
  {
    $.ajax({
      type:'POST',
      url:url,
      data: datos,
      dataType: 'json',
      success: function(datax){
        display_notify(datax.typeinfo,datax.msg);
        if(datax.typeinfo == 'Success')
        {
          setInterval("reload1();", 1000);
        }
      }
    });
  }
  else
  {
    display_notify("Error","Debe de seleccionar la sucursal");
  }

}

$('#fecha_resolucion').on('keydown', function (event)
   {
     if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
     {

     }
     else
     {
         if((event.keyCode>47 && event.keyCode<60 ) || (event.keyCode>95 && event.keyCode<106 ))
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
           event.preventDefault();
         }

     }
 });





$(document).on("click","#estado", function()
{
  var id_caja = $(this).parents("tr").find("#id_caja").val();
  var estado = $(this).parents("tr").find("#estado1").val();
  if(estado == 1)
  {
    var text = "Desactivar";
  }
  else
  {
      var text = "Activar";
  }
  swal({
    title: text+" esta caja?",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Si, "+text+" esta caja!",
    cancelButtonText: "No, cancelar!",
    closeOnConfirm: true,
    closeOnCancel: false
  },
  function(isConfirm) {
    if (isConfirm) {
      estado_pro(id_caja, estado);
      //swal("Exito", "Turno iniciado con exito", "error");
    } else {
      swal("Cancelado", "Operación cancelada", "error");
    }
  });
})
function estado_pro(id_caja, estado) {
  //var id_proveedor = $('#id_proveedor').val();
  var dataString = 'process=estado' + '&id_caja=' + id_caja+ '&estado=' + estado;
  $.ajax({
    type: "POST",
    url: "admin_caja.php",
    data: dataString,
    dataType: 'json',
    success: function(datax) {
      display_notify(datax.typeinfo, datax.msg);
      if (datax.typeinfo == "Success") {
        setInterval("reload1();", 1000);
        //$('#deleteModal').hide();
      }
    }
  });
}

function reload1() {
  location.href = 'admin_caja.php';
}
