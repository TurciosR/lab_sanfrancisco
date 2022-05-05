<?php
include_once "_core.php";
$query = $_REQUEST['query'];
$id_sucursal = $_SESSION['id_sucursal'];
$sql0="SELECT *FROM examen WHERE id_sucursal = '$id_sucursal' AND nombre_examen LIKE '".$query."%'";
$result = _query($sql0);
$array_prod = array();
while ($row = _fetch_array($result))
{
  $array_prod[] =$row['id_examen']."|".$row['nombre_examen']."";
}
echo json_encode ($array_prod);
?>
