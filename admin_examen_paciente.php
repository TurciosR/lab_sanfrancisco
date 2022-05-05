<?php
	include ("_core.php");
	// Page setup
function initial()
	{
	$_PAGE = array ();
	$title = 'Admin Examen Paciente';
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
	$fechaanterior=restar_dias($fecha_hoy,30);
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
						<h3 class="text-navy"><b><i class="fa fa-pencil-square-o"></i> <?php echo $title;?></b></h3>
					</header>
					<div class="row">
								<div class="col-lg-4">
									<label>Fecha Inicio</label>
									<input type="text" placeholder="Fecha Inicial" class="datepicker form-control p" id="fecha_inicial" name="fecha_inicial" value="<?php echo $fechaanterior; ?>">
								</div>
								<div class="col-lg-4">
									<label>Fecha Fin</label>
									<input type="text" placeholder="Fecha final" class="datepicker form-control p" id="fecha_final" name="fecha_final" value="<?php echo$fecha_hoy; ?>" >
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div><label>Buscar Examen</label> </div>
										<button type="button" id="btnMostrar" name="btnMostrar" class="btn btn-primary"><i class="fa fa-check"></i> Mostrar examen</button>
									</div>
        			</div>
					</div><br><br>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable4">
							<thead>
								<tr>
									<th class="col-lg-1">ID</th>
									<th class="col-lg-3">PACIENTE</th>
									<th class="col-lg-2">EXAMEN</th>
									<th class="col-lg-2">FECHA EXAMEN</th>
									<th class="col-lg-1">ESTADO</th>
									<th class="col-lg-1">ENVIADO</th>
									<th class="col-lg-1">ACCI&Oacute;N</th>
								</tr>
							</thead>

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
	echo "<script src='js/funciones/funciones_examen_paciente.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function impreso()
{
	$id_examen_paciente = $_POST ['id_examen_paciente'];
	$id_sucursal=$_SESSION["id_sucursal"];
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
	$where_clause = "id_examen_paciente ='".$id_examen_paciente."' and id_sucursal='$id_sucursal'";
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
}

if (!isset($_REQUEST['process']))
 {
      initial();
 }
 if (isset($_REQUEST['process'])) {
			 switch ($_REQUEST['process']) {
				 case 'impreso':
				 impreso();
				 break;
			 }
		 }


?>
