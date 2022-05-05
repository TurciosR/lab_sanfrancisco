<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $consulta= $_REQUEST['consulta'];

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
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);
    $id_sucursal=$_SESSION["id_sucursal"];
    $joinQuery = "
	FROM  examen_paciente as ep
  LEFT JOIN examen as e ON (ep.id_examen=e.id_examen)
  LEFT JOIN paciente as p ON (ep.id_paciente=p.id_paciente)
	";
  if($consulta=="1"){
    $extraWhere = "ep.id_examen>0 AND ep.estado_realizado='Pendiente' and ep.id_sucursal='$id_sucursal' and p.id_sucursal='$id_sucursal' and e.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo=0 ";

  }else{
    $extraWhere = "ep.id_examen>0 AND ep.estado_realizado='Pendiente' and ep.id_sucursal='$id_sucursal' and p.id_sucursal='$id_sucursal' and e.id_sucursal='$id_sucursal'  AND ep.examen_paciente_nulo=0 AND ep.fecha_examen= '$consulta' ";

  }
  $columns = array(
  array( 'db' => '`ep`.`id_examen_paciente`', 'dt' => 0, 'field' => 'id_examen_paciente'  ),
  array( 'db' => '`ep`.`fecha_cobro`', 'dt' => 1, 'formatter'=>function($fecha_cobro){
  return ED($fecha_cobro);
  },'field' => 'fecha_cobro' ),
  array( 'db' => "CONCAT(p.nombre,' ',p.apellido)", 'dt' => 2, 'field' => "nombre", 'as'=>'nombre',),
  array( 'db' => '`ep`.`fecha_examen`', 'dt' => 3,'formatter'=>function($fecha_examen){
  return ED($fecha_examen);
  } ,'field' => 'fecha_examen'),
  array( 'db' => '`ep`.`hora_examen`', 'dt' =>4, 'formatter' => function($hora){
    return  hora($hora);
    }, 'field' => 'hora_examen'),

  array( 'db' =>  '`ep`.`id_examen_paciente`', 'dt' => 5, 'formatter' => function ($id_examen_paciente) {
      include("_core.php");
      $sql = _query("SELECT id_cobro,id_paciente FROM examen_paciente WHERE id_examen_paciente='$id_examen_paciente'");
      $datos = _fetch_array($sql);
      $id_paciente = $datos["id_paciente"];
      $id_cobro = $datos["id_cobro"];

      $id_user=$_SESSION["id_usuario"];
      $boton="";
      $filename='examen_pendiente.php';
      $link=permission_usr($id_user,$filename);
      $admin=$_SESSION["admin"];
      if ($link!='NOT' || $admin=='1' ){
        $boton="<a data-toggle='modal' href='modal_realizar_examen.php?id_paciente=$id_paciente&id_cobro=$id_cobro' class='btn btn-primary' role='button' data-target='#viewModal' data-refresh='true'><i class='fa fa-plus icon-large'></i> Procesar</a>";
        }
      return $boton;
  } , 'field' => 'id_examen_paciente' ),

  );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere,"ep.id_paciente,ep.id_cobro")
    );
