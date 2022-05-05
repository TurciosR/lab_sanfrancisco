<?php
include ("_core.php");
function initial(){
	$id_cobro = $_REQUEST ['id_cobro'];
	$sql="SELECT * FROM cobro  WHERE id_cobro='$id_cobro' and estado='Pendiente'and id_sucursal='$id_sucursal'";
	$sql1="SELECT * FROM abonos  WHERE  id_cobro='$id_cobro' and anular='0' and id_abono>0 and id_sucursal='$id_sucursal'";
	$result = _query( $sql );
	$result1 = _query( $sql1);
	$count = _num_rows($result1);
	$row1=_fetch_array($result);
	$deu=$row1["monto_pendiente"];
	$abo=$row1["abono"];
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "abonos.php";
	date_default_timezone_set('America/El_Salvador');
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Abonar Credito</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">

		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-md-12">

				<div class="row">
					<div class="form-group col-lg-4" >
							<label>Deuda total</label>
							<input type="text"  class="form-control" id="deuda" name="deuda" readonly value="<?php echo $deu ?>">
					</div>
						<div class="form-group col-lg-4" >
								<label>Abonos</label>
								<input type="text"  class="form-control" id="aportado" name="aportado" readonly value="<?php echo $abo ?>">
						</div>
						<div class="form-group col-lg-4" >
								<label>Monto</label>
								<input type="text" autocomplete="off" placeholder="Ingresa monto " class="form-control" id="monto" name="monto">
						</div>
				</div>
					<div class="row">
						<div class="form-group col-lg-4 select">
								<label>Tipo de documento</label>
								<select class="col-md-12 select" id="tipo_doc" name="tipo_doc" >
									<option value="">Selecionar</option>
									<option value="tk">Tiket</option>
									<option value="fc">Factura</option>

								</select>
						</div>
						<div class="form-group col-lg-4">
								<label>Numero de documento</label>
								<input type="text" autocomplete="off" placeholder="Ingresa numero de documento" class="form-control" id="n_doc" name="n_doc"  >
						</div>
							<div class="form-group col-lg-2" >
									<label>Abonor</label>
									<input type="hidden" name="process" id="process" value="insert">
									<input type="submit"  class="btn btn-success" id="abonar" name="abonar" value="Abonor">
							</div>
					</div>

				<table class="table table-bordered table-striped" id="tab">
					<thead>
						<tr>
							<th class="col-sm-1">Fecha</th>
							<th class="col-sm-1">Hora</th>
							<th class="col-sm-1">Abono $</th>
							<th class="col-sm-1">Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody id="abo">
							 <?php
								if ($count > 0) {
								//	for($i = 0; $i < $count; $i ++) {

									//$cuenta = _num_rows($result1);
									//if($cuenta > 0)
									//{
										//$contar =_num_rows($result1);
										while ($row2=_fetch_array($result1))
										{
											echo "<tr><td >".$row2['fecha']."</td>
											 <td>".$row2['hora']."</td>
											<td ><input type='hidden' class='monto_abono' value=".$row2["monto"].">".$row2['monto']."</td>
											<td><a id='ac' class='ac'><input type='hidden' class='id_abono' value=".$row2["id_abono"]."><i class=\"fa fa-trash\"></i></a></td>
											</tr>";
										}
									}
								//		echo "<tr><td class='col-lg-4'>ID</td><td class='col-lg-8'>$id_cobro</td>";
					/*		}*/

									//}
								//}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			$fecha=date("Y-m-d");
			$hora=date("H:i:s");
			echo "<input type='hidden' nombre='bandera' id='bandera' value=''>";
			echo "<input type='hidden' nombre='anular' id='anular' value='0'>";
			echo "<input type='hidden' nombre='id_cobro' id='id_cobro' value='$id_cobro'>";
			echo "<input type='hidden' class='form-control datepicker' id='fecha' name='fecha' value='$fecha'>";
			echo "<input type='hidden' class='form-control ' nombre='hora' id='hora' value='$hora'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" id="close" name="close" class="btn btn-danger" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->
<?php
//"<script src='js/funciones/funciones_usuarios.js'></script>";
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function ver()
{
	$id_cobro = $_POST ['id_cobro'];
	if (isset($id_cobro)) {
		$xdatos ['typeinfo'] = 'Success';
		} else {
		$xdatos ['typeinfo'] = 'Error';
		}
	echo json_encode ( $xdatos );
}
function insertar_abono()
{
		$id_abono=$_POST["id_abono"];
    $monto=$_POST["monto"];
    $fecha=$_POST["fecha"];
    $hora=$_POST["hora"];
		$abonar=$_POST["abono"];
 	 $debe=$_POST["deuda"];
 	 $anular=$_POST["anular"];
 	 $tipo_doc=$_POST["tipo_doc"];
 	 $n_doc=$_POST["n_doc"];
 	 if($debe==0){
 		 $estado="Cancelado";
 	 }else {
 		 $estado="Pendiente";
 	 }
    $id_cobro=$_POST["id_cobro"];

    $sql_result=_query("SELECT id_cobro FROM abonos WHERE id_cobro='$id_cobro'and id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);
		if($anular==0){
    $table = 'abonos';
    $form_data = array (
    'monto' => $monto,
    'fecha' => $fecha,
    'hora' => $hora,
    'anular' => $anular,
    'numero_doc' => $n_doc,
    'tipo_docu' => $tipo_doc,
    'id_cobro' => $id_cobro,

    );
	}elseif ($anular==1) {
		$table = 'abonos';
		$form_data = array (
		'anular' => $anular,


		);


		$where_clause1 = "id_abono ='".$id_abono."'";
	}
    if(true)
    {
			if($anular==0){
	   		  $insertar = _insert($table,$form_data);
			}elseif ($anular==1) {
					 $insertar = _update($table,$form_data,$where_clause1);
		}

        if($insertar)
        {
					if($anular==0){
				//	$id_abono = _insert_id();
					$xdatos1 ['fecha'] = $fecha;
					$xdatos1 ['hora'] = $hora;
					$xdatos1 ['monto'] = $monto;
					$xdatos1 ['bon'] = $abonar;
					$xdatos1 ['das'] = $debe;
					$xdatos1 ['id_abono'] = $id_abono;

				}
				$xdatos1 ['anular'] = $anular;
				$xdatos1 ['typeinfo'] = 'Success';
					$sql_result1=_query("SELECT id_cobro FROM cobro WHERE  id_cobro='$id_cobro'and id_sucursal='$id_sucursal'");
			    $numrows=_num_rows($sql_result1);

			    $table3 = 'cobro';
			    $form_data1 = array (
			    'monto_pendiente' => $debe,
			    'abono' => $abonar,
			    'estado' => $estado,
			    );
			    $where_clause = "id_cobro ='".$id_cobro."'";
			    if($numrows != 0)
			    {
			        $insertar = _update($table3,$form_data1, $where_clause);
			        if($insertar)
			        {
			           $xdatos1['typeinfo']='Success';
			           //$xdatos['msg']='Empleado editado correctamente!';
			           $xdatos1['process']='insert';
								// $xdatos1['process1']='edited';
			        }
			        else
			        {
			           $xdatos1['typeinfo']='Error';
			           //$xdatos['msg']='Empleado no pudo ser editado!';
			    	}
			    }

        }
        else
        {

    	}
    }
    else
    {

    }
	echo json_encode($xdatos1);
}
/*
 function modificar(){

	 $abonar=$_POST["abono"];
	 $debe=$_POST["deuda"];
	 if($debe==0){
		 $estado="Cancelado";
	 }else {
		 $estado="Pendiente";
	 }
	 $sql_result1=_query("SELECT id_cobro FROM cobro WHERE  id_cobro='$id_cobro'");
	 $numrows=_num_rows($sql_result1);

	 $table3 = 'cobro';
	 $form_data1 = array (
	 'monto_pendiente' => $debe,
	 'abono' => $abonar,
	 'estado' => $estado,
	 );
	 $where_clause = "id_cobro ='".$id_cobro."'";
	 if($numrows != 0)
	 {
	   $insertar = _update($table3,$form_data1, $where_clause);
	   if($insertar)
	     {
	       $xdatos3['typeinfo']='Success';
	       //$xdatos['msg']='Empleado editado correctamente!';
	       $xdatos3['process']='edited';
	     }
	 	    else
	 	     {
	 	       $xdatos3['typeinfo']='Error';
	 	       //$xdatos['msg']='Empleado no pudo ser editado!';
	 	    	}
	 }

echo json_encode($xdatos3);
	 }
*/


if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if(isset($_POST['process']))
	{
			switch ($_POST['process'])
			{
				case 'insert':
					insertar_abono();
					break;
			}
	}
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
			case 'modificar' :
				modificar();
				break;
		}
	}
}

?>
