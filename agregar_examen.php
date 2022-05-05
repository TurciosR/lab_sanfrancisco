<?php
include_once "_core.php";

function initial() {
  // Page setup
  $title = 'Agregar Examen';
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
              <h3 class="text-navy"><b><i class="fa fa-plus-circle fa-1x"></i> <?php echo $title;?></b></h3>
            </div>
            <div class="ibox-content">
              <form name="formulario" id="formulario">
                <div class="row">
                  <div class="form-group col-lg-4">
                    <label>Categoria</label>
                    <select class="form-control select" name="id_categoria" id="id_categoria">
                      <option value="">Seleccionar</option>
                      <?php
                      $categoria = _query("SELECT * FROM categoria WHERE id_sucursal='$id_sucursal' AND estado=1");
                      while($row_categoria = _fetch_array($categoria))
                      {
                        echo "<option value='".$row_categoria["id_categoria"]."' >".$row_categoria["nombre_categoria"]."</option>";
                        //echo " >".$row_tipo_examen["nombre"]."</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-lg-4">
                    <label>Nombre</label>
                    <input type="text" placeholder="Ingrese nombre" class="form-control may" id="nombre_examen" name="nombre_examen">
                  </div>
                  <div class="form-group col-lg-4">
                    <label>Precio</label>
                    <input type="text" placeholder="Ingrese precio" class="form-control" id="precio_examen" name="precio_examen">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading" data-toggle="collapse" href="#collapse1">
                        <h4 class="panel-title">
                          <a>INSUMOS</a>
                        </h4>
                      </div>
                      <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                          <div class="row">
                            <div class="form-group col-lg-4">
                              <label>Insumo</label>
                              <input type="text" placeholder="Ingrese el nombre del insumo" class="form-control may" autocomplete="off" id="nombre_insumo" name="nombre_insumo" data-provide="typeahead">
                            </div>
                          </div>
                          <div>
                            <table class="table table-bordered table-hover" id="tablai">
                              <thead>
                                <tr>
                                  <th class="col-lg-1">ID</th>
                                  <th class="col-lg-4">INSUMO</th>
                                  <th class="col-lg-3">PRESENTACION</th>
                                  <th class="col-lg-3">CANTIDAD</th>
                                  <th class="col-lg-1">ACCION</th>
                                </tr>
                              </thead>
                              <tbody id="linsumo">

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">


                  <div class="form-group col-lg-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading" >
                        <h4 class="panel-title">
                          <a>CAMPOS</a>
                        </h4>
                      </div>
                      <div >
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-lg-2">
                              <a id='agregar_seccion' name='agregar_seccion' class='btn btn-primary '>Agregar Secci&oacute;n</a>
                              <br><br>
                            </div>
                          </div>


                          <div>
                            <table class="table table-bordered table-hover" id="tabla">
                              <thead>
                                <tr>
                                  <!--style="display:none;"-->
                                  <th class="col-lg-3">PARAMETRO</th>
                                  <th class="col-lg-2">UNIDAD DE MEDIDA</th>
                                  <th class="col-lg-3">VALOR PREDEFINIDO</th>
                                  <th style="display:none;" class="col-lg-2">VALORES DE REFERENCIA oculto</th>
                                  <th class="col-lg-2">VALORES DE REFERENCIA</th>
                                  <th class="col-lg-1">ACCION</th>
                                </tr>
                              </thead>
                              <tbody id="tsb">
                                <tr  style='height:35px;'>
                                  <td class='tex param'></td>
                                  <td class='tex unidad'></td>
                                  <td class='tex predefinido'></td>
                                  <td style="display:none;" class='vr'></td>
                                  <td  class="vr_hidden"></th>
                                  <td class='text-center'><a class=' lndelete' type='button' name='button'> <span class='fa fa-trash'></span> </a></td>
                                </tr>
                              </tbody>
                            </table>
                            <a  data-toggle='modal' href='valores_referencia.php' data-target='#viewModal' data-refresh='true' id="modal">  </a>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-actions col-lg-12">
                    <input type="hidden" name="process" id="process" value="insert">
                    <input type="submit" id="sig" name="sig" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                  </div>
                </div>

              </form>

            </div>
          </div>
          <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div>
      </div>

    </div>

    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_examen.js'></script>";
  } //permiso del script
  else {
    echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
  }
}


function insertar_examen()
{
  $insumos = $_POST["insumos"];
  $formulario = $_POST["formulario"];
  $id_categoria = $_POST["id_categoria"];
  $nombre_examen = $_POST["nombre_examen"];
  $precio = $_POST["precio_examen"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $sql_result=_query("SELECT id_examen FROM examen WHERE nombre_examen='$nombre_examen'and id_sucursal='$id_sucursal'");
  $numrows=_num_rows($sql_result);
  $table = 'examen';
  $form_data = array (
    'id_categoria' => $id_categoria,
    'nombre_examen' => $nombre_examen,
    'precio_examen' => $precio,
    'formulario' => $formulario,
    'id_sucursal'=>$id_sucursal
  );
  if($numrows == 0)
  {
    _begin();
    $insertar = _insert($table,$form_data);
    $sql = _query("SELECT id_examen FROM examen WHERE nombre_examen='$nombre_examen'and id_sucursal='$id_sucursal'");
    $datos = _fetch_array($sql);
    $id_examen=$datos["id_examen"];
    $fila = explode("#", $insumos);

    if($insertar)
    {

      /////////////SIN INSUMOS//////////
      if(count($fila)==1)
      {
        _commit();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Examen ingresado correctamente!';
        $xdatos['process']='insert';
      }
      ////////////CON INSUMOS/////////////
      else
      {
        $n = 0;
        for($i=0; $i<(count($fila)-1); $i++)
        {
          $columnas= explode("|", $fila[$i]);
          $table2 = 'insumo_examen';
          $form_data2 = array (
            'id_examen' => $id_examen,
            'id_producto' => $columnas[0],
            'id_presentacion' => $columnas[2],
            'cantidad' => $columnas[1],
            'id_sucursal'=>$id_sucursal
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
          $xdatos['msg']='Examen ingresado correctamente!';
          $xdatos['process']='insert';
        }
        else
        {
          _rollback();
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Examen no pudo ser ingresado!';
        }
      }
    }
    else
    {
      _rollback();
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Examen no pudo ser ingresado!';
    }
  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Este examen ya fue ingresado!';
  }
  echo json_encode($xdatos);
}
function getpresentacion()
{
  $id_producto =$_POST['id_producto'];
  $sql=_query("SELECT pp.*, p.nombre FROM presentacion_producto as pp JOIN presentacion as p ON (pp.presentacion=p.id_presentacion) WHERE pp.id_producto=$id_producto");
  $select= "<select class='form-control sel' style='width:100%;'>";
  while($row = _fetch_array($sql))
  {
    $select .= "<option value='".$row["id_presentacion"]."'>".$row["nombre"]."(".$row["unidad"].")</option>";
  }
  $select.="</select>";
  $xdatos["select"] = $select;
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
      insertar_examen();
      break;
      case 'getpresentacion':
      getpresentacion();
      break;
    }
  }
}
?>
