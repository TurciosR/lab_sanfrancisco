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
	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND id_empleado = '$id_user'");
	$cuenta = _num_rows($sql_apertura);
	$row_apertura = _fetch_array($sql_apertura);
	$id_apertura = $row_apertura["id_apertura"];
	$empleado = $row_apertura["id_empleado"];
	$turno = $row_apertura["turno"];
	$fecha_apertura = $row_apertura["fecha"];
	$hora_apertura = $row_apertura["hora"];
	$monto_apertura = $row_apertura["monto_apertura"];

	$hora_actual = date('H:i:s');
	if($cuenta > 0)
	{
	$id_movimiento = $_REQUEST["id_movimiento"];
	$sql_movimiento = _query("SELECT * FROM mov_caja WHERE id_movimiento = '$id_movimiento'");
	$rr = _fetch_array($sql_movimiento);
	$entrada = $rr["entrada"];
	$salida = $rr["salida"];
	$viatico = $rr["viatico"];
	$concepto = $rr["concepto"];
	$monto = $rr["valor"];
	$tipo_delige = $rr["tipo_delige"];
	$nombre_p = $rr["nombre_proveedor"];
	$numero_doc = $rr["numero_doc"];
  $n_empleado = $rr["n_empleado"];
	$detalle = "";
	if($entrada == 1 && $salida == 0 && $viatico == 0)
	{
		$detalle = "Entrada";
		$alert = "alert-success";
	}
	else if($salida == 1 && $entrada == 0 && $viatico == 0)
	{
		$detalle = "Salida";
		$alert = "alert-warning";
	}
	else if($salida == 0 && $entrada == 0 && $viatico == 1)
	{
		$detalle = "Viatico";
		$alert = "alert-warning";
	}
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Editar Movimiento</h4>
</div>
<div class="modal-body">
	<!--div class="wrapper wrapper-content  animated fadeInRight"-->
	<div class="row" id="row1">
		<!--div class="col-lg-12"-->
		<?php
					//permiso del script
			if ($links!='NOT' || $admin=='1' ){
		?>
    <div class="row">
      <div class="col-md-12">
        <div class="has-info single-line">
          <label>Empleado</label>
          <input type="text" name="n_empleado" id="n_empleado" class="form-control" value="<?php echo $n_empleado;?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group has-info single-line">
          <label>Tipo Diligencia</label>
          <select class="form-control" name="tipo_deli" id="tipo_deli">
            <option <?php if($tipo_delige=="pedido") echo 'selected' ?> value="pedido">Pedido</option>
            <option <?php if($tipo_delige=="cobro") echo 'selected' ?>  value="cobro">Cobro</option>
            <option <?php if($tipo_delige=="otro") echo 'selected' ?>  value="otro">Otro</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="has-info single-line">
          <label>Numero de Documen</label>
          <input type="text" name="n_doc" id="n_doc" class="form-control" value="<?php echo $numero_doc;?>">
        </div>
      </div>
    </div>
    <div class="row">
          <div class="col-md-6">
            <div class="form-group has-info single-line">
              <label>Concepto</label>
              <input type='text'  class='form-control' id='concepto' name='concepto' value="<?php echo $concepto ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group has-info single-line">
              <label>Monto </label> <input type='text'  class='form-control numeric' id='monto' name='monto' value="<?php echo $monto; ?>">
            </div>
          </div>
    </div>
      <div class="row">
          <div class="col-md-12">
              <div class="form-group has-info single-line">
              <label>Detalle </label>
              <textarea name="detalle" id="detalle" rows="7" cols="50"><?php echo $detalle; ?></textarea>
            </div>
          </div>
      </div>

	</div>
	<input type="hidden" name="id_movimiento" id="id_movimiento" value="<?php echo $id_movimiento;?>">
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnEditar_v">Guardar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->

<?php

} //permiso del script
	else
	{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}

function editar()
{
	date_default_timezone_set("America/El_Salvador");
	$id_movimiento = $_POST["id_movimiento"];
	$n_empleado = $_POST["n_empleado"];
	$concepto = $_POST["concepto"];
	$monto = $_POST["monto"];
	$tipo_deli = $_POST["tipo_deli"];
	$n_doc = $_POST["n_doc"];
	$tabla = "mov_caja";
	$form_data = array(
		'valor' => $monto,
		'concepto' => $concepto,
		'tipo_delige' => $tipo_deli,
		'numero_doc' => $n_doc,
		'nombre_recibe' =>$n_empleado,
		);
	$where_mov = "id_movimiento='".$id_movimiento."'";
	$update = _update($tabla, $form_data, $where_mov);
	if($update)
	{
		$xdatos['typeinfo']='Success';
		$xdatos['msg']='Movimiento editado correctamente !';
		$xdatos['process']='insert';
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Error al editar el movimiento !'._error();
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
			case 'editar' :
				editar();
				break;
		}
	}
}

?>
