<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title='Administrar Descuentos';
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	$fin=date("Y-m-d");
	$fini=date("Y")."-".date("m")."-01";
?>

	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<?php
						if ($links!='NOT' || $admin=='1' ){
						echo"
            <div class='ibox-title'>";
						$filename = 'agregar_descuento.php';
						$link=permission_usr($id_user,$filename);
						if ($link!='NOT' || $admin=='1' )
						echo "<a data-toggle='modal' href='agregar_descuento.php' class='btn btn-primary' data-target='#viewModal' data-refresh='true'><i class='fa fa-plus'></i> Agregar Descuento</a>";
            echo "</div>";
            ?>
						<div class="ibox-content">
							<!--load datables estructure html-->
							<header>
								<h4><?php echo $title; ?></h4>
							</header>
							<div class="row">
								<div class="input-group">
									<div class="col-md-3">
										<div class="form-group">
											<label>Fecha Inicio</label>
											<input type="text" placeholder="Fecha Inicio" class="datepick form-control" id="fini" name="fini" value="<?php echo  $fini; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label>Fecha Fin</label>
											<input type="text" placeholder="Fecha Fin" class="datepick form-control" id="fin" name="fin" value="<?php echo $fin; ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<div><label>Buscar</label> </div>
											<button type="button" id="btnMostrar" name="btnMostrar" class="btn btn-primary"><i class="fa fa-check"></i> Mostrar</button>
										</div>
									</div>
								</div>

							</div>
							<section>
								<table class="table table-striped table-bordered table-hover" id="editable2">
									<thead>
										<tr>
											<th class="col-lg-1">Id </th>
											<th class="col-lg-1">Fecha</th>
											<th class="col-lg-1">PIN</th>
											<th class="col-lg-1">Porcen.</th>
											<th class="col-lg-1">Aplicado</th>
											<th class="col-lg-2">Empleado Aplica</th>
											<th class="col-lg-1">Fecha Ap</th>
											<th class="col-lg-1">Tipo Doc</th>
											<th class="col-lg-1">Num Doc</th>
											<th class="col-lg-1">Acci&oacute;n</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</section>
							<!--Show Modal Popups View & Delete -->
							<div class='modal fade' id='viewModal1' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog modal-md'>
									<div class='modal-content modal-md'>

									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
							<!--Show Modal Popups View & Delete -->
							<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog modal-sm'>
									<div class='modal-content modal-sm'>

									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
							<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog modal-sm'>
									<div class='modal-content modal-sm'></div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
						</div>
						<!--div class='ibox-content'-->
				</div>
				<!--<div class='ibox float-e-margins' -->
			</div>
			<!--div class='col-lg-12'-->
		</div>
		<!--div class='row'-->
	</div>
	<!--div class='wrapper wrapper-content  animated fadeInRight'-->
	<?php
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/funciones_descuento.js'></script>";
		} //permiso del script
else {
		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div><div></div><div></div>";
	}

?>
