<?php
   include ("_core.php");
 function initial()
 {
	$ide = $_REQUEST['id_examen_paciente'];
	$sql = _query("SELECT p.nombre as nombrep, p.apellido as apellidop, e.nombre_examen,e.id_examen FROM examen_paciente as ep, paciente as p, examen as e WHERE ep.id_examen_paciente='$ide' AND ep.id_paciente=p.id_paciente AND ep.id_examen=e.id_examen  ");
	$datos = _fetch_array($sql);
  $count=_num_rows($sql);
  $nombre_examen = $datos["nombre_examen"];
	$nombre_paciente = $datos["nombrep"]." ".$datos["apellidop"];

  $sql_d=_query("SELECT * FROM doctor  WHERE id_doctor='$id_doctor'  ");
  $datos_d = _fetch_array($sql_d);
  $nombre_doctor = $datos_d["nombred"]." ".$datos_d["apellidod"];
  $correo = $datos_d["email"];

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Enviar Examen</h4>
</div>
<div class="modal-body">
	<?php
  if($count>0){
		if($links !="NOT" || $admin =='1'){
	?>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="form-group">
          <label>Doctor</label>
					<input type="text" name="nombre_doctor" id="nombre_doctor" class="form-control" readonly value="<?php echo $nombre_doctor; ?>">
				</div>
        <div class="form-group">
					<label>Examen</label>
					<input type="text" name="nombre_examen" id="nombre_examen" class="form-control" readonly value="<?php echo $nombre_examen; ?>">

				</div>
        <div class="form-group">
					<label>Paciente</label>
					<input type="text" name="nombre_paciente" id="nombre_paciente" class="form-control"  readonly value="<?php echo $nombre_paciente; ?>">

				</div>
				<div class="form-group">
					<label>Correo</label>
					<input type="text" name="correo" id="correo" class="form-control" value="<?php echo $correo; ?>">
					<input type="hidden" name="process" id="process" value="enviar">
					<input type="hidden" name="ide" id="ide" value="<?php echo $ide;?>">
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".numeric").numeric({negative:false, decimal:false});
</script>
<div class="modal-footer">
<?php

	echo "<button type='button' class='btn btn-primary' id='sendc'>Enviar</button>
	<button type='button' class='btn btn-default' data-dismiss='modal' id='salir'>Salir</button>
	</div><!--/modal-footer -->";
  }
  else
  {
  	echo "<div class='alert alert-warning'>Usted no tiene permiso para acceder a este modulo</div></div>";
  }
}else{
  echo "<div class='alert alert-warning'>No hay recomendacion de doctor</div></div>";

}
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
  $sqll = _query("SELECT * FROM laboratorio");
  $fila = _fetch_array($sqll);
  $nombrelab = $fila["nombre_lab"];
    date_default_timezone_set('America/El_Salvador');
	$destino = $correo;
  $asunto = "Envio de examen";
  $headers = "From: labclinicomigueleno@hotmail.com". "\r\n";
	$headers .= "CC:  labclinicomigueleno@hotmail.com";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$id = md5($ide);
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
						                        <p>Reciba un cordial saludo del laboratorio '.$nombrelab.'</p>
						                        <p>Enviamos resultados del examen '.$examen.'</p>
                                    <p>Realizado a '.$paciente.'</p>
						                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
						                          <tbody>
						                            <tr>
						                              <td align="left">
						                                <table border="0" cellpadding="0" cellspacing="0">
						                                  <tbody>
						                                    <tr>
						                                      <td> <a href="http://labsm.apps-oss.com/examen.php?ref='.$id.'" target="_blank">Ver Examen</a> </td>
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
	$form_data = array(
		'hasha' => $id,
    'enviado' => 1
	);
	$where = "id_examen_paciente='".$ide."'";
	$update = _update($table, $form_data, $where);

  $table_d = "detalle_envio";
	$form_data_d = array(
		'id_examen_paciente' => $ide,
    'fecha' => $fecha,
    'hora' => $hora,
    'correo' => $correo,
	);
  $insert = _insert($table_d, $form_data_d);
    if(mail($destino,$asunto,$message,$headers) && $update && $insert)
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
if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process'])){
	switch ($_POST['process']) {
		case 'sendc':
			enviar();
		break;
		}
	}
}
?>
