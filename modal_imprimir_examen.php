<?php
include ("_core.php");
function initial(){
date_default_timezone_set('America/El_Salvador');
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$filename = "modal_imprimir_examen.php";
	$links=permission_usr($id_user,$filename);
  	$id_cobro=$_REQUEST["id_cobro"];

	$result2=_query("SELECT *FROM examen_paciente where id_cobro='$id_cobro'");
	$row2=_fetch_array($result2);
	$id_paciente= $row2['id_paciente'];
	$id_doctor= $row2['id_doctor'];

	$result3=_query("SELECT *FROM paciente where id_paciente='$id_paciente'");
	$row3=_fetch_array($result3);
	$nombre= $row3['nombre']." ".$row3['apellido'];

	$result4=_query("SELECT *FROM doctor where id_doctor='$id_doctor'");
	if(_num_rows($result4) > 0){
		$row4=_fetch_array($result4);
		$nombre_doctor = $row4["nombre"]." ".$row4["apellido"];
		$correo = $row4["email"];
	}
	else{
		$nombre_doctor = "";
		$correo = "";
	}
	



?>
<div class="modal-header">

	<button type="button" class="close" id="cerrar" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Pendientes de imprimir</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if (true){
			?>
			<div class="col-lg-12">
		 		<div class="alert alert-info text-center">
			 		<h4><?php echo $nombre; ?></h4>
		 		</div>
			</div><br>
			<div class="col-lg-12 pre-scrollable">
				<table class="table table-bordered table-hover" id="tabla1">
					 <thead>
						 <tr>
 							<th class="col-lg-6">EXAMEN</th>
 							<th class="col-lg-1">ESTADO</th>
 							<th class="col-lg-4">ACCI&Oacute;N</th>
 						</tr>
					 </thead>

					 <tbody id="print_e">

					 	</tbody>
					</table>
			</div>


		</div>
		<div class="row">
			<div class="form-group col-lg-9">
				<label>Correo</label>
				<input type="text" name="correo" id="correo" class="form-control" value="<?php echo $correo; ?>">
			</div>
			<div class="form-group col-lg-3">
				<br>

				<a id='sendc_i' name='sendc_i'  class='btn btn-primary pull-right ' style="margin-top:5px;"><i class='fa fa-paper-plane'></i> Enviar</a>
			</div>
		</div>
		</div>

</div>
<div class="modal-footer">

	<input type="hidden" name="nombre_doctor" id="nombre_doctor"  value="<?php echo $nombre_doctor; ?>">
	<input type="hidden" name="nombre_paciente" id="nombre_paciente" value="<?php echo $nombre; ?>">
	<input type='hidden' name='id_cobro' id='id_cobro' value="<?php echo $id_cobro;?>" >
	<a id='imprimir' name='imprimir' class='btn btn-primary '><i class='fa fa-print icon-large'></i> Imprimir Todo</a>
	<a id='procesar_imprimir' name='procesar_imprimir' class='btn btn-primary '><i class='fa fa-check-circle-o icon-large'></i> Procesar Todo</a>

</div>
<!--/modal-footer -->
<script>
$(document).ready(function(){
	tmodal_pediente_impr();


});

</script>
<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}

}
function tabla_modal(){
	$id_user=$_SESSION["id_usuario"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$id_cobro=$_POST["id_cobro"];
	$admin=$_SESSION["admin"];
	$sql1="SELECT *
					FROM examen_paciente as ep LEFT JOIN examen as e ON (ep.id_examen=e.id_examen)
					WHERE ep.id_examen>0 AND ep.estado_impresion!='Hecho' AND ep.estado_realizado='Hecho' AND ep.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo= 0 and e.id_sucursal='$id_sucursal' and ep.id_cobro='$id_cobro' and ep.id_sucursal = '$id_sucursal'";
	$result1=_query($sql1);
	$n=_num_rows($result1);

	$tr="";
	 if($n>0)
	 {
		 for($i=0;$i<$n;$i++){
			 $row=_fetch_array($result1);
			 $estado="";


			 


			 if($row['estado_realizado']=="Hecho" AND $row['estado_impresion']=="Pendiente")
			 {
				 $estado="<h5 class='text-info'>Procesado</h5>";
			 }
			 if($row['estado_realizado']=="Pendiente")
			 {
				 $estado="<h5 class='text-warning'>Pendiente</h5>";
			 }



			 $tr.="<tr >";
				 $tr.="<td>".$row['nombre_examen']."<input type='hidden'  id='id_examen_paciente' class='id_examen_paciente'value='".$row['id_examen_paciente']."' ></td>";
				 $tr.="<td class='estado' >$estado</td>";
				 $tr.="<td>";
					$tr.="<div class='row'>";

					$id_examen_paciente=$row["id_examen_paciente"];
					$sub_sql = "SELECT examen.id_examen from examen inner join examen_paciente on examen_paciente.id_examen = examen.id_examen where id_examen_paciente = '$id_examen_paciente'";
					$sql_query = _query($sub_sql);
					$rowx = _fetch_array($sql_query);
					$id_examen_f = $rowx['id_examen'];
					
					$filename='impresion_examen.php';
					$link=permission_usr($id_user,$filename);
				 	$admin=$_SESSION["admin"];
					if ($link!='NOT' || $admin=='1') {
						if($id_examen_f == 698 || $id_examen_f == 699 || $id_examen_f == 700){
							$tr.="<div class='col-lg-4'><a target='_blank' href='impresion_constancia_covid19.php?id_examen_paciente=$id_examen_paciente'  data-refresh='true' class='btn btn-primary btn-circle' role='button'><i class='fa fa-print fa-1x'></i></a></div>";
						}
						else{
							$tr.="<div class='col-lg-4'><a target='_blank' href='impresion_examen_individual.php?id_examen_paciente=$id_examen_paciente'  data-refresh='true' class='btn btn-primary btn-circle' role='button'><i class='fa fa-print fa-1x'></i></a></div>";
						}
					}
					 $filename='editar_examen_paciente.php';
					 $link=permission_usr($id_user,$filename);
					 $admin=$_SESSION["admin"];
					 if ($link!='NOT' || $admin=='1') {
										$tr.="<div class='col-lg-4'><a  href='$filename?id_examen_paciente=".$id_examen_paciente."&proceso=edited_imprimir'   data-refresh='true' class='btn btn-success btn-circle' role='button'><i class='fa fa-pencil fa-1x'></i></a></div>";
					 }
						$tr.="<div class='col-lg-4'><a  id='impreso'  data-refresh='true' class='btn btn-danger btn-circle' role='button'><i class='fa fa-check-circle-o fa-1x'></i></a></div>";


				 $tr.="</div>";
				$tr.="</td>";
			 $tr.="</tr>";
			}
		 }
		 $xdatos["tr"] = $tr;
		 echo json_encode($xdatos);

}
function tiempo($fecha_realizado,$hora_realizado){
	date_default_timezone_set('America/El_Salvador');
	$fecha_hoy=date("Y-m-d");
	$hora_hoy=date("H:i:s");
	$fecha1 = new DateTime("".$fecha_realizado." ".$hora_realizado."");
	$fecha2 = new DateTime("".$fecha_hoy." ".$hora_hoy."");
	$fecha = $fecha1->diff($fecha2);
	$horas_y=(($fecha->y)*365)*24;
	$horas_m=(($fecha->m)*30)*24;
	$horas_d=(($fecha->d)*24);
	$horas=($fecha->h)+$horas_y+$horas_m+$horas_d;
	$minutos=$fecha->i;
	$segundos=$fecha->s;
	$tiempo=$horas.":".$minutos;

	return $tiempo;

}
function enviar()
{
	date_default_timezone_set('America/El_Salvador');
  $fecha = date('Y-m-d');
  $hora = date("H:i:s");
	$ide = $_POST["ide"];
	$doctor = $_POST["doctor"];
  $paciente = $_POST["paciente"];
  $examen=$_POST["examen"];
	$correo = $_POST["correo"];
  $sqll = _query("SELECT * FROM sucursal");
  $fila = _fetch_array($sqll);
  $nombrelab = $fila["nombre_lab"];
    date_default_timezone_set('America/El_Salvador');
	$destino = $correo;
  $asunto = "Envio de examen";
  $headers = "From: labclinicomigueleno@hotmail.com". "\r\n";
	$headers .= "CC:  labclinicomigueleno@hotmail.com";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$id = $ide;
	$message = '<!doctype html>
						<html>
						  <head>
						    <meta name="viewport" content="width=device-width" />
						    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						    <title>Open Solutions Systems</title>
						    <style>
						      /* -------------------------------------
						          GLOBAL RESETS
						      ------------------------------------- */
						      img {
						        border: none;
						        -ms-interpolation-mode: bicubic;
						        max-width: 100%; }

						      body {
						        background-color: #f6f6f6;
						        font-family: sans-serif;
						        -webkit-font-smoothing: antialiased;
						        font-size: 14px;
						        line-height: 1.4;
						        margin: 0;
						        padding: 0;
						        -ms-text-size-adjust: 100%;
						        -webkit-text-size-adjust: 100%; }

						      table {
						        border-collapse: separate;
						        mso-table-lspace: 0pt;
						        mso-table-rspace: 0pt;
						        width: 100%; }
						        table td {
						          font-family: sans-serif;
						          font-size: 14px;
						          vertical-align: top; }

						      /* -------------------------------------
						          BODY & CONTAINER
						      ------------------------------------- */

						      .body {
						        background-color: #f6f6f6;
						        width: 100%; }

						      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
						      .container {
						        display: block;
						        Margin: 0 auto !important;
						        /* makes it centered */
						        max-width: 580px;
						        padding: 10px;
						        width: 580px; }

						      /* This should also be a block element, so that it will fill 100% of the .container */
						      .content {
						        box-sizing: border-box;
						        display: block;
						        Margin: 0 auto;
						        max-width: 580px;
						        padding: 10px; }

						      /* -------------------------------------
						          HEADER, FOOTER, MAIN
						      ------------------------------------- */
						      .main {
						        background: #ffffff;
						        border-radius: 3px;
						        width: 100%; }

						      .wrapper {
						        box-sizing: border-box;
						        padding: 20px; }

						      .content-block {
						        padding-bottom: 10px;
						        padding-top: 10px;
						      }

						      .footer {
						        clear: both;
						        Margin-top: 10px;
						        text-align: center;
						        width: 100%; }
						        .footer td,
						        .footer p,
						        .footer span,
						        .footer a {
						          color: #999999;
						          font-size: 12px;
						          text-align: center; }

						      /* -------------------------------------
						          TYPOGRAPHY
						      ------------------------------------- */
						      h1,
						      h2,
						      h3,
						      h4 {
						        color: #000000;
						        font-family: sans-serif;
						        font-weight: 400;
						        line-height: 1.4;
						        margin: 0;
						        Margin-bottom: 30px; }

						      h1 {
						        font-size: 35px;
						        font-weight: 300;
						        text-align: center;
						        text-transform: capitalize; }

						      p,
						      ul,
						      ol {
						        font-family: sans-serif;
						        font-size: 14px;
						        font-weight: normal;
						        margin: 0;
						        Margin-bottom: 15px; }
						        p li,
						        ul li,
						        ol li {
						          list-style-position: inside;
						          margin-left: 5px; }

						      a {
						        color: #3498db;
						        text-decoration: underline; }

						      /* -------------------------------------
						          BUTTONS
						      ------------------------------------- */
						      .btn {
						        box-sizing: border-box;
						        width: 100%; }
						        .btn > tbody > tr > td {
						          padding-bottom: 15px; }
						        .btn table {
						          width: auto; }
						        .btn table td {
						          background-color: #ffffff;
						          border-radius: 5px;
						          text-align: center; }
						        .btn a {
						          background-color: #ffffff;
						          border: solid 1px #3498db;
						          border-radius: 5px;
						          box-sizing: border-box;
						          color: #3498db;
						          cursor: pointer;
						          display: inline-block;
						          font-size: 14px;
						          font-weight: bold;
						          margin: 0;
						          padding: 12px 25px;
						          text-decoration: none;
						          text-transform: capitalize; }

						      .btn-primary table td {
						        background-color: #3498db; }

						      .btn-primary a {
						        background-color: #3498db;
						        border-color: #3498db;
						        color: #ffffff; }

						      /* -------------------------------------
						          OTHER STYLES THAT MIGHT BE USEFUL
						      ------------------------------------- */
						      .last {
						        margin-bottom: 0; }

						      .first {
						        margin-top: 0; }

						      .align-center {
						        text-align: center; }

						      .align-right {
						        text-align: right; }

						      .align-left {
						        text-align: left; }

						      .clear {
						        clear: both; }

						      .mt0 {
						        margin-top: 0; }

						      .mb0 {
						        margin-bottom: 0; }

						      .preheader {
						        color: transparent;
						        display: none;
						        height: 0;
						        max-height: 0;
						        max-width: 0;
						        opacity: 0;
						        overflow: hidden;
						        mso-hide: all;
						        visibility: hidden;
						        width: 0; }

						      .powered-by a {
						        text-decoration: none; }

						      hr {
						        border: 0;
						        border-bottom: 1px solid #f6f6f6;
						        Margin: 20px 0; }

						      /* -------------------------------------
						          RESPONSIVE AND MOBILE FRIENDLY STYLES
						      ------------------------------------- */
						      @media only screen and (max-width: 620px) {
						        table[class=body] h1 {
						          font-size: 28px !important;
						          margin-bottom: 10px !important; }
						        table[class=body] p,
						        table[class=body] ul,
						        table[class=body] ol,
						        table[class=body] td,
						        table[class=body] span,
						        table[class=body] a {
						          font-size: 16px !important; }
						        table[class=body] .wrapper,
						        table[class=body] .article {
						          padding: 10px !important; }
						        table[class=body] .content {
						          padding: 0 !important; }
						        table[class=body] .container {
						          padding: 0 !important;
						          width: 100% !important; }
						        table[class=body] .main {
						          border-left-width: 0 !important;
						          border-radius: 0 !important;
						          border-right-width: 0 !important; }
						        table[class=body] .btn table {
						          width: 100% !important; }
						        table[class=body] .btn a {
						          width: 100% !important; }
						        table[class=body] .img-responsive {
						          height: auto !important;
						          max-width: 100% !important;
						          width: auto !important; }}

						      /* -------------------------------------
						          PRESERVE THESE STYLES IN THE HEAD
						      ------------------------------------- */
						      @media all {
						        .ExternalClass {
						          width: 100%; }
						        .ExternalClass,
						        .ExternalClass p,
						        .ExternalClass span,
						        .ExternalClass font,
						        .ExternalClass td,
						        .ExternalClass div {
						          line-height: 100%; }
						        .apple-link a {
						          color: inherit !important;
						          font-family: inherit !important;
						          font-size: inherit !important;
						          font-weight: inherit !important;
						          line-height: inherit !important;
						          text-decoration: none !important; }
						        .btn-primary table td:hover {
						          background-color: #34495e !important; }
						        .btn-primary a:hover {
						          background-color: #34495e !important;
						          border-color: #34495e !important; } }

						    </style>
						  </head>
						  <body class="">
						    <table border="0" cellpadding="0" cellspacing="0" class="body">
						      <tr>
						        <td>&nbsp;</td>
						        <td class="container">
						          <div class="content">

						            <!-- START CENTERED WHITE CONTAINER -->
						            <span class="preheader">Envio de examen</span>
						            <table class="main">

						              <!-- START MAIN CONTENT AREA -->
						              <tr>
						                <td class="wrapper">
						                  <table border="0" cellpadding="0" cellspacing="0">
						                    <tr>
						                      <td>
						                        <p>'.$doctor.' !!!</p>
						                        <p>Reciba un cordial saludo del Laboratorio Clínico Migueleño </p>
						                        <p>Enviamos resultados de los examenes</p>
                                    <p>Realizado a '.$paciente.'</p>
						                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
						                          <tbody>
						                            <tr>
						                              <td align="left">
						                                <table border="0" cellpadding="0" cellspacing="0">
						                                  <tbody>
						                                    <tr>
						                                      <td> <a href="http://labsm.apps-oss.com/examen_vario.php?ref='.$id.'" target="_blank">Ver Examen</a> </td>
						                                    </tr>
						                                  </tbody>
						                                </table>
						                              </td>
						                            </tr>
						                          </tbody>
						                        </table>
						                        <p>'.ucfirst(strtolower(date("Y-m-d"))).', '.date("H:i:s").' </p>
						                      </td>
						                    </tr>
						                  </table>
						                </td>
						              </tr>

						            <!-- END MAIN CONTENT AREA -->
						            </table>

						            <!-- START FOOTER -->
						            <div class="footer">
						              <table border="0" cellpadding="0" cellspacing="0">
						                <tr>
						                  <td class="content-block">
						                    <span class="apple-link">Laboratorio Clínico Migueleño</span>
						                  </td>
						                </tr>
						              </table>
						            </div>
						            <!-- END FOOTER -->

						          <!-- END CENTERED WHITE CONTAINER -->
						          </div>
						        </td>
						        <td>&nbsp;</td>
						      </tr>
						    </table>
						  </body>
						</html>';
	$table = "examen_paciente";
	/*$form_data = array(
		'hasha' => $id,
    'enviado' => 1
	);
	$where = "id_examen_paciente='".$ide."'";
	$update = _update($table, $form_data, $where);*/
	$table_d = "detalle_envio";
	$form_data_d = array(
		'id_cobro' => $ide,
		'fecha' => $fecha,
		'hora' => $hora,
		'correo' => $correo,
	);
	$insert = _insert($table_d, $form_data_d);
    if(mail($destino,$asunto,$message,$headers) && $insert)
    {
    	$xdata["typeinfo"] = "Success";
    	$xdata["msg"] = "Correo enviado con exito!!!";
    }
    else
    {
    	$xdata["typeinfo"] = "Error";
    	$xdata["msg"] = "Correo no pudo ser enviado!!!";
    }
    echo json_encode($xdata);
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
	  	case 'tabla_modal' :
				tabla_modal();
				break;
			case 'sendc_i':
				enviar();
				break;

		}
	}
}

?>
