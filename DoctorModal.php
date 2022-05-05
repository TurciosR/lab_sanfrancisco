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
		<h3 class="modal-title text-navy">Agregar Doctor</h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
							<div class="row">
									<div class="form-group col-lg-6">
											<label>Nombres</label>
											<input type="text" placeholder="Ingrese nombres" class="form-control may" id="nombre" name="nombre" class="mayuscula">
									</div>
									<div class="form-group col-lg-6">
											<label>Apellidos</label>
											<input type="text" placeholder="Ingrese apellidos" class="form-control may" id="apellido" name="apellido">
									</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-6">
										<label>Especialidad</label>
										<select class="col-md-12 sel form-control may" id="especialidad" name="especialidad" style="width:100%;">
												<option value="">Seleccione</option>
												<?php
														$sqld = "SELECT * FROM especialidades";
														$resultd=_query($sqld);
														while($depto = _fetch_array($resultd))
														{
																echo "<option value='".$depto["id_especialidades"]."'";
																echo">".$depto["nombre"]."</option>";
														}
												?>
										</select>
									</div>
							</div>

					<div>
						<input type="hidden" name="process" id="process" value="insert">
						<button type="button"  id="btnAddDoctor" class="btn btn-primary m-t-n-xs" >Guardar</button>
						<button type="button" class="btn btn-danger m-t-n-xs" data-dismiss="modal">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>

	$("#especialidad").select2();
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
