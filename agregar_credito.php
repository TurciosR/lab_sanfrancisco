<?php
include_once "_core.php";
function initial()
{
  $title = 'Agregar Credito';
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
              <h5><?php echo $title; ?></h5>
            </div>
            <div class="ibox-content">
              <form name="formulario" id="formulario">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group has-info single-line">
                        <label>Cliente  <span style="color:red;">*</span></label>
                        <input type="text" placeholder="Nombre del Cliente" class="form-control" id="cliente" name="clientecliente">
                        <input type="hidden" id="id_cliente">
                        <label id="mcliente"></label>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-info single-line">
                        <label>Fecha  <span style="color:red;">*</span></label>
                        <input type="text" placeholder="" class="form-control datepicker" id="fecha" name="fecha">
                      </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group has-info single-line">
                        <label>Tipo Doc</label>
                        <input type="text" placeholder="" class="form-control" id="tipo_doc" name="tipo_doc">
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-info single-line">
                      <label>Numero Doc</label>
                      <input type="text" placeholder="" class="form-control" id="numero_doc" name="numero_doc">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group has-info single-line">
                      <label>Dias Credito  <span style="color:red;">*</span></label>
                      <input type="text" placeholder="" class="form-control numeric" id="dias" name="dias">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-info single-line">
                      <label>Monto  <span style="color:red;">*</span></label>
                      <input type="text" placeholder="" class="form-control decimal" id="monto" name="monto">
                    </div>
                  </div>
                </div>
                <div class="alert alert-info"><h3>Abonos</h3></div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Fecha</label>
                      <input type="text" placeholder="" class="clear form-control datepicker" id="fecha_a" name="fecha_a">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Tipo Doc</label>
                      <input type="text" placeholder="" class="clear form-control" id="tipo_doca" name="tipo_doca">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Numero Doc</label>
                      <input type="text" placeholder="" class="clear form-control" id="numero_doca" name="numero_doca">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group has-info single-line">
                      <label>Abono</label>
                      <input type="text" placeholder="" class="clear form-control decimal" id="abono" name="abono">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                  <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Tipo Doc</th>
                      <th>Numero Doc</th>
                      <th>Monto</th>
                      <th>Accion</th>
                    </tr>
                  </thead>
                    <tbody id="append">

                    </tbody>
                  </table>
                </div>
                </div>
                <input type="hidden" name="process" id="process" value="insert"><br>
                <div>
                  <a class="btn btn-primary" id="submit1">Guardar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    include_once ("footer.php");
    echo "<script src='js/funciones/funciones_recons.js'></script>";
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
  $id_cliente=$_POST["id_cliente"];
  $fecha=$_POST["fecha"];
  $tipo_doc=$_POST["tipo_doc"];
  $numero_doc=$_POST["numero_doc"];
  $monto=$_POST["monto"];
  $dias=$_POST["dias"];
  $cuantos=$_POST["cuantos"];
  $lista=$_POST["lista"];
  $id_sucursal = $_SESSION["id_sucursal"];

  $sql_exis=_query("SELECT id_credito FROM credito WHERE id_cliente ='$id_cliente' AND fecha='$fecha' AND id_sucursal='$id_sucursal' AND total='$monto'");
  $num_exis = _num_rows($sql_exis);
  if($num_exis > 0)
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Ya se registro un credito con estos datos!';
  }
  else
  {
    $table_cre = 'credito';
    $form_data_cre = array(
      'id_cliente' => $id_cliente,
      'fecha' => $fecha,
      'tipo_doc' => $tipo_doc,
      'numero_doc' => $numero_doc,
      'id_cobro' => 0,
      'dias' => $dias,
      'total' => $monto,
      'abono' => 0,
      'saldo' => $monto,
      'id_sucursal' => $id_sucursal,
    );
    _begin();
    $insert_cre = _insert($table_cre, $form_data_cre);

    echo _error();
    if($insert_cre)
    {
      $id_credito = _insert_id();
      if($cuantos>0)
      {
        $dtos = explode("|",$lista);
        $table = 'abono_credito';
        $n=0;
        for($i=0; $i<$cuantos;$i++)
        {
          $detalle = explode(",",$dtos[$i]);
          $montoa = $detalle[3];
          $fechaa = $detalle[0];
          $tipo_doca = $detalle[1];
          $num_doc = $detalle[2];
          $form_data = array(
            'id_credito' => $id_credito,
            'abono' => $montoa,
            'fecha' => $fechaa,
            'hora' => "08:00:00",
            'tipo_doc' => $tipo_doca,
            'num_doc' => $num_doc,
          );
          $insertar1 = _insert($table, $form_data);
          if ($insertar1)
          {
            $sql=_query("SELECT credito.total,credito.abono,credito.saldo FROM credito WHERE credito.id_credito=$id_credito");
            $row=_fetch_array($sql);
            $abono_previo=$row['abono'];
            $saldo=$row['saldo'];
            $id_abono_credito = _insert_id();
            $nuevosaldo=round(($saldo-$montoa), 2);
            $nuevo_val_abono=round(($abono_previo+$montoa), 2);
            $form_data = array(
              'abono' => $nuevo_val_abono,
              'saldo' => $nuevosaldo,
            );
            $where_clause = "id_credito='" . $id_credito . "'";
            $updates = _update($table_cre, $form_data, $where_clause);
            if($updates)
            {
              $n++;
            }
          }
        }
        if($n==$cuantos)
        {
          _commit();
          $xdatos['typeinfo']='Success';
          $xdatos['msg']='Registro guardado con exito!';
          $xdatos['process']='insert';
        }
        else
        {
          _rollback();
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Registro no pudo ser guardado !'._error();
        }
      }
      else
      {
        _commit();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro guardado con exito!';
        $xdatos['process']='insert';
      }
    }
    else
    {
      _rollback();
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser guardado !'._error();
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
