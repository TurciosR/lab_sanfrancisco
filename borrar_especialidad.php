<?php
include ("_core.php");
function initial(){
	$id_especialidades = $_REQUEST ['id_especialidad'];
	$sql="SELECT *FROM especialidades WHERE id_especialidades='$id_especialidades'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_especialidad.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy">Borrar Especialidad</h3>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-lg-12">
				<table class="table table-bordered table-striped" id="tableview">
					<thead>
						<tr>
							<th>CAMPO</th>
							<th>DESCRICI&Oacute;N</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td>ID</th><td>$id_especialidades</td></tr>";
										echo "<tr><td>NOMBRE</td><td>".$row['nombre']."</td>";
										echo "</tr>";

									}
								}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' name='id_especialidad' id='id_especialidad' value='$id_especialidades'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnDelete">Eliminar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function deleted()
{
	$id_especialidades = $_POST ['id_especialidad'];
	$table = 'especialidades';
	$where_clause = "id_especialidades='" . $id_especialidades . "'";

	$sql_result=_query("SELECT * FROM doctor WHERE especialidad='$id_especialidades'");
	$contar=_num_rows($sql_result);
	if($contar>0){
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Especialidad no puede ser eliminada';

	}else{
		$delete = _delete ( $table, $where_clause );
		if ($delete) {
			$xdatos ['typeinfo'] = 'Success';
			$xdatos ['msg'] = 'Especialidad eliminada correctamente!';
		} else {
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = 'Especialidad no puede ser eliminada!';
		}

	}

	echo json_encode ( $xdatos );
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
