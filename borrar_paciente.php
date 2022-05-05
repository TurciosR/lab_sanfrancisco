<?php
include ("_core.php");
function initial()
{
	$id_paciente = $_REQUEST ['id_paciente'];
	$sql="SELECT *FROM paciente WHERE id_paciente='$id_paciente'";
	$result = _query($sql);
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
		<h4 class="modal-title">Borrar Paciente</h4>
	</div>
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<?php
					//permiso del script
					if ($links!='NOT' || $admin=='1' ){
						?>
						<table class="table table-bordered table-striped" id="tableview">
							<thead>
								<tr>
									<th>Campo</th>
									<th>Descripcion</th>
								</tr>
							</thead>
							<tbody>
								<?php
										$row = _fetch_array($result);
										echo "<tr><td>Id</th><td>$id_paciente</td></tr>";
										echo "<tr><td>Nombre</td><td>".$row ['nombre']."</td>";
										echo "<tr><td>Dirección</td><td>".$row ['direccion']."</td>";
										echo "</tr>";
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php
				echo "<input type='hidden' nombre='id_paciente' id='id_paciente' value='$id_paciente'>";
				?>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
		<!--/modal-footer -->
<?php
	} //permiso del script
	else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
	}
}
function deleted()
{
	$id_paciente = $_POST ['id_paciente'];
	$table = 'paciente';
	$where_clause = "id_paciente='".$id_paciente."'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Registro eliminado con exito!';
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registro no pudo ser eliminado!';
	}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
				case 'deleted' :
				deleted();
				break;
			}
		}
	}

	?>
