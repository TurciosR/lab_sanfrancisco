<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Expediente';
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
	include_once "header.php";
	include_once "main_menu.php";

	$id_sucursal=$_SESSION["id_sucursal"];
 	$sql="SELECT expediente.n_expediente, expediente.fecha_creada,
				expediente.ultima_visita, paciente.*,expediente.id_expediente
				FROM expediente
				JOIN paciente ON(expediente.id_paciente=paciente.id_paciente)
				WHERE paciente.id_sucursal='$id_sucursal'";
	$result=_query($sql);
	$count=_num_rows($result);
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
				<?php
				//if ($admin=='t' && $active=='t'){
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><b><i class="fa fa-clipboard fa-1x"></i> <?php echo $title?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-lg-1">N°</th>
									<th class="col-lg-1">N° DE EXPEDIENTE</th>
									<th class="col-lg-2">NOMBRES</th>
									<th class="col-lg-2">APELLIDOS</th>
									<th class="col-lg-2">DIRECCI&Oacute;N</th>
									<th class="col-lg-1">REGISTRO</th>
									<th class="col-lg-1">&Uacute;LTIMA VISITA</th>
									<th class="col-lg-1">ACCI&Oacute;N</th>
								</tr>
							</thead>
							<tbody>
					<?php
					$n=1;
 					while($row=_fetch_array($result))
					{
							echo "<tr>";
							echo"<td>".$n."</td>
								<td>".$row['n_expediente']."</td>
								<td>".$row['nombre']."</td>
								<td>".$row['apellido']."</td>
								<td>".$row['direccion']."</td>
								<td>".ED($row['fecha_creada'])."</td>
								<td>".ED($row['ultima_visita'])."</td>";
								$filename='ver_expediente.php';
								$link=permission_usr($id_user,$filename);
								if ($link!='NOT' || $admin=='1' ){
									echo "<td>.<a class='btn btn-primary' href='ver_expediente.php?id_paciente=".$row['id_paciente']."'><i class='fa fa-book'></i> Ver Expediente</a></li>.</td>";
								}
								echo "</tr>";
								$n++;
						}
				?>
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
							<div class='modal-content modal-md'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
<?php
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/funciones_expediente.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
?>
