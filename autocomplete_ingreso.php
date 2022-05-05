<?php
include_once "_conexion.php";
$query = $_REQUEST['query'];
$id_sucursal = $_REQUEST['id_sucursal'];
$sql0="SELECT pr.id_producto,pr.descripcion, pr.barcode FROM producto AS pr WHERE pr.id_producto and pr.id_sucursal='$id_sucursal' AND pr.descripcion LIKE '".$query."%'";
$result = _query($sql0);
$array_prod = array();

while ($row = _fetch_array($result)) {
    $array_prod[] =$row['id_producto']." | ".$row['descripcion']."";

}
//echo $array_prod;
echo json_encode ($array_prod);?>
