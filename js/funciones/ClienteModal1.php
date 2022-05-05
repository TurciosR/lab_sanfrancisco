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
	<style media="screen">
		 span.select2-container--open{
			z-index: 10050;
		}
	</style>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title text-navy">Agregar Cliente</h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
					<div class="row">
							<div class="form-group col-lg-6">
									<label>Nombre</label>
									<input type="text" placeholder="Ingrese Nombres" class="form-control may" id="nombre" name="nombre">
							</div>
							<div class="form-group col-lg-6">
									<label>Direccion</label>
									<input type="text" placeholder="Ingrese Direccion" class="form-control may" id="apellido" name="apellido">
							</div>
					</div>
						<div class="row">

								<div class="form-group col-lg-6">
										<label>G&eacute;nero</label>
										<select class="form-control select" name="sexo" id="sexo">
											<option value="">Seleccionar</option>
											<option value="FEMENINO">Femenino</option>
											<option value="MASCULINO">Masculino</option>
									</select>
								</div>

						</div>

					<div>
						<input type="hidden" name="process" id="process" value="insert">
						<input type="submit" id="btnAddClient1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
						<button type="button" class="btn btn-danger m-t-n-xs" data-dismiss="modal">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function(){
		$('#sexo').select2();
		$('#naci').datepicker();

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
function insert()
{
	$nombre=$_POST["nombre"];
	$descripcion=$_POST["descripcion"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql_result=_query("SELECT id_categoria FROM categoria WHERE nombre_categoria='$nombre'");
	$numrows=_num_rows($sql_result);

	$table = 'categoria';
	$form_data = array (
	'nombre_categoria' => $nombre,
	'descripcion_categoria' => $descripcion,
	'id_sucursal'=>$id_sucursal
	);

	if($numrows == 0)
	{
			$insertar = _insert($table,$form_data);
			if($insertar)
			{
				 $xdatos['typeinfo']='Success';
				 $xdatos['msg']='Categoria ingresada correctamente!';
				 $xdatos['process']='insert';
			}
			else
			{
				 $xdatos['typeinfo']='Error';
				 $xdatos['msg']='Categoria no pudo ser ingresado!';
		}
	}
	else
	{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Esta categoria ya fue ingresado!';
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
