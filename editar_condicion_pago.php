<?php
include_once "_core.php";
function initial() {
	$id_con_pa= $_REQUEST["id_con_pa"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$query_tex = _query("SELECT * FROM condicion_pago WHERE id_condicion_pago='$id_con_pa'and id_sucursal='$id_sucursal'");
	$datos_tex = _fetch_array($query_tex);
	$descripcion = $datos_tex["abreviatura"];
	$nombre = $datos_tex["descripcion"];

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title text-center text-navy">Editar Condición de Pago</h3>
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
					<div class="form-group has-info single-line">
						<label>Descripción</label>
					<input type="hidden" name="process" id="process" value="edited">
					<input type="text" placeholder="Ingrese Descripcion" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $descripcion;?>">
				</div>
					<input type="hidden" name="id_condicion_pago" id="id_condicion_pago" value="<?php echo $id_con_pa; ?>">
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
	$id_con_pa = $_POST["id_condicion_pago"];
  $nombre=$_POST["nombre"];
	$descripcion=$_POST["abre"];
	$id_sucursal=$_SESSION["id_sucursal"];

    $sql_result=_query("SELECT id_condicion_pago FROM condicion_pago WHERE id_sucursal='$id_sucursal' AND id_condicion_pago='$id_con_pa'");
    $numrows=_num_rows($sql_result);

    $table = 'condicion_pago';
    $form_data = array (
    'descripcion' => $nombre,
		'abreviatura' => $descripcion,
	);
	$where_clause = "id_condicion_pago ='".$id_con_pa."'and id_sucursal='".$id_sucursal."'";
    if($numrows == 1)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Condicion de pago editado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Condicion de pago no pudo ser editado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta condicion_pago no esta disponible, intente con uno diferente!';
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
