<?php
include ("_core.php");
function initial(){
	$id_doctor = $_REQUEST ['id_doctor'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT doc.*, es.nombre as nom FROM doctor as doc, especialidades as es WHERE es.id_especialidades=doc.especialidad and doc.id_doctor='$id_doctor'and doc.id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "ver_doctor.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy">Detalle del doctor</h3>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">
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
							<th>DESCRIPCI&Oacute;N</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td class='col-lg-4'>ID</td><td class='col-lg-8'>$id_doctor</td></tr>";
										echo "<tr><td>NOMBRE</td><td>".$row['nombre']."</td>";
										echo "<tr><td>APELLIDO</td><td>".$row['apellido']."</td>";
										echo "<tr><td>DIRECCI&Oacute;N</td><td>".$row['direccion']."</td>";
										echo "<tr><td>ESPECIALIDAD</td><td>".$row['nom']."</td>";
										echo "<tr><td>COMISI&Oacute;N</td><td>".$row['comision']."</td>";
										echo "<tr><td>TEL&Eacute;FONO</td><td>".$row['telefono']."</td>";
										echo "<tr><td>CORREO ELECTR&Oacute;NICO</td><td>".$row['email']."</td>";
										echo "<tr><td>NOMBRE CONSULTORIO</td><td>".$row['nombre_consultorio']."</td>";
										echo "</tr>";

									}
								}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_doctor' id='id_doctor' value='$id_doctor'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function ver()
{
	$id_empleado = $_POST ['id_empleado'];
	if (isset($id_empleado)) {
		$xdatos ['typeinfo'] = 'Success';
		} else {
		$xdatos ['typeinfo'] = 'Error';
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
			case 'formVer' :
				initial();
				break;
			case 'ver' :
				ver();
				break;
		}
	}
}

?>
