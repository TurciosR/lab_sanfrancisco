<?php
include ("_core.php");
function initial(){
	$id_detalle = $_REQUEST ['id_detalle'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT mp.*,mpd.*,concat(em.nombre,' ', em.apellido)as nombre, pr.descripcion  FROM movimiento_producto as mp
		JOIN movimiento_producto_detalle as mpd ON mpd.id_movimiento= mp.id_movimiento
    JOIN empleado as em on em.id_empleado=mp.id_empleado
		JOIN producto as pr on pr.id_producto=mpd.id_producto
		WHERE mp.id_movimiento='$id_detalle' and mp.id_sucursal='$id_sucursal' and mp.total>0";
	$result = _query( $sql );
	$count = _num_rows( $result );
	$sql2="SELECT mp.*,mpd.*,concat(em.nombre,' ', em.apellido)as nombre, pr.descripcion  FROM movimiento_producto as mp
		JOIN movimiento_producto_detalle as mpd ON mpd.id_movimiento= mp.id_movimiento
    JOIN empleado as em on em.id_empleado=mp.id_empleado
		JOIN producto as pr on pr.id_producto=mpd.id_producto
		WHERE mp.id_movimiento='$id_detalle' and mp.id_sucursal='$id_sucursal' and mp.total>0";
	$result2 = _query( $sql2 );
	$row2 = _fetch_array ( $result2);

	$nombre=$row2["nombre"];
	$fecha=$row2["fecha"];
  //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "ver_detalle_pago.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Detalle de Descargo</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-lg-12">
					<h4  class='text-navy'>Realizado por:  <?php echo $nombre;?> <p class="pull-right">Fecha:<?php echo ED($fecha); ?></p></h4>
				<table class="table table-condensed table-striped" id="inventable">
					<thead class="thead-inverse">
						<tr>
						<th class='info'>N°</th>
							<th class='info'>PRODUCTO </th>
							<th class='info'>CANTIDAD</th>
							<th class='info'>COSTO</th>
							<th class='info'>SUBTOTAL</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									$num=1;
									for($i = 0; $i < $count; $i ++) {

										$row = _fetch_array ( $result);
										$sub=$row['cantidad']*$row['costo'];
										echo "<tr><td>".$num."</td><td>".$row['descripcion']."</td><td>".$row['cantidad']."</td> <td>".$row['costo']."</td><td>".$sub."</td></tr>";
										$num+=1;
									}
								}
							?>
						</tbody>
						<tfoot>
							<tr>
							<td class="thick-line"></td>
							<td class="thick-line"></td>
							<td class="thick-line"></td>
							<td class="thick-line text-center"><strong>TOTAL $:</strong></td>
							<td  class="thick-line" id='total_dinero' ><strong><?php echo $row2["total"]; ?></strong></td>
							</tr>
						</tfoot>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_detalle' id='id_detalle' value='$id_detalle'>";
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
	$id_detalle = $_POST ['id_detalle'];
	if (isset($id_detalle)) {
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
