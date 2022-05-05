<?php
include_once "_core.php";

function initial() {
  // Page setup
  $title = 'Editar Perfil';
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
  $id_sucursal = $_SESSION['id_sucursal'];
  $id_perfil = $_REQUEST["id_perfil"];
  $sql = _query("SELECT *FROM perfil WHERE id_perfil='$id_perfil' AND  id_sucursal = '$id_sucursal' ");
  $datos = _fetch_array($sql);
  $nombre_perfil=$datos["nombre_perfil"];
  $precio_perfil=$datos["precio_perfil"];
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
              <h3 class="text-navy"><b><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo $title;?></b></h3>
            </div>
            <div class="ibox-content">
             <form name="formulario" id="formulario">
              <div class="row">
                <div class="form-group col-lg-6">
                  <label>Nombre</label>
                  <input type="text" placeholder="Ingrese nombre" class="form-control may" id="nombre_perfil" name="nombre_perfil"value="<?php echo $nombre_perfil; ?>">
                </div>
                <div class="form-group col-lg-6">
                  <label>Precio</label>
                  <input type="text" placeholder="Ingrese precio" class="form-control" id="precio_perfil" name="precio_perfil" value="<?php echo $precio_perfil; ?>">
                </div>
              </div>
                <div class="row">
                  <div class="form-group col-lg-6">
                    <label>Examen</label>
                    <input type="text" placeholder="Ingrese el nombre del examen" class="form-control may" autocomplete="off" id="nombre_examen" name="nombre_examen" data-provide="typeahead">
                  </div>
                </div>
                <div>
                  <table class="table table-bordered table-hover" id="tablai">
                    <thead>
                      <tr>
                        <th class="col-lg-1">ID</th>
                        <th class="col-lg-10">EXAMEN</th>
                        <th class="col-lg-1">ACCI&Oacute;N</th>
                      </tr>
                    </thead>
                    <tbody id="liexamen">
                      <?php
                      $sql1 = _query("SELECT p.*, ep.*, e.nombre_examen,e.id_examen FROM perfil as p, examen_perfil as ep, examen e WHERE p.id_perfil='$id_perfil' AND p.id_perfil=ep.id_perfil AND e.id_examen=ep.id_examen ");
                      while($datos=_fetch_array($sql1))
                      {

                        ?>
                        <tr >
                          <td class='id_examen'><?php echo $datos["id_examen"];?></td>
                          <td class='nombre_examen'><?php echo $datos["nombre_examen"];?></td>
                          <td class='text-center'><a class='lndelete btn'><i class='fa fa-trash'></i></a></td>
                        </tr>
                        <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>


                <div class="row">
                  <div class="form-actions col-lg-12">
                    <input type="hidden" name="process" id="process" value="edited">
                    <input type="hidden" name="id_perfil" id="id_perfil" value="<?php echo $id_perfil; ?>">
                    <input type="submit" id="sig" name="sig" value="Guardar" class="btn btn-primary  m-t-n-xs pull-right"/>
                  </div>
                </div>

              </form>

            </div>
          </div>
          <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>
        </div>
      </div>

    </div>

    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_perfil.js'></script>";
  } //permiso del script
  else {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
  }
}


function editar_perfil()
{
  $id_perfil = $_POST["id_perfil"];
  $nombre_perfil = $_POST["nombre_perfil"];
  $precio = $_POST["precio_perfil"];
  $id_examen= $_POST["id_examen"];
  $id_sucursal=$_SESSION["id_sucursal"];

  $sql_result=_query("SELECT id_perfil FROM perfil WHERE nombre_perfil='$nombre_perfil' AND id_perfil!='$id_perfil'");
  $numrows=_num_rows($sql_result);

  $table = 'perfil';
  $form_data = array (
    'nombre_perfil' => $nombre_perfil,
    'precio_perfil' => $precio,
    'id_sucursal'=>$id_sucursal
  );
  $where_clause = "id_perfil ='".$id_perfil."'";
  if($numrows == 0)
  {
    _begin();
    $insertar = _update($table,$form_data, $where_clause);
    $fila = explode("#", $id_examen);
    if($insertar)
    {
      $tabla = 'examen_perfil';
      $where_clausee = "id_perfil='" . $id_perfil . "'";
      $delete = _delete ( $tabla, $where_clausee );
      if($delete)
      {
        if($id_examen=="")
        {
          _rollback();
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='No se agregaron examenes!';
        }
        else
        {
          $n=0;
          for($i=0; $i<(count($fila)-1); $i++)
          {
            $columnas= explode("#", $id_examen);
            $table2 = 'examen_perfil';
            $form_data2 = array (
              'id_perfil' => $id_perfil,
              'id_examen' => $columnas[$i],
              'id_sucursal' => $id_sucursal
            );
            $insertar2 = _insert($table2,$form_data2);
            if($insertar2)
            {
              $n++;
            }
          }
          if($n == (count($fila)-1))
          {
            _commit();
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Perfil editado correctamente!';
            $xdatos['process']='insert';
          }
          else
          {
            _rollback();
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Perfil no pudo ser editado!';
          }
        }
      }
      else
      {
        _rollback();
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Perfil no pudo ser editado!';
      }
    }
    else
    {
      _rollback();
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Perfil no pudo ser editado!';
    }
  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Este perfil ya fue registrado!';
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
      editar_perfil();
      break;
    }
  }
}
?>
