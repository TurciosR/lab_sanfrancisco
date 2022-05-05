<?php
include_once "_core.php";
function initial() {
	$id_muestra = $_REQUEST["id_muestra"];
	$query_tex = _query("SELECT * FROM muestra WHERE id_muestra='$id_muestra'");
	$datos_tex = _fetch_array($query_tex);
	$nombre = $datos_tex["muestra"];

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title text-navy">Editar Categoría</h3>
	</div>
	<div class="modal-body">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<?php if ($links!='NOT' || $admin=='1' ){ ?>
				<form name="formulario" id="formulario" autocomplete="off">

					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Ingrese Nombre" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre;?>">
					</div>
					<input type="hidden" name="process" id="process" value="edited">
					<input type="hidden" name="id_muestra" id="id_muestra" value="<?php echo $id_muestra; ?>">
					<div>
						<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs"/>
					</div>
				</form>
			</div>
		</div>
	</div>
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
function edited()
{
	$id_muestra = $_POST["id_muestra"];
  	$nombre=$_POST["nombre"];

    $sql_result=_query("SELECT id_muestra FROM muestra WHERE muestra='$nombre' AND id_muestra!='$id_muestra'");
    $numrows=_num_rows($sql_result);

    $table = 'muestra';
    $form_data = array (
    'muestra' => $nombre,
    );
    $where_clause = "id_muestra ='".$id_muestra."'";
    if($numrows == 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='muestra editada correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='muestra no pudo ser editada!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta muestra no esta disponible, intente con uno diferente!';
    }
	echo json_encode($xdatos);
}
if(!isset($_REQUEST['process']))
{
	initial();
}
else
{
	if(isset($_REQUEST['process']))
	{
		switch ($_REQUEST['process'])
		{
			case 'edited':
				edited();
				break;
			case 'formEdit' :
				initial();
				break;
		}
	}
}
?>
