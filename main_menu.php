<?php
include("_core.php");
date_default_timezone_set('America/El_Salvador');
$id_sucursal = $_SESSION["id_sucursal"];
//////////////////////////////////VERIFICACION TIPO PAGO /////////////////////////////////////////
$credito_tipo_pago=0;
$remisiones_tipo_pago=0;
$sql_tipo_pago=_query("SELECT * FROM tipo_pago WHERE id_sucursal='$id_sucursal'");
while ($row = _fetch_array($sql_tipo_pago))
{
 $descripcion=$row["descripcion"];
 $estado=$row["estado"];

 if($descripcion=="CREDITO" AND $estado==0){
	 $credito_tipo_pago=14;
 }
 /*if($descripcion=="REMISIONES" AND $estado==1){
	 $remisiones_tipo_pago=0;
 }*/
}
/////////////////////////////////FIN VERIFICACION TIPO PAGO /////////////////////////////////////////
//////////////////////////////////VERIFICACION CONDICION PAGO /////////////////////////////////////////
$transferencia_con_pago=0;
$sql_con_pago=_query("SELECT * FROM condicion_pago WHERE id_sucursal='$id_sucursal'");
while ($row = _fetch_array($sql_con_pago))
{
 $descripcion=$row["descripcion"];
 $estado=$row["estado"];
 if($descripcion=="TRANSFERENCIA" AND $estado==0){
	 $transferencia_con_pago=15;
 }
}
/////////////////////////////////FIN VERIFICACION CONDICION  PAGO /////////////////////////////////////////
$sql_empresa=_query("SELECT * FROM sucursal,municipio WHERE sucursal.id_municipio=municipio.id_municipio");
$array_empresa=_fetch_array($sql_empresa);
$nombre_empresa=$array_empresa['nombre_municipio'];
$telefono=$array_empresa['telefono1'];
$logo_empresa=$array_empresa['logo'];
$credito =$array_empresa['credito'];
$texto_bienvenida_encabezado="Laboratorio clinico ";
$nombre_lab=$array_empresa["nombre_lab"];
$tipo_Fa=$array_empresa["tipo_facturacion"];
$tipo_Pa=$array_empresa["tipo_pag"];
/*if($credito==1){
	$sql_menus="SELECT id_menu, nombre, prioridad,icono FROM menu
							ORDER BY prioridad ASC";
}else if($credito==0) {*/
	$sql_menus="SELECT id_menu, nombre, prioridad,icono FROM menu
							WHERE menu.id_menu!=$credito_tipo_pago AND menu.id_menu!=$transferencia_con_pago
							ORDER BY prioridad ASC";
//}
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
					<img alt="image" class="logo" id='logo_menu' width="120" height="130" src="<?php echo "./".$logo_empresa; ?> ">

				</span>


			</div>
			<div class="logo-element">
				PB
			</div>
		</li>
		<!--li-->
		<!--a href="index.html"><i class="fa fa-archive"></i> <span class="nav-label">Productos</span> <span class="fa arrow"></span></a-->
		<?php
		//&& $active=='t'
		include_once '_core.php';
		$id_user=$_SESSION["id_usuario"];
		$admin=$_SESSION["admin"];


		//VALIDACION DE CAJA
		$fecha_actual = date("Y-m-d");
		$sql_apertura = _query("SELECT * FROM apertura_caja WHERE id_sucursal = '$id_sucursal' AND vigente = 1");
		$cuenta = _num_rows($sql_apertura);
		if($cuenta > 0)
		{

				while ($row_a = _fetch_array($sql_apertura))
				{
					$id_apertura = $row_a["id_apertura"];
					$fecha_ape = $row_a['fecha'];
					if($fecha_actual != $fecha_ape)
					{
							$tabla = "apertura_caja";
							$form_data = array(
									'vigente' => 0,
									'turno_vigente' => 0,
									);
							$where_up = "id_apertura='".$id_apertura."'";
							$update = _update($tabla, $form_data, $where_up);
							if($update)
							{
									$table_up = "detalle_apertura";
									$form_up = array(
											'vigente' => 0,
											);
									$where_deta = "id_apertura='".$id_apertura."' AND vigente = 1";
									$up_date = _update($table_up,$form_up, $where_deta);

							}
					}
				}

		}
		$icono='fa fa-star-o';
		$result=_query($sql_menus);
		$numrows=_num_rows($result);
		$main_lnk='dashboard.php';
		if($admin=='1'){
			echo  "<li class='active'>";
			echo "<a href='dashboard.php'><i class='".$icono."'></i> <span class='nav-label'>Inicio</span></a>";
			echo  "</li>";
		}
		else{
			echo  "<li class='active'>";
			echo "<a href='dashboard.php'><i class='".$icono."'></i> <span class='nav-label'>Inicio</span></a>";
			echo  "</li>";
		}
		for($i=0;$i<$numrows;$i++){
			$row=_fetch_array($result);
			$menuname=$row['nombre'];
			$id_menu=$row['id_menu'];
			$icono=$row['icono'];


			if($admin=='1'){
				$sql_links="SELECT distinct menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
				modulo.id_modulo, modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename, usuario.admin
				FROM menu, modulo, usuario
				WHERE usuario.id_usuario='$id_user'
				AND usuario.admin='1'
				AND menu.id_menu='$id_menu'
				AND menu.id_menu=modulo.id_menu
				AND modulo.mostrarmenu='1'
				";
			}
			else {
				$sql_links="
				SELECT menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
				modulo.id_modulo,  modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename,
				usuario_modulo.id_usuario,usuario.admin
				FROM menu, modulo, usuario_modulo, usuario
				WHERE usuario.id_usuario='$id_user'
				AND menu.id_menu='$id_menu'
				AND usuario.id_usuario=usuario_modulo.id_usuario
				AND usuario_modulo.id_modulo=modulo.id_modulo
				AND menu.id_menu=modulo.id_menu
				AND modulo.mostrarmenu='1'
				";

			}
			$result_modules=_query($sql_links);
			$numrow2=_num_rows($result_modules);
			if($numrow2>0){
				echo "<li><a href='".$main_lnk."'><i class='".$icono."'></i></i> <span class='nav-label'>".$menuname."</span> <span class='fa arrow'></span></a>";
				echo " <ul class='nav nav-second-level'>";
				for($j=0;$j<$numrow2;$j++){
					$row_modules=_fetch_array($result_modules);
					$lnk=strtolower($row_modules['filename']);
					$modulo=$row_modules['nombremodulo'];
					$id_modulo=$row_modules['id_modulo'];
					echo "<li><a href='".$lnk."'>".ucfirst($modulo)."</a></li>";
				}
				echo"</ul>";
				echo" </li>";
			}

		}


		?>


	</div>
</nav>
<div id="page-wrapper" class="gray-bg">
	<div class="row border-bottom">
		<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<a class="navbar-minimalize minimalize-styl-2 btn btn-primary"><i class="fa fa-bars"></i> </a>
			</div>
			<?php
							$id_sucursal=$_SESSION["id_sucursal"];
							$qsucursal=_query("SELECT nombre_lab FROM sucursal WHERE id_sucursal='$id_sucursal'");
							$row_sucursal=_fetch_array($qsucursal);
							$sucursal=$row_sucursal["nombre_lab"];

					?>
				<ul class="nav navbar-top-links navbar-right">
					<li>

					</li>
					<li class="dropdown top-menu-item-xs">
							<a href="" class="dropdown-toggle " data-toggle="dropdown" aria-expanded="true" style="background:transparent"><span class="m-r-sm text-muted welcome-message">Bienvenido <b><?php echo $_SESSION["nombre"].", ".$sucursal ?>   </b></span><img style="margin-top: -25px;margin-bottom: -25px;  width:36px; height:36px;" src="<?php if($_SESSION["imagen"] !=""){echo $_SESSION["imagen"];} else{ echo "img/5bb4eddc7659f_icono.jpg"; } ?>" alt="user-img" class="img-circle"></a>
							<ul class="dropdown-menu" style="margin-top: 1px; border-top-left-radius: 0; border-top-right-radius: 0;  box-shadow:1px 1px 2px 1px #e7eaec;">
									<li><a href="perfil.php" style="margin:0; margin-top: 5px; margin-bottom: 5px;  border-radius:0;"><i class="fa fa-user" style="color:rgb(69, 189, 241)"></i> Perfil</a></li>
									<li><a href="logout.php" style="margin:0; margin-bottom: 5px; border-radius:0"><i class="fa fa-sign-out"  style="color:rgb(255, 117, 117)"></i> Cerrar sesión</a></li>
							</ul>
					</li>

				</ul>

		</nav>
</div>
