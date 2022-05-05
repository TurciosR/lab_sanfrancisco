
<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Expediente del Paciente';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
  	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $filename = "ver_expediente.php";
  $links=permission_usr($id_user,$filename);

  $id_paciente = $_REQUEST["id_paciente"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $query_user = _query("SELECT p.*, xp.* FROM paciente as p JOIN expediente as xp ON xp.id_paciente=p.id_paciente WHERE p.id_paciente='$id_paciente' AND p.id_sucursal='$id_sucursal'");
  $datos_user = _fetch_array($query_user);
  $nombre = $datos_user["nombre"];
  $apellido = $datos_user["apellido"];
  $direccion = $datos_user["direccion"];
  $sexo = $datos_user["sexo"];
  $telefono = $datos_user["telefono"];
  $dui = $datos_user["dui"];
  $naci = $datos_user["fecha_nacimiento"];
  $correo = $datos_user["correo"];
  $n_expediente = $datos_user["n_expediente"];
  $fechaC = $datos_user["fecha_creada"];
  $fechaU = $datos_user["ultima_visita"];
  $foto = $datos_user["foto"];
  $entregar = $id_expediente;
  $enviar = $datos_user["id_paciente"];

  $fecha = date('Y-m-d');
  $fech = date('Y-m-d');
  $nuevafech = date('Y-m-d', strtotime('-1 year'));
  $nuevafecha = $nuevafech;


?>


<div class="wrapper wrapper-content  animated fadeInRight">
	<?php
		//permiso del script
		if ($links!='NOT' || $admin=='1' ){
			?>
		<div class="row" id="row1">
				<div class="col-lg-12">
						<div class="ibox float-e-margins">
								<div class="ibox-title">
										<h3 class="text-navy"><b><i class="fa fa-clipboard fa-1x"></i> <?php echo $title;?></b></h3>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="text-success"> Datos del Paciente
													<a class="pull-right" target="_blank" href="reporte_expediente.php?id_expediente=<?php echo $entregar;?>" ><i class="fa fa-print fa-2x"></i> Imprimir Expediente General</a>
                          <p class="text-center">N° <?php echo $n_expediente;?></p>
                        </h4>
												</div>
												<div class="panel-body">
													<div class="widget-content">
														<table class="table table-bordered table-hover">
																<tr>
																	<td rowspan="7" class="text-center" >
																		<img src="<?php echo $foto;?>" style="width:200px; height:250px;">
                                    <a href="foto_expediente.php?id_paciente=<?php echo $enviar;?>" data-toggle='modal' data-target='#fotoModal' data-refresh='true'><i class='fa fa-refresh'></i> Cambiar</a>
																	</td></tr>
																<tr>
																	<td style="width:10%;"><strong>NOMBRES:</strong></td>
																	<td style="width:40%;"><?php echo "$nombre";?></td>
																	<td style="width:10%;"><strong>APELLIDOS:</strong></td>
																	<td style="width:40%;"><?php echo "$apellido";?></td></tr>
																<tr>
																	<td><strong>DIRECCI&Oacute;N:</strong></td>
																	<td><?php echo "$direccion";?></td>
																	<td><strong>TEL&Eacute;FONO:</strong></td>
																	<td><?php echo "$telefono";?></td>
																</tr>
																<tr>
																	<td><strong>G&Eacute;NERO:</strong></td>
																	<td><?php echo "$sexo";?></td>
																	<td><strong>DUI:</strong></td>
																	<td><?php echo "$dui";?></td>
																</tr>
																<tr>
																	<td><strong>FECHA DE NACIMIENTO:</strong></td>
																	<td><?php echo "$naci";?></td>
																	<td><strong>CORREO EL&Eacute;CTRONICO:</strong></td>
																	<td><?php echo "$correo";?></td>
																</tr>
																<tr>
																	<td><strong>FECHA DE CREACI&Oacute;N:</strong></td>
																	<td><?php echo ED($fechaC);?></td>
																	<td><strong>&Uacute;LTIMA VISITA:</strong></td>
																	<td><?php echo ED($fechaU);?></td>
																</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="text-success"> Consultar Ex&aacute;menes Realizados
                        </div>
                        <div class="panel-body">
                          <div class="widget-content">
                            <div class="row">
                              <div class="col-md-1">
                                <label>Desde:</label>
                              </div>
                              <div class="col-md-4">
                                <input type="text" class="form-control datepicker" id="desde" name="desde" value="<?php echo $nuevafecha?>" readonly>
                              </div>
                              <div class="col-md-1">
                                <label>Hasta:</label>
                              </div>
                              <div class="col-md-4">
                                <input type="text" class="form-control datepicker" id="hasta" name="hasta" value="<?php echo $fecha?>" readonly>
                              </div>
                              <div class="col-md-2">
                                <input type="hidden" name="id_expediente" id="id_expediente" value="<?php echo $id_paciente;?>">
                                </div>
                          </div>
                        </div>
                      </div>
                    </div>
                   </div>
                 </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="text-success"> Examenes realizados</h4>
                          <!--<a class="pull-right" target="_blank"><i class="fa fa-print"></i> Imprimir</a></h4>-->
                        </div>
                        <div class="panel-body">
                          <div class="widget-content">
                            <section>
                  						<table class="table table-striped table-bordered table-hover" id="editable2">
                  							<thead>
                  								<tr>
                  									<th class="col-lg-1">ID </th>
                  									<th class="col-lg-2">EXAM&Eacute;N</th>
                  									<th class="col-lg-1">FECHA REALIZADO</th>
                  									<th class="col-lg-1">HORA REALIZADO</th>
																		<th class="col-lg-2">DOCTOR</th>
                  									<th class="col-lg-1">ACCI&Oacute;N</th>
                  								</tr>
                  							</thead>
                              </table>
															<input type='hidden' name='urlprocess' id='urlprocess'value="ver_expediente.php">
															<input type="hidden" name="autosave" id="autosave" value="false-0">
                            </section>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!--TABLA COMPARATIVA-->
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="text-success"> Rango de Fechas - Tabla Comparativa
                        </div>
                        <div class="panel-body">
                          <div class="widget-content">
                            <div class="row">
                              <div class="col-md-1">
                                <label>Desde:</label>
                              </div>
                              <div class="col-md-4">
                                <input type="text" class="form-control datepicker" id="desde1" name="desde1" value="<?php echo $nuevafecha?>" readonly>
                              </div>
                              <div class="col-md-1">
                                <label>Hasta:</label>
                              </div>
                              <div class="col-md-4">
                                <input type="text" class="form-control datepicker" id="hasta1" name="hasta1" value="<?php echo $fecha?>" readonly>
                              </div>
                              <div class="col-md-2">
                                <input type="hidden" name="id_expediente1" id="id_expediente1" value="<?php echo $id_expediente;?>">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="text-success"> Tabla Comparativa por Examen</h4>
                          <!--<a class="pull-right" target="_blank"><i class="fa fa-print"></i> Imprimir</a></h4>-->
                        </div>
                        <div class="panel-body">
                          <div class="widget-content">
                            <section>
                  						<table class="table table-striped table-bordered table-hover" id="editable3">
                  							<thead>
                  								<tr>
                  									<th class="col-lg-1">ID </th>
                  									<th class="col-lg-5">EXAM&Eacute;N</th>
                  									<th class="col-lg-3">AREA</th>
                  									<th class="col-lg-3">ACCI&Oacute;N</th>
                  								</tr>
                  							</thead>
                              </table>
															<input type='hidden' name='urlprocess' id='urlprocess'value="tabla_comparativa_pdf.php">
															<input type="hidden" name="autosave" id="autosave" value="false-0">
                            </section>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

									</div>
							</div>
				</div>
		</div>

    <div class='modal fade' id='fotoModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->

    <?php
    echo "<input type='hidden' name='id_paciente' id='id_paciente' value='$entregar'>";
    ?>
</div>


<?php
include("footer.php");
echo" <script type='text/javascript' src='js/funciones/reporte_expediente.js'></script>";
echo " <script src='js/plugins/fileinput/fileinput.js'></script>";
} //permiso del script
	//
	//
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function ver()
{
	$id_expediente = $_POST ['id_expediente'];
	if (isset($id_empleado)) {
		$xdatos ['typeinfo'] = 'Success';
		} else {
		$xdatos ['typeinfo'] = 'Error';
		}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formVer' :
				initial();
				break;
			case 'ver' :
				ver();
				break;
		}
	}
}

?>
