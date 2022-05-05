<?php
include_once "_core.php";
$id_sucursal=$_SESSION["id_sucursal"];
$query = $_REQUEST['query'];
$sql0="SELECT id_paciente, CONCAT(nombre,' ',apellido) AS nombre, 
    sexo, TIMESTAMPDIFF(YEAR,Fecha_nacimiento,CURDATE()) AS fecha_nacimiento_edad,
    fecha_nacimiento, telefono, pasaporte, paciente.dui FROM paciente  
    WHERE id_sucursal='$id_sucursal' AND CONCAT(nombre,' ',apellido) 
    LIKE '%$query%'";
$result = _query($sql0);
$array_prod = array();

while ($row = _fetch_array($result)) {
    $array_prod[] =$row['id_paciente']."| ".$row['nombre']." |".$row['fecha_nacimiento_edad']."|".$row['sexo']."|".$row['fecha_nacimiento']."|".$row['telefono']."|".$row['pasaporte']."|".$row['dui'];
}

echo json_encode ($array_prod);
?>
