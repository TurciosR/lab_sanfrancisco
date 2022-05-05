<?php
	include ("_core.php");
	// Page setup
function initial()
	{
	$_PAGE = array ();
	$title = 'Reporte de Insumos Utilizados';
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	date_default_timezone_set('America/El_Salvador');
	$fecha_hoy=date("Y-m-d");
		//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	//permiso del script
	if ($links!='NOT' || $admin=='1' ){

?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">

				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><b><i class="fa fa-files-o fa-1x"></i> <?php echo $title;?></b></h3>
					</header>
					<div class="row">
						<div class="form-group col-lg-4">
							<label>Desde</label>
							<input type="text" placeholder="Fecha Inicial" class="datepicker form-control p" id="fecha1" name="fecha1" value="<?php echo $fecha_hoy ?>" readonly>
						</div>
						<div class="form-group col-lg-4">
							<label>Hasta</label>
							<input type="text" placeholder="Fecha Final" class="datepicker form-control p" id="fecha2" name="fecha2" value="<?php echo $fecha_hoy ?>" readonly>
						</div>
						<div class="form-group col-lg-4">
							<!--<a class="pull-right" type="button" href="reporte_insumos2.php" id="enviar"><i class="fa fa-print fa"></i> Imprimir</a>-->
							<br>
							<a  class='btn btn-primary pull-right' role='button' id="enviar"><i class='fa fa-print fa'></i> Imprimir</a>
						</div>

					</div>
					<section>
						<table class="table table-striped table-bordered table-hover" >
							<thead>
								<tr>
									<th class="col-lg-1">ID</th>
									<th class="col-lg-2">PRODUCTO</th>
									<th class="col-lg-2">PRESENTACIÓN</th>
									<th class="col-lg-2">DESCRIPCIÓN</th>
									<th class="col-lg-1">CANTIDAD</th>
								</tr>
							</thead>
							<tbody id="traer">

							</tbody>

						</table>
						<input type='hidden' name='urlprocess' id='urlprocess'value="examen_pendiente_imprimir.php">
						 <input type="hidden" name="autosave" id="autosave" value="false-0">
					</section>
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<!--ver detalle -->
					<div class='modal fade' id='verModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
<?php
	include("footer.php");
	echo "<script src='js/funciones/funciones_reporte_insumo.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}/*
function impreso()
{
	$id_examen_paciente = $_POST ['id_examen_paciente'];
	date_default_timezone_set('America/El_Salvador');
  $fecha_impresion=date("Y-m-d");
  $hora_impresion=date("H:i:s");
	$estado_impresion="Hecho";

	$table = 'examen_paciente';
	$form_data = array (
	'fecha_impresion' => $fecha_impresion,
	'hora_impresion' => $hora_impresion,
	'estado_impresion' => $estado_impresion
	);
	$where_clause = "id_examen_paciente ='".$id_examen_paciente."'";
	$insertar = _update($table,$form_data, $where_clause);
	if($insertar)
	{
		 $xdatos['typeinfo']='Success';
		 $xdatos['msg']='Examen paciente impreso correctamente!';

	}
	else
	{
		 $xdatos['typeinfo']='Error';
		 $xdatos['msg']='Examen paciente no pudo ser impreso!';
}
	echo json_encode ( $xdatos );
}*/

function traer(){
	$sum=0;
	$desde=$_POST["desde"];
	$hasta=$_POST["hasta"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$query_user = _query("SELECT pr.descripcion, pr.id_producto,pre.nombre,pp.descripcion as descrip,SUM(ie.cantidad) as suma  FROM  examen_paciente as ep
	inner JOIN examen as e ON (ep.id_examen=e.id_examen )
	inner JOIN insumo_examen as ie ON (ep.id_examen=ie.id_examen)
	inner JOIN producto as pr ON (ie.id_producto=pr.id_producto )
	inner JOIN presentacion as pre ON (ie.id_presentacion=pre.id_presentacion)
	inner JOIN presentacion_producto as pp ON (pre.id_presentacion=pp.id_presentacion )
	Where ep.id_examen>0  AND ep.examen_paciente_nulo= 0 AND ep.fecha_realizado  between '$desde' and '$hasta' and ep.id_sucursal='$id_sucursal'	and
	 pr.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal' and pp.id_sucursal='$id_sucursal'  and ie.id_sucursal='$id_sucursal' group by pr.id_producto");
	$num=1;
  while($datos_user = _fetch_array($query_user))
	{
	//	$sum+=$datos_user["cantidad"];
		?>
		<tr >
			<td > <?php echo $num  ?></td>
			<td ><?php echo $datos_user["descripcion"]  ?></td>
			<td ><?php echo $datos_user["nombre"]  ?></td>
			<td ><?php echo $datos_user["descrip"]  ?></td>
			<td > <?php echo $datos_user["suma"]  ?></td>
		</tr>

		<?php
		$num++;
	}



}

if (!isset($_REQUEST['process']))
 {
      initial();
 }
 if (isset($_REQUEST['process'])) {
			 switch ($_REQUEST['process']) {
				 case 'traerdatos':
				 traer();
				 break;
			 }
		 }


?>
