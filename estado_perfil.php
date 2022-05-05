<?php
include ("_core.php");
function initial(){
	$estado=0;
	$id_perfil = $_REQUEST ['id_perfil'];
	$estadoS = $_REQUEST ['estado'];
	if($estadoS=="Activar"){
		$estado=1;
	}else{
		$estado=0;
	}
	$sql="SELECT *FROM perfil WHERE id_perfil='$id_perfil'";
	$result = _query( $sql );
	$count = _num_rows( $result );
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "estado_perfil.php";
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h3 class="modal-title text-navy"><?php echo $estadoS;?>  Perfil</h3>
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
							<th>CAMPO</th>
							<th>NOMBRE</th>
						</tr>
					</thead>
					<tbody>
							<?php
								if ($count > 0) {
									for($i = 0; $i < $count; $i ++) {
										$row = _fetch_array ( $result, $i );
										echo "<tr><td>ID </th><td>$id_perfil</td></tr>";
										echo "<tr><td>DESCRIPCI&Oacute;N</td><td>".$row['nombre_perfil']."</td>";
										echo "</tr>";

									}
								}
							?>
						</tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='id_perfil' id='id_perfil' value='$id_perfil'>";
			echo "<input type='hidden' nombre='estado' id='estado' value='$estado'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="anular"><?php echo $estadoS;?></button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function estado()
{

  $estado = $_POST ['estado'];
	$id_perfil = $_POST ['id_perfil'];
	$table = 'perfil';
	$form_data = array (
	'estado' => $estado
	);
	$where_clause = "id_perfil ='".$id_perfil."'";
	if($estado!=0){
		$insertar = _update($table,$form_data, $where_clause);
		if($insertar)
		{
			 $xdatos['typeinfo']='Success';
			 $xdatos['msg']='Perfil activado correctamente!';
			 $xdatos['process']='insert';
		}
		else
		{
			 $xdatos['typeinfo']='Error';
			 $xdatos['msg']='Perfil no pudo ser activado!';
		 }

	}else
	{

			$insertar = _update($table,$form_data, $where_clause);
			if($insertar)
			{
				 $xdatos['typeinfo']='Success';
				 $xdatos['msg']='Perfil desactivado correctamente!';
				 $xdatos['process']='insert';
			}
			else
			{
				 $xdatos['typeinfo']='Error';
				 $xdatos['msg']='Perfil no pudo ser desactivado!';
			 }


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
			case 'anular' :
				estado();
				break;
		}
	}
}

?>
