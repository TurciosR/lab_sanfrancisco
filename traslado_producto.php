<?php
include_once "_core.php";

function initial()
{
  $title = "Traslado de Productos";
  $_PAGE = array();
  $_PAGE ['title'] = $title;
  $_PAGE ['links'] = null;
  $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

  include_once "header.php";
  include_once "main_menu.php";

  $sql="SELECT * FROM producto";

  $result=_query($sql);
  $count=_num_rows($result);
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user, $filename);
  $fecha_actual=date("Y-m-d");

  ?>

  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-2"></div>
  </div>
  <div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox">
          <div class="ibox-title">
            <h5><?php echo $title;?></h5>
          </div>
          <?php if ($links!='NOT' || $admin=='1') { ?>
            <div class="ibox-content">
              <div class='row' id='form_invent_inicial'>
                <div class="col-lg-4">
                  <div class="form-group has-info">
                    <label>Concepto</label>
                    <input type='text' class='form-control' value='TRASLADO DE PRODUCTOS' id='concepto' name='concepto'>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group has-info">
                    <label>Destino</label>
                    <select class="form-control select" id="id_sucursal" name="id_sucursal">
                      <option value="">Seleccione</option>
                      <?php
                      $sql_suc=_query("SELECT * FROM sucursal WHERE id_sucursal!=$_SESSION[id_sucursal]");
                      while ($row_suc=_fetch_array($sql_suc)) {
                        # code...
                        $sql_suc=_query("SELECT * FROM sucursal WHERE id_sucursal!=$_SESSION[id_sucursal]");
                        while ($row_suc=_fetch_array($sql_suc)) {
                          # code...
                            $sql_su=_fetch_array(_query("SELECT CONCAT('Sucursal ',sucursal.nombre_lab,' ',sucursal.direccion) as destino FROM sucursal WHERE id_sucursal=$row_suc[id_sucursal]"));
                            $a=(Mayu(utf8_decode($sql_su['destino'])));
                          ?>
                          <option value="<?php echo $row_suc['id_sucursal'] ?>"><?php echo $a; ?></option>
                          <?php
                        }
                        ?>
                        <option value="<?php echo $row_suc['id_sucursal'] ?>"><?php echo MAYU(utf8_decode($row_suc['descripcion'])) ?></option>
                        <?php
                      }
                       ?>
                    </select>
                  </div>
                </div>

                <div class='col-lg-4'>
                  <div class='form-group has-info'>
                    <label>Fecha</label>
                    <input type='text' class='datepick form-control' value='<?php echo $fecha_actual; ?>' id='fecha1' name='fecha1'>
                  </div>
                </div>
              </div>
              <div class="row" id='buscador'>

                <div class="col-lg-4">
                  <div class='form-group has-info'><label>Origen</label>
                    <select name='origen' id="origen" class="form-control select">
                    <?php
                    $sql_suc=_query("SELECT * FROM sucursal WHERE id_sucursal=$_SESSION[id_sucursal]");
                    while ($row_suc=_fetch_array($sql_suc)) {
                      # code...
                      $sql_suc=_query("SELECT * FROM sucursal WHERE id_sucursal=$_SESSION[id_sucursal]");
                      while ($row_suc=_fetch_array($sql_suc)) {
                        # code...
                          $sql_su=_fetch_array(_query("SELECT CONCAT('Sucursal ',sucursal.nombre_lab,' ',sucursal.direccion) as destino FROM sucursal WHERE id_sucursal=$row_suc[id_sucursal]"));
                          $a=(Mayu(utf8_decode($sql_su['destino'])));
                        ?>
                        <option value="<?php echo $row_suc['id_sucursal'] ?>"><?php echo $a; ?></option>
                        <?php
                      }
                      ?>
                      <option value="<?php echo $row_suc['id_sucursal'] ?>"><?php echo MAYU(utf8_decode($row_suc['descripcion'])) ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class='form-group has-info'><label>Buscar Productos</label>
                    <input type="text" id="producto_buscar" name="producto_buscar" size="20" class="producto_buscar form-control" placeholder="Ingrese nombre de producto"  data-provide="typeahead">
                  </div>
                </div>
              </div>
              <div class="ibox">
                <div class="row">
                  <div class="ibox-content">
                    <!--load datables estructure html-->
                    <header>
                      <h4 class="text-navy">Lista de Productos</h4>
                    </header>


                  <div  class='widget-content' id="content">
                    <div class="row">
                  <div class="col-md-12">

                    <table class="table table-striped" id='loadtable'>
                      <thead class='thead1'>
                        <tr class='tr1'>
                          <th class="text-success col-lg-5">Descripción</th>
                          <th class="text-success col-lg-1 text-center">Presentación</th>
                          <th class="text-success col-lg-1 text-center">Detalle</th>
                          <th class="text-success col-lg-1 text-center">Costo</th>
                          <th class="text-success col-lg-1 text-center">Precio</th>
                          <th class="text-success col-lg-1 text-center">Exis Unid.</th>
                          <th class="text-success col-lg-1 text-center">Cantidad</th>
                          <th class="text-success col-lg-1 text-center"></th>
                        </tr>
                      </thead>
                      <tbody class='tbody1 ' id="mostrardatos">
                      </tbody>
                    </table>
                  </div>
                </div>
                <!--/div-->

              </div>
              <div id="paginador"></div>
              <div class="widget-content" >
                <label>Total: </label>
                <label id="total_dinero"> </label>
              </div>
              <div class="widget-content" >
                <label>Cantidad: </label>
                <label id="totcant"></label>
              </div>
                    <input type="hidden" name="process" id="process" value="insert"><br>
                    <div class="widget-content">
                      <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
                      <input type='hidden' name='urlprocess' id='urlprocess'value="<?php echo $filename ?> ">
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div><!--div class='ibox-content'-->
        </div>
      </div>
    </div>
  </div>
<?php
  include_once ("footer.php");
  echo "<script src='js/funciones/funciones_traslado.js'></script>";
} //permiso del script
else
{
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
    include_once ("footer.php");
}
}
function traerdatos()
{
  $start = !empty($_POST['page'])?$_POST['page']:0;
  $limit =$_POST['records'];
  $sortBy = $_POST['sortBy'];
  $producto_buscar = $_POST['producto_buscar'];
  $origen = $_POST['origen'];

  $sqlJoined="SELECT pr.id_producto,pr.descripcion, pr.barcode FROM
  producto AS pr, stock as st";
  //  $sqlParcial=get_sql($keywords, $id_color, $estilo, $talla, $barcode, $limite);
  $sqlParcial= get_sql($start,$limit,$producto_buscar,$origen,$sortBy);
  $groupBy="";
  $limitSQL= " LIMIT $start,$limit ";
  $sql_final= $sqlJoined." ".$sqlParcial." ".$groupBy." ".$limitSQL;
  $query = _query($sql_final);

  echo _error();
  $num_rows = _num_rows($query);
  $filas=0;
  if ($num_rows > 0)
  {
    while ($row = _fetch_array($query))
    {
      $id_producto = $row['id_producto'];
      $sql_existencia = _query("SELECT sum(stock) as existencia FROM stock WHERE id_producto='$id_producto' AND id_sucursal='$origen'");
      $dt_existencia = _fetch_array($sql_existencia);
      $existencia = $dt_existencia["existencia"];
      $descripcion=$row["descripcion"];
      $barcode = $row['barcode'];
      $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,prp.id_presentacion,prp.unidad,prp.costo,prp.precio
                            FROM presentacion_producto AS prp
                            JOIN presentacion ON presentacion.id_presentacion=prp.presentacion
                            WHERE prp.id_producto=$id_producto
                            AND prp.activo=1 AND prp.id_sucursal=$_SESSION[id_sucursal]");
      $i=0;
      $unidadp=0;
      $costop=0;
      $preciop=0;
      $descripcionp="";
      $select="<select class='sel'>";
      while ($row=_fetch_array($sql_p))
      {
        if ($i==0)
        {
          $unidadp=$row['unidad'];
          $costop=$row['costo'];
          $preciop=$row['precio'];
          $descripcionp=$row['descripcion'];
        }
        $select.="<option value='".$row["id_presentacion"]."'>".$row["nombre"]." (".$row["unidad"].")</option>";
        $i=$i+1;
      }
      $select.="</select>";
      $input = "<input type='text' readonly class='cant form-control numeric' style='width:100%;'>";
      ?>
      <tr>
        <td class='col-lg-5'> <input type='hidden' class='id_producto' name='' value='<?php echo $id_producto ?>'> <input type='hidden' class="unidad" value='<?php echo $unidadp; ?>'><?php echo $descripcion; ?></td>
        <td class='col-lg-1 text-center'><?php echo $select; ?></td>
        <td class='col-lg-1 text-center descp'><?php echo $descripcionp; ?></td>
        <td class='col-lg-1 text-center precio_compra'><?php echo $costop; ?></td>
        <td class='col-lg-1 text-center precio_venta'><?php echo $preciop; ?></td>
        <td class='col-lg-1 text-center exis'><?php echo $existencia; ?></td>
        <td class='col-lg-1 text-center'><?php echo $input; ?></td>
        <td class='col-lg-1 text-center'> <input type="checkbox" class='form-control cheke' name="" value=""></td>
      </tr>
      <?php
      $filas+=1;
    }
  }
}
function get_sql($start,$limit,$producto_buscar,$origen,$sortBy)
{
  $andSQL='';
  $id_sucursal= $_SESSION['id_sucursal'];
  $whereSQL=" WHERE pr.id_producto=st.id_producto
  AND st.stock >= 0
  AND st.id_sucursal = $origen ";
  $andSQL.= "AND  pr.descripcion LIKE '$producto_buscar%'";
  $orderBy="";
  $sql_parcial=$whereSQL.$andSQL.$orderBy;
  return $sql_parcial;
}
function traerpaginador()
{
  $start = !empty($_POST['page'])?$_POST['page']:0;
  $limit =$_POST['records'];
  $sortBy = $_POST['sortBy'];
  $producto_buscar= $_POST['producto_buscar'];
  $origen= $_POST['origen'];
  $limite=50;
  $whereSQL =$andSQL =  $orderSQL = '';
  if(isset($_POST['page']))
  {
    //Include pagination class file
    include('Pagination.php');
    //get partial values from sql sentence
    $sqlParcial=get_sql($start,$limit,$producto_buscar,$origen,$sortBy);
    //get number of rows
    $sql1="SELECT COUNT(*) as numRecords  FROM producto AS pr, stock AS st";
    $sql_numrows=$sql1.$sqlParcial;
    $queryNum = _query($sql_numrows);
    if(_num_rows($queryNum)>0)
    {
      $resultNum = _fetch_array($queryNum);
      $rowCount = $resultNum['numRecords'];
    }
    else
    {
        $rowCount = 0;
    }
    //initialize pagination class
    $pagConfig = array(
      'currentPage' => $start,
      'totalRows' => $rowCount,
      'perPage' => $limit,
      'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);
    echo $pagination->createLinks();
    echo '<input type="hidden" id="cuantos_reg"  value="'.$rowCount.'">';
  }
}
function insertar()
{
  $cuantos = $_POST['cuantos'];
  $datos = $_POST['datos'];
  $origen = $_POST['origen'];
  $fecha = $_POST['fecha'];
  $total = $_POST['total'];
  $concepto=$_POST['concepto'];
  $hora=date("H:i:s");
  $fecha_movimiento = date("Y-m-d");
  $id_empleado=$_SESSION["id_usuario"];

  $id_suc_destino=$_POST['id_suc_destino'];
  $id_ubicacion_destino=$_POST['id_ubicacion_destino'];

  $id_sucursal = $_SESSION["id_sucursal"];
  $sql_num = _query("SELECT tre FROM correlativo WHERE id_sucursal='$id_sucursal'");
  $datos_num = _fetch_array($sql_num);
  $ult = $datos_num["tre"]+1;
  $numero_doc=str_pad($ult,7,"0",STR_PAD_LEFT).'_TRE';
  $tipo_entrada_salida='TRASLADO DE PRODUCTO';

  _begin();
  $z=1;
  $up=1;

  /*actualizar los correlativos de TRE*/
  $corr=1;
  $table="correlativo";
  $form_data = array(
    'tre' =>$ult
  );
  $where_clause_c="id_sucursal='".$id_sucursal."'";
  $up_corr=_update($table,$form_data,$where_clause_c);
  if ($up_corr) {
    # code...
  }
  else {
    $corr=0;
  }
  if ($concepto=='')
  {
    $concepto='TRASLADO DE PRODUCTO';
  }

  /*Crear traslado*/
  $table="traslado";
  $form_data = array(
    'concepto' => $concepto,
    'fecha' => $fecha,
    'hora' => $hora,
    'id_empleado_envia' => $id_empleado,
    'id_empleado_recibe' =>0,
    'id_sucursal_origen' => $id_sucursal,
    'id_sucursal_destino' => $id_suc_destino,
    'id_ubicacion_destino'=>$id_ubicacion_destino,
    'total' =>  $total,
    'anulada' => 0,
    'finalizada' => 0,
   );
   $w=1;
   $insert_tra=_insert($table,$form_data);
   $id_traslado=_insert_id();

   if ($insert_tra) {
     # code...
   }
   else {
     # code...
     $w=0;
   }


  /*crear el movimiento de salida*/
  $concepto=$concepto;
  $table='movimiento_producto';
  $form_data = array(
    'id_sucursal' => $id_sucursal,
    'correlativo' => $numero_doc,
    'concepto' => $concepto,
    'total' => $total,
    'tipo' => 'SALIDA',
    'proceso' => 'TRE',
    'referencia' => $numero_doc,
    'id_empleado' => $id_empleado,
    'fecha' => $fecha,
    'hora' => $hora,
    'id_suc_origen' => $id_sucursal,
    'id_suc_destino' => $id_suc_destino,
    'id_proveedor' => 0,
    'id_traslado' => $id_traslado,
  );
  $insert_mov =_insert($table,$form_data);
  $id_movimiento=_insert_id();
  $lista=explode('#',$datos);
  $j = 1 ;
  $k = 1 ;
  $l = 1 ;
  $m = 1 ;
  $y = 1 ;

  for ($i=0;$i<$cuantos ;$i++)
  {
    list($id_producto,$precio_compra,$precio_venta,$cantidad,$unidades,$fecha_caduca,$id_presentacion)=explode('|',$lista[$i]);


    $sql2="SELECT stock FROM stock WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'";
    $stock2=_query($sql2);
    $row2=_fetch_array($stock2);
    $nrow2=_num_rows($stock2);
    if ($nrow2>0)
    {
      $existencias=$row2['stock'];
    }
    else
    {
      $existencias=0;
    }

    $sql_get_p=_fetch_array(_query("SELECT presentacion FROM presentacion_producto WHERE id_presentacion=$id_presentacion"));
    $presentacion=$sql_get_p['presentacion'];

    $table='traslado_detalle';
    $form_data = array(
      'id_traslado' => $id_traslado,
      'id_producto' => $id_producto,
      'cantidad' => $cantidad,
      'unidad' => $unidades,
      'costo' => $precio_compra,
      'id_presentacion' => $id_presentacion,
      'presentacion'=> $presentacion,
    );
    $insert_tra_det=_insert($table,$form_data);
    if ($insert_tra_det) {
      # code...
    }
    else {
      # code...
      $y=0;
    }

    $table1= 'movimiento_producto_detalle';
    $cant_total=$existencias-$cantidad;
    $form_data1 = array(
      'id_movimiento'=>$id_movimiento,
      'id_producto' => $id_producto,
      'cantidad' => $cantidad,
      'costo' => $precio_compra,
      'precio' => $precio_venta,
      'stock_anterior'=>$existencias,
      'stock_actual'=>$cant_total,
      'lote' => 0,
      'id_presentacion' => $id_presentacion,
    );
    $insert_mov_det = _insert($table1,$form_data1);
    if(!$insert_mov_det)
    {
      $j = 0;
    }


    $table2= 'stock';
    if($nrow2==0)
    {
      $cant_total=$cantidad;
      $form_data2 = array(
        'id_producto' => $id_producto,
        'stock' => $cant_total,
        'costo_unitario'=>round(($precio_compra/$unidades),2),
        'precio_unitario'=>round(($precio_venta/$unidades),2),
        'create_date'=>$fecha_movimiento,
        'update_date'=>$fecha_movimiento,
        'id_sucursal' => $id_sucursal
      );
      $insert_stock = _insert($table2,$form_data2 );
    }
    else
    {
      $cant_total=$existencias-$cantidad;
      $form_data2 = array(
        'id_producto' => $id_producto,
        'stock' => $cant_total,
        'costo_unitario'=>round(($precio_compra/$unidades),2),
        'precio_unitario'=>round(($precio_venta/$unidades),2),
        'update_date'=>$fecha_movimiento,
        'id_sucursal' => $id_sucursal
      );
      $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'";
      $insert_stock = _update($table2,$form_data2, $where_clause );
    }
    if(!$insert_stock)
    {
      $k = 0;
    }

  }
  if($insert_mov&&$w&&$corr&&$z&&$j&&$k&&$l&&$m&&$y)
  {
    _commit();
    $xdatos['typeinfo']='Success';
    $xdatos['msg']='Registro ingresado con éxito!';
  }
  else
  {
    _rollback();
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Registro de no pudo ser ingresado!'.$insert_mov.$w .$corr .$z . $j . $k . $l . $m .$y;
  }
  echo json_encode($xdatos);
}
function consultar_stock()
{
  $id_producto = $_REQUEST['id_producto'];
  $id_sucursal=$_SESSION['id_sucursal'];

  $i=0;
  $unidadp=0;
  $preciop=0;
  $costop=0;
  $descripcionp=0;

  $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,prp.id_presentacion,prp.unidad,prp.costo,prp.precio FROM presentacion_producto AS prp JOIN presentacion ON presentacion.id_presentacion=prp.presentacion WHERE prp.id_producto=$id_producto AND prp.activo=1");
  $select="<select class='sel'>";
  while ($row=_fetch_array($sql_p))
  {
    if ($i==0)
    {
      $unidadp=$row['unidad'];
      $costop=$row['costo'];
      $preciop=$row['precio'];
      $descripcionp=$row['descripcion'];
    }
    $select.="<option value='".$row["id_presentacion"]."'>".$row["nombre"]." (".$row["unidad"].")</option>";
    $i=$i+1;
  }
  $select.="</select>";
  $xdatos['select']= $select;
  $xdatos['costop']= $costop;
  $xdatos['preciop']= $preciop;
  $xdatos['unidadp']= $unidadp;
  $xdatos['descripcionp']= $descripcionp;

  $sql_perece="SELECT * FROM producto WHERE id_producto='$id_producto'";
  $result_perece=_query($sql_perece);
  $row_perece=_fetch_array($result_perece);
  $perecedero=$row_perece['perecedero'];
  $xdatos['perecedero'] = $perecedero;
  echo json_encode($xdatos);
}
function getpresentacion()
{
  $id_presentacion =$_REQUEST['id_presentacion'];
  $sql=_fetch_array(_query("SELECT * FROM presentacion_producto WHERE id_presentacion=$id_presentacion"));
  $precio=$sql['precio'];
  $unidad=$sql['unidad'];
  $descripcion=$sql['descripcion'];
  $costo=$sql['costo'];
  $xdatos['precio']=$precio;
  $xdatos['costo']=$costo;
  $xdatos['unidad']=$unidad;
  $xdatos['descripcion']=$descripcion;
  echo json_encode($xdatos);
}
function ubicacion()
{
		$id_sucursal = $_POST['id_sucursal'];
    $sql = _query("SELECT * FROM ubicacion WHERE id_sucursal='$id_sucursal'");
    $opt = "<option value=''>Seleccione</option>";
    while ($row = _fetch_array($sql)) {
        $opt .="<option value='".$row["id_ubicacion"]."'>".$row["descripcion"]."</option>";
    }
    $xdatos["typeinfo"] = "Success";
    $xdatos["opt"] = $opt;
    echo json_encode($xdatos);
}
if (!isset($_REQUEST['process']))
{
  initial();
}
if (isset($_REQUEST['process']))
{
  switch ($_REQUEST['process'])
  {
    case 'insert':
    insertar();
    break;
    case 'consultar_stock':
    consultar_stock();
    break;
    case 'getpresentacion':
    getpresentacion();
    break;
    case 'traerdatos':
    traerdatos();
    break;
    case'traerpaginador':
    traerpaginador();
    break;
    case 'val':
    ubicacion();
    break;
  }
}
?>
