<?php
include ("_core.php");
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial(){
	$id_detalle_cobro = $_REQUEST ['id_detalle_cobro'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT paciente.apellido,paciente.nombre,cobro.fecha, cobro.total_des,cobro.total, detalle_cobro.detalles , detalle_cobro.val_descuento , detalle_cobro.subtotal FROM cobro JOIN detalle_cobro ON cobro.id_cobro=detalle_cobro.id_cobro JOIN paciente on cobro.id_paciente=paciente.id_paciente WHERE cobro.id_cobro='$id_detalle_cobro'and cobro.id_sucursal='$id_sucursal'and
	detalle_cobro.id_sucursal='$id_sucursal'and paciente.id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$count = _num_rows( $result );
	$sql2="SELECT paciente.apellido,paciente.nombre,cobro.fecha, cobro.total_des,cobro.total, detalle_cobro.detalles , detalle_cobro.val_descuento , detalle_cobro.subtotal FROM cobro JOIN detalle_cobro ON cobro.id_cobro=detalle_cobro.id_cobro JOIN paciente on cobro.id_paciente=paciente.id_paciente WHERE cobro.id_cobro='$id_detalle_cobro'and cobro.id_sucursal='$id_sucursal'and
	detalle_cobro.id_sucursal='$id_sucursal'and paciente.id_sucursal='$id_sucursal'";
	$result2 = _query( $sql2 );
	$row2 = _fetch_array ( $result2);

	$nombre=$row2["nombre"]." ".$row2["apellido"];
  //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "ver_detalle_cobro.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" id="Cerrar"data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-center text-navy">Detalle de Pago</h3>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<header>
				<h4>Cliente:<?php echo $nombre; ?></h4>
			</header>
			<div class="col-lg-12">
				<table class="table table-condensed table-striped" id="inventable">
					<thead class="thead-inverse">
						<tr>
							<th class='success'>Detalle</th>
							<th class='success'>Fecha de Cobro</th>
							<th class='success'>Descuento</th>
							<th class='success'>Subtotal</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result);
										echo "<tr><td>".$row['detalles']."</td><td>".$row['fecha']."</td> <td>".$row['val_descuento']."</td><td>".$row['subtotal']."</td></tr>";

									}
								}
							?>
						</tbody>
						<tfoot>
							<tr>
							<td class="thick-line"></td>
							<td class="thick-line"></td>
							<td class="thick-line text-center"><strong>TOTAL $:</strong></td>
							<td  class="thick-line text-right" id='total_dinero' ><strong><?php echo $row2["total"]; ?></strong></td>
							</tr>
						</tfoot>

				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_detalle_cobro' id='id_detalle_cobro' value='$id_detalle_cobro'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnBorrar">Eliminar</button>
	<button type="button" id="Cerrar" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function Borrar() {
	$id_factura = $_REQUEST['id_factura'];

	$table="cobro";
	$where_clause="id_cobro='".$id_factura."'";
	$update=_delete($table,$where_clause);
	$table1="detalle_cobro";
	$where_clause="id_cobro='".$id_factura."'";
	$update=_delete($table,$where_clause);
	$table2="examen_paciente";
	$where_clause="id_cobro='".$id_factura."'";
	$update=_delete($table,$where_clause);
	$update1=_delete($table1,$where_clause);
	$update2=_delete($table2,$where_clause);
	if ($update) {
		// code...
		$xdatos['typeinfo']="Success";
		$xdatos['msg']="Cobro Eliminado correctamente";
	}
	else {
		$xdatos['typeinfo']="Error";
		$xdatos['msg']="Cobro no pudo ser Eliminar";
	}
	echo json_encode($xdatos);

}


if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
			case 'Eliminar' :
				Borrar();
				break;
		}
	}
}

?>
