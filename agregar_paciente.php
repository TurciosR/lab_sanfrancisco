<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Agregar Paciente';
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
  $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
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
                            <h3 class="text-navy"><i class="fa fa-plus-circle"></i><b> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Nombres</label>
                                        <input type="text" placeholder="Ingrese Nombres" class="form-control may" id="nombre" name="nombre">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Apellidos</label>
                                        <input type="text" placeholder="Ingrese Apellidos" class="form-control may" id="apellido" name="apellido">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Dirección</label>
                                        <input type="text" placeholder="Ingrese Dirección " class="form-control may" id="direccion" name="direccion">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Teléfono</label>
                                        <input type="text" placeholder="Ingrese Teléfono" class="form-control telefono" id="telefono" name="telefono">
                                    </div>

                                </div>
                                  <div class="row">

                                      <div class="form-group col-lg-6">
                                          <label>G&eacute;nero</label>
                                          <select class="form-control select may" name="sexo" id="sexo">
                                            <option value="">Seleccionar</option>
                                            <option value="FEMENINO">Femenino</option>
                                            <option value="MASCULINO">Masculino</option>
                                        </select>
                                      </div>
                                      <div class="form-group col-lg-6">
                                          <label>N° Dui</label>
                                          <input type="text" placeholder="Ingrese N° Dui" class="form-control" id="dui" name="dui">
                                      </div>
                                  </div>
                                  <div class="row">

                                      <div class="form-group col-lg-6">
                                          <label>Fecha de Nacimiento</label>

										                        <input type="text" placeholder="Ingrese fecha nacimiento" class="datepicker form-control" id="fecha_nacimiento" name="fecha_nacimiento"   >

                                      </div>
                                      <div class="form-group col-lg-6">
                                          <label>Correo Electr&oacute;nico </label>
                                          <input type="text" placeholder="Ingrese correo electrónico" class="form-control " id="correo" name="correo">
                                      </div>
                                  </div>
                                  <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>Pasaporte</label>
                                            <input type="text" placeholder="Ingrese N° Pasaporte" class="form-control" id="pasaporte" name="pasaporte">
                                        </div>
                                      <div class="form-group col-lg-6">
                                          <label>Whatsap</label>
                                          <input type="text" placeholder="Ingrese whatsapp" class="form-control telefono" id="whatsap" name="whatsap">
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="form-group has-info">
                                              <label>Foto</label>
                                              <input type="file" name="foto" id="foto" class="file" data-preview-file-type="image">
                                          </div>
                                      </div>
                                  </div>
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
echo "<script src='js/funciones/funciones_paciente.js'></script>";
echo " <script src='js/plugins/fileinput/fileinput.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function insertar_paciente()
{
  $id_sucursal=$_SESSION["id_sucursal"];
  $query_user = _query("SELECT n_expediente FROM expediente WHERE id_sucursal='$id_sucursal' ORDER BY n_expediente DESC LIMIT 1");
  $datos_user = _fetch_array($query_user);
  $numero = $datos_user["n_expediente"];
  $query_user1 = _query("SELECT id_paciente FROM paciente WHERE id_sucursal='$id_sucursal'ORDER BY id_paciente DESC LIMIT 1");
  $datos_user1 = _fetch_array($query_user1);
  $numero2 = $datos_user1["id_paciente"];

  $numero1 = (int)$numero;

  if($numero1 == ""){
    $numero1 = 201800000;
  }else{
    $numero1+=1;
  }
  $numero3 = (int)$numero2 +1;

  $nombre=trim($_POST["nombre"]);
  $apellido=trim($_POST["apellido"]);
  $direccion=$_POST["direccion"];
  $telefono = $_POST["telefono"];
  $whatsap = $_POST["whatsap"];
  $sexo = $_POST["sexo"];
  $dui = $_POST["dui"];
  $fecha_nacimiento = $_POST["fecha_nacimiento"];
  $correo = $_POST["correo"];
  $pasaporte = $_POST['pasaporte'];
$id_sucursal=$_SESSION["id_sucursal"];  
  $expediente = $numero1;
  $paciente = $numero3;
  $fechaU = date("Y-m-d");
  $fechaC = date("Y-m-d");

  require_once 'class.upload.php';
  $url = "";
  if ($_FILES["foto"]["name"]!="")
  {
  $foo = new Upload($_FILES['foto'],'es_ES');
  if ($foo->uploaded) {
      $pref = uniqid()."_";
      $foo->file_force_extension = false;
      $foo->no_script = false;
      $foo->file_name_body_pre = $pref;
     // save uploaded image with no changes
     $foo->Process('img/pacientes/');
     if ($foo->processed)
     {
       $cuerpo=quitar_tildes($foo->file_src_name_body);
       $cuerpo=trim($cuerpo);
       $url = 'img/pacientes/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
    }
  }
  else
  {
     $xdatos['typeinfo']='Error';
     $xdatos['msg']='Error al guardar la imagen!';
  }
  }
  else
  {
   $xdatos['typeinfo']='Error';
   $xdatos['msg']='Error al subir la imagen!';
  }
    $sql_result=_query("SELECT id_paciente FROM paciente WHERE nombre='$nombre' AND apellido='$apellido' AND id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);

    $table = 'paciente';
    $form_data = array (
    'nombre' => str_replace("   "," ",$nombre),
    'apellido' => str_replace("   "," ",$apellido),
    'direccion' => $direccion,
    'telefono' => $telefono,
    'telefono_whatsapp' => $whatsap,
    'sexo' => $sexo,
    'dui' => $dui,
    'fecha_nacimiento' => $fecha_nacimiento,
    'correo' => $correo,
    'estado' => 1,
    'id_sucursal'=>$id_sucursal,
    'foto' => $url,
    'pasaporte' => $pasaporte
    );



    if($numrows == 0)
    {
        $insertar = _insert($table,$form_data);
        $id_paciente=_insert_id();
        $table1 = 'expediente';
        $form_data1 = array (
        'n_expediente' => $expediente,
        'id_paciente' => $id_paciente,
        'fecha_creada' => $fechaC,
        'ultima_visita' => $fechaU,
        'id_sucursal'=>$id_sucursal
        );
        if($insertar)
        {
          $insertar1 = _insert($table1,$form_data1);
          if($insertar1){
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Paciente ingresado correctamente!';
           $xdatos['process']='insert';
         }
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Paciente no pudo ser ingresado!';
    	}

  }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este paciente ya fue ingresado!';
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
        		insertar_paciente();
        		break;
        }
    }
}
?>
