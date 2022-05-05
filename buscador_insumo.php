<?php
include_once "_conexion.php";
include_once "_session.php";
$id_sucursal=$_SESSION["id_sucursal"];
$query = $_REQUEST['query'];
$sql0="SELECT * FROM producto WHERE id_sucursal='$id_sucursal'and descripcion LIKE '".$query."%'";
$result = _query($sql0);
$array_prod = array();
while ($row = _fetch_array($result))
{
  $array_prod[] =$row['id_producto']."|".$row['descripcion']."";
}
echo json_encode ($array_prod);
?>
