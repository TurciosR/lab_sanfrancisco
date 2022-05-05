<?php
include ("_core.php");
function initial(){
	$id_tipo_empleado = $_REQUEST ['id_tipo_empleado'];
	$sql="SELECT *FROM tipo_empleado WHERE id_tipo_empleado='$id_tipo_empleado'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_tipo_empleado.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-center text-navy">Borrar Tipo Empleado</h3>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-lg-10">
				<table class="table table-bordered table-striped" id="tableview">
					<thead>
						<tr>
							<th class="col-lg-3">Campo</th>
							<th class="col-lg-5">Nombre</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td>ID</th><td>$id_tipo_empleado</td></tr>";
										echo "<tr><td>Nombre</td><td>".$row['descripcion']."</td>";
										echo "</tr>";

									}
								}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' name='id_tipo_empleado' id='id_tipo_empleado' value='$id_tipo_empleado'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnDelete">Eliminar</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>

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
	$id_tipo_empleado = $_POST ['id_tipo_empleado'];
	$table = 'tipo_empleado';
	$where_clause = "id_tipo_empleado='" . $id_tipo_empleado . "'";
	$sql_result=_query("SELECT * FROM empleado WHERE id_tipo_empleado='$id_tipo_empleado'");
	$contar=_num_rows($sql_result);
	if($contar>0){
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Tipo empleado no puede ser eliminada';

	}else{
		$delete = _delete ( $table, $where_clause );
		if ($delete) {
			$xdatos ['typeinfo'] = 'Success';
			$xdatos ['msg'] = 'Tipo Empleado eliminado correctamente!';
		} else {
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = 'Tipo Empleado no pudo ser eliminado';
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
