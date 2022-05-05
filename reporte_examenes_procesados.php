<?php
	include ("_core.php");
	// Page setup
function initial()
	{
	$_PAGE = array ();
	$title = 'Reporte de Examenes Procesados';
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
	$fecha1=date("Y-m-d");
	$fecha2=date('Y-m-d', strtotime('-1 year'));

	//permiso del script
	if ($links!='NOT' || $admin=='1' ){

?>

<div class="row wrapper border-bottom white-bg page-heading">
			 <div class="col-lg-2">
			 </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
	 <div class="row">
			 <div class="col-lg-12">
					 <div class="ibox ">
							 <div class="ibox-title">
									 <h3 class="text-navy"><b><i class="fa fa-files-o fa-1x"></i> <?php echo $title;?></b></h3>
							 </div>
							 <div class="ibox-content">
									 <div class="row">
										 <div class="col-md-6">
											 <div class="form-group has-info single-line">
												 <label>Fecha Inicio</label>
												 <input type="text" class="form-control datepicker" id="fecha1" name="fecha1" value="<?php echo $fecha2; ?>">
											 </div>
										 </div>
										 <div class="col-md-6">
											 <div class="form-group has-info single-line">
												 <label>Fecha Fin</label>
												 <input type="text" class="form-control datepicker" id="fecha2" name="fecha2" value="<?php echo $fecha1; ?>">
											 </div>
										 </div>
									 </div>
									 <input type="hidden" name="process" id="process" value="edit">
									 <div class="row">
										 <div class="col-md-12">
											 <div class="form-group">
												 <input type="hidden" name="id_sucursal" id="id_sucursal">
												 <input type="submit" id="xls" name="xls" value="EXCEL" class="btn btn-primary m-t-n-xs pull-right" />
												 <span class="pull-right">&nbsp</span>
												 <input type="submit" id="submit1" name="submit1" value="Imprimir" class="btn btn-primary m-t-n-xs pull-right" />
											 </div>
										 </div>
									 </div>
							 </div>
					 </div>
			 </div>
	 </div>

</div>

<?php
	include("footer.php");
	echo "<script src='js/funciones/funciones_reporte_examenes_procesados.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
if (!isset($_REQUEST['process']))
 {
      initial();
 }


?>
