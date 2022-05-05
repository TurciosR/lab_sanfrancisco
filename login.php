<?php
session_start();
include ("_conexion.php");
if($_POST){

	//require_once "_conexion.php";
	$user=$_POST["username"];
    $pass=MD5($_POST["password"]);
    $sql = "SELECT * FROM usuario WHERE usuario ='$user' AND password ='$pass' AND activo='1'";
    $result = _query($sql);
	$num = _num_rows($result);
	if($num > 0){
		$row= _fetch_array($result);

			$_SESSION["id_usuario"] = $row['id_usuario'];
			$_SESSION["id_sucursal"] = $row['id_sucursal'];
			$_SESSION["usuario"] = $row['usuario'];
			$_SESSION["nombre"] = $row['nombre'];
			$_SESSION["admin"] = $row['admin'];
			$_SESSION["imagen"] = $row['imagen'];
			//$_SESSION["id_sucursal"] = $row['id_sucursal'];
			if($_SESSION["admin"]==1)
			{
				$sql_empresa=_query("SELECT * FROM sucursal,municipio WHERE sucursal.id_municipio=municipio.id_municipio ");
				$array_empresa=_fetch_array($sql_empresa);
				/*	Guardando el id_municipalidad para que aparezca en admin_info_alcaldia.php		*/
				$_SESSION["id_municipalidad"] = $array_empresa['id_municipalidad'];
				$_SESSION["id_municipio"] = $array_empresa['id_municipio'];
				header('location: dashboard.php');
			}
			else
			{
				header('location: dashboard.php');
			}


	}else{
		$error_msg = "Datos ingresados no son correctos";
		header('location: login.php');
	}
	db_close();
}

// Page setup
$_PAGE = array();
$_PAGE['title'] = 'Login';
$_PAGE['links'] = null;
$_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';
$_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
include_once "header.php";

$sql_empresa=_query("SELECT * FROM sucursal,municipio WHERE sucursal.id_municipio=municipio.id_municipio ");
	$array_empresa=_fetch_array($sql_empresa);
	$nombre_empresa=$array_empresa['nombre_municipio'];
	$telefono=$array_empresa['telefono1'];
	$logo_empresa=$array_empresa['logo'];



?>
<body class="gray-bg">
	<div class="loginColumns animated fadeInUp" style="margin-top:-5%;">
		<div class="loginColumns animated fadeInLeft">
		<div class="row">
			<div class="col-md-6" style="margin-top:-2%;">
				<h2 class="font-bold">Consola de Administración</h2>
				<p>
					Por favor ingrese las credenciales, luego pulse en el boton login.
				</p>
				<div>
					<center>
				 		<img alt="image" class="logo" src="<?php echo "./".$logo_empresa; ?> ">
					</center>
				</div>
			</div>
			<div class="col-sm-6 b-r">
				<div class="ibox-content">
					<p class="m-t">
						<?php
						if(isset($error_msg)){
							echo "<strong>$error_msg</strong>";
						}
						?>
					</p>
					<form class="m-t" role="form" method="POST">
						<div class="form-group">
							<label for="User Name">Usuario</label>
							<input type="text" class="form-control" placeholder="Nombre de usuario" required="" id="username" name="username">
						</div>
						<div class="form-group">
							<label for="User Name">Clave</label>
							<input type="password" class="form-control" placeholder="Clave" required="" id="password" name="password">
						</div>
						<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
					</form>
				</div>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-md-6">
				Sistema de Laboratorio Clinico
			</div>
			<div class="col-md-6 text-right">
				<small>Todos los derechos reservados <a href="http://opensolutionsystems.com" target="_blank">Open Solution Systems</a> © <?=date('Y');?></small>
			</div>
		</div>
	</div>
</div>

</html>
