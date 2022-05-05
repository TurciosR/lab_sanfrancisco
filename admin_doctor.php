<?php
	include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Doctores';
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
 	$sql="SELECT doc.*, esp.nombre as nom FROM doctor as doc, especialidades as esp WHERE esp.id_especialidades=doc.especialidad AND doc.id_doctor >0 and doc.id_sucursal='$id_sucursal'";
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
				echo "<div class='ibox-title'>";
				$filename='agregar_doctor.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_doctor.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Doctor</a>";
				echo "</div>";

				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><b><i class="fa fa-user-md fa-1x"></i> <?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-lg-1">ID </th>
									<th class="col-lg-2">NOMBRE</th>
									<th class="col-lg-2">APELLIDO</th>
									<th class="col-lg-1">ESPECIALIDAD</th>
									<th class="col-lg-2">DIRECCI&Oacute;N</th>
									<th class="col-lg-1">TEL&Eacute;FONO</th>
									<th class="col-lg-2">EMAIL</th>
									<th class="col-lg-1">ACCI&Oacute;N</th>
								</tr>
							</thead>
							<tbody>
				<?php
 					if ($count>0){
						$id=1;
						for($i=0;$i<$count;$i++){
							$row=_fetch_array($result);

							echo "<tr>";
							echo"<td>".$row['id_doctor']."</td>
								<td>".$row['nombre']."</td>
								<td>".$row['apellido']."</td>
								<td>".$row['nom']."</td>
								<td>".$row['direccion']."</td>
								<td>".$row['telefono']."</td>
								<td>".$row['email']."</td>
								";

								echo"<td><div class=\"btn-group\">
									<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
									<ul class=\"dropdown-menu dropdown-primary\">";
									/*echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";*/
									$filename='editar_doctor.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a  href='editar_doctor.php?id_doctor=".$row['id_doctor']."' data-target='#editModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
									$filename='ver_doctor.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='ver_doctor.php?id_doctor=".$row['id_doctor']."' data-target='#verModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver</a></li>";

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
									$filename='estado_doctor.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='estado_doctor.php?id_doctor=".$row['id_doctor']."&estado=".$estadoS."' data-target='#deleteModal' data-refresh='true'><i class=\"$icono\"></i> $estadoS</a></li>";


								echo "	</ul>
											</div>
											</td>
											</tr>";
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
							<div class='modal-content modal-md'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
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
					<div class='modal fade' id='editModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
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
	echo" <script type='text/javascript' src='js/funciones/funciones_doctor.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
?>
