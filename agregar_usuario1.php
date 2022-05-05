<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Agregar Usuario';
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
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){
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
                            <h3></i> <b><?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Tipo</label>
                                        <select class="form-control select" name="tipo" id="tipo">
                                          <option value="1">Texto</option>
                                          <option value="2">Seleccion</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Nombre</label>
                                        <input type="text" placeholder="" class="form-control" id="nombre" name="nombre">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Ancho</label>
                                        <select class="form-control select" name="tamanio" id="tamanio">
                                          <option value="6">2 en Hoja</option>
                                          <option value="12">Completo</option>
                                          <option value="3">4 en Hoja</option>
                                          <option value="4">3 en Hoja</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="insert">
                                        <input type="hidden" name="activo" id="activo" value="1">
                                        <button type="button" id="submit1" name="submit1"  class="btn btn-primary m-t-n-xs pull-right">Guardar</button>
                                    </div>
                                </div>
                                <div class="row"><br>
                                  <div class="col-lg-12">
                                    <div class="alert alert-warning text-center">
                                      <h4>Previsualizacion</h4>
                                    </div>
                                    </div>
                                </div>
                                <div id="prev">
                                  <table class="table table-bordered table-hover">
                                    <thead>
                                      <tr>
                                        <th>N</th>
                                        <th>Nombre</th>
                                        <th>Tamaño</th>
                                        <th>Tipo</th>
                                        <th>Validacion</th>
                                        <th>Requerido</th>
                                        <th>Accion</th>
                                      </tr>
                                    </thead>
                                    <tbody id="appd">

                                    </tbody>
                                  </table>
                                </div>
                                  <?php
                                    $sql = _query("SELECT id_empleado FROM usuario WHERE id_usuario=-1");
                                    $datos = _fetch_array($sql);
                                    $examen = $datos["id_empleado"];
                                    $campos = explode("#", $examen);
                                    $ncampos = count($campos);
                                    $rows = 0;
                                    for($i=0; $i<($ncampos-1); $i++)
                                    {
                                      if($rows == 0)
                                      {
                                        echo "<div class='row'>";
                                      }
                                      list($nombre, $tamanio, $tipo) = explode("|",$campos[$i]);
                                      if($tipo == 1)
                                      {
                                        switch ($tamanio)
                                        {
                                          case '12':
                                            $rows = 1;
                                            break;
                                          case '6':
                                            $rows += 0.5;
                                            break;
                                          case '3':
                                            $rows += 0.34;
                                            break;
                                          case '4':
                                            $rows += 0.25;
                                            break;
                                        }
                                        echo "<div class='col-lg-".$tamanio." form-group'>";
                                        echo "<label>".ucfirst($nombre)."</label>";
                                        echo "<input type='text' class='form-control' id='".strtolower($nombre)."'>";
                                        echo "</div>";
                                      }
                                      if($rows >= 1)
                                      {
                                        echo "</div>";
                                        $rows = 0;
                                      }
                                    }
                                  ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_usuarios1.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function insertar_usuario()
{

    $nombre=$_POST["nombre"];
    $usuario=$_POST["usuario"];
	$clave=md5($_POST["clave"]);
    $activo = $_POST["activo"];
    $admin = $_POST["admin"];
    $sql_result=_query("SELECT id_usuario FROM usuario WHERE usuario='$usuario'");
    $numrows=_num_rows($sql_result);

    $table = 'usuario';
    $form_data = array (
    'nombre' => $nombre,
    'usuario' => $usuario,
    'password' => $clave,
    'admin' => $admin,
    'activo' => $activo
    );

    if($numrows == 0)
    {
        $insertar = _insert($table,$form_data);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Usuario ingresado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Usuario no pudo ser ingresado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este usuario ya fue ingresado!';
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
        		insertar_usuario();
        		break;
        }
    }
}
?>
