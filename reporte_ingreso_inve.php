<?php
	include ("_core.php");
	// Page setup
function initial()
	{
	$_PAGE = array ();
	$title = 'Reporte de Ingresos de Inventario Realizados';
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
						<h3><b><?php echo $title;?></b></h3>
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
									<th class="col-lg-2">CONCEPTO</th>
									<th class="col-lg-2">TIPO DE DESCARGO</th>
									<th class="col-lg-2">CANTIDAD</th>
									<th class="col-lg-1">FECHA</th>
								</tr>
							</thead>
							<tbody id="traer">

							</tbody>

						</table>
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
	echo "<script src='js/funciones/funciones_reporte_ingreso_inve.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}

function traer(){
	$sum=0;
	$desde=$_POST["desde"];
	$hasta=$_POST["hasta"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$query_user = _query("SELECT  mp.concepto, mpd.cantidad, mp.fecha ,mp.tipo FROM  movimiento_producto as mp
	inner JOIN movimiento_producto_detalle as mpd ON (mp.id_movimiento=mpd.id_movimiento)
	Where mp.id_movimiento>0  AND mp.id_sucursal='$id_sucursal' AND mp.tipo='ENTRADA' AND mp.fecha between '$desde' and '$hasta'");
	$num=1;
  while($datos_user = _fetch_array($query_user))
	{
	//	$sum+=$datos_user["cantidad"];
		?>
		<tr >
			<td > <?php echo $num  ?></td>
			<td ><?php echo $datos_user["concepto"]  ?></td>
			<td ><?php echo $datos_user["tipo"]  ?></td>
			<td ><?php echo $datos_user["cantidad"]  ?></td>
			<td > <?php echo $datos_user["fecha"]  ?></td>
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
