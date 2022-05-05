<?php
include_once "_core.php";

function initial() {
	// Page setup
    $title = 'Editar Doctor';
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
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
  $id_sucursal=$_SESSION["id_sucursal"];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    $id_doctor = $_REQUEST["id_doctor"];
    $query_user = _query("SELECT * FROM doctor WHERE id_doctor='$id_doctor'and id_sucursal='$id_sucursal'");
    $datos_user = _fetch_array($query_user);
    $nombre = $datos_user["nombre"];
    $apellido = $datos_user["apellido"];
    $especialidad = $datos_user["especialidad"];
    $direccion = $datos_user["direccion"];
    $telefono = $datos_user["telefono"];
    $email = $datos_user["email"];
    $nombre_consultorio = $datos_user["nombre_consultorio"];
    $comision = $datos_user["comision"];
    //permiso del script
	if ($links!='NOT' || $admin=='1' )
    {
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
                            <h3 class="text-navy"><i class="fa fa-pencil-square-o"></i><b> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                              <div class="row">
                                  <div class="form-group col-lg-6">
                                      <label>Nombre</label>
                                      <input type="text" placeholder="Ingrese Nombres" class="form-control may" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                                  </div>
                                  <div class="form-group col-lg-6">
                                      <label>Apellido</label>
                                      <input type="text" placeholder="Ingrese Apellidos" class="form-control may" id="apellido" name="apellido" value="<?php echo $apellido; ?>">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="form-group col-lg-6">
                                      <label>Especialidad</label>
                                      <select class="select form-control may" id="especialidad" name="especialidad" value="<?php echo $id_doctor;?>">
                                       <option value="">Seleccione</option>
                                          <?php
                                              $sqld = "SELECT * FROM especialidades";
                                              $resultd=_query($sqld);
                                              while($depto = _fetch_array($resultd))
                                              {
                                                  echo "<option value='".$depto["id_especialidades"]."'";

                                                  if($depto["id_especialidades"]==$especialidad){
                                                    echo "selected";
                                                  }
                                                  echo">".$depto["nombre"]."</option>";
                                              }
                                          ?>
                                      </select>
                                    </div>
                                  <div class="form-group col-lg-6">
                                      <label>Direcc&oacute;n</label>
                                      <input type="text" placeholder="Ingrese la direccion del doctor" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion;?>">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="form-group col-lg-6">
                                      <label>Telefono</label>
                                      <input type="text" placeholder="Ingrese Tel&eacute;fono" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
                                  </div>
                                  <div class="form-group col-lg-6">
                                      <label>Email</label>
                                      <input type="email" placeholder="Ingrese email" class="form-control may" id="email" name="email" value="<?php echo $email; ?>">
                                  </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-6">
                                  <label>Comision</label>
                                  <input type="text" step="4" placeholder="Ingrese comision" class="form-control nume" id="comision" name="comision" value="<?php echo $comision; ?>">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Nombre Consultorio</label>
                                    <input type="text" placeholder="Ingrese nombre consultorio" class="form-control may" id="nombre_consultorio" name="nombre_consultorio" value="<?php echo $nombre_consultorio; ?>">
                                </div>
                              </div>
                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="edited">
                                        <input type="hidden" name="id_doctor" id="id_doctor" value="<?php echo $id_doctor; ?>">
                                        <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_doctor.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function editar_doctor()
{
    $id_doctor = $_POST["id_doctor"];
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $especialidad=$_POST["especialidad"];
    $direccion=$_POST["direccion"];
    $telefono = $_POST["telefono"];
    $nombre_consultorio=$_POST["nombre_consultorio"];
    $email=$_POST["email"];
    $comision=$_POST["comision"];
    $id_sucursal=$_SESSION["id_sucursal"];
    $sql_result=_query("SELECT id_doctor FROM doctor WHERE  id_doctor='$id_doctor'and id_sucursal='$id_sucursal'");
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
    'email' => $email
    );
    $where_clause = "id_doctor ='".$id_doctor."'and id_sucursal='".$id_sucursal."'";
    if($numrows != 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Doctor editado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Doctor no pudo ser editado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Doctor no esta disponible, intente con uno diferente!';
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
        	case 'edited':
        		editar_doctor();
        		break;
        }
    }
}
?>
