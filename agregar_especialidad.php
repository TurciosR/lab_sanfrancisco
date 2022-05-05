<?php
include_once "_core.php";
function initial() {
  //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "agregar_especialidad.php";
  $links=permission_usr($id_user,$filename);
  ?>

<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy">Agregar Especialidad</h3>
</div>
<?php
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){
?>
<form name="formulario" id="formulario">
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInUp">
	    <div class="row">

	        <div class="col-lg-10">
	            <div class="container">

	                  <div class="row">
	                      <div class="form-group col-sm-5">
	                          <label>Nombre</label>
	                          <input type="text" placeholder="Ingrese Nombre" class="form-control " id="nombre" name="nombre">
	                      </div>
	                  </div>

	            </div>
	        </div>
	    </div>
		</div>
	</div>
	<div class="modal-footer">
	    <input type="hidden" name="process" id="process" value="insert">
	    <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary"/>
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
	</div>
</form>
<script>
$(document).ready(function(){
	$('#formulario').validate({
		rules: {
			nombre: {
				required: true,
			},
		},
		messages: {
			nombre: "Por favor ingrese un nombre",
		},
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		submitHandler: function (form) {
			senddata();
		}
	});
	$("#nombre").keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
	$("#nombre").change(function(){
				$(this).val($(this).val().toUpperCase());
	});

});
</script>

<?php

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function insertar()
{

    $nombre=$_POST["nombre"];
		$id_sucursal=$_SESSION["id_sucursal"];

    $sql_result=_query("SELECT id_especialidades FROM especialidades WHERE nombre='$nombre'");
    $numrows=_num_rows($sql_result);

    $table = 'especialidades';
    $form_data = array (
    'nombre' => $nombre,
		'id_sucursal'=>$id_sucursal
    );

    if($numrows == 0)
    {
        $insertar = _insert($table,$form_data);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Especialidad ingresada correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Especialidad no pudo ser ingresada!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este especialidad ya fue ingresada!';
    }
	echo json_encode($xdatos);
}

if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formVer' :
				initial();
				break;
			case 'insert' :
				insertar();
				break;
		}
	}
  }
?>
