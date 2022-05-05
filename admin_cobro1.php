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
 	$sql="SELECT cb.id_cobro,cb.total,cb.fecha,cb.hora_cobro,cli.nombre  as cliente, dc.precio,dc.cantidad FROM cobro as cb,
	 detalle_cobro as dc, cliente as cli WHERE cb.id_cobro=dc.id_cobro and cb.cliente=cli.id_cliente and cb.id_sucursal='$id_sucursal' and
	 cli.id_sucursal='$id_sucursal' and dc.id_sucursal='$id_sucursal' GROUP BY cb.id_cobro ORDER by cb.id_cobro DESC";

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
									<th class="col-md-2">CLIENTE</th>
									<th class="col-md-2">FECHA DE PAGO</th>
									<th class="col-md-2">HORA DE PAGO</th>
									<th class="col-md-1">PRECIO</th>
									<th class="col-md-1">CANTIDAD</th>
									<th class="col-md-1">TOTAL</th>
									<th class="col-md-2">ACCI&Oacute;N</th>
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
								<td>".$row['precio']."</td>
								<td>".$row['cantidad']."</td>
								<td>".$row['total']."</td>";
									$filename='ver_detalle_cobro.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<td><a class='btn btn-primary' data-toggle='modal' href='ver_detalle_cobro.php?id_detalle_cobro=".$row['id_cobro']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></td>";
								echo "</tr>";
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
