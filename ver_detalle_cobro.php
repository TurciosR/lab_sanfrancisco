<?php
include ("_core.php");
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial(){
	$id_detalle_cobro = $_REQUEST ['id_detalle_cobro'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT CONCAT(paciente.nombre,' ',paciente.apellido) as nombre,cobro.fecha, cobro.total_des,cobro.total, detalle_cobro.detalles ,
	detalle_cobro.val_descuento , detalle_cobro.subtotal
	FROM cobro JOIN detalle_cobro ON cobro.id_cobro=detalle_cobro.id_cobro
	JOIN paciente on cobro.id_paciente=paciente.id_paciente WHERE cobro.id_cobro='$id_detalle_cobro'and cobro.id_sucursal='$id_sucursal'
	and	detalle_cobro.id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$count = _num_rows( $result );
	$sql2="SELECT CONCAT(paciente.nombre,' ',paciente.apellido) as nombre,cobro.fecha, cobro.total_des,cobro.total, detalle_cobro.detalles ,
	detalle_cobro.val_descuento , detalle_cobro.subtotal
	FROM cobro JOIN detalle_cobro ON cobro.id_cobro=detalle_cobro.id_cobro
	JOIN paciente on cobro.id_paciente=paciente.id_paciente WHERE cobro.id_cobro='$id_detalle_cobro'and cobro.id_sucursal='$id_sucursal'
	and	detalle_cobro.id_sucursal='$id_sucursal'";
	$result2 = _query( $sql2 );
	$row2 = _fetch_array ( $result2);

	$nombre=$row2["nombre"];
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
	<button type="button" class="btn btn-primary" id="btnPrint">Imprimir</button>
	<button type="button" id="Cerrar" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function imprimir_fact() {
	$id_factura = $_REQUEST['id_factura'];
	$numero_doc_print =0; //$_REQUEST['num_fact_print'];
	$sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
			$fecha_movimiento=$row_fact['fecha'];
			$total_venta=$row_fact['total'];
			$hora=$row_fact['hora_cobro'];
			$table_fact= 'cobro';

	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';

	if($nrows_fact>0){
		$id_cliente=$row_fact['cliente'];
		$fecha=$row_fact['fecha'];
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
    $tipo_impresion =$row_fact['tipo_doc'];

		$sql="SELECT * FROM cliente
		WHERE
		id_cliente='$id_cliente'";

		$result=_query($sql);
		$count=_num_rows($result);
		if ($count > 0) {
			for($i = 0; $i < $count; $i ++) {
				$row = _fetch_array ( $result);
				$id_cliente=$row["id_cliente"];
				$nombre=$row["nombre"];
				$direccion=$row["direccion"];
				$nit=$row["nit"];
				$dui=$row["dui"];
				$nrc=$row["nrc"];
				$nombreape=$nombre;
			}
		}

		if ($tipo_impresion=='COF'){
			//$info_facturas=print_fact($id_factura,$tipo_impresion,$nombreape,$direccion);
			$info_facturas=print_fact($id_factura, $tipo_impresion, $nit, $nrc, $nombreape, $direccion);
		}
		if ($tipo_impresion=='CCF'){
				$info_facturas=print_ccf($id_factura, $tipo_impresion, $nit, $nrc, $nombreape, $direccion);
		}
		if ($tipo_impresion=='ENV'){
				$info_facturas=print_fact($id_factura,$tipo_impresion,$nombreape,$direccion);
		}
		if ($tipo_impresion=='DEV'){
				$info_facturas=print_ncr($id_factura,$tipo_impresion,$nombreape,$direccion);
		}
		//directorio de script impresion cliente
		$headers="";
		$footers="";
		if ($tipo_impresion=='TIK' ||$tipo_impresion=='COB') {
			$info_facturas=print_ticket($id_factura, $tipo_impresion);
			$sql_pos="SELECT *  FROM config_pos  WHERE id_sucursal='$id_sucursal' AND alias_tipodoc='TIK'";

			$result_pos=_query($sql_pos);
			$row1=_fetch_array($result_pos);

			$headers=$row1['header1']."|".$row1['header2']."|".$row1['header3']."|".$row1['header4']."|".$row1['header5']."|";
			$headers.=$row1['header6']."|".$row1['header7']."|".$row1['header8']."|".$row1['header9']."|".$row1['header10'];
			$footers=$row1['footer1']."|".$row1['footer2']."|".$row1['footer3']."|".$row1['footer4']."|".$row1['footer5']."|";
			$footers.=$row1['footer6']."|".$row1['footer7']."|".$row1['footer8']."|".$row1['footer8']."|".$row1['footer10']."|";

		}

		$sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
		$result_dir_print=_query($sql_dir_print);
		$row_dir_print=_fetch_array($result_dir_print);
		$dir_print=$row_dir_print['dir_print_script'];
		$shared_printer_win=$row_dir_print['shared_printer_matrix'];
		$shared_printer_pos=$row_dir_print['shared_printer_pos'];
		$nreg_encode['shared_printer_win'] =$shared_printer_win;
		$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
		$nreg_encode['dir_print'] =$dir_print;
		$nreg_encode['tipo_impresion'] =$tipo_impresion;
		$nreg_encode['facturar'] =$info_facturas;
		$nreg_encode['sist_ope'] =$so_cliente;
		$nreg_encode['headers'] =$headers;
		$nreg_encode['footers'] =$footers;

		echo json_encode($nreg_encode);
	}
}


if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
			case 'imprimir_fact' :
				imprimir_fact();
				break;
		}
	}
}

?>
