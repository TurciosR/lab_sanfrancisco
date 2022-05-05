<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Descargos';
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
 	$sql="SELECT mp.*,concat(em.nombre,' ', em.apellido)as nombre FROM movimiento_producto mp, empleado as em
	 WHERE em.id_empleado=mp.id_empleado and mp.id_movimiento>0 and mp.tipo='SALIDA' and
	mp.id_sucursal='$id_sucursal' ORDER by mp.id_movimiento DESC";
	$result=_query($sql);
	$count=_num_rows($result);
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				if ($links!='NOT' || $admin=='1' ){

					echo "<div class='ibox-title'>";
					$filename='descargo_inventario.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					echo "<a href='descargo_inventario.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Descargo</a>";
					echo	"</div>";

					?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><i class='fa fa-table fa-1x'></i><b> <?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-sm-1">ID</th>
									<th class="col-sm-3">DETALLE</th>
									<th class="col-sm-2">TIPO</th>
									<th class="col-sm-2">RESPONSABLE</th>
									<th class="col-sm-1">TOTAL</th>
									<th class="col-sm-2">FECHA</th>
									<th class="col-sm-1">ACCI&Oacute;N</th>
								</tr>
							</thead>
							<tbody>
				<?php
 					if ($count>0){
						$id = 1;
						for($i=0;$i<$count;$i++){
							$row=_fetch_array($result);
							$tipo_usuario=$admin;
							if($tipo_usuario==1)
			                    {
			                      $tipo_usuario="Administrador";
			                    }
			                    else
			                    {
			                      $tipo_usuario="Usuario normal";
			                    }
							$concepto1=$row['concepto'];
					    $concepto2=explode("|",$concepto1);
					    $tipo=$row['tipo'];
					    if(count($concepto2)==2){
					      $deta=$concepto2[0];
								$tip=$concepto2[1];
								if($tip==""){
										$tip=$tipo;
								}
					    }


							echo "<tr>";
							echo"<td>".$id."</td>
								<td>".$deta."</td>
								<td>".$tip."</td>
								<td>".$row['nombre']."</td>
								<td>".$row['total']."</td>
								<td>".$row['fecha']."</td>
								";

							echo"<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
									$filename='ver_detalle_movimiento.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='ver_detalle_movimiento.php?id_detalle=".$row['id_movimiento']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-book\"></i> Ver Detalle</a></li>";
							echo "	</ul>
										</div>
										</td>
										</tr>";
										$id += 1;
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
	echo" <script type='text/javascript' src='js/funciones/funciones_movimiento_inve.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
?>
