<?php
include ("_core.php");

function initial(){
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id_sucursal=$_SESSION['id_sucursal'];
	date_default_timezone_set('America/El_Salvador');
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script

	//include ('facturacion_funcion_imprimir.php');
	//$sql="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'");
	$cuenta = _num_rows($sql_apertura);
	$row_apertura = _fetch_array($sql_apertura);
	$id_apertura = $row_apertura["id_apertura"];
	$empleado = $row_apertura["id_empleado"];
	$turno = $row_apertura["turno"];
	$fecha_apertura = $row_apertura["fecha"];
	$hora_apertura = $row_apertura["hora"];
	$monto_apertura = $row_apertura["monto_apertura"];
	$caja = $row_apertura["caja"];

	$hora_actual = date('H:i:s');
	if($cuenta > 0)
	{
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Costos</h4>
</div>
<div class="modal-body">
	<!--div class="wrapper wrapper-content  animated fadeInRight"-->
	<div class="row" id="row1">
		<!--div class="col-lg-12"-->
		<?php

		?>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group has-info single-line">
					<label>Tipo Documento </label>
					<select class="form-control" name="tipo_doc" id="tipo_doc">
						<option value="CCF">Credito Fiscal</option>
						<option value="COF">Factura</option>
						<option value="RE">Recibo</option>
						<option value="VAL">Vale</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="has-info single-line">
					<label>Numero de Documento</label>
					<input type="text" name="n_doc" id="n_doc" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
					<div class="col-md-6">
	          <div class="form-group has-info single-line">
	          	<label>Concepto</label>
	          	<input type='text'  class='form-control' id='concepto' name='concepto'>
	          </div>
					</div>
					<div class="col-md-6">
	          <div class="form-group has-info single-line">
	          	<label>Proveedor/Otro </label> <input type='text'  class='form-control' id='proveedor' name='proproveedor'>
	          </div>
					</div>
    	</div>
    	<div class="row">
    			<div class="col-md-6">
	          <div class="form-group has-info single-line">
	          	<label>Monto </label> <input type='text'  class='form-control numeric' id='monto' name='monto'>
	          </div>
					</div>
					<div class="col-md-6">
	          <div class="form-group has-info single-line">
	          	<label>Recibe </label> <input type='text'  class='form-control' id='recibe' name='recibe'>
	          </div>
					</div>
    	</div>


			<div class="row">

    	</div>
	</div>
		<!--/div-->
		<!--/div-->
	<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $empleado;?>">
	<input type="hidden" name="caja" id="caja" value="<?php echo $caja;?>">
	<input type="hidden" name="turno" id="turno" value="<?php echo $turno;?>">
	<input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnSalida">Guardar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
	$(".numeric").numeric(
		{
			negative:false,
		}
	);
	$(document).ready(function() {
  $("#proveedor").typeahead({
		source: function(query, process) {
			$.ajax({
				type: 'POST',
				url: 'autocomplete_proveedor.php',
				data: 'query=' + query,
				dataType: 'JSON',
				async: true,
				success: function(data) {
					process(data);
				}
			});
		},
	});
});
</script><!--/modal-footer -->

<?php


}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}

function salida()
{
	date_default_timezone_set("America/El_Salvador");
	$id_empleado = $_POST["id_empleado"];
	$id_apertura = $_POST["id_apertura"];
	$caja = $_POST["caja"];
	$turno = $_POST["turno"];
	$concepto = $_POST["concepto"];
	$monto = $_POST["monto"];
	$id_sucursal=$_SESSION['id_sucursal'];
	$proveedor = $_POST["proveedor"];
	$tipo_doc = $_POST["tipo_doc"];
	$n_doc = $_POST["n_doc"];
	$recibe = $_POST["recibe"];
	$autoriza = "Lic. Silvia de Melendez";

	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$iva = 0;
	if($tipo_doc == "CCF")
	{
		$iva = round($monto - ($monto / 1.13), 2);
	}
	//agregar correlativo agregar vale
	$sql_num = _query("SELECT av FROM correlativo WHERE id_sucursal='$id_sucursal'");
	$datos_num = _fetch_array($sql_num);
	$ult = $datos_num["av"]+1;
	$len_ult = strlen($ult);
	$cantidad_ceros = 7-$len_ult;
	$numero_doc=ceros_izquierda($cantidad_ceros,$ult).'_AV';
	/*actualizar los correlativos de II*/
	$corr=1;
	$table="correlativo";
	$form_data = array(
		'av' =>$ult
	);
	$where_clause_c="id_sucursal='".$id_sucursal."'";
	$up_corr=_update($table,$form_data,$where_clause_c);
	if ($up_corr) {
		# code...
	}
	else {
		$corr=0;
	}

	$tabla = "mov_caja";
	$form_data = array(
		'fecha' => $fecha,
		'hora' => $hora,
		'valor' => $monto,
		'concepto' => $concepto,
		'id_empleado' => $id_empleado,
		'id_sucursal' => $id_sucursal,
		'salida' => 1,
		'turno' => $turno,
		'id_apertura' => $id_apertura,
		'nombre_proveedor' => $proveedor,
		'nombre_autoriza' => $autoriza,
		'tipo_doc' => $tipo_doc,
		'numero_doc' => $n_doc,
		'iva' => $iva,
		'nombre_recibe' => $recibe,
		'correlativo' => $ult,
		'caja' =>$caja,
		);
	$insetar = _insert($tabla, $form_data);
	$id_mov= _insert_id();
	if($insetar)
	{
		$mont=_query("SELECT monto_ch_actual FROM apertura_caja WHERE id_apertura='$id_apertura'");
		$monto_a=_fetch_array($mont);
		$monto_ap=$monto_a["monto_ch_actual"];
		$resta=$monto_ap-$monto;
		$tabla_aper = "apertura_caja";
		$form_aper = array(
			'monto_ch_actual' => $resta,
			);
			$where_m = "id_apertura='".$id_apertura."'";
	    $update_apertura = _update($tabla_aper, $form_aper, $where_m);
		if($update_apertura){
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Vale agregado correctamente !';
			$xdatos['process']='insert';
			$xdatos['id_mov']=$id_mov;
		}

	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Error al realizar el vale !'._error();
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
			case 'salida' :
				salida();
				break;
		}
	}
}

?>
