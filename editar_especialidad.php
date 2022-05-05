<?php
include_once "_core.php";

function initial() {
    //permiso del script
	   $id_user=$_SESSION["id_usuario"];
	   $admin=$_SESSION["admin"];
     $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    $id_especialidades = $_REQUEST["id_especialidad"];
    $query_user = _query("SELECT * FROM especialidades WHERE id_especialidades='$id_especialidades'");
    $datos_user = _fetch_array($query_user);
    $nombre = $datos_user["nombre"];
    //permiso del script023+-
?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy">Editar Especialidad</h3>
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
	                	<div class="form-group col-md-5">
	                  	<label>Nombre</label>
	                    	<input type="text" placeholder="Ingresa Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
	                  </div>
	                </div>

	          </div>
	        </div>
	    </div>
	  </div>
	</div>
	<div class="modal-footer">
	  <input type="hidden" name="process" id="process" value="edited">
	  <input type="hidden" name="id_especialidades" id="id_especialidades" value="<?php echo $id_especialidades; ?>">
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


function editar_especialidad()
{
    $id_especialidades = $_POST["id_especialidades"];
    $nombre = $_POST["nombre"];

    $sql_result=_query("SELECT id_especialidades FROM especialidades WHERE  id_especialidades='$id_especialidades'");
    $numrows=_num_rows($sql_result);

    $table = 'especialidades';
    $form_data = array (
    'nombre' => $nombre
    );
    $where_clause = "id_especialidades ='".$id_especialidades."'";
    if($numrows != 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Especialidad editada correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Especialidad no pudo ser editada!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta especialidad no esta disponible, intente con uno diferente!';
    }
	echo json_encode($xdatos);
}

if(!isset($_POST['process']))
{
	initial();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
        	case 'edited':
        		editar_especialidad();
        		break;
        }
    }
}
?>
