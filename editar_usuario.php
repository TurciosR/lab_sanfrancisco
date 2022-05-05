<?php
include_once "_core.php";

function initial() {
	// Page setup
    $title = 'Editar Usuario';
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
  $id_sucursal=$_SESSION["id_sucursal"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    $id_usuario = $_REQUEST["id_usuario"];
    $query_user = _query("SELECT * FROM usuario WHERE id_usuario='$id_usuario'and id_sucursal='$id_sucursal'");
    $datos_user = _fetch_array($query_user);
    $nombre = $datos_user["nombre"];
    $contra = $datos_user["password"];
    $usuario = $datos_user["usuario"];
    $admin_b = $datos_user["admin"];
    $activo = $datos_user["activo"];
    $id_empleado = $datos_user["id_empleado"];

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
                            <h3 class="text-navy"><b><i class="fa fa-edit"></i> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Usuario</label>
                                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Clave</label>
                                        <input type="password" placeholder="Ingrese una contraseña" class="form-control" id="clave" name="clave" value="<?php echo $contra; ?>" >
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <div class="form-group">
                                            <div class='checkbox i-checks'><br>
                                                <label id='frentex'>
                                                    <input type='checkbox' id='admi' name='admi' <?php if($admin_b) echo " checked ";?>> <strong> Administrador</strong>
                                                </label>
                                            </div>
                                            <input type='hidden' id='admin' name='admin' <?php if($admin_b) echo " value='1' "; else echo " value='0' "; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="form-group col-lg-6" >
                                    <label>Empleado</label>
                                    <select class="form-control select" name="empleado" id="empleado">
                                    <option value="">Seleccionar</option>
                                    <?php
                                              $consulta = _query("SELECT * FROM empleado WHERE id_sucursal='$id_sucursal'");
                                              while($row_consulta = _fetch_array($consulta))
                                              {
                                              echo "<option value='".$row_consulta["id_empleado"]."'";
                                              if($row_consulta["id_empleado"] == $id_empleado)
                                              {
                                                  echo " selected ";
                                              }
                                              echo ">".$row_consulta["nombre"]." ".$row_consulta["apellido"]."</option>";
                                              //echo " >".$row_tipo_examen["nombre"]."</option>";
                                              }
                                      ?>
                                    </select>
                                  </div>
                                    <div class="form-group col-lg-6">
                                        <div class="form-group">
                                            <div class='checkbox i-checks'><br>
                                                <label id='frentex'>
                                                    <input type='checkbox' id='activ' name='activ' <?php if($activo) echo " checked ";?>> <strong> Activo</strong>
                                                </label>
                                            </div>
                                            <input type='hidden' id='activo' name='activo' <?php if($activo) echo " value='1' "; else echo " value='0' "; ?>>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="edited">
                                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>">
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
echo "<script src='js/funciones/funciones_usuarios.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function editar_usuario()
{

    $id_usuario = $_POST["id_usuario"];
    $nombre=$_POST["nombre"];
    $usuario=$_POST["usuario"];
	  $clave=$_POST["clave"];
    $admin=$_POST["admin"];
    $empleado = $_POST["empleado"];
    $activo=$_POST["activo"];
    $id_sucursal=$_SESSION["id_sucursal"];

    $string_user="SELECT password FROM usuario WHERE id_usuario='$id_usuario' and id_sucursal='$id_sucursal'";
    $clav=_query($string_user);
    $clave_row=_fetch_array($clav);
    $clave_user=$clave_row['password'];
    if($clave_user==$clave){
      $clave_cambio=$clave_user;
    }
    else
    {
      $clave_cambio=md5($_POST["clave"]);
    }

    $sql_result=_query("SELECT id_usuario FROM usuario WHERE usuario='$usuario' AND id_usuario!='$id_usuario'and id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);

    $table = 'usuario';
    $form_data = array (
    'nombre' => $nombre,
    'usuario' => $usuario,
    'password' => $clave_cambio,
    'admin' => $admin,
    'activo' => $activo,
    'id_empleado' => $empleado
    );
    $where_clause = "id_usuario ='".$id_usuario."'and id_sucursal='".$id_sucursal."' ";
    if($numrows == 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Usuario editado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Usuario no pudo ser editado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este usuario no esta disponible, intente con uno diferente!';
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
        		editar_usuario();
        		break;
        }
    }
}
?>
