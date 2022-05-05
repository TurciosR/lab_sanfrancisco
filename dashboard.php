<?php
include_once "_core.php";
// Page setup
$_PAGE = array();
$_PAGE['title'] = 'Dashboard';
$_PAGE['links'] = null;
$_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';

include_once "header.php";
include_once "main_menu.php";
 //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$id_sucursal=$_SESSION["id_sucursal"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	$sql="SELECT ep.fecha_examen, ep.hora_examen, e.nombre_examen, p.nombre, p.apellido FROM examen_paciente as ep INNER JOIN examen as e ON(ep.id_examen=e.id_examen)
	INNER JOIN paciente as p ON(ep.id_paciente=p.id_paciente) WHERE ep.estado_realizado='Pendiente'
	and ep.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'and p.id_sucursal='$id_sucursal'ORDER BY ep.id_examen_paciente DESC LIMIT 5  ";
	$result=_query($sql);
	$count=_num_rows($result);

	$sql1="SELECT ep.fecha_examen, ep.hora_examen, e.nombre_examen, p.nombre, p.apellido FROM examen_paciente as ep
	INNER JOIN examen as e ON(ep.id_examen=e.id_examen) INNER JOIN paciente as p ON(ep.id_paciente=p.id_paciente)
	WHERE ep.estado_impresion='Pendiente' and ep.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'and
	 p.id_sucursal='$id_sucursal'ORDER BY ep.id_examen_paciente DESC LIMIT 5 ";
	$result1=_query($sql1);
	$count1=_num_rows($result1);

	$sql2 ="SELECT s.stock, s.costo_unitario FROM stock as s WHERE s.stock>0 and s.id_sucursal='$id_sucursal' ";
	$result2 = _query($sql2);
	$count2 = _num_rows($result2);

	if ($count2>0){
		$IT=0;
		for($i2=0;$i2<$count2;$i2++){
			$row2=_fetch_array($result2);
			$costo=$row2['costo_unitario'];
			$stock=$row2['stock'];
			$s1 = floatval($costo);
			$c1 = floatval($stock);
			$plus = $s1*$c1;
			$IT+=$plus;

		}
	}else{
		$IT=0;
	}
	$MP=number_format ($IT,2 ,"." ,",");
	$men=0;
	$ahora=date("Y-m-d");
	$mesP = date('Y-m-d', strtotime('-1 month')) ;
	$sql3="SELECT pp.id_producto, ie.cantidad, pp.costo, ep.id_examen, ep.id_examen_paciente FROM presentacion_producto as pp
	INNER JOIN insumo_examen as ie ON(pp.id_producto=ie.id_producto) RIGHT JOIN examen_paciente as ep
	ON(ep.id_examen=ie.id_examen) WHERE ie.id_presentacion=pp.id_presentacion AND ep.estado_realizado='Hecho' AND
	 ep.fecha_realizado BETWEEN '$mesP'	AND '$ahora' and ie.id_sucursal='$id_sucursal' and ep.id_sucursal='$id_sucursal'";
	$result3 = _query($sql3);
	$count3 = _num_rows($result3);
	if ($count3>0){
		$men=0;
		for($i3=0;$i3<$count3;$i3++){
			$row3=_fetch_array($result3);
			$cant=$row3['cantidad'];
			$cost=$row3['costo'];
			$s2 = floatval($cant);
			$c2 = floatval($cost);
			$plus = $s2*$c2;
			$men+=$plus;

		}
	}
	$mensual=number_format ($men,2 ,"." ,",");

	$hoy=date("Y-m-d");
	$mesPasado = date('Y-m-d', strtotime('-1 month')) ;
	$sql4="SELECT sum(dc.precio) as total FROM detalle_cobro as dc INNER JOIN examen as e ON(dc.id_examen=e.id_examen) INNER JOIN examen_paciente as ep ON(ep.id_examen=e.id_examen)
	WHERE ep.estado_realizado='Hecho' AND e.id_sucursal='1' AND ep.id_sucursal='1' AND ep.fecha_examen BETWEEN '$mesPasado' AND '$hoy' ";
	$result4 = _query($sql4);
	$datos_user1 = _fetch_array($result4);
	$men1 = $datos_user1["total"];
	$mensual1=number_format ($men1,2 ,"." ,",");

	$utilidad = $mensual1 - $mensual;

//permiso del script
if ($links!='NOT' || $admin=='1' )
                        {
?>
        <div class="row">
          <div class="col-lg-12">
          	<div class="wrapper wrapper-content">
          		<div class="row">

							<div class="col-lg-3">
									<a href="admin_paciente.php">
										<div class="widget style1 navy-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-user fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Gestionar </span>
													<h2 class="font-bold">Pacientes</h2>
												</div>
											</div>
										</div>
									</a>
							</div>

							<div class="col-lg-3">
									<a href="expediente.php">
										<div class="widget style1 yellow-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-archive fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Ver Expediente </span>
													<h2 class="font-bold">Expediente</h2>
												</div>
											</div>
										</div>
									</a>
							</div>

							<div class="col-lg-3">
									<a href="admin_examen.php">
										<div class="widget style1 lazur-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-clipboard fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Gestionar </span>
													<h2 class="font-bold">Examen</h2>
												</div>
											</div>
										</div>
									</a>
							</div>

							<div class="col-lg-3">
									<a href="admin_doctor.php">
										<div class="widget style1 navy-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-user-md fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Gestionar </span>
													<h2 class="font-bold">Doctores</h2>
												</div>
											</div>
										</div>
									</a>
							</div>
							<div class="col-lg-3">
									<a href="venta.php">
										<div class="widget style1 yellow-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-money fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Puntos de venta </span>
													<h2 class="font-bold">Cobros</h2>
												</div>
											</div>
										</div>
									</a>
							</div>
							<div class="col-lg-3">
									<a href="examen_pendiente.php">
										<div class="widget style1 lazur-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-file-text fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Pendiente de realizar </span>
													<h2 class="font-bold">Examenes</h2>
												</div>
											</div>
										</div>
									</a>
							</div>

							<div class="col-lg-3">
									<a href="admin_producto.php">
										<div class="widget style1 navy-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-table fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Consultar </span>
													<h2 class="font-bold">Insumos</h2>
												</div>
											</div>
										</div>
									</a>
							</div>

							<div class="col-lg-3">
									<a href="admin_usuario.php">
										<div class="widget style1 yellow-bg">
											<div class="row">
												<div class="col-xs-4">
													<i class="fa fa-users fa-3x"></i>
												</div>
												<div class="col-xs-8 text-right">
													<span> Gestionar </span>
													<h2 class="font-bold">Usuarios</h2>
												</div>
											</div>
										</div>
									</a>
							</div>
            </div>

	<div class="row"><br>

			<div class="col-lg-6">
					<div class="ibox float-e-margins">
							<div class="ibox-title bg-info">
									<h5 style="color:#FFF;">Examenes por Mes</h5>
									<div class="ibox-tools">
											<a class="collapse-link">
												<i class="fa fa-chevron-up" style="color:#FFF;"></i>
											</a>
									</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
									<div>
											<canvas id="myChart1" style="width: 495px; height: 250px;"></canvas>
									</div>
							</div>
					</div>
			</div>
			<div class="col-lg-6">
					<div class="ibox float-e-margins">
							<div class="ibox-title bg-green">
									<h5 style="color:#FFF;">Examenes Realizados por Año</h5>
									<div class="ibox-tools">
											<a class="collapse-link">
												<i class="fa fa-chevron-up" style="color:#FFF;"></i>
											</a>
									</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.8px;">
									<div>
											<canvas id="myChart" style="width: 495px; height: 250px;"></canvas>
									</div>
							</div>
					</div>
			</div>
	</div>
	<div class="row"><br>
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5 style="color:#000;"> Inversión Actual</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up" style="color:#000;"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content" style="margin-top: 1.8px;">
					<div>
						<h1 class="no-margins">$ <?php echo $MP;?></h1><small>Total Inversión</small>
						<table class="table">
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5 style="color:#000;"> Insumos Mensuales</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up" style="color:#000;"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content" style="margin-top: 1.8px;">
					<div>
						<h1 class="no-margins">$ <?php echo $mensual;?></h1><small>Insumos Consumidos</small>
						<table class="table">
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5 style="color:#000;"> Utilidad Mensual</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up" style="color:#000;"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content" style="margin-top: 1.8px;">
					<div>
						<h1 class="no-margins">$ <?php echo $utilidad;?></h1><small>Total Utilidad Mensual</small>
						<table class="table">
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row"><br>
			<div class="col-lg-6">
					<div class="ibox float-e-margins">
							<div class="ibox-title bg-success">
									<h5>Examenes Pendientes Impresion<label class="badge white-bg" style="margin-left: 15px;" id="count1"><?php echo $count1;?></label></h5>
									<div class="ibox-tools">
											<a class="collapse-link">
												<i class="fa fa-chevron-up" style="color: #fff;"></i>
											</a>
									</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.7px;">
								<div>
									<table class="table">
										<thead>
											<tr>
												<td><strong>ID</strong></td>
												<td><strong>PACIENTE</strong></td>
												<td><strong>EXAMEN</strong></td>
												<td><strong>FECHA</strong></td>
												<td><strong>HORA</strong></td>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($count1>0){
												$id1=1;
												for($i1=0;$i1<$count1;$i1++){
													$row1=_fetch_array($result1);

													echo "<tr>";
													echo"<td>".$id1."</td>
														<td>".Mayu($row1['nombre']." ".$row1['apellido'])."</td>
														<td>".Mayu($row1['nombre_examen'])."</td>
														<td>".ED($row1['fecha_examen'])."</td>
														<td>".hora($row1['hora_examen'])."</td>";
														echo"</div>
																	</td>
																	</tr>";
																	$id1+=1;
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="ibox-content">
								<a href="examen_pendiente_imprimir.php" > <span class="fa fa-plus"></span> Ver mas</a>
							</div>
					</div>
			</div>
			<div class="col-lg-6">
					<div class="ibox float-e-margins">
							<div class="ibox-title bg-success">
									<h5>Examenes Pendientes de Realizar<label class="badge white-bg" style="margin-left: 15px;" id="count1"><?php echo $count;?></label></h5>
									<div class="ibox-tools">
											<a class="collapse-link">
												<i class="fa fa-chevron-up" style="color: #fff;"></i>
											</a>
									</div>
							</div>
							<div class="ibox-content" style="margin-top: 1.7px;">
									<div>
										<table class="table">
											<thead>
												<tr>
													<td><strong>ID</strong></td>
													<td><strong>PACIENTE</strong></td>
													<td><strong>EXAMEN</strong></td>
													<td><strong>FECHA</strong></td>
													<td><strong>HORA</strong></td>
												</tr>
											</thead>
											<tbody>
												<?php
												if ($count>0){
													$id=1;
													for($i=0;$i<$count;$i++){
														$row=_fetch_array($result);

														echo "<tr>";
														echo"<td>".$id."</td>
															<td>".utf8_decode(Mayu($row['nombre']." ".$row['apellido']))."</td>
															<td>".Mayu($row['nombre_examen'])."</td>
															<td>".ED($row['fecha_examen'])."</td>
															<td>".hora($row['hora_examen'])."</td>";
															echo"</div>
																		</td>
																		</tr>";
																		$id+=1;
													}
												}
												?>
											</tbody>
										</table>
									</div>
							</div>
							<div class="ibox-content">
								<a href="examen_pendiente.php"> <span class="fa fa-plus"></span> Ver mas</a>
							</div>
					</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php   }else { ?>
		<div class="row">
			<?php

			$filename='venta.php';
			$link=permission_usr($id_user,$filename);
			if ($link!='NOT' ){
			?>
				<div class="col-lg-3">
					<a href="venta.php">
						<div class="widget style1 yellow-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-money fa-3x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span> Punto de venta </span>
									<h2 class="font-bold">Cobro</h2>
								</div>
							</div>
						</div>
					</a>
			</div>
		<?php }
		$filename='examen_pendiente.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' ){
		 ?>
			<div class="col-lg-3">
					<a href="examen_pendiente.php">
						<div class="widget style1 lazur-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-file-text fa-3x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span> Pendiente de realizar </span>
									<h2 class="font-bold">Examenes</h2>
								</div>
							</div>
						</div>
					</a>
			</div>
		<?php }
		$filename='examen_pendiente_imprimir.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' ){
		?>
		<div class="col-lg-3">
				<a href="examen_pendiente_imprimir.php">
					<div class="widget style1 navy-bg">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-file-text fa-3x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<span>Pendiente de imprimir</span>
								<h2 class="font-bold">Examenes</h2>
							</div>
						</div>
					</div>
				</a>
		</div>
	<?php }
	$filename='admin_cobro.php';
	$link=permission_usr($id_user,$filename);
	if ($link!='NOT' ){
	?>
		<div class="col-lg-3">
			<a href="admin_cobro.php">
				<div class="widget style1 yellow-bg">
					<div class="row">
						<div class="col-xs-4">
							<i class="fa fa-money fa-3x"></i>
						</div>
						<div class="col-xs-8 text-right">
							<span> Gestionar </span>
							<h2 class="font-bold">Cobros</h2>
						</div>
					</div>
				</div>
			</a>
	</div>
<?php }
$filename='admin_corte.php';
$link=permission_usr($id_user,$filename);
if ($link!='NOT' ){
?>
	<div class="col-lg-3">
		<a href="admin_corte.php">
			<div class="widget style1 lazur-bg">
				<div class="row">
					<div class="col-xs-4">
						<i class="fa fa-money fa-3x"></i>
					</div>
					<div class="col-xs-8 text-right">
						<span> Gestionar </span>
						<h2 class="font-bold">Caja</h2>
					</div>
				</div>
			</div>
		</a>
</div>
<?php }
?>

</div>
<?php
}
 include("footer.php");
	echo '<script src="js/funciones/funciones_dashboard.js"></script>';
?>
