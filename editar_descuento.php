<?php
include_once "_core.php";
function initial()
{
  $id_descuento = $_REQUEST["id_descuento"];
  $sql = _query("SELECT porcentaje, hash FROM descuento WHERE id_descuento='$id_descuento'");
  $datos = _fetch_array($sql);
  $porcentaje = $datos["porcentaje"];
  $hash = $datos["hash"];

  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  //permiso del script
  ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btn_clos">&times;</button>
    <h4 class="modal-title">Editar Descuento</h4>
  </div>
  <div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row" id="row1">
        <div class="col-lg-12">
          <?php if($links != 'NOT' || $admin == '1'){ ?>
            <form name="formulario" id="formulario" autocomplete="off">
              <div class="form-group has-info single-line">
                <label class="control-label">Porcentaje</label>
                <input type="text" placeholder="" class="form-control" id="porcentaje" name="porcentaje" value="<?php echo $porcentaje; ?>">
              </div>
              <div class="form-group has-info single-line">
                <label class="control-label">PIN</label>
                <input type="text" placeholder="" readOnly class="form-control" id="pin" name="pin" value="<?php echo $hash; ?>">
              </div>
              <div>
                <button type='button' class="btn btn-primary" id="btn_generar">Generar PIN</button>
                <button type='button' class="btn btn-primary pull-right" id="btn_savea">Guardar</button>
                <input type="hidden" name="process" id="process" value="edit_d">
                <input type="hidden" name="id_descuento" id="id_descuento" value="<?php echo $id_descuento; ?>">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
      $("#porcentaje").numeric({negative:false, maxLength: 2, decimalPlaces:2});
      $("#porcentaje").keyup(function(){
        valida();
      });
      $("#btn_generar").click(function(){
        generar_pin();
        valida();
      });
    });
    function valida()
    {
      if(parseFloat($("#porcentaje").val())>=100)
      {
        $("#porcentaje").val("100");
      }
    }
    </script>
    <?php
  }
  else
  {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  }
}
function editar()
{
    $id_descuento = $_POST["id_descuento"];
    $porcentaje = $_POST["porcentaje"];
    $pin = $_POST["pin"];
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $id_sucursal = $_SESSION["id_sucursal"];
    $sql_result= _query("SELECT * FROM descuento WHERE hash='$pin' AND id_descuento!='$id_descuento'");
    $numrows=_num_rows($sql_result);

    $table = 'descuento';
    $form_data = array(
      'porcentaje' => $porcentaje,
      'hash' => $pin,
      'fecha' => $fecha,
      'hora' => $hora,
    );
    if ($numrows == 0)
    {

      if($porcentaje > 0 && $porcentaje <=100)
      {
        $where = "id_descuento ='".$id_descuento."'";
        $insertar = _update($table, $form_data, $where);
        if ($insertar)
        {
          $xdatos['typeinfo']='Success';
          $xdatos['msg']='Registro editado correctamente!';
          $xdatos['process']='insert';
        }
        else
        {
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Registro no pudo ser editado!';
          $xdatos['process']='none';
        }
      }
      else
      {
        $xdatos['typeinfo']='Warning';
        $xdatos['msg']='Ingrese un porcentaje mayor que cero y menor o igual a cien!';
      }
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Ya se utilizo este PIN por favor genere uno distinto!';
      $xdatos['process']='none';
    }
    echo json_encode($xdatos);
}

if (!isset($_POST['process'])) {
  initial();
} else {
  if (isset($_POST['process'])) {
    switch ($_POST['process']) {
      case 'edit_d':
      editar();
      break;
      case 'pin':
      generate_pin();
      break;
    }
  }
}
?>
