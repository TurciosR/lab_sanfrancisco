<?php
include_once "_core.php";
function initial()
{
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  //permiso del script
  ?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btn_clos">&times;</button>
    <h4 class="modal-title">Agregar Descuento</h4>
  </div>
  <div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row" id="row1">
        <div class="col-lg-12">
          <?php if($links != 'NOT' || $admin == '1'){ ?>
            <form name="formulario" id="formulario" autocomplete="off">
              <div class="form-group has-info single-line">
                <label class="control-label">Porcentaje</label>
                <input type="text" placeholder="" class="form-control" id="porcentaje" name="porcentaje">
              </div>
              <div class="form-group has-info single-line">
                <label class="control-label">PIN</label>
                <input type="text" placeholder="" readOnly class="form-control" id="pin" name="pin">
              </div>
              <div>
                <button type='button' class="btn btn-primary" id="btn_generar">Generar PIN</button>
                <button type='button' class="btn btn-primary pull-right" id="btn_savea">Guardar</button>
                <input type="hidden" name="process" id="process" value="insert_d">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
      $("#porcentaje").numeric({negative:false});
      $("#btn_generar").click(function(){
        generar_pin();
      });
      generar_pin();
    });
    </script>
    <?php
  }
  else
  {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  }
}
function generate_pin()
{
    $length = 4;
    $number = '0123456789';
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $numbersLength = strlen($number);
    $randomString = '';
    $randomString .= $characters[rand(0, $charactersLength - 1)];
    for ($i = 0; $i < $length; $i++)
    {
        $randomString .= $number[rand(0, $numbersLength - 1)];
    }
    $randomString .= $characters[rand(0, $charactersLength - 1)];
    $xdata["pin"] = $randomString;
    echo json_encode($xdata);
}
function insert()
{
  $porcentaje = $_POST["porcentaje"];
  $pin = $_POST["pin"];
  $fecha = date("Y-m-d");
  $hora = date("H:i:s");
  $id_sucursal = $_SESSION["id_sucursal"];
  $id_usuario = $_SESSION["id_usuario"];
  $sql_result= _query("SELECT * FROM descuento WHERE hash='$pin'");
  $numrows=_num_rows($sql_result);

  $table = 'descuento';
  $form_data = array(
    'id_usuario_gen' => $id_usuario,
    'porcentaje' => $porcentaje,
    'hash' => $pin,
    'fecha' => $fecha,
    'hora' => $hora,
    'id_sucursal' => $id_sucursal,
  );
  if ($numrows == 0)
  {
    $insertar = _insert($table, $form_data);
    if ($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro ingresado correctamente!';
      $xdatos['process']='insert';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser ingresado!'._error();
      $xdatos['process']='none';
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
      case 'insert_d':
      insert();
      break;
      case 'pin':
      generate_pin();
      break;
    }
  }
}
?>
