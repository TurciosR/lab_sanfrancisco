<?php
header("Access-Control-Allow-Origin: *");
include_once "_conexion.php";
$process = $_REQUEST['process'];
if($process == "aut")
{
	$id_sucursal=2;//$_SESSION["id_sucursal"];
	$query = $_REQUEST['query'];
	$sql="SELECT e.id_examen as id,concat(e.nombre_examen,'|', 'E')as nombre,  e.precio_examen as precio FROM examen as e
	WHERE e.nombre_examen LIKE '%$query%' and e.id_sucursal='$id_sucursal'
	UNION
	SELECT p.id_perfil as id,concat(p.nombre_perfil,'|', 'P')as nombre ,p.precio_perfil as precio  FROM perfil as p
	WHERE  p.nombre_perfil LIKE '%$query%'  AND p.id_sucursal='$id_sucursal'
	ORDER BY nombre ASC
		 	";
		//echo $sql;
	$result = _query($sql);
	$numrows = _num_rows($result);
	$array_prod = array();
	if ($numrows>0){
		/*
		$row1 = _fetch_array($result1);
		$id_producto=
		$sql_existencia = "SELECT su.id_producto, su.cantidad, su.id_ubicacion, u.id_ubicacion, u.bodega
		FROM stock_ubicacion as su, ubicacion as u
		WHERE su.id_producto = '$id_producto' AND su.id_ubicacion = u.id_ubicacion AND u.bodega != 1 ORDER BY su.id_su ASC";
		$resul_existencia = _query($sql_existencia);
		$cuenta_existencia = _num_rows($resul_existencia);
		*/

		while ($row = _fetch_assoc($result)) {

				$array_prod[] =$row['id']."|".$row['nombre'];
		}
	}
	echo json_encode ($array_prod); //Return the JSON Array
}
if($process == "consultar_stock")
{
	$id_producto = $_REQUEST['id_producto'];
	$id_sucursal=2;//$_SESSION['id_sucursal'];
	$tipo=$_REQUEST['tipo'];
	$nombre_examen = "";
	$id_prods = "";
	$select2 = "";
	$cortesia = "";
	$cuantos = 0;
	if($tipo == "E")
	{
		$xdatos["precio_p"] = 0;

		$sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_producto' AND e.id_sucursal='$id_sucursal'";
		$stock1=_query($sql1);
		$row1=_fetch_array($stock1);
		$nombre_examen = $row1['nombre_examen']."|";
		$id_prods = $row1['id_examen']."|";
		$precio= $row1['precio_examen'];
		//$xdatos["precio_p"]= $row1['precio_examen'];
		$select2 .= $precio."|";
		$cortesia = "<input type='checkbox' id='activar' name='activar' class='checkbox i-checks cort'>|";
		$cuantos = 1;
	}
	else
	{
		$sql_aux = _query("SELECT precio_perfil FROM perfil WHERE id_perfil='$id_producto' AND id_sucursal='$id_sucursal'");
		$dats = _fetch_array($sql_aux);
		$xdatos["precio_p"] = $dats["precio_perfil"];
		$xdatos["cortesia_p"] = "<input type='checkbox' id='activar' name='activar' class='checkbox i-checks cort'>";
		$sql_p = _query("SELECT * FROM examen_perfil WHERE id_perfil='$id_producto' AND id_sucursal='$id_sucursal'");
		$nombre_examen = "";
		$id_prods = "";
		$select2 = "";
		$cortesia = "";
		$cuantos = 0;
		while($row = _fetch_array($sql_p))
		{
			$cuantos++;
			$id_prod = $row["id_examen"];
			$sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_prod' AND e.id_sucursal='$id_sucursal'";
			$stock1=_query($sql1);
			$row1=_fetch_array($stock1);
			$nombre_examen.=$row1['nombre_examen']."|";
			$id_prods .= $row1['id_examen']."|";
			$precio = $row1['precio_examen'];
			$select2 .= "|";
			$cortesia .= "|";
		}
	}
	$xdatos['select2']= $select2;
	$xdatos['cortesia']= $cortesia;
	/*  $xdatos['horas']= $hora;
	$xdatos['fecha']= $fecha;*/
	$xdatos['id_prods']= $id_prods;
	$xdatos['descripcionp']= $nombre_examen;
	$xdatos['cuantos']= $cuantos;

	echo json_encode($xdatos); //Return the JSON Array
}
?>
