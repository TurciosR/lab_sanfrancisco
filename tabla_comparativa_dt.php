<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $desde = $_REQUEST['desde1'];
    $hasta = $_REQUEST['hasta1'];
    $id_expediente= $_REQUEST['id_expediente'];
    $id_sucursal=$_SESSION["id_sucursal"];

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'examen';
    // Table's primary key
    $primaryKey = 'id_examen';

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
    FROM examen as e
    JOIN examen_paciente as ep ON (ep.id_examen=e.id_examen)
    JOIN categoria as c ON c.id_categoria=e.id_categoria
    JOIN expediente as xp ON (xp.id_paciente=ep.id_paciente)
    JOIN paciente as p ON (ep.id_paciente=p.id_paciente)";
    $extraWhere = "xp.id_expediente='1' and xp.id_sucursal='$id_sucursal' AND ep.id_examen>0 AND ep.estado_realizado='Hecho' AND ep.examen_paciente_nulo= 0  GROUP BY e.id_examen";
    $columns = array(
    array( 'db' => '`e`.`id_examen`', 'dt' => 0, 'field' => 'id_examen'  ),
    array( 'db' => '`e`.`nombre_examen`', 'dt' => 1, 'field' => 'nombre_examen'),
    array( 'db' => '`c`.`nombre_categoria`', 'dt' => 2, 'field' => 'nombre_categoria'),
    array( 'db' => '`e`.id_examen', 'dt' => 3, 'formatter' => function ($id_examen) {
        include("_core.php");
        $id_user=$_SESSION["id_usuario"];
        $boton="";
        $id_expediente= $_REQUEST['id_expediente'];
        $filename='tabla_comparativa_pdf.php';
        $link=permission_usr($id_user,$filename);
        $admin=$_SESSION["admin"];
        //if ($link!='NOT' || $admin=='1') {
                $boton="<a class='btn btn-primary' target='_blank' href='$filename?id_examen=$id_examen&id_expediente=$id_expediente'  data-refresh='true'><i class='fa fa-eye'></i> Visualizar Datos</a>";
        //}
        return $boton;
    } , 'field' => 'id_examen' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );
