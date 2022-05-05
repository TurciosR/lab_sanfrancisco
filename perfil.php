<?php
include_once "_core.php";
function initial()
{
    $title = 'Perfil de Usuario';
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
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2">
    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
    			<?php
    	   		  //permiso del script
        			if (true ){
    			?>
                <div class="ibox-title">

                </div>
                <div class="ibox-content">
                  <!-- Start content -->
                  <div class="content">
                    <div class="container">
                      <!-- Poner acá todo lo que se quiera mostrar en la caja derecha de la plantilla -->
                      <!-- Page-Title -->
                      <div class="row">
                        <div class="col-sm-12">
                          <h3 class="text-navy"><b><i class="fa fa-user"></i> <?php echo $title;?></b></h3>
                        </div>
                      </div>
                      <?php
                        $id_usuario = $_SESSION["id_usuario"];
                        $id_sucursal = $_SESSION["id_sucursal"];
                        $datos_empresa=_query("SELECT u.*,e.direccion,e.telefono,e.dui FROM usuario as u, empleado as e  WHERE u.id_usuario='$id_usuario' AND e.id_empleado=u.id_empleado
                        and u.id_sucursal='$id_sucursal' and e.id_sucursal='$id_sucursal'");
                        $num = _num_rows($datos_empresa);
                        if($num >0){
                          $datos =_fetch_array($datos_empresa);
                          $nombre= $datos["nombre"];
                          $direccion = $datos["direccion"];
                          $telefono1 = trim($datos["telefono"]);
                          $usuario = $datos["usuario"];
                          $clave = $datos["password"];
                          $logo = $datos['imagen'];
                          $dui = $datos["dui"];
                          $readonly="";
                        }else{
                          $datos_empresaa=_query("SELECT *FROM usuario WHERE id_usuario='$id_usuario' and id_sucursal='$id_sucursal'");
                          $datoss =_fetch_array($datos_empresaa);
                          $nombre= $datoss["nombre"];
                          $usuario = $datoss["usuario"];
                          $clave = $datoss["password"];
                          $logo = $datoss['imagen'];
                          $direccion = "";
                          $telefono1 = "";
                          $dui = "";
                          $readonly="readonly";


                        }

                        //$nit = $datos['nit'];

                        ?>
                        <br>
                        <br>
                        <div class="col-md-7">
                          <form class="form-horizontal" id="formulario">
                            <div class="form-group">
                              <label class="col-md-4 control-label">Nombre</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
                              </div>
                            </div>
                            <div class="form-group ocultar">
                              <label class="col-md-4 control-label">Direcci&oacute;n</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $direccion; ?>"  >
                              </div>
                            </div>
                            <div class="form-group ocultar">
                              <label class="col-md-4 control-label">Tel&eacute;fono</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="telefono1" id="telefono1" value="<?php echo $telefono1; ?>" >
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-4 control-label">Usuario</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-4 control-label">Clave</label>
                              <div class="col-md-8">
                                <input type="password" class="form-control" name="clave" id="clave" value="<?php echo $clave; ?>">
                              </div>
                            </div>
                            <div class="form-group ocultar">
                              <label class="col-md-4 control-label">DUI</label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="dui" id="dui" value="<?php echo $dui; ?>" >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-4 control-label">Imagen</label>
                              <div class="col-md-8">
                                <div class=" has-info single-line">
                                    <input type="file" name="logo" id="logo" class="file" data-preview-file-type="image">
                                </div>
                              </div>

                              <input type="hidden" name="process" id="process" value="insert"><br>
                              <div class="col-lg-12 text-center">
                                <br>
                                <input type="hidden" name="img_logo" id="img_logo" value="<?php echo $logo; ?>">
                                <input type="hidden" name="readonly" id="readonly" value="<?php echo $readonly; ?>">
                                <input type="submit" id="submit1" name="submit1" style="" value="Guardar" class="btn  btn-primary m-t-n-xs" />
                              </div>
                            </div>
                          </form>
                        </div>
                        <div class="col-md-4">
                          <?php
                                                if ($logo=="") {
                                                    $logo = "img/5bb4eddc7659f_icono.jpg";
                                                }
                                                echo "<img style='width:80%; heigth:80%;' src='".$logo."'>";
                                               ?>
                        </div>

                    </div>
                    <!-- End container -->
                  </div>
                  <!-- End content -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        include_once ("footer.php");
        echo "<script src='js/funciones/perfil.js'></script>";
        echo " <script src='js/plugins/fileinput/fileinput.js'></script>";
	} //permiso del script
    else
    {
    		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }
}

function insertar()
{
  require_once 'class.upload.php';
  $id_usuario=$_SESSION['id_usuario'];
  $id_sucursal=$_SESSION['id_sucursal'];
  $nombre=$_POST["nombre"];
  $direccion=$_POST["direccion"];
  $telefono1=$_POST["telefono1"];
  $usuario=$_POST["usuario"];
  $clave=$_POST["clave"];
  $readonly=$_POST["readonly"];
  $dui=$_POST["dui"];
  $logo=$_POST["img_logo"];

  $sql_exis=_query("SELECT * FROM usuario WHERE id_usuario='$id_usuario' AND id_sucursal='$id_sucursal'");
  $num_exis = _num_rows($sql_exis);
  $datos_row=_fetch_array($sql_exis);
  //echo "SELECT * FROM usuario WHERE id_usuario='$id_usuario' id_sucursal='$id_sucursal'";

  if($num_exis == 0)
  {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='El Usuario no existe!';
  }
  else
  {
      if ($_FILES["logo"]["name"]!="")
      {
          $foo = new Upload($_FILES['logo'],'es_ES');
          if ($foo->uploaded)
          {
              $pref = uniqid()."_";
              $foo->file_force_extension = false;
              $foo->no_script = false;
              $foo->file_name_body_pre = $pref;
             // save uploaded image with no changes
             $foo->Process('img/');
             if ($foo->processed)
             {
                  $query = _query("SELECT imagen FROM usuario WHERE id_usuario='$id_usuario' and id_sucursal='$id_sucursal'");
                  $result = _fetch_array($query);
                  $urlb=$result["imagen"];
                  if($urlb!="" && file_exists($urlb))
                  {
                      unlink($urlb);
                  }
                  $cuerpo=quitar_tildes($foo->file_src_name_body);
                  $cuerpo=trim($cuerpo);
                  $logo = 'img/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
              }
          }
      }

      $clave_cambio=0;

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
      $id_empleado=$datos_row['id_empleado'];
      $table = 'usuario';
      $form_data = array(
      'nombre' => $nombre,
      'usuario' => $usuario,
      'password' => $clave_cambio,
      'imagen'=>$logo
      );
      $table_empleado = 'empleado';
      $form_data_empleado = array(
      'direccion' => $direccion,
      'telefono' => $telefono1,
      'dui' => $dui
      );
      $_SESSION['imagen']=$logo;
      $where = "id_usuario='".$id_usuario."'";
      $where_empleado = "id_empleado='".$id_empleado."'";
      if($readonly == ""){
        $upadte = _update($table,$form_data,$where);
        if($upadte)
        {
           $upadte = _update($table_empleado,$form_data_empleado,$where_empleado);
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Registro actualizado con exito!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Registro no pudo ser actualizado !';
        }

      }else if($readonly == "readonly"){
        $upadte = _update($table,$form_data,$where);
        if($upadte)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Registro actualizado con exito!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Registro no pudo ser actualizado !';
        }

      }

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
                insertar();
                break;
        }
    }
}
?>
