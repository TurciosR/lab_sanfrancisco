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
		<h3 class="modal-title text-navy">Agregar Procedencia</h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
							<div class="row">
								<div class="form-group has-info single-line">
									<label>Nombre</label>
									<input type="text" placeholder="Nombre" class="form-control may" id="nombre" name="nombre" value="">
								</div>
								<div class="form-group has-info single-line">
									<label>Direccion</label>
									<input type="text" placeholder="Descripcion" class="form-control may" id="descripcion" name="descripcion" value="">
								</div>
								<div class="form-group has-info single-line">
									<label>Telefono</label>
									<input type="text" placeholder="Telefono" class="form-control" id="telefono" name="telefono" value="">
								</div>

								<div>
						<input type="hidden" name="process" id="process" value="insert">
						<button type="button" id="btnAddProcedencia" class="btn btn-primary m-t-n-xs">Guardar</button>
						<button type="button" class="btn btn-danger m-t-n-xs" data-dismiss="modal">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$(".may").keyup(function() {
			$(this).val($(this).val().toUpperCase());
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

if(!isset($_REQUEST['process'])){
	initial();
}
else
{
	if(isset($_REQUEST['process']))
	{
		switch ($_REQUEST['process'])
		{
			case 'formEdit' :
				initial();
				break;
		}
	}
}
	?>
