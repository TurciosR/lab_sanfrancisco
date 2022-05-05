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
	$sql_empleado = _query("SELECT nombre FROM usuario WHERE  id_usuario = '$empleado'");
	$nombre= _fetch_array($sql_empleado);
	$nombre_empleado=$nombre["nombre"];
	$hora_actual = date('H:i:s');
	if($cuenta > 0)
	{
		if($id_user==$empleado || $admin=='1' ){
?>
<script>
$('#valor').numeric({
	negative: false,
	decimalPlaces: 4
});</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Agregar Viáticos</h4>
</div>
<div class="modal-body">
	<!--div class="wrapper wrapper-content  animated fadeInRight"-->
	<div class="row" id="row1">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group has-info single-line">
					<label>Tipo Diligencia</label>
					<select class="form-control" name="tipo_deli" id="tipo_deli">
						<option value="pedido">Pedido</option>
						<option value="cobro">Cobro</option>
						<option value="otro">Otro</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group has-info single-line">
					<label>Concepto</label>
					<input type='text'  class='form-control' id='concepto' name='concepto'>
				</div>
			</div>
		</div>
		<div class="row">
					<div class="col-md-6">
						<div class="form-group has-info single-line">
							<label>Monto </label>
							<input type='text'  class='form-control numeric' id='monto' name='monto'>
						</div>
					</div>
					<div class="col-md-6">
						<div class="has-info single-line">
							<label>Recibe</label>
							<input type="text" name="n_empleado" id="n_empleado" class="form-control">
						</div>
					</div>
		</div>
    	<div class="row">
    			<div class="col-md-4">
							<div class="form-group has-info single-line">
	          	<label>Naturaleza de gasto</label>
							<input id="natu" name="natu" class="form-control clear">
						</div>
					</div>
					<div class="col-md-3">
							<div class="form-group has-info single-line">
							<label>Detalle</label>
							<input id="detalle" name="detalle" class="form-control clear">
						</div>
					</div>
					<div class="col-md-3">
							<div class="form-group has-info single-line">
							<label>Valor </label>
							<input id="valor" name="valor" class="form-control clear">
						</div>
					</div>
					<div class="col-md-2">
							<div class="form-group">
								<br>
							<button  type="button" class="btn btn-primary" id="add_pre">Agregar</button>
						</div>
					</div>
    	</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th class="col-md-4">Naturaleza de Gasto</th>
								<th class="col-sm-4">Detalle</th>
								<th class="col-md-3">Valor</th>
								<th class="col-md-1">Acción</th>
							</tr>
						</thead>
						<tbody id="presentacion_table">

						</tbody>
					</table>
				</div>
			</div>
		<!--/div-->
		<!--/div-->
	<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $empleado;?>">
	<input type="hidden" name="caja" id="caja" value="<?php echo $caja;?>">
	<input type="hidden" name="turno" id="turno" value="<?php echo $turno;?>">
	<input type="hidden" name="process" id="process" value="insert">
	<input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnViatico">Guardar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
	$(".numeric").numeric(
		{
			negative:false,
		}
	);

$(document).ready(function(){



});
</script>
<!--/modal-footer -->

<?php
}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'> Ya existe una apertura de caja realizada por '".$nombre_empleado."' Debe de realizar el corte para poder iniciar una nueva apertura de caja. .</div>";
}
}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}

function viatico()
{
	date_default_timezone_set("America/El_Salvador");
	$id_empleado = $_POST["id_empleado"];
	$recibe = $_POST["recibe"];
	$caja=$_POST["caja"];
	$id_apertura = $_POST["id_apertura"];
	$turno = $_POST["turno"];
	$concepto = $_POST["concepto"];
	$monto = $_POST["monto"];
	$id_sucursal=$_SESSION['id_sucursal'];
	$tipo_deli = $_POST["tipo_deli"];
	$lista = $_POST["lista"];
	$cuantos = $_POST["cuantos"];
	$autoriza = "Lic. Silvia de Melendez";

	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	//$iva = 0;
	//agregar correlativo agregar vale
	$sql_num = _query("SELECT avi FROM correlativo WHERE id_sucursal='$id_sucursal'");
	$datos_num = _fetch_array($sql_num);
	$ult = $datos_num["avi"]+1;
	$len_ult = strlen($ult);
	$cantidad_ceros = 7-$len_ult;
	$numero_doc=ceros_izquierda($cantidad_ceros,$ult).'_AVI';
	/*actualizar los correlativos de II*/
	$corr=1;
	_begin();
	$table="correlativo";
	$form_data = array(
		'avi' =>$ult,
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
		'viatico' => 1,
		'turno' => $turno,
		'id_apertura' => $id_apertura,
		'nombre_autoriza' => $autoriza,
		'tipo_delige' => $tipo_deli,
		'nombre_recibe'=> $recibe,
		'correlativo'=>$numero_doc,
		'caja' =>$caja,
		);
	echo _error();
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
			$lista = explode("|", $lista);
			$n = 0;
			for ($i=0; $i < $cuantos ; $i++)
			{
				list($natu,$detalle,$valor)=explode(',',$lista[$i]);
	        $tablee='mov_caja_detalle';
	        $form_data_detalle = array(
	          'natu_gasto' => $natu,
	          'detalle' => $detalle,
	          'valor' => $valor,
	          'id_mov_caja' => $id_mov,
	        );
	        $insert_detalle = _insert($tablee,$form_data_detalle);
					if($insert_detalle){
						$n++;
					}
			}
			if($n == $cuantos)
			{
				$xdatos['typeinfo']='Success';
				$xdatos['msg']='Registro ingresado con exito!';
				$xdatos['process']='insert';
				$xdatos['id_mov']=$id_mov;
				_commit();
			}
			else
			{
				_rollback();
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='Registro no pudo ser ingresado !';
				$xdatos['process']='insert';
			}
		}
	}
	else
	{	_rollback();
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
			case 'insert' :
				viatico();
				break;
		}
	}
}

?>
