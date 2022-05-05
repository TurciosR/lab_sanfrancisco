<?php
include_once "_core.php";
$id_sucursal=$_SESSION["id_sucursal"];
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
?>
