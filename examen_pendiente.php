<?php
	include ("_core.php");
	// Page setup
function initial()
	{
	$_PAGE = array ();
	$title = 'Examen Pendiente Realizar';
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
						<h3 class="text-navy"><i class="fa fa-exclamation-circle fa-1x"></i><b> <?php echo $title;?></b></h3>
					</header>
					<div class="row">
								<div class="col-lg-4">
									<label>Fecha</label>
									<input type="text" placeholder="Fecha Inicial" class="datepicker form-control p" id="fecha" name="fecha" value="<?php echo $fecha_hoy ?>" readonly>
								</div>
								<div class="col-lg-8"><br>
									<a id='cargar' name='cargar' class='btn btn-primary m-t-n-xs ' style="margin-top: 0.6%;"><i class="fa fa-spinner"></i> Cargar Todo</a>
								</div>
					</div><br><br>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable2">
							<thead>
								<tr>
									<th class="col-lg-1">ID</th>
									<th class="col-lg-2">FECHA COBRO</th>
									<th class="col-lg-3">PACIENTE</th>
									<th class="col-lg-2">FECHA E</th>
									<th class="col-lg-2">HORA E</th>
									<th class="col-lg-2">ACCI&Oacute;N</th>
								</tr>
							</thead>

						</table>
						<input type='hidden' name='urlprocess' id='urlprocess'value="examen_pendiente.php">
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
	echo "<script src='js/funciones/funciones_examen_paciente.js'></script>";
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
