<?php
include("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php');
// DB table to use
$table = 'movimiento_producto';

$origen=$_REQUEST['origen'];
$pro=$_REQUEST['pro'];
$estado=$_REQUEST['estado'];

/*
$hostname = "localhost";
$username = "libreria";
$password = "L1br3r1@18";
$dbname
*/
// Table's primary key
$primaryKey = 'id_movimiento';
// MySQL server connection information
$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $dbname,
  'host' => $hostname
);
$joinQuery="";
$joinQuery="
FROM movimiento_producto  JOIN traslado ON traslado.id_traslado=movimiento_producto.id_traslado
LEFT JOIN usuario ON usuario.id_usuario=traslado.id_empleado_envia
LEFT JOIN usuario as us ON us.id_usuario=traslado.id_empleado_recibe ";

$est="";

switch ($estado) {
  case 'fi':
    # code...
    $est="AND traslado.anulada=0 AND traslado.finalizada=1 ";
    break;
  case 'an':
    # code...
    $est="AND traslado.anulada=1 AND traslado.finalizada=0 ";
    break;
  case 'pe':
    # code...
    $est="AND traslado.anulada=0 AND traslado.finalizada=0 ";
    break;

  default:
    # code...

    break;
}

$ubi="";


$extraWhere="";
if ($pro=="gen") {
  # code...
  if ($origen=="gen") {
    # code...
    if ($estado=="pe") {
      # code...
      $extraWhere="  traslado.id_sucursal_destino=$_SESSION[id_sucursal]  $est ";
    }
    else
    {
      $extraWhere="  movimiento_producto.id_sucursal=$_SESSION[id_sucursal]  $est ";
    }
  }
  else {
    # code...
    $extraWhere="  movimiento_producto.id_sucursal=$_SESSION[id_sucursal]  $est ";
  }
}

if ($pro=="env") {
  $extraWhere="  movimiento_producto.id_sucursal=$_SESSION[id_sucursal] AND movimiento_producto.proceso='TRE'  $est ";
}
if ($pro=="rec") {
  # code...
  $extraWhere="  movimiento_producto.id_sucursal=$_SESSION[id_sucursal]   AND movimiento_producto.proceso='TRR' $est ";
}




$columns = array(
  array( 'db' => 'movimiento_producto.id_movimiento',  'dt' => 0, 'field' => 'id_movimiento'),
  array( 'db' => 'movimiento_producto.fecha', 'dt' => 1, 'field' => 'fecha'),
  array( 'db' => 'movimiento_producto.hora', 'dt' => 2,
  'formatter' => function ($hora, $row) {

    $hora=hora($hora);
    return $hora;
    },
  'field' => 'hora'),
  array( 'db' => 'movimiento_producto.id_movimiento',   'dt' => 3,
  'formatter' =>
  function ($id_movimiento, $row) {

    $sel=_fetch_array(_query("SELECT id_traslado FROM movimiento_producto WHERE id_movimiento=$id_movimiento "));
    $id_traslado=$sel['id_traslado'];

    $sql_suc=_fetch_array(_query("SELECT CONCAT('Sucursal ',sucursal.nombre_lab,' ',sucursal.direccion) as origen FROM traslado JOIN sucursal ON traslado.id_sucursal_origen=sucursal.id_sucursal WHERE traslado.id_traslado=$id_traslado"));
    $a=utf8_decode(Mayu(utf8_decode($sql_suc['origen'])));
    return $a;
    },
  'field' => 'id_movimiento'),
  array( 'db' => 'movimiento_producto.id_movimiento',   'dt' => 4,
  'formatter' =>
  function ($id_movimiento, $row) {

    $sel=_fetch_array(_query("SELECT id_traslado FROM movimiento_producto WHERE id_movimiento=$id_movimiento "));
    $id_traslado=$sel['id_traslado'];

    $sql_suc=_fetch_array(_query("SELECT CONCAT('Sucursal ',sucursal.nombre_lab,' ',sucursal.direccion) as destino FROM traslado JOIN sucursal ON traslado.id_sucursal_destino=sucursal.id_sucursal WHERE traslado.id_traslado=$id_traslado"));
    $a=utf8_decode(Mayu(utf8_decode($sql_suc['destino'])));
    return $a;
    },
  'field' => 'id_movimiento'),
  array( 'db' => 'usuario.nombre',   'dt' => 5, 'field' => 5),
  array( 'db' => 'us.nombre',   'dt' => 6, 'field' => 6),
  array( 'db' => 'movimiento_producto.id_movimiento',   'dt' => 7,
  'formatter' => function ($id_movimiento, $row) {

    $sel=_fetch_array(_query("SELECT id_traslado FROM movimiento_producto WHERE id_movimiento=$id_movimiento "));
    $id_traslado=$sel['id_traslado'];

    $sql_tra=_fetch_array(_query("SELECT * FROM traslado WHERE id_traslado=$id_traslado"));
    $finalizada=$sql_tra['finalizada'];
    $anulada=$sql_tra['anulada'];
    $val="";
    if ($anulada==0&&$finalizada==0) {

      $val="<strong class='text-info'>PENDIENTE</strong>";
    }
    if ($anulada==1&&$finalizada==0) {
      # code...
      $val="<strong class='text-danger'>NULA</strong>";
    }
    if ($anulada==0&&$finalizada==1) {
      # code...
      $val="<strong class='text-primary'>FINALIZADA</strong>";
    }

    return $val;
    },
   'field' => 'id_movimiento'),
  array( 'db' => 'movimiento_producto.id_movimiento','dt' => 8,
  'formatter' => function ($id_movimiento, $row) {
      $sql=_query("SELECT * FROM movimiento_producto WHERE movimiento_producto.id_movimiento=$id_movimiento");
      $row=_fetch_array($sql);
      $tipo=$row['proceso'];

      $sel=_fetch_array(_query("SELECT id_traslado FROM movimiento_producto WHERE id_movimiento=$id_movimiento "));
      $id_traslado=$sel['id_traslado'];


      $sql_tra=_fetch_array(_query("SELECT * FROM traslado WHERE id_traslado=$id_traslado"));
      $finalizada=$sql_tra['finalizada'];
        $anulada=$sql_tra['anulada'];
      $id_sucursal_envia=$sql_tra['id_sucursal_origen'];
      $id_sucursal_recibe=$sql_tra['id_sucursal_destino'];

      $menudrop="<div class='btn-group'>
      <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
      <ul class='dropdown-menu dropdown-primary'>";
      $id_user=$_SESSION["id_usuario"];
      $admin=$_SESSION["admin"];
      $id_sucursal=$_SESSION['id_sucursal'];

      if ($id_sucursal_recibe==$id_sucursal&&$finalizada==0&&$anulada==0) {
        # code...
        $filename='recibir_traslado.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.= "<li><a href='recibir_traslado.php?id_movimiento=".$id_movimiento."' ><i class=\"fa fa-plus\"></i> Recibir Traslado</a></li>";
        }
      }

      $filename='ver_traslado.php';
      $link=permission_usr($id_user, $filename);
      if ($link!='NOT' || $admin=='1') {
          $menudrop.= "<li><a data-toggle='modal'  href='ver_traslado.php?id_traslado=".$id_traslado."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Detalle Traslado</a></li>";
      }

      $filename='reporte_traslado.php';
      $link=permission_usr($id_user, $filename);
      if ($link!='NOT' || $admin=='1') {
          $menudrop.= "<li><a href='reporte_traslado.php?id_traslado=".$id_traslado."' target='_blank'><i class=\"fa fa-print\"></i> Reporte Traslado</a></li>";
      }

      if ($tipo=="TRR"&&$finalizada==1) {
        # code...
        $filename='reporte_traslado_recibido.php';
        $link=permission_usr($id_user, $filename);
        if ($link!='NOT' || $admin=='1') {
            $menudrop.= "<li><a href='reporte_traslado_recibido.php?id_traslado=".$id_traslado."' target='_blank'><i class=\"fa fa-print\"></i> Reporte Traslado Recibido</a></li>";
        }
      }



      $sql=_fetch_array(_query("SELECT SUM(traslado.anulada) as ANULADA FROM traslado JOIN movimiento_producto ON movimiento_producto.id_traslado=traslado.id_traslado  WHERE movimiento_producto.id_movimiento=$id_movimiento"));
      $sum=$sql['ANULADA'];
      $val="";

      if ($sum==0&&$tipo=="TRE"&&$finalizada==0&&$id_sucursal_envia==$id_sucursal) {
          $filename='anular_traslado.php';
          $link=permission_usr($id_user, $filename);
          if ($link!='NOT' || $admin=='1') {
              $menudrop.= "<li><a data-toggle='modal' href='anular_traslado.php?id_movimiento=".$id_movimiento."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-times\"></i> Anular</a></li>";
          }
      }

      $menudrop.="</ul>
          </div>";
      return $menudrop;
      }, 'field' => 'id_movimiento')
        );
        echo json_encode(
          SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
        );
