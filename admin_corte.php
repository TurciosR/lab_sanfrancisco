 	<?php
  include ("_core.php");

	function initial()
	{// Page setup
		$_PAGE = array ();
		$_PAGE ['title'] = 'Administrar Cortes';
		$_PAGE ['links'] = null;
		$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
		$_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
		include_once "header.php";
		include_once "main_menu.php";
		$id_sucursal=$_SESSION['id_sucursal'];
		$id_user = $_SESSION["id_usuario"];
		$sql_user = _query("SELECT * FROM usuario WHERE id_usuario = '$id_user'");
		$row_user = _fetch_array($sql_user);
		$tipo_usuario = $row_user["admin"];


    $sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
  	$array_sucursal=_fetch_array($sql_sucursal);
  	$formal=$array_sucursal['forma_fiscal'];
    $c_interno=$array_sucursal['control_interno'];
    $url_corte="";
    /*if($formal==1 AND $c_interno==0){
      $url_corte="corte_caja_diario.php";
    }
    if($formal==0 AND $c_interno==1){*/
      $url_corte="corte_caja_diario_informal.php";
    //}

		date_default_timezone_set('America/El_Salvador');
		$fecha_actual = date("Y-m-d");
		$hora_actual = date("H:i:s");
	 	$id_user=$_SESSION["id_usuario"];
		$admin=$_SESSION["admin"];
		$fecha_2 = date('Y-m-d');
		$fecha_1 = date('Y-m-01');

		$uri = $_SERVER['SCRIPT_NAME'];
		$filename=get_name_script($uri);
		$links=permission_usr($id_user,$filename);
		//permiso del script
		if ($links!='NOT' || $admin=='1' ){
	?>
	<input type="hidden" name="admin" id="admin" value="<?php echo $admin;?>">
	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_user;?>">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<!--?php
					echo"<div class='ibox-title'>
						<a href='facturacion.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar factura</a>
						</div>";
					?-->
					<div class="ibox-content">
						<!--load datables estructure html-->
						<header>
							<h4>Administrar Cortes</h4>
						</header>
						<section>
							<?php
              //////////////vERIFICA SI HAY UNA APERTURA VIJENTE ///////////////////////////////////////////////////////
								$sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'");
                //echo "SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND id_empleado = '$id_user'";
								//echo "SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND id_empleado = '$id_user'";
	    						$cuenta_apertura = _num_rows($sql_apertura);
	    						if($cuenta_apertura != 0 )
		    						{
	    							///////////////////////////////////////////////////////////////////////////////////////////
	    							$row_apertura = _fetch_array($sql_apertura);
	    							$id_apertura = $row_apertura["id_apertura"];
	    							$monto_apertura = $row_apertura["monto_apertura"];
	    							$id_usuario = $row_apertura["id_empleado"];
	    							$fecha_apertura = $row_apertura["fecha"];
	    							$hora_apertura = $row_apertura["hora"];
	    							$turno = $row_apertura["turno"];
                    $caja = $row_apertura["caja"];
	    							$turno_vigente = $row_apertura["turno_vigente"];
	    							$sql_empleado = _query("SELECT * FROM usuario WHERE id_usuario = '$id_usuario'");
	    							$rr = _fetch_array($sql_empleado);
	    							$nombre = $rr["nombre"];
	    							$turno_txt = "";
										echo "<input type='hidden' id='aper_id' name='aper_id' value='".$id_apertura."'>";
	    						/////////////////////////////////////////////////////////////////////////////////////////////
	    					  $sql_corte = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora_cobro BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND pagada = 1 AND anulada = 0 AND tipo_pago != 'CRE'");
									$cuenta = _num_rows($sql_corte);
                  //echo "SELECT * FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND pagada = 1 AND anulada = 0 AND tipo_pago != 'CRE'";
									$total_tike = 0;
									$total_factura = 0;
									$total_credito_fiscal = 0;
									$total_dev = 0;
									if($cuenta > 0)
									{
										while ($row_corte = _fetch_array($sql_corte))
										{
											$id_factura = $row_corte["id_cobro"];
								            $anulada = $row_corte["anulada"];
								            $suma = $row_corte["sumas"];
								            $iva = $row_corte["iva"];
								            $total = $row_corte["total"];
								            $numero_doc = $row_corte["numero_doc"];
                            $alias_tipodoc = $row_corte["tipo_doc"];

								            $ax = explode("_", $numero_doc);
								            $numero_co = $ax[0];


											if($alias_tipodoc == 'COB')
								            {
								                $total_tike += $total;
								            }
								            else if($alias_tipodoc == 'COF')
								            {
								                $total_factura += $total;
								            }
								            else if($alias_tipodoc == 'CCF')
								            {
								                $total_credito_fiscal += $total;
								            }



										}
									}

									$total_corte = $total_tike + $total_factura + $total_credito_fiscal;
	    						?>

			                        <div class="row">
			                        <input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
			                        <input type="hidden" name="caja_id" id="caja_id" value="<?php echo $caja;?>">
			                        	<table class="table table-bordered">
			                        		<thead>
			                        			<tr>
			                        				<th colspan="3" style="text-align: center"><label class="badge badge-success" style="font-size: 15px; ">Apertura Vigente</label></th>
			                        			</tr>
			                        			<tr>
			                        				<th>Nombre: <?php echo $nombre;?></th>
			                        				<th>Fecha Apertura: <?php echo ED($fecha_apertura);?></th>
			                        				<th>Hora Apertura: <?php echo $hora_apertura;?></th>
			                        			</tr>
			                        			<tr>
			                        				<th>Monto Apertura: <?php echo "$".$monto_apertura;?></th>
			                        				<th>Turno: <?php echo $turno;?></th>
			                        				<th>Monto Registrado: <?php echo $total_corte;?></th>
			                        			</tr>
			                        			<?php
			                        				$sql_d_ap = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 AND id_usuario = '$id_user'");
			                        				$cuenta_a = _num_rows($sql_d_ap);
			                        				if($cuenta_a == 1)
			                        				{
				                        			?>
				                        			<tr>
				                        				<th colspan="3" style="text-align: center">
				                        					<a <?php echo "href='".$url_corte."?aper_id=".$id_apertura."'";?> id="generar_corte" name="generar_corte" class="btn btn-primary m-t-n-xs" > Realizar Corte</a>
				                        					<?php if($turno_vigente == 1){?>
				                        					<a id="cerrar_turno" name="cerrar_turno" class="btn btn-primary m-t-n-xs">Cerrar Turno</a>
				                        					<?php
				                        					}
				                        					?>
				                        				</th>
				                        			</tr>
				                        			<?php
			                        				}
			                        				else
			                        				{
			                        					$sql_d_ap1 = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1");
			                        					$row_sp1 = _fetch_array($sql_d_ap1);
			                        					$id_d_ap = $row_sp1["id_detalle"];
			                        					$emp = $row_sp1["id_usuario"];
			                        					if($emp != 0)
			                        					{
			                        						$sql_empleado1 = _query("SELECT * FROM usuario WHERE id_usuario = '$emp'");
          							    							$rr1 = _fetch_array($sql_empleado1);
          							    							$nombre1 = $rr1["nombre"];
          							    							if($tipo_usuario != 1)
          							    							{
          							    								echo "<tr>";
          					                        					echo "<th colspan='3' style='text-align: center'>";
          					                        					echo "Ya existe un turno vigente realizado por ".$nombre1;
          					                        					echo "</th>";
          					                        					echo "</tr>";
          							    							}
          							    							else
          							    							{
          							    								echo "<tr>";
          					                        					echo "<th colspan='3' style='text-align: center'>";
          					                        					echo "Ya existe un turno vigente realizado por ".$nombre1;
          					                        					echo "</th>";
          					                        					echo "</tr>";
          					                        					echo "<tr>";
          					                        					echo "<th colspan='3' style='text-align: center'>";
          					                        					echo "<a href='".$url_corte."?aper_id=".$id_apertura."' id='generar_corte' name='generar_corte' class='btn btn-primary m-t-n-xs' > Realizar Corte</a> <a id='cerrar_turno' name='cerrar_turno' class='btn btn-primary m-t-n-xs'>Cerrar Turno Vigente</a>";
          					                        					echo "</th>";
          					                        					echo "</tr>";
          							    							}

			                        					}
			                        					else
			                        					{
			                        						echo "<tr>";
				                        					echo "<th colspan='3' style='text-align: center'>";
				                        					echo "<a id='apertura_turno' name='apertura_turno' class='btn btn-primary m-t-n-xs' >Iniciar Turno</a>";
				                        					echo "</th>";
				                        					echo "</tr>";
				                        					echo "<input type='hidden' class='id_d_ap1' id='id_d_ap1' value='".$id_d_ap."'>";
			                        					}

			                        				}
			                        			?>

			                        		</thead>
			                        	</table>
			                        </div>
	    						<?php
	    						}
	    						else
	    						{
										if($admin == 1)
										{

											?>
											<div class="">
												<table class="table table-bordered">
													<thead>
														<tr>
															<td>
															<select class="select col-lg-6" name="id_caja" id="id_caja">
																<?php
																		$sql_caja = _query("SELECT * FROM caja WHERE activa = 1 ORDER BY id_caja  ASC");
																		while ($row_caja = _fetch_array($sql_caja))
																		{
																			$id_caja = $row_caja["id_caja"];
																			$nombre = $row_caja["nombre"];
																			echo "<option value='".$id_caja."'>".$nombre."</option>";
																		}

																?>
															</select>
															</td>
														</tr>
													</thead>
												</table>
												<div id="caja_caja">

												</div>
											</div>
                      <input type="hidden" name="caja_id" id="caja_id" value="0">
											<?php


										}
										else
										{
											$sql_coprueba = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'");
		    							$cuenta_prueba = _num_rows($sql_coprueba);
		    							if ($cuenta_prueba > 0)
		    							{
		    								$row_comprueba = _fetch_array($sql_coprueba);
		    								$id_empleadox = $row_comprueba["id_empleado"];
		    								$sql_em = _query("SELECT nombre FROM usuario WHERE id_usuario = '$id_empleadox'");
		    								$rrs = _fetch_array($sql_em);
		    								$nombre_em = $rrs["nombre"];
		    								if($id_empleadox != $id_user)
		    								{
		    									echo "<div></div>
					    							<div class='alert alert-warning text-center' style='font-weight: bold;'>
					    								<label style='font-size: 15px;'>Ya existe una apertura de caja realizada ".$nombre_em."!!</label>
					    								<br>
					    								<label style='font-size: 15px;'>Debe de realizar el corte para poder iniciar una nueva apertura de caja.</label>

					    							</div>";
					    								}
			    							}
			    							else
			    							{
			    							echo "<div></div>
			    							<div class='alert alert-warning text-center' style='font-weight: bold;'>
			    								<label style='font-size: 15px;'>Sin apertura de caja</label>
			    								<br>
			    								<br>
			    								<a href='apertura_caja.php?id_caja=0' id='apertura' name='apertura' class='btn btn-primary m-t-n-xs' >Realizar Apertura</a>
			    							</div>";
			    							}
											}

	    						}
							?>

						</section>
						<section>
							<div class="widget">
							<div class="row">
								<div class="widget-content">
									<div class="col-lg-4">
                    <label>Desde:</label>
										<input type="text" name="fecha1" id="fecha1" class="form-control datepicker" value="<?php echo $fecha_1;?>">
									</div>
									<div class="col-lg-4">
                    <label>Hasta</label>
										<input type="text" name="fecha2" id="fecha2" class="form-control datepicker" value="<?php echo $fecha_2;?>">
									</div>
									<div class="col-lg-2">

									</div>
									<div class="col-lg-1" style="text-align: left;">
                    <label>Buscar</label>
										<a id='search' name='search' class='btn btn-primary m-t-n-xs' style="margin-top: 0.5%;"><i class="fa fa-search"></i> Buscar</a>
									</div>
								</div>
							</div>
							</div>
						</section>
						<section>
							<table class="table table-striped table-bordered table-hover" id="editable">
								<thead>
									<tr>
										<th>N°</th>
										<th>Fecha</th>
										<th>Hora</th>
										<th>Empleado</th>
										<th>Turno</th>
										<th>Tipo Corte</th>
										<th>Total</th>
										<th>Acci&oacute;n</th>
									</tr>
								</thead>
								<tbody id="caja_x">
								<?php
									$s = 1;
									$sql_cc =_query("SELECT * FROM controlcaja WHERE id_sucursal = '$id_sucursal' AND fecha_corte BETWEEN '$fecha_1' AND '$fecha_2' AND tipo_corte != '' ORDER BY id_corte DESC");
									$cuenta_corte = _num_rows($sql_cc);
									if($cuenta_corte > 0)
									{
										while ($row_cc = _fetch_array($sql_cc))
										{
											$id_corte = $row_cc["id_corte"];
											$fecha_corte = ED($row_cc["fecha_corte"]);
											$hora_corte = $row_cc["hora_corte"];
											$id_empleado_c = $row_cc["id_empleado"];
											$id_apertura = $row_cc["id_apertura"];
											$tipo_corte = $row_cc["tipo_corte"];
											$total = $row_cc["cashfinal"];
											$turno = $row_cc["turno"];

											$sql_empleadox = _query("SELECT * FROM usuario WHERE id_usuario = '$id_empleado_c'");
			    							$rr = _fetch_array($sql_empleadox);
			    							$nombre = $rr["nombre"];



			    							echo "<tr>";
			    							echo "<td>".$s."</td>";
			    							echo "<td>".$fecha_corte."</td>";
			    							echo "<td>".$hora_corte."</td>";
			    							echo "<td>".$nombre."</td>";
			    							echo "<td>".$turno."</td>";
			    							echo "<td>".$tipo_corte."</td>";
			    							echo "<td>".$total."</td>";
			    							echo "<td><div class=\"btn-group\">
													<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
													<ul class=\"dropdown-menu dropdown-primary\">";

														echo "
														<li><a data-toggle='modal' href='imprimir_corte.php?id_corte=".$id_corte."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-print\"></i> Imprimir</a></li>
														";
                            echo "
														<li><a href='corte_caja_pdf.php?id_corte=".$id_corte."' target='_blank'><i class=\"fa fa-print\"></i> Imprimir Reporte</a></li>
														";


												echo "	</ul>
															</div>
															</td>
															</tr>";
			    							$s += 1;
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
	               	</div><!--div class='ibox-content'-->
	       		</div><!--<div class='ibox float-e-margins' -->
			</div> <!--div class='col-lg-12'-->
		</div> <!--div class='row'-->
	</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
	<?php
		include("footer.php");
		echo" <script type='text/javascript' src='js/funciones/funciones_corte_caja.js'></script>";
		} //permiso del script
	else {
    $mensaje = mensaje_permiso();
		echo "<br><br>$mensaje</div></div></div></div>";
		include "footer.php";
		}
	}
	function caja()
	{
		$admin=$_SESSION["admin"];
		$id_caja = $_POST["id_caja"];
		$id_empleado1 = $_POST["id_usuario"];
		$id_sucursal = $_SESSION["id_sucursal"];
		date_default_timezone_set('America/El_Salvador');
		$fecha_actual = date("Y-m-d");
		$hora_actual = date("H:i:s");
		$sql_inicio = _query("SELECT * FROM apertura_caja WHERE caja = '$id_caja' AND vigente = 1 AND id_sucursal = '$id_sucursal'");
		$cuenta = _num_rows($sql_inicio);
		$total_corte = 0;
		if($cuenta > 0)
		{
			$row_apertura = _fetch_array($sql_inicio);
			$id_apertura = $row_apertura["id_apertura"];
			$monto_apertura = $row_apertura["monto_apertura"];
			$id_empleado = $row_apertura["id_empleado"];
			$fecha_apertura = $row_apertura["fecha"];
			$hora_apertura = $row_apertura["hora"];
			$turno = $row_apertura["turno"];
			$turno_vigente = $row_apertura["turno_vigente"];
			$sql_empleado = _query("SELECT * FROM usuario WHERE id_usuario = '$id_empleado'");
			$rr = _fetch_array($sql_empleado);
			$nombre = $rr["nombre"];
			$turno_txt = "";
			echo "<input type='hidden' id='aper_id' name='aper_id' value='".$id_apertura."'>";
			/////////////////////////////////////////////////////////////////////////////////////////////
			$sql_corte = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora_cobro BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND finalizada = 1 AND anulada = 0");
			//echo "SELECT * FROM factura WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND finalizada = 1 AND anulada = 0";
			$cuenta = _num_rows($sql_corte);
			$total_tike = 0;
			$total_factura = 0;
			$total_credito_fiscal = 0;
			$total_dev = 0;
			if($cuenta > 0)
			{
				while ($row_corte = _fetch_array($sql_corte))
				{
					$id_factura = $row_corte["id_cobro"];
					$anulada = $row_corte["anulada"];
					$suma = $row_corte["sumas"];
					$iva = $row_corte["iva"];
					$total = $row_corte["total"];
					$numero_doc = $row_corte["numero_doc"];
          $alias_tipodoc = $row_corte["tipo_doc"];

					$ax = explode("_", $numero_doc);
					$numero_co = $ax[0];


					if($alias_tipodoc == 'TIK')
					{
							$total_tike += $total;
					}
					else if($alias_tipodoc == 'COF')
					{
							$total_factura += $total;
					}
					else if($alias_tipodoc == 'CCF')
					{
							$total_credito_fiscal += $total;
					}
				}
			}

		$total_corte = $total_tike + $total_factura + $total_credito_fiscal;
?>
		<div class="row">
		<input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th colspan="3" style="text-align: center"><label class="badge badge-success" style="font-size: 15px; ">Apertura Vigente</label></th>
					</tr>
					<tr>
						<th>Nombre: <?php echo $nombre;?></th>
						<th>Fecha Apertura: <?php echo ED($fecha_apertura);?></th>
						<th>Hora Apertura: <?php echo $hora_apertura;?></th>
					</tr>
					<tr>
						<th>Monto Apertura: <?php echo "$".$monto_apertura;?></th>
						<th>Turno: <?php echo $turno;?></th>
						<th>Monto Registrado: <?php echo $total_corte;?></th>
					</tr>
					<?php
						$sql_d_ap = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 AND id_usuario = '$id_empleado1'");
						//echo "SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1 AND id_usuario = '$id_empleado'";
						$cuenta_a = _num_rows($sql_d_ap);
						if($cuenta_a == 1)
						{
						?>
						<tr>
							<th colspan="3" style="text-align: center">
								<a <?php echo "href='".$url_corte."?aper_id=".$id_apertura."'";?> id="generar_corte" name="generar_corte" class="btn btn-primary m-t-n-xs" > Realizar Corte</a>
								<?php if($turno_vigente == 1){?>
								<a data-toggle='modal' id="cerrar_turno" name="cerrar_turno" class="btn btn-primary m-t-n-xs" <?php  echo "href='cierre_turno.php?id_apertura=".$id_apertura."&turno=".$turno."&val=0'"?>
			          data-target='#viewModal' data-refresh='true' >Cerrar Turno</a>
								<?php
								}
								?>
							</th>
						</tr>
						<?php
						}
						else
						{
							$sql_d_ap1 = _query("SELECT * FROM detalle_apertura WHERE id_apertura = '$id_apertura' AND vigente = 1");
							$row_sp1 = _fetch_array($sql_d_ap1);
							$id_d_ap = $row_sp1["id_detalle"];
							$emp = $row_sp1["id_usuario"];
							if($emp != 0)
							{
								$sql_empleado1 = _query("SELECT * FROM usuario WHERE id_usuario = '$emp'");
								$rr1 = _fetch_array($sql_empleado1);
								$nombre1 = $rr1["nombre"];
								if($admin != 1)
								{
									echo "<tr>";
														echo "<th colspan='3' style='text-align: center'>";
														echo "Ya existe un turno vigente realizado por ".$nombre1;
														echo "</th>";
														echo "</tr>";
								}
								else
								{
									echo "<tr>";
														echo "<th colspan='3' style='text-align: center'>";
														echo "Ya existe un turno vigente realizado por ".$nombre1;
														echo "</th>";
														echo "</tr>";
														echo "<tr>";
														echo "<th colspan='3' style='text-align: center'>";
														echo "<a href='".$url_corte."?aper_id=".$id_apertura."' id='generar_corte' name='generar_corte' class='btn btn-primary m-t-n-xs' > Realizar Corte</a> <a data-toggle='modal' id='cerrar_turno' name='cerrar_turno' class='btn btn-primary m-t-n-xs' href='cierre_turno.php?id_apertura=".$id_apertura."&turno=".$turno."&id_detalle=".$id_d_ap."&emp=".$emp."&val=1' data-target='#viewModal' data-refresh='true' >Cerrar Turno Vigente</a>";
														echo "</th>";
														echo "</tr>";
								}

							}
							else
							{
								echo "<tr>";
								echo "<th colspan='3' style='text-align: center'>";
								echo "<a id='apertura_turno' name='apertura_turno' class='btn btn-primary m-t-n-xs' >Iniciar Turno</a>";
								echo "</th>";
								echo "</tr>";
								echo "<input type='hidden' class='id_d_ap1' id='id_d_ap1' value='".$id_d_ap."'>";
							}

						}
					?>

				</thead>
			</table>
		</div>
<?php
		}
		else
		{
			$sql_coprueba = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal' AND caja = '$id_caja'");
			//echo "SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'";
			$cuenta_prueba = _num_rows($sql_coprueba);
			if ($cuenta_prueba > 0)
			{
				$row_comprueba = _fetch_array($sql_coprueba);
				$id_empleadox = $row_comprueba["id_empleado"];
				$sql_em = _query("SELECT nombre FROM usuario WHERE id_usuario = '$id_empleadox'");
				$rrs = _fetch_array($sql_em);
				$nombre_em = $rrs["nombre"];
				if($id_empleadox != $id_empleado1)
				{
					echo "<div></div>
						<div class='alert alert-warning text-center' style='font-weight: bold;'>
							<label style='font-size: 15px;'>Ya existe una apertura de caja realizada ".$nombre_em."!!</label>
							<br>
							<label style='font-size: 15px;'>Debe de realizar el corte para poder iniciar una nueva apertura de caja.</label>

						</div>";
				}
			}
			else
			{
			echo "<div></div>
			<div class='alert alert-warning text-center' style='font-weight: bold;'>
				<label style='font-size: 15px;'>Sin apertura de caja</label>
				<br>
				<br>
				<a id='apertura' name='apertura' class='btn btn-primary m-t-n-xs aper' >Realizar Apertura</a>
			</div>";
			}
		}
	}
	function search()
	{
		$id_sucursal = $_SESSION["id_sucursal"];
		$fecha1 = $_POST["fecha1"];
		$fecha2 = $_POST["fecha2"];
		$s = 1;

		$sql_cc =_query("SELECT * FROM controlcaja WHERE id_sucursal = '$id_sucursal' AND fecha_corte BETWEEN '$fecha1' AND '$fecha2' AND tipo_corte != '' ORDER BY id_corte DESC");
		$cuenta_corte = _num_rows($sql_cc);
		$lista = "";
		if($cuenta_corte > 0)
		{
			while ($row_cc = _fetch_array($sql_cc))
			{
				$id_corte = $row_cc["id_corte"];
				$fecha_corte = ED($row_cc["fecha_corte"]);
				$hora_corte = $row_cc["hora_corte"];
				$id_empleado_c = $row_cc["id_empleado"];
				$id_apertura = $row_cc["id_apertura"];
				$tipo_corte = $row_cc["tipo_corte"];
				$total = $row_cc["cashfinal"];
				$turno = $row_cc["turno"];

				$sql_empleadox = _query("SELECT * FROM usuario WHERE id_usuario = '$id_empleado_c'");
				$rr = _fetch_array($sql_empleadox);
				$nombre = $rr["nombre"];


				$lista.= "<tr>";
				$lista.= "<td>".$s."</td>";
				$lista.= "<td>".$fecha_corte."</td>";
				$lista.= "<td>".$hora_corte."</td>";
				$lista.= "<td>".$nombre."</td>";
				$lista.= "<td>".$turno."</td>";
				$lista.= "<td>".$tipo_corte."</td>";
				$lista.= "<td>".$total."</td>";
        $lista.= "<td><div class=\"btn-group\">
          <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
          <ul class=\"dropdown-menu dropdown-primary\">";

            $lista.= "
            <li><a data-toggle='modal' href='imprimir_corte.php?id_corte=".$id_corte."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-print\"></i> Imprimir</a></li>
            ";
            $lista.= "
            <li><a href='corte_caja_pdf.php?id_corte=".$id_corte."' target='_blank'><i class=\"fa fa-print\"></i> Imprimir Reporte</a></li>
            ";


				$lista.= "	</ul>
								</div>
								</td>
								</tr>";
				$s += 1;
			}
		}
		echo $lista;

	}
	if (!isset($_REQUEST['process'])) {
	    initial();
	}
	//else {
	if (isset($_REQUEST['process'])) {
	    switch ($_REQUEST['process']) {
	    case 'ok':
	        search();
	        break;
			case 'caja':
					caja();
						break;
	    }

	 //}
	}
	?>
