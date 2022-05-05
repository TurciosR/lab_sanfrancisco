<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Empleado';
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
 	$sql="SELECT em.*, te.descripcion FROM empleado as em, tipo_empleado as te
	WHERE  te.id_tipo_empleado=em.id_tipo_empleado and em.id_sucursal='$id_sucursal' and em.id_empleado>0 ORDER by em.id_empleado DESC";
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
				$filename='agregar_empleado.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_empleado.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Empleado</a>";
				echo "</div>";

				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy" id='title-table'><i class="fa fa-group"></i> <?php echo $title; ?></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-sm-1">ID</th>
									<th class="col-sm-2">NOMBRES</th>
									<th class="col-sm-2">APELLIDOS</th>
									<th class="col-sm-2">DIRECCIÓN</th>
									<th class="col-sm-2">TELÉFONO</th>
									<th class="col-sm-2">CARGO</th>
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

							echo "<tr>";
							echo"<td>".$id."</td>
								<td>".$row['nombre']."</td>
								<td>".$row['apellido']."</td>
								<td>".$row['direccion']."</td>
								<td>".$row['telefono']."</td>
								<td>".$row['descripcion']."</td>";

							echo"<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
								$filename='editar_empleado.php';
								$link=permission_usr($id_user,$filename);
								if ($link!='NOT' || $admin=='1' )
									echo "<li><a href=\"editar_empleado.php?id_empleado=".$row['id_empleado']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
									$filename='ver_empleado.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='ver_empleado.php?id_empleado=".$row['id_empleado']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-book\"></i> Ver Detalle</a></li>";
										$estadoS="";
										$icono=""	;
										if($row['estado']==1)
										{
											$estadoS="Desactivar";
											$icono="fa fa-toggle-off"	;

										}
										else if($row['estado']==0)
										{
											$estadoS="Activar";
											$icono="fa fa-toggle-on"	;
										}
										$filename='estado_empleado.php';
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
											echo "<li><a data-toggle='modal' href='estado_empleado.php?id_empleado=".$row['id_empleado']."&estado=".$estadoS."' data-target='#deleteModal' data-refresh='true'><i class=\"$icono\"></i> $estadoS</a></li>";


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
	echo" <script type='text/javascript' src='js/funciones/funciones_empleado.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
?>
