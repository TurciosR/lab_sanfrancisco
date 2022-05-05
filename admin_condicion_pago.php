<?php
	include ("_core.php");
	// Page setup
		function initial() {
	$_PAGE = array ();
	$title = 'Administrar Condicion de Pago';
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

	$id_surcu=$_SESSION["id_sucursal"];
 	$sql="SELECT * FROM condicion_pago WHERE id_sucursal='$id_surcu' and id_tipo_pago >0 order by id_tipo_pago desc";
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
				$filename='agregar_condicion_pago.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a data-toggle='modal' href='agregar_condicion_pago.php' class='btn btn-primary' role='button' data-target='#viewModal' data-refresh='true'><i class='fa fa-plus icon-large'></i> Agregar Codicion Pago</a>";
				echo "</div>";

				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 class="text-navy"><b><i class="fa fa-dollar"></i> <?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-lg-1">ID</th>
									<th class="col-lg-5">NOMBRE</th>
									<th class="col-lg-4">ABREVIATURA</th>
									<th class="col-lg-1 center">Activar</th>
									<th class="col-lg-1">ACCI&Oacute;N</th>
								</tr>
							</thead>
							<tbody id="nom">
				<?php
 					if ($count>0){
						$id=1;
						for($i=0;$i<$count;$i++){
							$row=_fetch_array($result);
							$activo=$row["estado"];
							echo "<tr>";
							echo"
								<td>".$id."</td>
								<td>".$row['descripcion']."</td>
								<td>".$row['abreviatura']."</td>
								";
								?>
								<td class='text-center'>	<input type='hidden' id='id' name='id' value="<?php echo $row["id_tipo_pago"] ?>">
									<input type='checkbox' id='activar' name='activar' class='checkbox i-checks'  <?php  if($activo) echo ' checked ' ?>>


								</td>
								<?php


								echo"<td><div class=\"btn-group\">
									<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
									<ul class=\"dropdown-menu dropdown-primary\">";
									/*echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";*/
								 $filename='editar_condicion_pago.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal'   href='editar_condicion_pago.php?id_con_pa=".$row['id_tipo_pago']."' role='button' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
								$filename='borrar_condicion_pago.php';
								 $link=permission_usr($id_user,$filename);
								 if ($link!='NOT' || $admin=='1' )
									 echo "<li><a data-toggle='modal'   href='borrar_condicion_pago.php?id_con_pa=".$row['id_tipo_pago']."' role='button' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-clear\"></i> Eliminar</a></li>";
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
						<input type='hidden' id='activo' name='activo' value="0">
						<input type='hidden' id='idc' name='idc' value="">
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
	echo" <script type='text/javascript' src='js/funciones/funciones_condicion_pago.js'></script>";
		} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
	function Activar()
	{
		$id=$_POST["id"];
		$estado=$_POST["estado"];
		$id_sucursal=$_SESSION["id_sucursal"];

		$sql_result=_query("SELECT id_tipo_pago FROM condicion_pago WHERE id_tipo_pago='$id' and id_sucursal='$id_sucursal'");
		$numrows=_num_rows($sql_result);

		$table = 'condicion_pago';
		$form_data = array (
		'estado' => $estado,
		);
		$where_clause = "id_tipo_pago ='".$id."'and id_sucursal='$id_sucursal'";
		if($numrows == 1)
		{
				$insertar = _update($table,$form_data,$where_clause);
				if($insertar)
				{ if($estado==1){
					 $xdatos['typeinfo']='Success';
					 $xdatos['msg']='Codicion de pago Habilitada correctamente!';
					 $xdatos['process']='activar';
				 }else {
					 $xdatos['typeinfo']='Success';
 					$xdatos['msg']='Codicion de pago Desabilitada correctamente!';
 					$xdatos['process']='activar';
				 }

				}
				else
				{
					 $xdatos['typeinfo']='Error';
					 $xdatos['msg']='Condicion de pago no pudo ser editada!';
			}
		}
	echo json_encode($xdatos);
	}

	if(!isset($_REQUEST['process'])){
		initial();
	}
	else
	{
		if(isset($_REQUEST['process']))
		{
			switch ($_REQUEST['process'])
			{
				case 'activar':
					Activar();
					break;
				case 'formactivar' :
					initial();
					break;
			}
		}
	}

?>
