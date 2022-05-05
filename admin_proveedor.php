<?php
include ("_core.php");
// Page setup
function initial(){
$title =  'Administrar Proveedores';
$_PAGE = array ();
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
$_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
include_once "header.php";
include_once "main_menu.php";
$id_sucursal = $_SESSION["id_sucursal"];
$sql="SELECT * FROM proveedor WHERE id_sucursal='$id_sucursal' ORDER BY id_proveedor desc";

//$user=mysql_fetch_array($query1);

$result=_query($sql);
$count=_num_rows($result);

//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri=$_SERVER['REQUEST_URI'];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
					echo"<div class='ibox-title'>";
					$filename='agregar_proveedor.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_proveedor.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Proveedor</a>";
					echo "</div>";

					?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h3 class="text-navy"><b><i class="fa fa-group fa-1x"></i> <?php echo $title; ?></b></h3>
						</header>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable">
								<thead>
									<tr>
										<th class="col-lg-1">ID</th>
										<th class="col-lg-4">NOMBRE</th>
										<th class="col-lg-1">NIT</th>
										<th class="col-lg-3">ESTADO</th>
										<th class="col-lg-2">TELÉFONO</th>
										<th class="col-lg-1">ACCI&Oacute;N</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$id=1;
									while($row=_fetch_array($result))
									{
										$id_proveedor = $row['id_proveedor'];
										$nit=$row['nit'];
										$nombre=$row['nombre'];
										$contacto=$row['contacto'];
										$telefonos=$row["telefono1"];
										$telefono2=$row["telefono2"];
										$estado = $row["estado"];
										if($estado == 0)
										{
											$text = "Activo";
											$text1 = "Desactivar";
											$fa = "fa fa-eye-slash";
										}
										else
										{
											$text = "Inactivo";
											$text1 = "Activar";
											$fa = "fa fa-eye";
										}
										if($telefonos != "")
										{
											if($telefono2 !="")
											{
												$telefonos .= "; ".$telefono2;
											}
										}
										else
										{
											$telefonos = $telefono2;
										}
										echo "<tr>";
										echo"<td><input type='hidden' id='id_pro' value='".$id_proveedor."'>".$id."</td>
										<td>".$nombre."</td>
										<td>".$nit."</td>
										<td><input type='hidden' id='estado' value='".$estado."'>".$text."</td>
										<td>".$telefonos."</td>";

										echo"<td><div class=\"btn-group\">
										<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
										<ul class=\"dropdown-menu dropdown-primary\">";
										$filename='editar_proveedor.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo "<li><a href=\"editar_proveedor.php?id_proveedor=".$id_proveedor."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";

										$filename='borrar_proveedor.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='borrar_proveedor.php?id_proveedor=".$id_proveedor."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Borrar</a></li>";


										$filename='borrar_proveedor.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										//echo "<li><a data-toggle='modal' href='borrar_proveedor.php?id_proveedor=".$id_proveedor."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Borrar</a></li>";
										echo "<li><a id='estado' ><i class='".$fa."'></i> ".$text1."</a></li>";

										$filename='ver_proveedor.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='ver_proveedor.php?id_proveedor=".$id_proveedor."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>";

										echo "	</ul>
										</div>
										</td>
										</tr>";
										$id+=1;
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
					</div><!--div class='ibox-content'-->
				</div><!--<div class='ibox float-e-margins' -->
				</div> <!--div class='col-lg-12'-->
			</div> <!--div class='row'-->
		</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
		<?php
		include("footer.php");
		echo" <script type='text/javascript' src='js/funciones/funciones_proveedor.js'></script>";
	} //permiso del script
	else {
		$mensaje = mensaje_permiso();
		echo "<br><br>$mensaje<div><div></div></div</div></div>";
		include "footer.php";
	}
}
function estado_pro() {
	$id_proveedor = $_POST ['id_proveedor'];
	$estado = $_POST["estado"];
	if($estado == 1)
	{
		$n = 0;
	}
	else
	{
		$n = 1;
	}
	$table = 'proveedor';
	$id_sucursal = $_SESSION["id_sucursal"];
	$form_data = array(
		'estado' => $n,
	);
	$where_clause = "id_proveedor='".$id_proveedor."' AND id_sucursal='".$id_sucursal."'";
	$delete = _update ( $table, $form_data, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Registro actualizado con exito!';
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registro no pudo ser actualizado!';
	}
	echo json_encode ( $xdatos );
}
	if (! isset ( $_REQUEST ['process'] ))
	{
		initial();
	}
	else
	{
		if (isset ( $_REQUEST ['process'] ))
		{
			switch ($_REQUEST ['process'])
			{
				case 'formDelete' :
					initial();
					break;
					case 'estado' :
					estado_pro();
					break;
			}
		}
	}
	?>
