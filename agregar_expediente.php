<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Agregar Expediente';
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
    $_PAGE ['links'] .= '<link href="js/plugins/typehead/bootstrap3-typeahead.min.js" rel="stylesheet">';
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
  $query_user = _query("SELECT n_expediente FROM expediente ORDER BY n_expediente DESC LIMIT 1");
  $datos_user = _fetch_array($query_user);
  $numero = $datos_user["n_expediente"];
  $numero1 = (int)$numero +1;
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
                                        <label>N&uacute;mero de expediente</label>
                                        <input type="text" placeholder="Numero de expediente" class="form-control" id="n_expediente" name="n_expediente" value="<?php echo $numero1;?>" readonly>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>ID del paciente</label>
                                        <input type="text" placeholder="Ingrese nombre" class="form-control paciente" id="id_paciente" name="id_paciente" size="30" data-provide="typeahead">
                                    </div>
                                </div>
                                        <input type="hidden"  id="fecha_creada" name="fecha_creada" value="<?php echo date("Y-m-d"); ?>">
                                        <input type="hidden"  id="ultima_visita" name="ultima_visita" value="<?php echo date("Y-m-d"); ?>">
                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="insert">
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
echo "<script src='js/funciones/funciones_expediente.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function insertar_expediente()
{
    $expediente = $_POST["n_expediente"];
    $paciente = $_POST["id_paciente"];
    $fechaC = $_POST["fecha_creada"];
    $fechaU = $_POST["ultima_visita"];
    $id_sucursal=$_SESSION["id_sucursal"];

    $table = 'expediente';
    $form_data = array (
    'n_expediente' => $expediente,
    'id_paciente' => $paciente,
    'fecha_creada' => $fechaC,
    'ultima_visita' => $fechaU,
    'id_sucursal'=>$id_sucursal
    );

    $sql_result=_query("SELECT id_expediente FROM expediente WHERE nombre='$nombre'");
    $numrows=_num_rows($sql_result);

    if($numrows == 0)
    {
        $insertar = _insert($table,$form_data);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Expediente ingresado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Expediente no pudo ser ingresado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Expediente ya fue ingresado!';
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
        		insertar_expediente();
        		break;
        }
    }
}
?>
