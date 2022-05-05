<?php
include_once "_core.php";
function initial()
{
  $title = 'Agregar Cliente';
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
          if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
              <h5 class="text-navy"><i class="fa fa-plus-circle"></i> <?php echo $title; ?></h5>
            </div>
            <div class="ibox-content">
              <form name="formulario" id="formulario">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group has-info ">
                        <label>Nombre  <span style="color:red;">*</span></label>
                        <input type="text" placeholder="Nombre del Cliente" class="form-control may" id="nombre" name="nombre">
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-info ">
                        <label>Dirección</label>
                        <input type="text" placeholder="Dirección" class="form-control may" id="direccion" name="direccion">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>Departamento </label>
                      <select class="col-md-12 select" id="departamento" name="departamento">
                        <option value="">Seleccione</option>
                        <?php
                        $sqld = "SELECT * FROM departamento";
                        $resultd=_query($sqld);
                        while($depto = _fetch_array($resultd))
                        {
                          echo "<option value='".$depto["id_departamento"]."'";

                          echo">".$depto["nombre_departamento"]."</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>Municipio </label>
                      <select class="col-md-12 select" id="municipio" name="municipio">
                        <option value="">Primero seleccione un departamento</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>DUI</label>
                      <input type="text" placeholder="00000000-0" class="form-control" id="dui" name="dui">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>NIT</label>
                      <input type="text" placeholder="0000-000000-000-0" class="form-control" id="nit" name="nit">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>NRC  </label>
                      <input type="text" placeholder="Registro" class="form-control" id="nrc" name="nrc">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>Giro </label>
                      <input type="text" placeholder="Giro del Cliente" class="form-control may" id="giro" name="giro">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>Teléfono 1 </label>
                      <input type="text" placeholder="0000-0000" class="form-control tel" id="telefono1" name="telefono1">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group has-info ">
                      <label>Teléfono 2</label>
                      <input type="text" placeholder="0000-0000" class="form-control tel" id="telefono2" name="telefono2">
                    </div>
                  </div>
                </div>

                  <div class="row">

                  <div class="col-md-4">
                    <div class="form-group has-info ">
                      <label>E-mail</label>
                      <input type="text" placeholder="mail@miempresa.com" class="form-control" id="correo" name="correo">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group has-info ">
                      <label>Porcentaje Descuento</label>
                      <input type="text" placeholder="10%" class="form-control decimal" id="porcentaje_descuento" name="porcentaje_descuento">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group has-info ">
                      <br>
                      <div class='radio i-checks'><label><input id='retiene' name='retiene' type='checkbox'> <span class="label-text"><b> Remision</b></span></label></div>
                      <input type="hidden" name="hi_retiene" id="hi_retiene" value="0">
                    </div>
                  </div>

                </div>
                <input type="hidden" name="process" id="process" value="insert"><br>
                <div>
                  <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_cliente.js'></script>";
  } //permiso del script
  else
  {
    $mensaje = mensaje_permiso();
  	echo "<br><br>$mensaje<div><div></div></div</div></div>";
  	include "footer.php";
  }
}

function insertar()
{
  $nombre=$_POST["nombre"];
  $direccion=$_POST["direccion"];
  $departamento=$_POST["departamento"];
  $municipio=$_POST["municipio"];
  $dui=$_POST["dui"];
  $nit=$_POST["nit"];
  $nrc=$_POST["nrc"];
  $giro=$_POST["giro"];
  $remision= $_POST["hi_retiene"];
  $telefono1=$_POST["telefono1"];
  $telefono2=$_POST["telefono2"];
  $porcentaje_descuento=$_POST["porcentaje_descuento"];
  $correo=$_POST["correo"];

  $id_sucursal = $_SESSION["id_sucursal"];
  $sql_exis=_query("SELECT id_cliente FROM cliente WHERE nombre ='$nombre' AND id_sucursal='$id_sucursal' AND nit='$nit'");
  $num_exis = _num_rows($sql_exis);
  if($num_exis > 0)
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Ya se registro un cliente con estos datos!';
  }
  else
  {
    $table = 'cliente';
    $form_data = array(
      'nombre' => $nombre,
      'direccion' => $direccion,
      'municipio' => $municipio,
      'depto' => $departamento,
      'nrc' => $nrc,
      'nit' => $nit,
      'dui' => $dui,
      'giro' => $giro,
      'telefono1' => $telefono1,
      'telefono2' => $telefono2,
      'porcentaje_descuento' => $porcentaje_descuento,
      'email' => $correo,
      'remision' => $remision,
      'id_sucursal' => $id_sucursal
    );
    $insertar = _insert($table,$form_data );
    if($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro guardado con exito!';
      $xdatos['process']='insert';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser guardado !'._error();
    }
  }
  echo json_encode($xdatos);
}
function municipio()
{
  $id_departamento = $_POST["id_departamento"];
  $option = "";
  $sql_mun = _query("SELECT * FROM municipio WHERE id_departamento_municipio='$id_departamento'");
  while($mun_dt=_fetch_array($sql_mun))
  {
    $option .= "<option value='".$mun_dt["id_municipio"]."'>".$mun_dt["nombre_municipio"]."</option>";
  }
  echo $option;
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
      case 'municipio':
      municipio();
      break;

    }
  }
}
?>
