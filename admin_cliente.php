<?php
include ("_core.php");
function initial()
{
// Page setup
$title = "Administrar Clientes";
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
$sql="SELECT * FROM cliente WHERE id_sucursal='$id_sucursal' ORDER BY nombre ASC";
$result=_query($sql);
//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];

$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);

//permiso del script
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
					echo"<div class='ibox-title'>";
					$filename='agregar_cliente.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_cliente.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Cliente</a>";
					echo "</div>";
					?>
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4><?php echo $title; ?></h4>
						</header>
						<section>
							<table class="table table-striped table-bordered table-hover"id="editable">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-3">Nombre</th>
										<th class="col-lg-2">NIT</th>
										<th class="col-lg-2">NRC</th>
										<th class="col-lg-2">Telefonos</th>
										<th class="col-lg-1">Estado</th>
										<th class="col-lg-1">Acci&oacute;n</th>
									</tr>
								</thead>
								<tbody>
									<?php
									while($row=_fetch_array($result))
									{
										$contacto=$row['nombre'];
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
										echo"<td><input type='hidden' id='id_cli' value='".$row['id_cliente']."'>".$row['id_cliente']."</td>
										<td>".$contacto."</td>
										<td>".$row["nit"]."</td>
										<td>".$row["nrc"]."</td>
										<td>".$telefonos."</td>
										<td><input type='hidden' id='estado' value='".$estado."'>".$text."</td>";
										echo"<td><div class=\"btn-group\">
										<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
										<ul class=\"dropdown-menu dropdown-primary\">";
										$filename='editar_cliente.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo"<li><a href=\"editar_cliente.php?id_cliente=".$row['id_cliente']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
										$filename='borrar_cliente.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='borrar_cliente.php?id_cliente=".$row['id_cliente']."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Borrar</a></li>";
										//echo "<li><a id='estado'><i class='".$fa."'></i> ".$text1."</a></li>";
										$filename='borrar_cliente.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										//echo "<li><a data-toggle='modal' href='borrar_cliente.php?id_cliente=".$row['id_cliente']."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Borrar</a></li>";
										echo "<li><a id='estado'><i class='".$fa."'></i> ".$text1."</a></li>";
										$filename='ver_cliente.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='ver_cliente.php?id_cliente=".$row['id_cliente']."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>";
										echo "	</ul>
										</div>
										</td>
										</tr>";
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
		include ("footer.php");
		echo" <script type='text/javascript' src='js/funciones/funciones_cliente.js'></script>";
	} //permiso del script
	else {
		$mensaje = mensaje_permiso();
		echo "<br><br>$mensaje</div></div></div></div>";
		include "footer.php";
	}
}
function estado_cli() {
	$id_cliente = $_POST ['id_cliente'];
	$estado = $_POST["estado"];
	if($estado == 1)
	{
		$n = 0;
	}
	else
	{
		$n = 1;
	}
	$table = 'cliente';
	$id_sucursal = $_SESSION["id_sucursal"];
	$form_data = array(
		'estado' => $n,
	);
	$where_clause = "id_cliente='".$id_cliente."' AND id_sucursal='".$id_sucursal."'";
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
					estado_cli();
					break;
			}
		}
	}
	?>
