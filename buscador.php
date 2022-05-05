<?php
include_once "_conexion.php";

$query = $_REQUEST['query'];
$sql0="SELECT id_producto as id, nombre FROM producto  WHERE nombre'$query'";
$result = _query($sql0);
$numrows= _num_rows($result);
if ($numrows==0){
 $sql="SELECT id_producto as id, nombre FROM producto  WHERE nombre LIKE '%{$query}%'";
 $result = _query($sql);
//$numrows = mysql_num_rows($result);
}
//$sql = mysql_query("SELECT producto.id_producto,producto.descripcion,producto.marca FROM producto WHERE descripcion LIKE '%{$query}%'");
$array_prod = array();

while ($row = _fetch_array($result)) {
    $array_prod[] =$row['id']."|".$row['nombre']."";

}
//echo $array_prod;
echo json_encode ($array_prod);?>
