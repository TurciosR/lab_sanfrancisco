<?php
include ("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'descuento';

$id_sucursal = $_SESSION["id_sucursal"];
$fini = $_REQUEST["fini"];
$fin = $_REQUEST["fin"];
// Table's primary key
$primaryKey = 'id_descuento';
// MySQL server connection information
$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $dbname,
  'host' => $hostname
);
$joinQuery=" FROM descuento LEFT JOIN empleado ON descuento.id_empleado=empleado.id_empleado";
$extraWhere=" descuento.id_sucursal='$id_sucursal' AND descuento.fecha BETWEEN '$fini' AND '$fin'";

$columns = array(
  array( 'db' => '`id_descuento`', 'dt' => 0, 'field' => 'id_descuento'),
  array( 'db' => '`fecha`',  'dt' => 1, 'field' => 'fecha'),
  array( 'db' => '`hash`',   'dt' => 2, 'field' => 'hash'),
  array( 'db' => '`porcentaje`',   'dt' => 3, 'field' => 'porcentaje'),
  array( 'db' => '`aplicado`',   'dt' => 4, 'formatter' => function($aplicado, $row){ if($aplicado) return "SI"; else return "NO"; },'field' => 'aplicado'),
  array( 'db' => '`empleado`.`nombre`',   'dt' => 5, 'field' => 'nombreaplica', 'as' => 'nombreaplica'),
  array( 'db' => '`fecha_aplicado`',   'dt' => 6, 'field' => 'fecha_aplicado'),
  array( 'db' => '`tipo_doc`',   'dt' => 7, 'field' => 'tipo_doc'),
  array( 'db' => '`numero_doc`',   'dt' => 8, 'field' => 'numero_doc'),
  array( 'db' => 'id_descuento','dt' => 9,
  'formatter' => function($id_descuento, $row){
    $menudrop="<div class='btn-group'>
    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
    <ul class='dropdown-menu dropdown-primary'>";

    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];

    $filename='ver_descuento.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
    {
      $menudrop.="<li><a data-toggle='modal' href='ver_descuento.php?id_descuento=".$id_descuento."' data-target='#viewModal1' data-refresh='true'><i class='fa fa-eye'></i> Ver detalle</a></li>";
    }
    $filename='editar_descuento.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
    {
      $menudrop.="<li><a data-toggle='modal' href='editar_descuento.php?id_descuento=".$id_descuento."' data-target='#viewModal' data-refresh='true'><i class='fa fa-pencil'></i> Editar</a></li>";
    }
    $filename='borrar_descuento.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
    {
      $menudrop.="<li><a data-toggle='modal' href='borrar_descuento.php?id_descuento=".$id_descuento."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-trash'></i> Eliminar</a></li>";
    }
    $menudrop.="</ul>
    </div>";
    return $menudrop;}, 'field' => 'id_descuento')
    );
    echo json_encode(
      SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
    );
    ?>
