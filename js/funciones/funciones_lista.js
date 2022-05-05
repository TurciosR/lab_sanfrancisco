
function reload1()
{
   location.href = 'lista_producto.php';
}
$(document).ready(function() {

   $("#producto").typeahead({
    source: function(query, process) {
        $.ajax({
            url: 'buscador.php',
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
     var cantidad=1;
     var fila = "<tr><td>"+id_prod+"</td>";
     fila += "<td>"+descrip+"</td>";
     fila += "<td>"+cantidad+"<input type='hidden' id='cd' value='Saludos"+id_prod+"'></td>";
     fila += "<td><a id='ac' class='ac'><i class=\"fa fa-trash\"></i></a></td></tr>";
    $("#resultados").append(fila);

     //agregar_producto_lista(id_prod, descrip,marca);
}

});

})


$(document).on("click", "#ac", function()
{
  var parent = $(this).parents("tr").get(0);
  $(parent).remove();
/*var lista = "";
$("#example tbody>tr").each(function()
{
  var id_saludo = $(this).find("#cd").val();
  lista += id_saludo+"|";
})
console.log(lista);*/
})
