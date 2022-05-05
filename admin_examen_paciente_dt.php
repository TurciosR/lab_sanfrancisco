<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $fechai= $_REQUEST['fechai'];
	  $fechaf= $_REQUEST['fechaf'];

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

    $extraWhere = "ep.id_examen>0  and ep.id_sucursal='$id_sucursal' and p.id_sucursal='$id_sucursal' and e.id_sucursal='$id_sucursal'  AND ep.fecha_examen BETWEEN '$fechai' AND '$fechaf' ";

    $columns = array(
    array( 'db' => '`ep`.`id_examen_paciente`', 'dt' => 0, 'field' => 'id_examen_paciente'  ),
    array( 'db' => "CONCAT(p.nombre,' ',p.apellido)", 'dt' => 1, 'field' => "nombre", 'as'=>'nombre',),
    array( 'db' => '`e`.`nombre_examen`', 'dt' => 2, 'field' => 'nombre_examen'),
    array( 'db' => '`ep`.`fecha_examen`', 'dt' => 3, 'field' => 'fecha_examen'),
    array( 'db' => '`ep`.`id_examen_paciente`', 'dt' =>4 , 'formatter' => function($id_examen_paciente){
      return  estado($id_examen_paciente);
      }, 'field' => 'id_examen_paciente'),
    array( 'db' => '`ep`.`enviado`', 'dt' => 5, 'formatter' => function ($enviado) {
      if($enviado==0)
      {
        $estado="No";
      }
      else
      {
        $estado="Si";
      }

      return $estado;

    } , 'field' => 'enviado' ),
    array( 'db' => 'id_examen_paciente', 'dt' => 6, 'formatter' => function ($id_examen_paciente) {
     return  dropdown($id_examen_paciente);

    } , 'field' => 'id_examen_paciente' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );


function estado($id_examen_paciente){
    	$id_sucursal=$_SESSION["id_sucursal"];
    	$sql="SELECT examen_paciente_nulo,estado_realizado, estado_impresion FROM examen_paciente WHERE id_examen_paciente='$id_examen_paciente' AND id_sucursal='$id_sucursal' ";
    	$result=_query($sql);
    	$row=_fetch_array($result);
      $estado="";
      if($row['examen_paciente_nulo']==0){
        if($row['estado_realizado']=="Hecho")
        {
          $estado="<h5 class='text-info'>".'PROCESADO'."</h5>";

        }
        if($row['estado_impresion']=="Hecho")
        {
          $estado="<h5 class='text-success'>".'REALIZADO'."</h5>";

        }
        if($row['estado_realizado']=="Pendiente")
        {
          $estado="<h5 class='text-warning'>".'PENDIENTE'."</h5>";

        }
      }else{
        $estado="<h5 class='text-danger'>".'NULO'."</h5>";

      }


    		return $estado;
}

function dropdown($id_examen_paciente){
  $id_sucursal=$_SESSION["id_sucursal"];
  $menudrop="<div class='btn-group'>
			<a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
			<ul class='dropdown-menu dropdown-primary'>";

			$sql="SELECT * FROM examen_paciente WHERE id_examen_paciente='$id_examen_paciente' AND id_sucursal='$id_sucursal'";
			$result=_query($sql);
			$count=_num_rows($result);
			$row=_fetch_array($result);
			$id_user=$_SESSION["id_usuario"];
			$id_sucursal=$_SESSION["id_sucursal"];
			$admin=$_SESSION["admin"];
      $id_examen_f = $row['id_examen'];

      if($row['examen_paciente_nulo']==0 && $row['estado_realizado']=="Pendiente" ){
        $filename='agregar_examen_paciente.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1')
          $menudrop.="<li><a href='agregar_examen_paciente.php?id_examen_paciente=".$row['id_examen_paciente']."&proceso=edited_admin'  data-refresh='true'><i class=\"fa fa-plus icon-large\"></i> Procesar</a></li>";

     }
     if($row['estado_realizado']=="Hecho" && $row['examen_paciente_nulo']==0){
       $filename='editar_examen_paciente.php';
       $link=permission_usr($id_user,$filename);
       if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a  href='editar_examen_paciente.php?id_examen_paciente=".$row['id_examen_paciente']."&proceso=edited1' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";

      $filename='enviar_examen.php';
      $link=permission_usr($id_user,$filename);
      if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a data-toggle='modal' href='enviar_examen.php?id_examen_paciente=".$row['id_examen_paciente']."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-paper-plane\"></i> Enviar examen</a></li>";

     }
     if($row['estado_realizado']=="Hecho" && $row['examen_paciente_nulo']==0){
        if($id_examen_f == 698 || $id_examen_f == 699 || $id_examen_f == 700){
          $filename='impresion_examen.php';
          $link=permission_usr($id_user,$filename);
          if ($link!='NOT' || $admin=='1' ){
              $menudrop.="<li><a  target='_blank' href='impresion_constancia_covid19.php?id_examen_paciente=$id_examen_paciente'  data-refresh='true'><i class=\"fa fa-print\"></i> Imprimir</a></li>";
          }
        }
        else{
          $filename='impresion_examen.php';
          $link=permission_usr($id_user,$filename);
          if ($link!='NOT' || $admin=='1' ){
              $menudrop.="<li><a  target='_blank' href='impresion_examen_individual.php?id_examen_paciente=$id_examen_paciente'  data-refresh='true'><i class=\"fa fa-print\"></i> Imprimir</a></li>";
          }
        }
     }

      

      if($row['examen_paciente_nulo']==0){
        $filename='anular_examen_paciente.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1')
          $menudrop.="<li><a data-toggle='modal' href='anular_examen_paciente.php?id_examen_paciente=".$row['id_examen_paciente']."'  data-target='#verModal' data-refresh='true'><i class=\"fa fa-toggle-off\"></i> Anular</a></li>";

     }
		 $menudrop.="</ul>
						</div>";
		return $menudrop;

    }


    ?>
