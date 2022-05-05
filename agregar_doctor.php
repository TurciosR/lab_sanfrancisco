<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Agregar Doctor';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
  $id_sucursal=$_SESSION["id_sucursal"];
  $sql_espe = _query("SELECT * FROM especialidades WHERE id_sucursal = '$id_sucursal'");
  $cuenta = _num_rows($sql_espe);
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){
      if ($cuenta!=0 ){
?>

            <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h3 class="text-navy"><i class="fa fa-plus-circle"></i><b> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Nombres</label>
                                        <input type="text" placeholder="Ingrese nombres" class="form-control may" id="nombre" name="nombre" class="mayuscula">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Apellidos</label>
                                        <input type="text" placeholder="Ingrese apellidos" class="form-control may" id="apellido" name="apellido">
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="form-group col-lg-6">
                                      <label>Especialidad</label>
                                      <select class="col-md-12 select form-control may" id="especialidad" name="especialidad">
                                          <?php
                                              $sqld = "SELECT * FROM especialidades";
                                              $resultd=_query($sqld);
                                              while($depto = _fetch_array($resultd))
                                              {
                                                  echo "<option value='".$depto["id_especialidades"]."'";
                                                  echo">".$depto["nombre"]."</option>";
                                              }
                                          ?>
                                      </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Direcci&oacute;n</label>
                                        <input type="text" placeholder="Ingrese la direcci&oacute;n del doctor" class="form-control may" id="direccion" name="direccion">
                                    </div>
                                </div>
                                    <div class="row">
                                      <div class="form-group col-lg-6">
                                          <label>Tel&eacute;fono</label>
                                          <input type="text" placeholder="Ingrese tel&eacute;fono" class="form-control " id="telefono" name="telefono">
                                      </div>
                                      <div class="form-group col-lg-6">
                                        <label>Correo Electr&oacute;nico</label>
                                        <input type="email" placeholder="Ingrese correo electr&oacute;nico" class="form-control may" id="email" name="email">
                                      </div>
                                </div>
                                <div class="row">
                                  <div class="form-group col-lg-6">
                                    <label>Comisi&oacute;n</label>
                                    <input type="text" placeholder="Ingrese Comisi&oacute;n" class="form-control nume" id="comision" name="comision">
                                  </div>
                                  <div class="form-group col-lg-6">
                                      <label>Nombre Consultorio</label>
                                      <input type="text" placeholder="Ingrese nombre consultorio" class="form-control may" id="nombre_consultorio" name="nombre_consultorio">
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="insert">
                                        <input type="hidden" name="activo" id="activo" value="1">
                                        <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                                    </div>
                                </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_doctor.js'></script>";
}else {
  echo "<div></div><br><br><div class='alert alert-warning'><h1>Antes debe Agregar una especialidad.</h1></div>";
  include_once("footer.php");

}
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function insertar_doctor()
{

    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $telefono=$_POST["telefono"];
    $comision=$_POST["comision"];
    $especialidad=$_POST["especialidad"];
    $direccion=$_POST["direccion"];
    $direccion=$_POST["direccion"];
    $nombre_consultorio=$_POST["nombre_consultorio"];
    $email=$_POST["email"];
    $estado=1;
    $id_sucursal=$_SESSION["id_sucursal"];
    $sql_result=_query("SELECT id_doctor FROM doctor WHERE nombre='$nombre'and id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);

    $table = 'doctor';
    $form_data = array (
    'nombre' => $nombre,
    'apellido' => $apellido,
    'especialidad' => $especialidad,
    'direccion' => $direccion,
    'telefono' => $telefono,
    'comision' => $comision,
    'nombre_consultorio' => $nombre_consultorio,
    'email' => $email,
    'estado' => $estado,
    'id_sucursal'=>$id_sucursal
    );

    if($numrows == 0)
    {
        $insertar = _insert($table,$form_data);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Doctor ingresado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Doctor no pudo ser ingresado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este doctor ya fue ingresado!';
    }
	echo json_encode($xdatos);
}

if(!isset($_POST['process']))
{
	initial();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
        	case 'insert':
        		insertar_doctor();
        		break;
        }
    }
}
?>
