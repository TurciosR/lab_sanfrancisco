<?php
include_once "_core.php";
include ('num2letras.php');
include ('facturacion_funcion_imprimir.php');
function initial() {
	$_PAGE = array ();
	$title= 'Corte de Caja Diario';
	$_PAGE ['title'] =$title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	date_default_timezone_set('America/El_Salvador');
	$fecha_actual=date("Y-m-d");
	$id_sucursal=$_SESSION['id_sucursal'];
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['nombre_lab'];

 //////////////////////////////////VERIFICACION TIPO PAGO /////////////////////////////////////////
 $contado_tipo_pago="false";
 $credito_tipo_pago="false";
 $seguro_tipo_pago="false";
 $remisiones_tipo_pago="false";
 $sql_tipo_pago=_query("SELECT * FROM tipo_pago WHERE id_sucursal='$id_sucursal'");

 while ($row = _fetch_array($sql_tipo_pago))
 {
	$descripcion=$row["descripcion"];
	$estado=$row["estado"];
	if($descripcion=="CONTADO" AND $estado==1){
		$contado_tipo_pago="true";
	}
	if($descripcion=="CREDITO" AND $estado==1){
		$credito_tipo_pago="true";
	}
	if($descripcion=="SEGUROS" AND $estado==1){
		$seguro_tipo_pago="true";
	}
	if($descripcion=="REMISIONES" AND $estado==1){
		$remisiones_tipo_pago="true";
	}
 }
echo "<input type='hidden' name='contado_tipo_pago' id='contado_tipo_pago' value='$contado_tipo_pago'>";
echo "<input type='hidden' name='credito_tipo_pago' id='credito_tipo_pago' value='$credito_tipo_pago'>";
echo "<input type='hidden' name='remisiones_tipo_pago' id='remisiones_tipo_pago' value='$remisiones_tipo_pago'>";
echo "<input type='hidden' name='seguro_tipo_pago' id='seguro_tipo_pago' value='$seguro_tipo_pago'>";
/////////////////////////////////FIN VERIFICACION TIPO PAGO /////////////////////////////////////////
//////////////////////////////////VERIFICACION CONDICION PAGO /////////////////////////////////////////
$efectivo_con_pago="false";
$cheque_con_pago="false";
$transferencia_con_pago="false";
$tarjeta_con_pago="false";
$sql_con_pago=_query("SELECT * FROM condicion_pago WHERE id_sucursal='$id_sucursal'");

while ($row = _fetch_array($sql_con_pago))
{
 $descripcion=$row["descripcion"];
 $estado=$row["estado"];
 if($descripcion=="EFECTIVO" AND $estado==1){
	 $efectivo_con_pago="true";
 }
 if($descripcion=="CHEQUE" AND $estado==1){
	 $cheque_con_pago="true";
 }
 if($descripcion=="TRANSFERENCIA" AND $estado==1){
	 $transferencia_con_pago="true";
 }
 if($descripcion=="TARJETA" AND $estado==1){
	 $tarjeta_con_pago="true";
 }
}
echo "<input type='hidden' name='efectivo_con_pago' id='efectivo_con_pago' value='$efectivo_con_pago'>";
echo "<input type='hidden' name='cheque_con_pago' id='cheque_con_pago' value='$cheque_con_pago'>";
echo "<input type='hidden' name='transferencia_con_pago' id='transferencia_con_pago' value='$transferencia_con_pago'>";
echo "<input type='hidden' name='tarjeta_con_pago' id='tarjeta_con_pago' value='$tarjeta_con_pago'>";
/////////////////////////////////FIN VERIFICACION CONDICION  PAGO /////////////////////////////////////////
	//permiso del script
 	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$aper_id = $_REQUEST["aper_id"];
	$sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_apertura = '$aper_id' AND vigente = 1 AND id_sucursal = '$id_sucursal'");
	$cuenta = _num_rows($sql_apertura);
	$row_apertura = _fetch_array($sql_apertura);
	$id_apertura = $row_apertura["id_apertura"];
	$tike_inicia = $row_apertura["tiket_inicia"];
	$factura_inicia = $row_apertura["factura_inicia"];
	$credito_inicia = $row_apertura["credito_fiscal_inicia"];
	$empleado = $row_apertura["id_empleado"];
	$dev_inicia = $row_apertura["dev_inicia"];
	$turno = $row_apertura["turno"];
	$fecha_apertura = $row_apertura["fecha"];
	$hora_apertura = $row_apertura["hora"];
	$monto_apertura = $row_apertura["monto_apertura"];

	$monto_ch = $row_apertura["monto_ch"];
	$caja = $row_apertura["caja"];

	$hora_actual = date('H:i:s');

	//////////////////////////////////################## MOVIMIENTOS DE CAJA Y ABONOS A CREDITO ##################///////////////////
	$sql_caja = _query("SELECT * FROM mov_caja WHERE fecha = '$fecha_apertura' AND id_apertura = '$id_apertura' AND hora BETWEEN '$hora_apertura' AND '$hora_actual' AND id_sucursal = '$id_sucursal' AND turno = '$turno'");
	$cuenta_caja = _num_rows($sql_caja);

	$total_entrada_caja = 0;
	$total_abono_tik_e = 0;
	$total_abono_tik_c = 0;
	$total_abono_tik_t = 0;
	$total_abono_tik_tar = 0;
	$total_salida_caja = 0;
	$total_viatico = 0;
	if($cuenta_caja > 0)
	{
		while ($row_caja = _fetch_array($sql_caja))
		{
			$monto = $row_caja["valor"];
			$entrada = $row_caja["entrada"];
			$salida = $row_caja["salida"];
			$viatico = $row_caja["viatico"];
			$idtransase = $row_caja["idtransace"];
			$tipo_doc = $row_caja["tipo_doc"];
			////////////////Entradas//////////////////////////////////////////////
			if($entrada == 1 && $salida == 0 && $viatico == 0)
			{
				if($idtransase != 0 AND $credito_tipo_pago=="true")
				{

				$sql_abono = _query("SELECT * FROM abono_credito WHERE id_abono_credito = '$idtransase'");
					$row_abono = _fetch_array($sql_abono);
					$tipo_pago_abo = $row_abono["tipo_pago"];
					if($tipo_pago_abo == "Efectivo" AND $efectivo_con_pago=="true")
					{
						if($tipo_doc == "TIK")
						{
							$total_abono_tik_e += $monto;

						}
					}
					if($tipo_pago_abo == "Cheque" AND $cheque_con_pago=="true")
					{
						if($tipo_doc == "TIK")
						{
							$total_abono_tik_c += $monto;
						}

					}
					if($tipo_pago_abo == "Transferencia" AND $transferencia_con_pago=="true")
					{
						if($tipo_doc == "TIK")
						{
							$total_abono_tik_t += $monto;
						}

					}
					if($tipo_pago_abo == "Tarjeta" AND $tarjeta_con_pago=="true")
					{
						if($tipo_doc == "TIK")
						{
							$total_abono_tik_tar += $monto;
						}

					}
				}
				else
				{
					$total_entrada_caja += $monto;
				}

			}
			/////////////////Salidas///////////////////////////////
			else if($salida == 1 && $entrada == 0 && $viatico == 0)
			{
				$total_salida_caja += $monto;
			}
			////////////////viatico////////////////////////////////////
			else if($viatico == 1 && $entrada == 0  && $salida == 0)
			{
				$total_viatico += $monto;
			}
		}
	}
	//////////////////////////////############ FIN MOVIMIENTO DE CAJA Y ABONOS CREDITO ##############///////////////////////

	///////////////////////////////////################ COBRO CORTE CAJA ###################///////////////////////////////////////
	$total_tike_2 = 0;


	$total_efectivo_tik = 0;
	$total_transferencia_tik = 0;
	$total_cheque_tik = 0;
	$total_tarjeta_tik = 0;

	$t_tike_2 = 0;


	$sql_corte_caja = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND pagada = 1 AND id_apertura ='$id_apertura' AND (tipo_pago = 'CON' OR tipo_pago = 'TAR') AND turno = '$turno'");
	//echo "SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND pagada = 1 AND id_apertura ='$id_apertura' AND tipo_pago = 'CON' AND turno = '$turno'";
	$cuenta_caja = _num_rows($sql_corte_caja);
	if($cuenta_caja > 0 AND $contado_tipo_pago=="true")
	{
		while ($row_corte = _fetch_array($sql_corte_caja))
		{
			$id_factura = $row_corte["id_cobro"];
			$anulada = $row_corte["anulada"];
			$suma = $row_corte["sumas"];
			$iva = $row_corte["iva"];
			$total = $row_corte["total"];
			$numero_doc = $row_corte["numero_doc"];
			$condicion_pago = $row_corte["con_pago"];
			$pagada = $row_corte["pagada"];
			$tipo_documento = $row_corte["tipo_doc"];
			$numero_im = $row_corte["num_fact_impresa"];
			$tipo_pago = $row_corte['tipo_pago'];

			if($tipo_documento == 'COB')
			{
				$total_tike_2 += $total;
				if($condicion_pago == "EFE" AND $efectivo_con_pago=="true" AND $tipo_pago != "TAR")
				{
					$total_efectivo_tik += $total;
				}
				else if($condicion_pago == "TRA" AND $transferencia_con_pago=="true")
				{
					$total_transferencia_tik += $total;
				}
				else if($condicion_pago == "CHE" AND $cheque_con_pago=="true")
				{
					$total_cheque_tik += $total;
				}
				if($tipo_pago == "TAR" AND $tarjeta_con_pago=="true")
				{
					$total_tarjeta_tik += $total;
				}
				$t_tike_2 += 1;
			}

		}
	}
	/////////////////////////############### FIN VENTA CORTE DE CAJA #############////////////////////////////////

	////////////////////////////////################ VENTA AL CREDITO #############///////////////////////////
	$total_tike_cre = 0;
	$t_tike_cre = 0;
	$sql_credito = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND id_apertura ='$id_apertura' AND turno = '$turno' AND credito = 1  AND tipo_pago = 'CRE'");
	$cuenta_cre = _num_rows($sql_credito);
	if($cuenta_cre > 0 AND $credito_tipo_pago=="true")
	{
		while ($row_cre = _fetch_array($sql_credito))
		{
			$id_factura = $row_cre["id_cobro"];
      $anulada = $row_cre["anulada"];
      $suma = $row_cre["sumas"];
      $iva = $row_cre["iva"];
      $total = $row_cre["total"];
      $numero_doc = $row_cre["numero_doc"];
			$pagada = $row_cre["pagada"];
			$tipo_documento = $row_cre["tipo_doc"];
			$numero_im = $row_cre["num_fact_impresa"];

			if($tipo_documento == 'COB')
      {
          $total_tike_cre += $total;

					$t_tike_cre += 1;
      }


		}
	}
	/////////////////////////////////////########## FIN VENTA AL CREDITO ###################////////////////////////

	////////////////////////////////################ VENTA REMISIONES #############///////////////////////////
	$total_tike_rem = 0;
	$t_tike_rem = 0;
	$sql_remisiones = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND id_apertura ='$id_apertura' AND turno = '$turno' AND tipo_pago = 'REM'");
	$cuenta_rem = _num_rows($sql_remisiones);
	if($cuenta_rem > 0 AND $remisiones_tipo_pago=="true")
	{
		while ($row_rem = _fetch_array($sql_remisiones))
		{
			$id_factura = $row_rem["id_cobro"];
			$anulada = $row_rem["anulada"];
			$suma = $row_rem["sumas"];
			$iva = $row_rem["iva"];
			$total = $row_rem["total"];
			$numero_doc = $row_rem["numero_doc"];
			$pagada = $row_rem["pagada"];
			$tipo_documento = $row_rem["tipo_doc"];
			$numero_im = $row_rem["num_fact_impresa"];

			if($tipo_documento == 'COB')
			{
					$total_tike_rem += $total;

					$t_tike_rem += 1;
			}


		}
	}
	/////////////////////////////////////########## FIN VENTA AL REMISIONES ###################////////////////////////
	////////////////////////////////################ VENTA SEGURO #############///////////////////////////
	$total_tike_seg = 0;
	$t_tike_seg = 0;
	$sql_seguro = _query("SELECT * FROM cobro WHERE fecha = '$fecha_apertura' AND id_sucursal = '$id_sucursal' AND anulada = 0 AND id_apertura ='$id_apertura' AND turno = '$turno' AND tipo_pago = 'SEG'");
	$cuenta_seg = _num_rows($sql_seguro);
	if($cuenta_seg > 0 AND $seguro_tipo_pago=="true")
	{
		while ($row_seg = _fetch_array($sql_seguro))
		{
			$id_factura = $row_seg["id_cobro"];
			$anulada = $row_seg["anulada"];
			$suma = $row_seg["sumas"];
			$iva = $row_seg["iva"];
			$total = $row_seg["total"];
			$numero_doc = $row_seg["numero_doc"];
			$pagada = $row_seg["pagada"];
			$tipo_documento = $row_seg["tipo_doc"];
			$numero_im = $row_seg["num_fact_impresa"];

			if($tipo_documento == 'COB')
			{
					$total_tike_seg += $total;

					$t_tike_seg += 1;
			}


		}
	}
	/////////////////////////////////////########## FIN VENTA AL REMISIONES ###################////////////////////////

	///////////////////////////////////// TOTALES /////////////////////////////////////////////////////////////////

	//////////////////////////////////TOTALES VENTA AL CONTADO ///////////////////////////////////////////////////
	$total_efectivo_n = $total_efectivo_tik ;
	$total_cheque_n = $total_cheque_tik ;
	$total_tarjeta_n = $total_tarjeta_tik ;
	$total_transferencia_n = $total_transferencia_tik;
	$total_general_contado1 = $total_efectivo_n + $total_transferencia_n + $total_cheque_n+$total_tarjeta_n;
	$total_general_contado = $total_efectivo_n + $total_transferencia_n + $total_cheque_n;



 /////TOTAL REMISIONES
 $total_remisiones=$total_tike_rem;
 /////TOTAL SEGURO
 $total_seguro=$total_tike_seg;
	///////////////////////////////////TOTALES ABONOS A CREDITO//////////////////////////////////////////////////
	$total_abono_tik = $total_abono_tik_e + $total_abono_tik_c + $total_abono_tik_t+ $total_abono_tik_tar;

	$total_abono_efectivo = $total_abono_tik_e;
	$total_abono_cheque = $total_abono_tik_c;
	$total_abono_transferencia = $total_abono_tik_t;
	$total_abono_tarjeta = $total_abono_tik_tar;

	$total_abono_credito = $total_abono_efectivo + $total_abono_cheque + $total_abono_transferencia + $total_abono_tarjeta;

	$full_recuperacion = $total_abono_efectivo ;
	$total_caja_chica = $monto_apertura + $total_entrada_caja - $total_salida_caja - $total_viatico;
	$total_primario = $total_general_contado;
	$total_credito = $total_tike_cre ;
	$total_facturado = $total_credito + $total_primario;
	$total_remesa = $total_efectivo_n +  $total_abono_efectivo;

	$total_caja = $total_primario + $monto_apertura;
	$total_caja2 = $total_efectivo_n + $total_abono_efectivo;
	$total_corte_2 = $total_remesa;

	$saldo_caja = $monto_apertura ;
	$total_doc = $t_tike_2 ;

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script
?>


        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
						<?php
						//permiso del script
						if ($links!='NOT' || $admin=='1' ){
							?>
                        <div class="ibox-title">
                            <h5>Registrar Corte <?php echo $nombre_sucursal;?></h5>
                        </div>
                        <div class="ibox-content">


                            	<div class="row">
																<div class="col-md-6">
		                              <div class="form-group has-info single-line">
		                              	<label>Tipo de corte</label>
		                              	<select id="tipo_corte" name="tipo_corte" class="form-control">
		                              		<option value="C">Corte de caja</option>
		                              	</select>
		                              </div>
																</div>
		                           <?php
															 $fecha_actual=date("Y-m-d");

															 $nrows_tot_sist=0;
															 $total_diario =0;

															 echo "<div class='col-md-6' >";
															 echo "<div class='form-group has-info single-line'><label>Fecha:</label> <input type='text' class='form-control' id='fecha' name='fecha' value='$fecha_actual' readonly></div>";
															 echo "</div>";
		                           ?>
		                        </div>


														<br>
													  <div class="row">
																<!--- VENTAS DE CONTADO---->
															<div class='col-md-4 contado'>

																<div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-size: 15px; font-weight: bold;'>Ventas de Contado</div>
  																<div class="panel-body" >
																		<table class="table table-bordered" id="tabla_contado">
						                        	<thead id="encabeza_contado">
																				<tr>
							                        		<th class='col-lg-2'><i>DESCRIPCI&Oacute;N</i></th>
							                        		<th class='col-lg-2'><i >TOTAL</i></th>
						                        		</tr>
						                        	</thead>
						                        	<tbody id='tabla_doc'>
						                        		<tr class='efectivo'>
						                        			<td class='col-lg-2'><i >EFECTIVO</i></td>
																	<td><label ><?php echo "$ ".number_format($total_efectivo_n, 2);?></label></td>
															</tr>
															<tr class='transferencia'>
																<td class='col-lg-2'><i>TRANSFERENCIA</i></td>
																<td><label ><?php echo "$ ".number_format($total_transferencia_n, 2);?></label></td>
															</tr>
															<tr class='cheque'>
																<td class='col-lg-2'><i >CHEQUE</i></td>
																<td><label ><?php echo "$ ".number_format($total_cheque_n, 2);?></label></td>
															</tr>
															<tr class='tarjeta'>
																<td class='col-lg-2'><i >TARJETA</i></td>
																<td><label ><?php echo "$ ".number_format($total_tarjeta_n, 2);?></label></td>
															</tr>
						                        		<tr>
														<th class='col-lg-2'><i >TOTAL</i></th>
						                        			<td><label ><?php echo "$ ".number_format($total_general_contado1, 2);?></label></td>
						                        		</tr>
						                        	</tbody>
						                        </table>
																	</div>
																</div>


															</div>
															<!--- VENTAS DE CREDITO---->
															<div class='col-md-4 credito'>
																<div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-size: 15px; font-weight: bold;'>Ventas de Credito</div>
  																<div class="panel-body"  >
																		<table class="table table-bordered" id="tabla_contado">
						                        	<thead id="encabeza_contado">
																				<tr>
							                        		<th class='col-lg-2'><i>DESCRIPCI&Oacute;N</i></th>
							                        		<th class='col-lg-2'><i >TOTAL</i></th>
						                        		</tr>
						                        	</thead>
						                        	<tbody id='tabla_doc'>
						                        		<tr>
						                        			<td class='col-lg-2'><i >CREDITO</i></td>
																					<td><label ><?php echo "$ ".number_format($total_credito, 2);?></label></td>
																				</tr>
																				<tr>
																					<td class='col-lg-2'><i >TOTAL</i></td>
																					<td><label ><?php echo "$ ".number_format($total_credito, 2);?></label></td>
																				</tr>



						                        	</tbody>
						                        </table>
																	</div>
																</div>


															</div>
																<!--- rECUPERACION ABONO CREDITO---->
															<div class='col-lg-4 credito'>
																<div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-size: 15px;font-weight: bold;'>Recuperación: Abono Crédito</div>
  																<div class="panel-body">
																		<table class="table table-bordered" >
																			<thead>
																				<tr>
																					<th class='col-lg-2'><i>DESCRIPCI&Oacute;N</i></th>
																					<th class='col-lg-2'><i >TOTAL</i></th>
																				</tr>
						                        	</thead>

						                        	<tbody>
																				<tr class='efectivo'>
																					<td class='col-lg-2'><i >EFECTIVO</i></td>
																					<td><label ><?php echo "$ ".number_format($total_abono_efectivo, 2);?></label></td>
																				</tr>
																				<tr class='transferencia'>
																					<td class='col-lg-2'><i>TRANSFERENCIA</i></td>
																					<td><label ><?php echo "$ ".number_format($total_abono_transferencia, 2);?></label></td>
																				</tr>
																				<tr class='cheque'>
																					<td class='col-lg-2'><i >CHEQUE</i></td>
																					<td><label ><?php echo "$ ".number_format($total_abono_cheque, 2);?></label></td>
																				</tr>
																				<tr class='tarjeta'>
																					<td class='col-lg-2'><i >TARJETA</i></td>
																					<td><label ><?php echo "$ ".number_format($total_abono_tarjeta, 2);?></label></td>
																				</tr>
																				<tr>
																					<th class='col-lg-2'><i >TOTAL</i></th>
																					<td><label ><?php echo "$ ".number_format($total_abono_credito, 2);?></label></td>
																				</tr>
						                        	</tbody>
						                        </table>

																	</div>
																</div>


															</div>

															<!--- VENTAS REMISIONES---->
															<div class='col-md-4 remisiones'>

																<div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-size: 15px; font-weight: bold;'>Ventas remisiones</div>
  																<div class="panel-body">
																		<table class="table table-bordered" id="tabla_contado">
																		 <thead id="encabeza_contado">
																			 <tr>
																				 <th class='col-lg-2'><i>DESCRIPCI&Oacute;N</i></th>
																				 <th class='col-lg-2'><i >TOTAL</i></th>
																			 </tr>
																		 </thead>
																		 <tbody id='tabla_doc'>
																			 <tr>
																				 <td class='col-lg-2'><i >REMISIONES</i></td>
																				 <td><label ><?php echo "$ ".number_format($total_remisiones, 2);?></label></td>
																			 </tr>
																			 <tr>
																				 <td class='col-lg-2'><i >TOTAL</i></td>
																				 <td><label ><?php echo "$ ".number_format($total_remisiones, 2);?></label></td>
																			 </tr>
																		 </tbody>
																	 </table>
																	</div>
																</div>


															</div>
															<!--- VENTAS SEGUROS---->
															<div class='col-md-4 seguro'>

																<div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-size: 15px; font-weight: bold;'>Ventas seguros</div>
  																<div class="panel-body">
																		<table class="table table-bordered" id="tabla_contado">
						                        	<thead id="encabeza_contado">
																				<tr>
							                        		<th class='col-lg-2'><i>DESCRIPCI&Oacute;N</i></th>
							                        		<th class='col-lg-2'><i >TOTAL</i></th>
						                        		</tr>
						                        	</thead>
						                        	<tbody id='tabla_doc'>
						                        		<tr>
						                        			<td class='col-lg-2'><i >SEGURO</i></td>
																					<td><label ><?php echo "$ ".number_format($total_seguro, 2);?></label></td>
																				</tr>
																				<tr>
																					<td class='col-lg-2'><i >TOTAL</i></td>
																					<td><label ><?php echo "$ ".number_format($total_seguro, 2);?></label></td>
																				</tr>

						                        	</tbody>
						                        </table>
																	</div>
																</div>


															</div>
															<div class='col-md-4 '>
				                        <div class="panel panel-primary" style='height: 380px;'>
  																<div class="panel-heading" style='font-weight: bold; font-size: 15px;'>Movimientos de Caja</div>
  																<div class="panel-body">
																		<table class="table table-bordered" id="table_mov">
						                        	<thead>
						                        		<tr>
							                        		<th class="col-md-8">TIPO MOVIMINETO</th>
							                        		<th class="col-md-4"><i >TOTAL</i></th>
						                        		</tr>
						                        	</thead>
						                        	<tbody>
																				<tr>
						                        			<td>APERTURA CAJA</td>
						                        			<td><label class='pull-right'><?php echo "$ ".number_format($monto_apertura, 2);?></label></td>
						                        		</tr>
																				<tr>
																				<tr>
																					<td >CONTADO</td>
						                        			<td><label class='pull-right' ><?php echo "$ ".number_format($total_general_contado, 2);?></label></td>
						                        		</tr>
																				<!--	<td>VALES</td>
																					<td><label class='pull-right'><?php echo "$ ".number_format($total_salida_caja, 2);?></label></td>
																				</tr>-->
						                        		<tr>
						                        			<td>VALES</td>
						                        			<td><label class='pull-right'><?php echo "$ ".number_format($total_salida_caja, 2);?></label></td>
						                        		</tr>
																				<tr>
						                        			<td>VIATICOS</td>
						                        			<td><label class='pull-right'><?php echo "$ ".number_format($total_viatico, 2);?></label></td>
						                        		</tr>
																				<tr>
						                        			<td>INGRESOS</td>
						                        			<td><label class='pull-right'><?php echo "$ ".number_format($total_entrada_caja, 2);?></label></td>
						                        		</tr>

																				<tr>
						                        			<td>SALDO CAJA</td>
						                        			<td><label class='pull-right'><?php echo "$ ".number_format($total_caja_chica+$total_general_contado, 2);?></label></td>
						                        		</tr>
						                        	</tbody>
						                        </table>

																	</div>
																</div>

															</div>

														</div>


														<div class="row">
															<div class="col-md-6">
																<div class="panel panel-primary" style='height: 340px;'>
  																<div class="panel-heading" style='font-weight: bold;font-size: 15px;'>Saldo Caja</div>
  																<div class="panel-body">
																		<table class="table table-border" id="table_t">

																			<tbody id="table_data">
																				<tr>
																					<td colspan="3">SALDO CAJA</td>
																					<td><label class='pull-right'><?php echo "$ ".number_format($total_caja_chica+$total_general_contado, 2);?></label></td>
																				</tr>
																				<tr>
																					<td class="col-md-3"><label >EFECTIVO</label></td>
																					<td class="col-md-3"><label >SOBRANTE</label></td>
																					<td class="col-md-3"><label >FALTANTE</label></td>
																					<td class="col-md-3"><label >TOTAL SALDO</label></td>
																				</tr>
																				<tr>
																					<td><label class=''><input class="form-control" id='saldo_caja' name='saldo_caja'></label></td>
																					<td><label class='sobrante' style="color:blue">0.00</label></td>
																					<td><label class='faltante' style="color:red"><?php echo ($total_caja_chica+$total_general_contado) ;?></label></td>
																					<td><label ><?php echo "$ ".number_format($total_caja_chica+$total_general_contado , 2);?></label></td>
																				</tr>
																			</tbody>
																		</table>

																	</div>
																</div>

															</div>
															<!--Remesaaa-->
															<div class="col-md-6">
																 <div class="panel panel-primary" style='height: 340px;'>
  															 	<div class="panel-heading" style='font-weight: bold;font-size: 15px;'>Remesa</div>
  																<div class="panel-body">
																		<table class="table table-border" id="table_aper">
																			<thead >
																				<tr>
																					<th class="col-md-12" colspan="2" style="text-align: center"><label>TOTALES EN EFECTIVO</label></th>
																			 </tr>

																			</thead>
																			<tbody id='caja_o'>

																				<tr>
																					<td class="col-md-8">VENTAS CONTADO</td>
																					<td class="col-md-4"><label id="id_total12" class='pull-right'><?php echo "$ ".number_format($total_efectivo_n, 2);?></label></td>
																				</tr>

																				<tr class="credito">
																					<td class="col-md-8">(+)RECUPERACIÓN: ABONO CREDITO</td>
																					<td class="col-md-4"><label id="id_total12" class='pull-right'><?php echo "$ ".number_format($total_abono_efectivo, 2);?></label></td>
																				</tr>

																				<tr>
																					<td class="col-md-8">TOTAL REMESA</td>
																					<td class="col-md-4"><label id="id_total12" class='pull-right'><?php echo "$ ".number_format($total_remesa, 2);?></label></td>
																				</tr>
																			</tbody>
																		</table>
																		<!--Fin remesa-->
																			 <div class="col-lg-4 col-md-4 col-sm-4">
																					 <div class="form-group">
																							 <label style="color:blue">N° REMESA </label><input class="form-control" id='n_remesa' name='n_remesa'></label>
																					 </div>
																			 </div>

																			 <!--
																			 <div class="col-lg-12">
																					 <div class="form-group">
																							 <label>PEDIDOS PENDIENTES </label><input type="text" id="pedido_pendiente" name="pedido_pendiente" value=""  class="form-control ">
																					 </div>
																			 </div>
																			 <div class="col-lg-12">
																					 <div class="form-group">
																							 <label>COBROS PENDIENTES </label><input type="text" id="cobro_pendiente" name="cobro_pendiente" value=""  class="form-control ">
																					 </div>
																			 </div>
																			 <div class="col-lg-12">
																					 <div class="form-group">
																							 <label>MENSAJE </label><input type="text" id="mensaje" name="mensaje" value=""  class="form-control ">
																					 </div>
																			 </div>
																		 -->

																	</div>
																</div>

															</div>
														</div>






		                       <div>
															<input type="hidden" name="process" id="process" value="insert"><br>


															<input type="hidden" name="t_tike" id="t_tike" value="<?php echo $t_tike_2;?>">


															<input type="hidden" name="total_tike" id="total_tike" value="<?php echo $total_efectivo_tik;?>">





															<!--datos generales -->
															<input type="hidden" name="id_empleado" id="id_empleado" value="<?php echo $empleado;?>">
															<input type="hidden" name="turno" id="turno" value="<?php echo $turno;?>">
															<input type="hidden" name="id_apertura" id="id_apertura" value="<?php echo $id_apertura;?>">
															<input type="hidden" name="caja_apertura" id="caja_apertura" value="<?php echo $caja;?>">

															<!--datos de movimiento-->
															<input type="hidden" name="monto_apertura" id="monto_apertura" value="<?php echo $monto_apertura;?>">
															<input type="hidden" name="total_ch" id="total_ch" value="<?php echo $total_caja_chica;?>">
															<input type="hidden" name="total_contado" id="total_contado" value="<?php echo $total_general_contado;?>">
															<input type="hidden" name="monto_ch" id="monto_ch" value="<?php echo $monto_ch;?>">
															<input type="hidden" name="aper_id" id="aper_id" value="<?php echo $aper_id;?>">
															<input type="hidden" name="viaticos" id="viaticos" value="<?php echo $total_viatico;?>">
															<input type="hidden" name="abono_credito" id="abono_credito" value="<?php echo $total_abono_credito;?>">

															<input type="hidden" id="total_corte" name="total_corte" value="<?php echo $total_corte_2;?>">


															<input type='hidden' id='total_entrada' name='total_entrada' value='<?php echo $total_entrada_caja;?>'>
															<input type='hidden' id='total_salida' name='total_salida' value='<?php echo $total_salida_caja;?>'>

															<input type='hidden' id='total_vcontado' name='total_vcontado' value='<?php echo $total_efectivo_n;?>'>
															<input type='hidden' id='total_vcheque' name='total_vcheque' value='<?php echo $total_cheque_n;?>'>
															<input type='hidden' id='total_vtransferencia' name='total_vtransferencia' value='<?php echo $total_transferencia_n;?>'>
															<input type='hidden' id='total_vtarjeta' name='total_vtarjeta' value='<?php echo $total_tarjeta_n;?>'>

															<input type='hidden' id='total_vcredito' name='total_vcredito' value='<?php echo $total_credito;?>'>

															<input type='hidden' id='total_vremisiones' name='total_vremisiones' value='<?php echo $total_remisiones;?>'>


															<input type="hidden" name="total_rcredito" id="total_rcredito" value="<?php echo $total_abono_efectivo;?>">
															<input type="hidden" name="remesa" id="remesa" value="<?php echo $total_remesa;?>">
															<input type="hidden" name="total_facturado" id="total_facturado" value="<?php echo $total_facturado;?>">
															<input type="hidden" name="caja_saldo" id="caja_saldo" value="<?php echo $saldo_caja;?>">
															<input type="hidden" name="caja_saldo1" id="caja_saldo1" value="">
															<input type="hidden" name="sobrante" id="sobrante" value="">
															<input type="hidden" name="faltante" id="faltante" value="">

															<!--Abonos credito-->
															<input type="hidden" name="abono_creditoE" id="abono_creditoE" value="<?php echo $total_abono_efectivo;?>">
															<input type="hidden" name="abono_creditoC" id="abono_creditoC" value="<?php echo $total_abono_cheque;?>">
															<input type="hidden" name="abono_creditoT" id="abono_creditoT" value="<?php echo $total_abono_transferencia;?>">
															<input type="hidden" name="abono_creditoTar" id="abono_creditoTar" value="<?php echo $total_abono_tarjeta;?>">

															<!--Recuperacion Venta pendiente-->

	                           	<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
															<input type="submit" id="submit2" name="submit2" value="Guardar y Cerrar" class="btn btn-primary m-t-n-xs" />
                    </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_corte_informal.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}

function cierre()
{
	date_default_timezone_set('America/El_Salvador');
	$fecha_corte = $_POST["fecha"];
	$total_corte = $_POST["total_corte"];
	$t_tike = $_POST["t_tike"];
	$fecha_actual = date("Y-m-d");
	$hora_actual = date("H:i:s");
	$id_sucursal = $_SESSION["id_sucursal"];
	$id_empleado = $_POST["id_empleado"];
	$turno = $_POST["turno"];
	$id_apertura = $_POST["id_apertura"];
	$monto_apertura = $_POST["monto_apertura"];
	$tipo_corte = $_POST["tipo_corte"];
	////////////////////////////////////
	$total_entrada = $_POST["total_entrada"];
	$total_salida = $_POST["total_salida"];
	$total_viatico = $_POST["viaticos"];

	$recuperacion = $_POST["recuperacion"];
	$abono_credito = $_POST["abono_credito"];


	$total_vcontado = $_POST["total_vcontado"];
	$total_vcheque = $_POST["total_vcheque"];
	$total_vtransferencia = $_POST["total_vtransferencia"];
	$total_vtarjeta = $_POST["total_vtarjeta"];
	$total_vpendiente = $_POST["total_vpendiente"];
	$total_vcredito = $_POST["total_vcredito"];
	$total_vremisiones = $_POST["total_vremisiones"];

	$total_rcredito = $_POST["total_rcredito"];

	$remesa = $_POST["remesa"];
	$n_remesa = $_POST["n_remesa"];

	$total_facturado = $_POST["total_facturado"];
	$total_caja_chica = $_POST["total_ch"];


	$saldo_caja2 = $_POST["saldo_caja"];
	if($saldo_caja2 == ""){
		$saldo_caja2 = 0;
	}
	$sobrante = $_POST["sobrante"];
	$faltante = $_POST["faltante"];

	$pedido_pendiente = $_POST["pedido_pendiente"];
	$cobro_pendiente = $_POST["cobro_pendiente"];
	$mensaje = $_POST["mensaje"];

	///////////////////////////////////
	$total_contado = $_POST["total_vcontado"];
	$monto_ch = $_POST["monto_ch"];
	$caja = $_POST["caja_apertura"];
	$sql_cajax = _query("SELECT correlativo_dispo FROM caja WHERE id_caja = '$caja'");
	$rc = _fetch_array($sql_cajax);
	$correlativo_dispo = $rc["correlativo_dispo"];
	$nn_tik = $correlativo_dispo + 1;
	$total_tike= $_POST["total_tike"];
	$total_factura = $_POST["total_factura"];
	$total_credito_fiscal = $_POST["total_credito"];

	$abono_creditoE = $_POST["abono_creditoE"];
	$abono_creditoC = $_POST["abono_creditoC"];
	$abono_creditoT = $_POST["abono_creditoT"];
	$abono_creditoTar = $_POST["abono_creditoTar"];
	if(!is_numeric($monto_ch == "")){
		$monto_ch = 0;
	}
	if(!is_numeric($total_vpendiente)){
		$total_vpendiente=0;
	}
	if(!is_numeric($n_remesa)){
		$n_remesa=0;
	}
	if(!is_numeric($faltante)){
		$faltante=0;
	}
	if(!is_numeric($sobrante)){
		$sobrante=0;
	}
	$fecha_hoy=date("Y-m-d");
	$tabla = "controlcaja";
	$form_data = array(
		'fecha_corte' => $fecha_actual,
		'hora_corte' => $hora_actual,
		'id_empleado' => $id_empleado,
		'id_sucursal' => $id_sucursal,
		'id_apertura' => $id_apertura,
		'totalci' => $total_tike,
		'totalgral' => $total_corte,
		'cashfinal' => $total_corte,
		'totalnoci' => $t_tike,
		'turno' => $turno,
		'cashinicial' => $monto_apertura,
		'tipo_corte' => $tipo_corte,
		'vtaefectivo' => $total_contado,
		'vales' => $total_salida,
		'ingresos' => $total_entrada,
		'monto_ch' => $monto_ch,
		'caja' => $caja,
		'viaticos' => $total_viatico,
		'abono_credito' => $total_rcredito,
		'venta_pendiente' => $total_vpendiente,
		'remesa' => $remesa,
		'total_facturado' => $total_facturado,
		'saldo_caja' => $saldo_caja2,
		'faltante' => $faltante,
		'sobrante' => $sobrante,
		'n_remesa' => $n_remesa,
		'pedido_pendiente' => $pedido_pendiente,
		'cobro_pendiente' => $cobro_pendiente,
		'mensaje' => $mensaje,
		'caja_chica' => $total_caja_chica,
		'vtacontado' => $total_vcontado,
		'vtatcredito' => $total_vcredito,
		'vtaremisiones' => $total_vremisiones,
		'vcheque' => $total_vcheque,
		'vtarjeta' => $total_vtarjeta,
		'vtransferencia' => $total_vtransferencia,
		'abono_creditoE' => $abono_creditoE,
		'abono_creditoC' => $abono_creditoC,
		'abono_creditoT' => $abono_creditoT,
		'abono_creditoTar' => $abono_creditoTar,
		'fecha' => $fecha_hoy
	);
	echo _error();
	$sql_ = _query("SELECT * FROM controlcaja WHERE id_apertura = '$id_apertura' AND tipo_corte = 'Z'");
	$cuentax = _num_rows($sql_);
	if($cuentax == 0)
	{
		if($tipo_corte == "C")
		{
			$insertar = _insert($tabla, $form_data);
			$id_cortex= _insert_id();

			///////////APERTURA CAJA///////////////
			$table_apertura = "apertura_caja";
			$form_up = array(
				'vigente' => 0,
			);
			$where_apertura = "id_apertura='".$id_apertura."'";
			$up_apertura = _update($table_apertura, $form_up, $where_apertura);

			////////////DETALLLE APERTURA
			$tab = "detalle_apertura";
			$form_d = array(
				'vigente' => 0 , );
			$ww = "id_apertura='".$id_apertura."' AND turno='".$turno."'";
			$up_turno = _update($tab,$form_d, $ww);

		}



		if($insertar)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Corte guardado correctamente !';
			$xdatos['process']='insert';
			$xdatos['id_corte']=$id_cortex;
		}
		else
		{
			$xdatos['typeinfo']='Error';
		 	$xdatos['msg']='Error al guardar el corte !'._error();
		}
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya existe un corte con esta apertura!';
	}
	echo json_encode($xdatos);

}
function corte()
{
	date_default_timezone_set('America/El_Salvador');
	$fecha_corte = $_POST["fecha"];
	$total_corte = $_POST["total_corte"];
	$t_tike = $_POST["t_tike"];
	$fecha_actual = date("Y-m-d");
	$hora_actual = date("H:i:s");
	$id_sucursal = $_SESSION["id_sucursal"];
	$id_empleado = $_POST["id_empleado"];
	$turno = $_POST["turno"];
	$id_apertura = $_POST["id_apertura"];
	$monto_apertura = $_POST["monto_apertura"];
	$tipo_corte = $_POST["tipo_corte"];
	////////////////////////////////////
	$total_entrada = $_POST["total_entrada"];
	$total_salida = $_POST["total_salida"];
	$total_viatico = $_POST["viaticos"];
	$fecha_hoy=date("Y-m-d");
	$recuperacion = $_POST["recuperacion"];
	$abono_credito = $_POST["abono_credito"];


	$total_vcontado = $_POST["total_vcontado"];
	$total_vcheque = $_POST["total_vcheque"];
	$total_vtransferencia = $_POST["total_vtransferencia"];
	$total_vtarjeta = $_POST["total_vtarjeta"];
	$total_vpendiente = $_POST["total_vpendiente"];
	$total_vcredito = $_POST["total_vcredito"];
	$total_vremisiones = $_POST["total_vremisiones"];

	$total_rcredito = $_POST["total_rcredito"];

	$remesa = $_POST["remesa"];
	$n_remesa = $_POST["n_remesa"];

	$total_facturado = $_POST["total_facturado"];
	$total_caja_chica = $_POST["total_ch"];


	$saldo_caja2 = $_POST["saldo_caja"];
	$sobrante = $_POST["sobrante"];
	$faltante = $_POST["faltante"];

	$pedido_pendiente = $_POST["pedido_pendiente"];
	$cobro_pendiente = $_POST["cobro_pendiente"];
	$mensaje = $_POST["mensaje"];

	///////////////////////////////////
	$total_contado = $_POST["total_vcontado"];
	$monto_ch = $_POST["monto_ch"];
	$caja = $_POST["caja_apertura"];
	$sql_cajax = _query("SELECT correlativo_dispo FROM caja WHERE id_caja = '$caja'");
	$rc = _fetch_array($sql_cajax);
	$correlativo_dispo = $rc["correlativo_dispo"];
	$nn_tik = $correlativo_dispo + 1;
	$total_tike= $_POST["total_tike"];
	$total_factura = $_POST["total_factura"];
	$total_credito_fiscal = $_POST["total_credito"];

	$abono_creditoE = $_POST["abono_creditoE"];
	$abono_creditoC = $_POST["abono_creditoC"];
	$abono_creditoT = $_POST["abono_creditoT"];
	$abono_creditoTar = $_POST["abono_creditoTar"];
	if(!is_numeric($monto_ch == "")){
		$monto_ch = 0;
	}
	if(!is_numeric($total_vpendiente)){
		$total_vpendiente=0;
	}
	if(!is_numeric($n_remesa)){
		$n_remesa=0;
	}
	$tabla = "controlcaja";
	$form_data = array(
		'fecha_corte' => $fecha_actual,
		'hora_corte' => $hora_actual,
		'id_empleado' => $id_empleado,
		'id_sucursal' => $id_sucursal,
		'id_apertura' => $id_apertura,
		'totalci' => $total_tike,
		'totalgral' => $total_corte,
		'cashfinal' => $total_corte,
		'totalnoci' => $t_tike,
		'turno' => $turno,
		'cashinicial' => $monto_apertura,
		'tipo_corte' => $tipo_corte,
		'vtaefectivo' => $total_contado,
		'vales' => $total_salida,
		'ingresos' => $total_entrada,
		'monto_ch' => $monto_ch,
		'caja' => $caja,
		'viaticos' => $total_viatico,
		'abono_credito' => $total_rcredito,
		'venta_pendiente' => $total_vpendiente,
		'remesa' => $remesa,
		'total_facturado' => $total_facturado,
		'saldo_caja' => $saldo_caja2,
		'faltante' => $faltante,
		'sobrante' => $sobrante,
		'n_remesa' => $n_remesa,
		'pedido_pendiente' => $pedido_pendiente,
		'cobro_pendiente' => $cobro_pendiente,
		'mensaje' => $mensaje,
		'caja_chica' => $total_caja_chica,
		'vtacontado' => $total_vcontado,
		'vtatcredito' => $total_vcredito,
		'vtaremisiones' => $total_vremisiones,
		'vcheque' => $total_vcheque,
		'vtarjeta' => $total_vtarjeta,
		'vtransferencia' => $total_vtransferencia,
		'abono_creditoE' => $abono_creditoE,
		'abono_creditoC' => $abono_creditoC,
		'abono_creditoT' => $abono_creditoT,
		'abono_creditoTar' => $abono_creditoTar,
		'fecha' => $fecha_hoy
	);
	echo _error();
	$sql_ = _query("SELECT * FROM controlcaja WHERE id_apertura = '$id_apertura' AND tipo_corte = 'Z'");
	$cuentax = _num_rows($sql_);
	if($cuentax == 0)
	{
		if($tipo_corte == "C")
		{
			$insertar = _insert($tabla, $form_data);
			$id_cortex= _insert_id();
		}
		if($insertar)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Corte guardado correctamente !';
			$xdatos['process']='insert';
			$xdatos['id_corte']=$id_cortex;
		}
		else
		{
			$xdatos['typeinfo']='Error';
		 	$xdatos['msg']='Error al guardar el corte !'._error();
		}
	}
	else
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya existe un corte con esta apertura!';
	}
	echo json_encode($xdatos);
}

function  imprimir(){
	$id_corte = $_POST["id_corte"];
	$id_sucursal=$_SESSION['id_sucursal'];
	//directorio de script impresion cliente
	$sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
	//$sql_dir_print="SELECT * FROM `config_dir` WHERE `id_sucursal`=1 ";
	$result_dir_print=_query($sql_dir_print);
	$row0=_fetch_array($result_dir_print);
	$dir_print=$row0['dir_print_script'];
	$shared_printer_win=$row0['shared_printer_matrix'];
	$shared_printer_pos=$row0['shared_printer_pos'];

	$info_mov=print_corte($id_corte);
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
		$so_cliente='win';
	else
		$so_cliente='lin';
	$nreg_encode['shared_printer_win'] =$shared_printer_win;
	$nreg_encode['shared_printer_pos'] =$shared_printer_pos;
	$nreg_encode['dir_print'] =$dir_print;
	$nreg_encode['movimiento'] =$info_mov;
	$nreg_encode['sist_ope'] =$so_cliente;
echo json_encode($nreg_encode);
}

if(!isset($_REQUEST['process'])){
	initial();
}
else
{
if(isset($_REQUEST['process'])){
switch ($_REQUEST['process']) {
	case 'insert':
		corte();
		break;
	case 'cierre':
		cierre();
		break;
	case 'total_sistema':
		//total_sistema();
		break;
	case 'imprimir':
		 imprimir();
		 break;
	}
}
}
?>
