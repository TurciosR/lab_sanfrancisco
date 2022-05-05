<?php
include ("_core.php");
function initial(){
	$id_examen_paciente = $_REQUEST ['id_examen_paciente'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$query = _query("SELECT  p.nombre as nombrep, p.apellido as apellidop, e.nombre_examen FROM examen_paciente as ep, paciente as p, examen as e WHERE ep.id_examen_paciente='$id_examen_paciente' AND
		ep.id_paciente=p.id_paciente AND ep.id_examen=e.id_examen and p.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'and
		ep.id_sucursal='$id_sucursal'");
  $datos = _fetch_array($query);
  $nombre= $datos["nombrep"]." ".$datos["apellidop"];
	$nombre_examen = $datos["nombre_examen"];
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "anular_examen_paciente.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Anular Examen Paciente</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="col-lg-12">
				<table class="table table-bordered table-striped" id="tableview">
					<thead>
						<tr>
							<th>Campo</th>
							<th>Nombre</th>
						</tr>
					</thead>
					<tbody>
							<tr>
								<td>Id examen paciente</th>
								<td><?php echo $id_examen_paciente;?></td>
							</tr>
							<tr>
								<td>Paciente</td>
								<td><?php echo $nombre;?></td>
							</tr>
							<tr>
								<td>Examen</td>
								<td><?php echo $nombre_examen;?></td>
							</tr>


						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_examen_paciente' id='id_examen_paciente' value='$id_examen_paciente'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="anular">Anular</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function anular()
{
	$id_examen_paciente = $_POST ['id_examen_paciente'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql=_query("SELECT * FROM examen_paciente WHERE  id_examen_paciente='$id_examen_paciente'");
	$datos=_fetch_array($sql);
	$id_cobro=$datos["id_cobro"];
	$id_examen=$datos["id_examen"];

	$sql_c=_query("SELECT * FROM cobro WHERE  id_cobro='$id_cobro'");
	$datos_c=_fetch_array($sql_c);
	$total=$datos_c["total"];

	$sql_d=_query("SELECT * FROM detalle_cobro WHERE  id_cobro='$id_cobro' and id_examen='$id_examen'");
	$datos_d=_fetch_array($sql_d);
	$precio=$datos_d["precio"];
	$id_detalle=$datos_d["id_detalle_cobro"];

 $total_nuevo=$total-$precio;

	$table = 'examen_paciente';
	$form_data = array (
	'examen_paciente_nulo' => 1
	);
	$where_clause = "id_examen_paciente ='".$id_examen_paciente."'and id_sucursal='".$id_sucursal."'";
	$insertar = _update($table,$form_data, $where_clause);

	$table_c = 'cobro';
	$form_data_c = array (
	'total' => $total_nuevo
	);
	$where_clause_c = "id_cobro ='".$id_cobro."'and id_sucursal='".$id_sucursal."'";
	$insertar_c = _update($table_c,$form_data_c, $where_clause_c);

	$tabla_d="detalle_cobro";
	$where_clause_d = "id_detalle_cobro ='".$id_detalle."'";
	$delete=_delete($tabla_d,$where_clause_d);
	if($insertar && $insertar_c &&  $delete)
	{
		 $xdatos['typeinfo']='Success';
		 $xdatos['msg']='Examen paciente anulado correctamente!';
		 $xdatos['process']='insert';
	}
	else
	{
		 $xdatos['typeinfo']='Error';
		 $xdatos['msg']='Examen paciente no pudo ser anulado!';
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
			case 'formDelete' :
				initial();
				break;
			case 'deleted' :
				anular();
				break;
		}
	}
}

?>
