<?php
include_once "_core.php";
function initial() {
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
$id_sucursal=$_SESSION["id_sucursal"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title text-center text-navy">Agregar Condicion de Pago</h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre">
					</div>
					<div class="form-group has-info single-line">
						<label>Abreviatura</label>
						<input type="text" placeholder="Descripcion" class="form-control" id="abreviatura" name="abreviatura">
					</div>
					<input type="hidden" name="process" id="process" value="insert">
					<div>
						<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
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
				abreviatura: {
					required: true,
				},
			},
			messages: {
				nombre: "Por favor ingrese un nombre",
				abreviatura: "Por favor ingrese un abreviatura",
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
	}
	else
	{
		//$mensaje = mensaje_permiso();
		echo "<br><br>No tiene permiso para este modulo</div></div></div></div>";;
	}
}
function insert(){
	$nombre=$_POST["nombre"];
	$abre=$_POST["abre"];
	$id_sucursal=$_SESSION["id_sucursal"];

	$sql_result=_query("SELECT id_condicion_pago FROM condicion_pago WHERE descripcion='$nombre' and id_sucursal='$id_sucursal'");
	$numrows=_num_rows($sql_result);

	$table = 'condicion_pago';
	$form_data = array (
	'descripcion' => $nombre,
	'abreviatura' => $abre,
	'id_sucursal' => $id_sucursal
	);

	if($numrows == 0)
	{
			$insertar = _insert($table,$form_data);
			if($insertar)
			{
				 $xdatos['typeinfo']='Success';
				 $xdatos['msg']='Codicion de pago ingresada correctamente!';
				 $xdatos['process']='insert';
			}
			else
			{
				 $xdatos['typeinfo']='Error';
				 $xdatos['msg']='Condicion de pago no pudo ser ingresado!';
		}
	}
	else
	{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Esta condición de pago ya fue ingresado!';
	}
echo json_encode($xdatos);
}

if(!isset($_REQUEST['process'])){
	initial();
}
else
{
	if(isset($_REQUEST['process']))
	{
		switch ($_REQUEST['process'])
		{
			case 'insert':
				insert();
				break;
			case 'formEdit' :
				initial();
				break;
		}
	}
}
	?>
