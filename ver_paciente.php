<?php
include ("_core.php");
function initial(){
	$id_paciente = $_REQUEST ['id_paciente'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT *FROM paciente WHERE id_paciente='$id_paciente' AND id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "ver_paciente.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy">Detalles de Paciente</h3>
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
							<th>NOMBRE</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td class='col-lg-4'>ID</td><td class='col-lg-8'>$id_paciente</td></tr>";
										echo "<tr><td>NOMBRES</td><td>".$row['nombre']."</td>";
										echo "<tr><td>APELLIDOS</td><td>".$row['apellido']."</td>";
										echo "<tr><td>DIRECCI&oacuteN</td><td>".$row['direccion']."</td>";
										echo "<tr><td>TELEFONO</td><td>".$row['telefono']."</td>";
										echo "<tr><td>WHATSAP</td><td>".$row['telefono_whatsapp']."</td>";
										echo "<tr><td>G&eacute;nero</td><td>".$row['sexo']."</td>";
										echo "<tr><td>DUI</td><td>".$row['dui']."</td>";
										echo "<tr><td>EDAD</td><td>".edad($row['fecha_nacimiento'])."</td>";
										echo "<tr><td>CORREO</td><td>".$row['correo']."</td>";
										echo "</tr>";

									}
								}
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
	$id_producto = $_POST ['id_producto'];
	if (isset($id_producto)) {
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
