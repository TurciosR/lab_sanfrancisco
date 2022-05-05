<?php
include ("_core.php");
function initial(){
date_default_timezone_set('America/El_Salvador');
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$filename = "examen_pendiente.php";
	$links=permission_usr($id_user,$filename);
  $id_paciente=$_REQUEST["id_paciente"];
	$id_cobro=$_REQUEST["id_cobro"];
	$sql1="SELECT *
					FROM examen_paciente as ep LEFT JOIN examen as e ON (ep.id_examen=e.id_examen)
					WHERE ep.id_examen>0 AND ep.estado_realizado='Pendiente' AND ep.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo= 0 and e.id_sucursal='$id_sucursal' and ep.id_paciente='$id_paciente' and ep.id_cobro='$id_cobro' ";
	$result1=_query($sql1);
	$n=_num_rows($result1);

	$result3=_query("SELECT *FROM paciente where id_paciente='$id_paciente'");
	$row3=_fetch_array($result3);
	$nombre= $row3['nombre']." ".$row3['apellido'];


?>
<div class="modal-header">

	<button type="button" class="close" id="cerrar" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Pendientes de procesar</h4>
</div>
<div class="modal-body">

		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-lg-12">
		 		<div class="alert alert-info text-center">
			 		<h4><?php echo $nombre; ?></h4>
		 		</div>
			</div><br>
			<div class="col-lg-12 pre-scrollable">
				<table  class="table table-bordered table-hover" id="tabla1 ">
					 <thead>
						 <tr>
							 <th class="col-lg-6">EXAMEN</th>
							 <th class="col-lg-2">TIEMPO</th>
							 <th class="col-lg-1">ESTADO</th>
							 <th class="col-lg-1">ACCI&Oacute;N</th>
						 </tr>
					 </thead>

					 <tbody id="print_e">
						 <?php
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
									?>
									<tr >
										<td><?php echo $row['nombre_examen']; ?> <input type='hidden'  class="id_examen_paciente" value="<?php echo $row['id_examen_paciente'];?>" ></td>
										<td><?php
										$fecha_cobro = $row["fecha_cobro"];
							    	$hora_cobro = $row["hora_cobro"];
							      date_default_timezone_set('America/El_Salvador');
							      $fecha_hoy=date("Y-m-d");
							      $hora_hoy=date("H:i:s");

							      $fecha1 = new DateTime("".$fecha_cobro." ".$hora_cobro."");
							      $fecha2 = new DateTime("".$fecha_hoy." ".$hora_hoy."");
							      $fecha = $fecha1->diff($fecha2);
							      $horas_y=(($fecha->y)*365)*24;
							      $horas_m=(($fecha->m)*30)*24;
							      $horas_d=(($fecha->d)*24);
							      $horas=($fecha->h)+$horas_y+$horas_m+$horas_d;
							      $minutos=$fecha->i;
							      $segundos=$fecha->s;
							      $tiempo=$horas.":".$minutos;

										echo $tiempo;
										?> </td>
										<td class="estado" ><?php echo $estado ?></td>
										<td>
											<?php
											$filename='agregar_examen_paciente.php';
							        $link=permission_usr($id_user,$filename);
							        if ($link!='NOT' || $admin=='1'){
												$boton="<a href='agregar_examen_paciente.php?id_examen_paciente=".$row['id_examen_paciente']."&proceso=edited_modal' class='btn btn-primary' role='button' data-refresh='true'><i class=\"fa fa-plus icon-large\"></i> Procesar</a>";
												echo $boton;

											}

											 ?>
										</td>
			 						</tr>
									<?php
								 }
								}
						 ?>
					 	</tbody>
					</table>
			</div>
		</div>

		</div>

</div>
<div  class="modal-footer">
	<input type='hidden' name='id_paciente' id='id_paciente' value="<?php echo $id_paciente;?>" >
	<input type='hidden' name='id_cobro' id='id_cobro' value="<?php echo $id_cobro;?>" >
	<a id='procesar' name='procesar' class='btn btn-primary '><i class='fa fa-plus  icon-large'></i> Procesar Todo</a>

</div>
<!--/modal-footer -->
<script>
$(document).ready(function(){


});

</script>
<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}

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
