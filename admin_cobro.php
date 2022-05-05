<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Cobros';
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
 	$sql="SELECT cb.id_cobro,cb.anulada,cb.total,cb.fecha,cb.hora_cobro,concat(cli.nombre,' ',cli.apellido )  as cliente, dc.precio,dc.cantidad,ti.abreviatura as tipo, cb.num_fact_impresa as num
	FROM cobro as cb, detalle_cobro as dc, paciente as cli, tipo_impresion as ti
	WHERE cb.id_cobro=dc.id_cobro
	and cb.id_paciente=cli.id_paciente
	and cb.tipo_doc=ti.abreviatura
	and cb.id_sucursal='$id_sucursal'
	and cli.id_sucursal='$id_sucursal'
	and dc.id_sucursal='$id_sucursal' GROUP BY cb.id_cobro ORDER by cb.id_cobro DESC";

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
				echo "<div class='ibox-title'>";
				$filename='venta.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='venta.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Cobro</a>";
				echo "</div>";

				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><b><i class="fa fa-money fa-1x"></i> <?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-md-1">ID</th>
									<th class="col-md-5">CLIENTE</th>
									<th class="col-md-1">FECHA</th>
									<th class="col-md-1">HORA </th>
									<th class="col-md-1">TOTAL</th>
									<th class="col-md-2">ESTADO</th>
									<th class="col-md-1">ACCI&Oacute;N</th>
								</tr>
							</thead>
							<tbody>
				<?php
 					if ($count>0){
						$id=1;
						for($i=0;$i<$count;$i++){

							$row=_fetch_array($result);

							echo "<tr>";
							echo"<td>".$id."</td>
								<td>".$row['cliente']."</td>
								<td>".ED($row['fecha'])."</td>
								<td>".hora($row['hora_cobro'])."</td>
								<td>".$row['total']."</td>";

								if ($row['anulada']==0) {
									// code...
									echo "<td><strong class='text-success'>FINALIZADA<strong></td>";
								}
								else
								{
									echo "<td><strong class='text-warning'>ANULADA<strong></td>";
								}

								echo "<td><div class='btn-group'>
								<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
								<ul class='dropdown-menu dropdown-primary'>";

									$filename='ver_detalle_cobro.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' ){
										echo "<li><a data-toggle='modal' href='ver_detalle_cobro.php?id_detalle_cobro=".$row['id_cobro']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></li>";
									}
									if ($row['anulada']==0) {
										// code...
										$filename='anular.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' ){
											echo "<li><a data-toggle='modal' href='anular.php?id_detalle_cobro=".$row['id_cobro']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-times\"></i>  Anular</a></li>";
										}
									}
									$filename='borrar_cob.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' ){
										echo "<li><a data-toggle='modal' href='borrar_cob.php?id_detalle_cobro=".$row['id_cobro']."' data-target='#verBorrado' data-refresh='true'><i class=\"fa fa-trash\"></i>  Eliminar</a></li>";
									}

								echo "</ul>
								</div>";

								echo "</td></tr>";
								$id+=1;
						}
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
				<div class='modal fade' id='verBorrado' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content modal-md'></div><!-- /.modal-content -->
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
	echo" <script type='text/javascript' src='js/funciones/funciones_cobro.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
?>
