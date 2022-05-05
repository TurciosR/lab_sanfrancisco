<?php
include_once "_core.php";

function initial()
{
  include ("_core.php");
  $id_movimiento = $_REQUEST['id_movimiento'];
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  //permiso del script
  ?>
  <div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  	<h4 class="modal-title">Anular Traslado</h4>
  </div>
  <div class="modal-body">
  	<div class="wrapper wrapper-content  animated fadeInRight">
  		<div class="row" id="row1">
  			<div class="col-lg-12">
  				<?php if ($links!='NOT' || $admin=='1' ){ ?>
            <div class="alert alert-warning" role="alert">
              ¿Esta seguro de anular este traslado?
            </div>

  				</div>
  			</div>
  		</div>
  	</div>
  	<div class="modal-footer">
      <button type='button' id="anular" name="anular" class='btn btn-danger'>Anular</button>
      <button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
      <input type="hidden" id="id_movimiento" name="id_movimiento" value="<?php echo $id_movimiento ?>">
  	</div><!--/modal-footer -->
  		<?php
  	} //permiso del script
  	else {
  		echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
  	}
}
function anular()
{
  _begin();
  $id_movimiento = $_POST["id_movimiento"];
  $up=0;
  $up2=0;
  $i=0;
  $an=0;


  $sel=_fetch_array(_query("SELECT id_traslado FROM movimiento_producto WHERE id_movimiento=$id_movimiento"));
  $id_traslado=$sel['id_traslado'];

  $table="traslado";
  $form_data = array
  (
    'anulada' => 1,
  );
  $where_clause="id_traslado='".$id_traslado."'";
  $update=_update($table,$form_data,$where_clause);

  if ($update) {
    # code...

  }
  else {
    # code...
    $an=1;
  }

  $sql_su=_query("SELECT movimiento_producto_detalle.id_producto,movimiento_producto_detalle.cantidad,movimiento_producto_detalle.id_presentacion FROM movimiento_producto_detalle WHERE id_movimiento=$id_movimiento");
  while ($row=_fetch_array($sql_su)) {
    # code...
    $id_producto=$row['id_producto'];
    $cantidad=$row['cantidad'];
    $id_presentacion=$row['id_presentacion'];


      $sql_stock=_fetch_array(_query("SELECT id_stock,stock FROM stock WHERE id_producto='".$id_producto."' AND id_sucursal=$_SESSION[id_sucursal]"));
      $sql_stock_anterior=$sql_stock['stock'];
      $stock_nuevo=$sql_stock_anterior+$cantidad;
      $id_stock=$sql_stock['id_stock'];

      $table="stock";
      $form_data = array(
        'stock' => $stock_nuevo,
      );
      $where_clause="id_stock='".$id_stock."'";

      $update=_update($table,$form_data,$where_clause);
      if ($update) {
        # code...
      }
      else {
        # code...
        $up=1;
      }
  }

  if($i==0)
  {
    if ($up==0&&$up2==0&&$an==0)
    {
      _commit();
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Registro ingresado correctamente!';
      $xdatos['process']='insert';
    }
    else
    {
      _rollback();
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Registro no pudo ser ingresado!';
      $xdatos['process']='none';
    }
 }
 else {
   _rollback();
   $xdatos['typeinfo']='Error';
   $xdatos['msg']='Stock insuficiente para realizar anulación!'.$stock_destino;
   $xdatos['process']='none';
 }
echo json_encode($xdatos);
}

if (!isset($_POST['process'])) {
  initial();
} else {
  if (isset($_POST['process'])) {
    switch ($_POST['process']) {
      case 'anular':
      anular();
      break;
    }
  }
}
?>
