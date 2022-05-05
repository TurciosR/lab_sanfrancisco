<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $desde = $_REQUEST['desde'];
    $hasta = $_REQUEST['hasta'];
    $id_paciente=$_REQUEST['id_expediente'];
    $id_sucursal=$_SESSION["id_sucursal"];

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

    $joinQuery = "
    FROM  examen_paciente as ep
    JOIN examen as e ON (ep.id_examen=e.id_examen)
    JOIN expediente as xp ON (xp.id_paciente=ep.id_paciente)
    JOIN paciente as p ON (ep.id_paciente=p.id_paciente)
    LEFT JOIN doctor as dr ON (ep.id_doctor=dr.id_doctor)";
    $extraWhere = "p.id_paciente='$id_paciente' AND xp.id_sucursal='$id_sucursal' AND ep.id_examen>0 AND ep.estado_realizado='Hecho' AND ep.examen_paciente_nulo= 0 AND ep.fecha_realizado BETWEEN '$desde' AND '$hasta'";
    $columns = array(
    array( 'db' => '`ep`.`id_examen_paciente`', 'dt' => 0, 'field' => 'id_examen_paciente'  ),
    array( 'db' => '`e`.`nombre_examen`', 'dt' => 1, 'field' => 'nombre_examen'),
    array( 'db' => '`ep`.`fecha_cobro`', 'dt' => 2, 'formatter'=> function($fecha_cobro){
		return ED($fecha_cobro);
		},'field' => 'fecha_cobro' ),
    array( 'db' => '`ep`.`hora_cobro`', 'dt' => 3, 'field' => 'hora_cobro' ),
    array( 'db' => '`dr`.`nombre`', 'dt' =>4 , 'field' => 'nombre'),
    array( 'db' => 'id_examen_paciente', 'dt' => 5, 'formatter' => function ($id_examen_paciente) {
        include("_core.php");
        /*$menudrop="<div class='btn-group'>
						<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
						<ul class='dropdown-menu dropdown-primary'>";*/
        $id_user=$_SESSION["id_usuario"];
        $boton="";
        $filename='impresion_examen_individual.php';
        $link=permission_usr($id_user,$filename);
        $admin=$_SESSION["admin"];
        //if ($link!='NOT' || $admin=='1') {
        $boton="<a class='btn btn-primary' target='_blank' href='$filename?id_examen_paciente=$id_examen_paciente'  data-refresh='true'><i class='fa fa-file-pdf-o'></i> Imprimir Examen</a>";
        //}
        return $boton;
    } , 'field' => 'id_examen_paciente' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );
