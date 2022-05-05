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
		<h3 class="modal-title text-navy">Agregar Categoría</h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
					<div class="form-group has-info single-line">
						<label>Nombre</label>
						<input type="text" placeholder="Nombre" class="form-control" id="nombre" name="nombre" value="">
					</div>
					<div class="form-group has-info single-line">
						<label>Descripción</label>
						<input type="text" placeholder="Descripcion" class="form-control" id="descripcion" name="descripcion" value="">
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
		$("#descripcion").keyup(function() {
			$(this).val($(this).val().toUpperCase());
		});
		$("#descripcion").change(function(){
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
	'estado' => 1,
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
