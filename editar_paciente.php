<?php
include_once "_core.php";

function initial() {
	// Page setup
    $title = 'Editar Paciente';
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

    $id_paciente = $_REQUEST["id_paciente"];
    $query_pac = _query("SELECT * FROM paciente WHERE id_paciente='$id_paciente' and id_sucursal='$id_sucursal'");
    $datos_pac = _fetch_array($query_pac);
    $nombre = $datos_pac["nombre"];
    $apellido = $datos_pac["apellido"];
    $direccion = $datos_pac["direccion"];
    $telefono = $datos_pac["telefono"];
    $whatsap = $datos_pac["telefono_whatsapp"];
    $sexo = $datos_pac["sexo"];
    $dui = $datos_pac["dui"];
    $fecha_nacimiento = $datos_pac["fecha_nacimiento"];
    $correo = $datos_pac["correo"];
    $foto = $datos_pac["foto"];
    $pasaporte = $datos_pac['pasaporte'];


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
                                      <label>Nombres</label>
                                      <input type="text" placeholder="Ingrese Nombres" class="form-control may" id="nombre" name="nombre" value="<?php echo $nombre;?>">
                                  </div>
                                  <div class="form-group col-lg-6">
                                      <label>Apellidos</label>
                                      <input type="text" placeholder="Ingrese Apellidos" class="form-control may" id="apellido" name="apellido" value="<?php echo $apellido;?>">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="form-group col-lg-6">
                                      <label>Dirección</label>
                                      <input type="text" placeholder="Ingrese Dirección " class="form-control may" id="direccion" name="direccion" value="<?php echo $direccion;?>">
                                  </div>
                                  <div class="form-group col-lg-6">
                                      <label>Teléfono</label>
                                      <input type="text" placeholder="Ingrese Telefono" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono;?>">
                                  </div>

                              </div>
                                <div class="row">

                                    <div class="form-group col-lg-4">
                                        <label>Género</label>
                                        <?php
                                        $selectF;
                                        $selectM;
                                        if($sexo=="FEMENINO")
                                        {
                                          $selectF="selected";
                                          $selectM="";

                                        }else if($sexo=="MASCULINO")
                                        {
                                          $selectF="selected";
                                          $selectM="selected";

                                        }

                                         ?>
                                        <select class="form-control select" name="sexo" id="sexo" value="<?php echo $sexo;?>">
                                          <option value="">Seleccionar</option>
                                          <option value="FEMENINO"  <?php echo $selectF ?>>FEMENINO</option>
                                          <option value="MASCULINO" <?php echo $selectM ?>>MASCULINO</option>
                                      </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>N° Dui</label>
                                        <input type="text" placeholder="Ingrese N° Dui" class="form-control" id="dui" name="dui" value="<?php echo $dui;?>">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Whatsapp</label>
                                        <input type="text" placeholder="Ingrese whatsap" class="form-control telefono" id="whatsap" name="whatsap" value="<?php echo $whatsap;?>">
                                    </div>
                                </div>
                                <div class="row">
                                <div class="form-group col-lg-4">
                                            <label>Pasaporte</label>
                                            <input type="text" placeholder="Ingrese N° Pasaporte" class="form-control" id="pasaporte" name="pasaporte"  value="<?php echo $pasaporte;?>" >
                                        </div>
                                    <div class="form-group col-lg-4">
                                        <label>Fecha de Nacimiento</label>

                                          <input type="text" placeholder="Fecha" class="datepicker form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento;?>"  >

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Correo Eléctronico</label>
                                        <input type="text" placeholder="Ingrese correo" class="form-control MAY" id="correo" name="correo" value="<?php echo $correo;?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group has-info">
                                            <label>Foto</label>
                                            <input type="file" name="foto" id="foto" class="file" data-preview-file-type="image">
                                        </div>
                                    </div>
                                    <?php
                                        if ($foto=="") {
                                            $foto = "img/default.png";
                                        }

                                     ?>
                                    <div class="col-md-6">
                                        <div class="form-group has-info">
                                            <img id="logo_view" src="<?php echo $foto;?>" style='width: 100px; height: 100px;'>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="edit">
                                        <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente; ?>">
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


function editar_paciente(){
  $id_paciente = $_POST["id_paciente"];
  $id_sucursal =$_SESSION["id_sucursal"];
  require_once 'class.upload.php';
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
      $query = _query("SELECT foto FROM paciente WHERE id_paciente='1' and id_sucursal='$id_sucursal'");
      $result = _fetch_array($query);
      $urlb=$result["foto"];
      if($urlb!="")
      {
      //    unlink($urlb);
      }
      $nombre=$_POST["nombre"];
      $apellido=$_POST["apellido"];
      $direccion=$_POST["direccion"];
      $telefono = $_POST["telefono"];
      $whatsap = $_POST["whatsap"];
      $sexo = $_POST["sexo"];
      $dui = $_POST["dui"];
      $pasaporte = $_POST['pasaporte'];
      $fecha_nacimiento = $_POST["fecha_nacimiento"];
      $correo = $_POST["correo"];
      $cuerpo=quitar_tildes($foo->file_src_name_body);
      $cuerpo=trim($cuerpo);
      $url = 'img/pacientes/'.$pref.$cuerpo.".".$foo->file_src_name_ext;



    /*$sql_result=_query("SELECT id_paciente FROM paciente WHERE dui='$dui' AND id_paciente!='$id_paciente'");
    $numrows=_num_rows($sql_result);*/

    $table = 'paciente';
    $form_data = array (
    'nombre' => $nombre,
    'apellido' => $apellido,
    'direccion' => $direccion,
    'telefono' => $telefono,
    'telefono_whatsapp' => $whatsap,
    'sexo' => $sexo,
    'dui' => $dui,
    'fecha_nacimiento' => $fecha_nacimiento,
    'correo' => $correo,
    'foto' => $url,
    'pasaporte' => $pasaporte
    );
    $where_clause = "id_paciente ='".$id_paciente."'";
    $editar =_update($table, $form_data, $where_clause);
    if($editar)
    {
       $xdatos['typeinfo']='Success';
       $xdatos['msg']='Datos de paciente editados correctamente !';
       $xdatos['process']='edit';
    }
    else{
       $xdatos['typeinfo']='Error';
       $xdatos['msg']='Datos del paciente no pudieron ser editados!'._error();
    }
    }
    else{
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Error al guardar la imagen!';
      }
    }
    else{
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Error al subir la imagen!';
      }
    }
    else
    {
      $nombre=$_POST["nombre"];
      $apellido=$_POST["apellido"];
      $direccion=$_POST["direccion"];
      $telefono = $_POST["telefono"];
      $whatsap = $_POST["whatsap"];
      $sexo = $_POST["sexo"];
      $dui = $_POST["dui"];
      $fecha_nacimiento = $_POST["fecha_nacimiento"];
      $correo = $_POST["correo"];
        $pasaporte = $_POST['pasaporte'];
        $tabla = 'paciente';

        $form_data = array (
          'nombre' => $nombre,
          'apellido' => $apellido,
          'direccion' => $direccion,
          'telefono' => $telefono,
          'telefono_whatsapp' => $whatsap,
          'sexo' => $sexo,
          'dui' => $dui,
          'fecha_nacimiento' => $fecha_nacimiento,
          'correo' => $correo,
          'pasaporte' => $pasaporte
          );
          $where_clause = "id_paciente ='".$id_paciente."'and id_sucursal='".$id_sucursal."'";
        $editar =_update($tabla, $form_data, $where_clause);
        if($editar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Datos de paciente editados correctamente !';
           $xdatos['process']='edit';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Datos de paciete no pudieron ser editados!'._error();
        }
    }
    echo json_encode($xdatos);

    /*if($numrows == 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Paciente editado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Paciente no pudo ser editado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Paciente no esta disponible, intente con uno diferente!';
    }
	echo json_encode($xdatos);*/
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
              case 'edit':
                  editar_paciente();
                  break;
          }
      }
  }

?>
