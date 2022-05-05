<?php
include ("_core.php");
function initial(){
	$id_usuario = $_REQUEST ['id_usuario'];
	$id_sucursal=$_SESSION['id_sucursal'];
	$sql="SELECT *FROM usuario WHERE id_usuario='$id_usuario' and id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_usuario.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Usuario</h4>
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
							<th>Campo</th>
							<th>Nombre</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td>Id Usuario</th><td>$id_usuario</td></tr>";
										echo "<tr><td>Usuario</td><td>".$row['usuario']."</td>";
										echo "</tr>";
										echo "<input type='hidden' nombre='id_sucursal' id='id_sucursal' value='".$row['id_sucursal']."'>";
									}
								}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_usuario' id='id_usuario' value='$id_usuario'>";

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
	$id_usuario = $_POST ['id_usuario'];
	$id_surcursalA=$_POST['id_sucursal'];
	echo "$id_sucursalA";
	$id_sucursal=$_SESSION["id_sucursal"];
	$id_usuarioA=$_SESSION["id_usuario"];
	if($id_usuario!=$id_usuarioA && $id_sucursal==$id_sucursalA){
	$table = 'usuario';
	$where_clause = "id_usuario='" . $id_usuario . "'and id_surcursal='".$id_sucursal."'";
	$delete = _delete ( $table, $where_clause );
	if ($delete) {
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Usuario eliminado correctamente!';
	} else {
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Usuario no pudo ser eliminado';
	}

}
else {
	$xdatos ['typeinfo'] = 'Error';
	$xdatos ['msg'] = 'Usuario no pudo ser eliminado';
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
