<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $fecha= $_REQUEST['fecha'];

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'examen_paciente';
    // Table's primary key
    $primaryKey = 'id_examen_paciente';

    // MySQL server connection information
    $sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname,
    'host' => $hostname
    );

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $id_sucursal=$_SESSION["id_sucursal"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $joinQuery = " FROM paciente as p RIGHT JOIN ( SELECT *FROM examen_paciente ORDER BY fecha_realizado DESC) as ep ON (p.id_paciente=ep.id_paciente)";
    if($fecha=="1"){
        $extraWhere = "ep.id_examen>0 AND ep.estado_impresion='Pendiente' AND ep.estado_realizado = 'Hecho' and ep.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo=0 and p.id_sucursal='$id_sucursal'";
    }else{
        $extraWhere = "ep.id_examen>0 AND ep.estado_impresion='Pendiente'  AND ep.estado_realizado = 'Hecho' and ep.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo=0 and p.id_sucursal='$id_sucursal' AND ep.fecha_realizado= '$fecha'";
    }
    $columns = array(
    array( 'db' => '`ep`.`id_examen_paciente`', 'dt' => 0, 'field' => 'id_examen_paciente'  ),
    array( 'db' => "CONCAT(p.nombre,' ',p.apellido)", 'dt' => 1, 'field' => "nombre", 'as'=>'nombre',),  
    array( 'db' => '`ep`.`fecha_cobro`', 'dt' => 2, 'field' => 'fecha_cobro' ),
    array( 'db' => '`ep`.`hora_cobro`', 'dt' => 3,  'formatter' => function($hora){
        return  hora($hora);
    }, 'field' => 'hora_cobro' ),
    array( 'db' => '`ep`.`fecha_realizado`', 'dt' => 4, 'field' => 'fecha_realizado'),
    array( 'db' => '`ep`.`hora_realizado`', 'dt' =>5 , 'formatter' => function($hora){
      return  hora($hora);
      }, 'field' => 'hora_realizado'),
      array( 'db' => 'id_examen_paciente', 'dt' => 6, 'formatter' => function ($id_examen_paciente) {
        $sql = _query("SELECT fecha_realizado,hora_realizado FROM examen_paciente WHERE id_examen_paciente='$id_examen_paciente'");
        $datos = _fetch_array($sql);
        $fecha_realizado = $datos["fecha_realizado"];
      	$hora_realizado = $datos["hora_realizado"];
        date_default_timezone_set('America/El_Salvador');
        $fecha_hoy=date("Y-m-d");
        $hora_hoy=date("H:i:s");

        $fecha1 = new DateTime("".$fecha_realizado." ".$hora_realizado."");
        $fecha2 = new DateTime("".$fecha_hoy." ".$hora_hoy."");
        $fecha = $fecha1->diff($fecha2);
        $horas_y=(($fecha->y)*365)*24;
        $horas_m=(($fecha->m)*30)*24;
        $horas_d=(($fecha->d)*24);
        $horas=($fecha->h)+$horas_y+$horas_m+$horas_d;
        $minutos=$fecha->i;
        $segundos=$fecha->s;
        $tiempo=$horas.":".$minutos;

        return $tiempo;

      } , 'field' => 'id_examen_paciente' ),
    array( 'db' => '`ep`.`id_cobro`', 'dt' => 7, 'formatter' => function ($id_cobro) {
      $ids="";
      $id_sucursal=$_SESSION["id_sucursal"];
        include("_core.php");
        $boton="";
        $id_user=$_SESSION["id_usuario"];
        $boton="";
        $filename='examen_pendiente_imprimir.php';
        $link=permission_usr($id_user,$filename);
        $admin=$_SESSION["admin"];
        //if ($link!='NOT' || $admin=='1') {
          $boton="<a href='modal_imprimir_examen.php?id_cobro=$id_cobro' class='btn btn-primary' role='button' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-print icon-large'></i> Imprimir</a>";
        //}
        return $boton;
    } , 'field' => 'id_cobro' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere,"ep.id_cobro")
    );
