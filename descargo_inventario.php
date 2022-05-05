<?php
include_once "_core.php";

function initial()
{
  $title = "Descargo de Productos de Inventario";
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
  $id_sucursal=$_SESSION["id_sucursal"];
  $sql="SELECT * FROM producto WHERE id_sucursal='$id_sucursal'";
  $result=_query($sql);
  $count=_num_rows($result);
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user, $filename);
  date_default_timezone_set('America/El_Salvador');
	$fecha_hoy=date("Y-m-d");
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
                <div class="col-lg-3">
                  <div class="form-group has-info">
                    <label>Concepto</label>
                    <input type='text' class='form-control' value='DESCARGO DE PRODUCTOS' id='concepto' name='concepto'>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group has-info">
                    <label>Tipo</label>
                    <select class="form-control select" id="tipo" name="tipo">
                      <option value="VENCIMIENTO">VENCIMIENTO</option>
                      <option value="DESCARTE">DESCARTE</option>
                      <option value="PRODUCTO DAÑADO">PRODUCTO DAÑADO</option>
                      <option value="CONSUMO INTERNO">CONSUMO INTERNO</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group has-info">
                    <label>Tipo Descargo</label>
                    <select class="form-control select" id="tipoD" name="tipoD">
                      <option value="DESCARGA">DESCARGA</option>
                      <option value="AJUSTE INVENTARIO">INSUMO UTILIZADOS</option>
                        </select>
                  </div>
                </div>
                <div class='col-lg-3'>
                  <div class='form-group has-info'>
                    <label>Fecha</label>
                    <input type='text' class='datepicker form-control' value='<?php echo $fecha_actual; ?>' id='fecha1' name='fecha1'>
                  </div>
                </div>
              </div>
              <div class="row" id='buscador'>


                <div class="col-lg-8"  id="pruebas2">
                  <div class='form-group has-info'><label>Buscar Productos</label>
                    <input type="text" id="producto_buscar" name="producto_buscar" size="20" class="producto_buscar form-control" placeholder="Ingrese nombre de producto"  data-provide="typeahead">
                  </div>
                </div>

              </div>
              <div class="row" id="rangosF" hidden>
                <div class="form-group col-lg-4">
                  <label>Hasta</label>
                  <input type="text" placeholder="Fecha Final" class="datepicker form-control" id="hasta" name="hasta" value="<?php echo $fecha_hoy ?>" >
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
                          <th class="text-success col-lg-1 text-center">PRESENTACI&Oacute;N</th>
                          <th class="text-success col-lg-1 text-center">DETALLE</th>
                          <th class="text-success col-lg-1 text-center">COSTO</th>
                          <th class="text-success col-lg-1 text-center">PRECIO</th>
                          <th class="text-success col-lg-1 text-center">EXIS UNID.</th>
                          <th class="text-success col-lg-1 text-center">CANTIDAD</th>
                          <th class="text-success col-lg-1 text-center">ACCI&Oacute;N</th>
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
                      <input type='hidden' name='id_sucursal' id='id_sucursal'value="<?php echo $id_sucursal ?> ">
                    </div>

                  </form>
                </div>
              </div>
            </div>
            <div class='modal fade' id='verModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
              <div class='modal-dialog'>
                <div class='modal-content modal-md'></div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
          </div><!--div class='ibox-content'-->
        </div>
      </div>
    </div>
  </div>
<?php
  include("footer.php");
  echo "<script src='js/funciones/funciones_descargo_inventario.js'></script>";
} //permiso del script
else
{
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
}
function recibir()
{
  $hasta = $_POST["hasta"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $query_user = _query("SELECT pr.descripcion, pr.id_producto,pre.nombre,pp.descripcion as descrip,SUM(ie.cantidad) as suma  FROM  examen_paciente as ep
	inner JOIN examen as e ON (ep.id_examen=e.id_examen)
	inner JOIN insumo_examen as ie ON (ep.id_examen=ie.id_examen)
	inner JOIN producto as pr ON (ie.id_producto=pr.id_producto)
	inner JOIN presentacion as pre ON (ie.id_presentacion=pre.id_presentacion)
	inner JOIN presentacion_producto as pp ON (pre.id_presentacion=pp.id_presentacion)
  inner JOIN stock as st ON (pr.id_producto=st.id_producto)
	Where ep.id_examen>0  AND ep.examen_paciente_nulo= 0 AND  ep.inventariado=0 AND ep.fecha_realizado <='$hasta' and pr.id_sucursal='$id_sucursal'
  and ep.id_sucursal='$id_sucursal'and st.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'and
  ie.id_sucursal='$id_sucursal'group by pr.id_producto");

$num_rows = _num_rows($query_user);
$filas=0;
if ($num_rows > 0)
{
  while($datos_user = _fetch_array($query_user))
	{
    $cantidad=$datos_user["suma"];
    $id_pro=$datos_user["id_producto"];
    $descripcion=$datos_user["descripcion"];
    $sql_existencia = _query("SELECT sum(stock) as existencia FROM stock WHERE id_producto='$id_pro' and id_sucursal='$id_sucursal'");
    $sql_faltante = _query("SELECT sum(cantidad_fal) as fal FROM producto_faltante WHERE id_producto='$id_pro' and fecha<='$hasta' AND faltante!=0 and id_sucursal='$id_sucursal'");
    $dt_existencia = _fetch_array($sql_existencia);
    $dt_faltante = _fetch_array($sql_faltante);
    $existencia = $dt_existencia["existencia"];
    $faltante = $dt_faltante["fal"];
    $cantidadN=$cantidad+$faltante;
    $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,prp.id_presentacion,prp.unidad,prp.costo,prp.precio
                   FROM presentacion_producto AS prp
                   JOIN presentacion ON presentacion.id_presentacion=prp.presentacion
                   WHERE prp.id_producto='$id_pro'
                   AND prp.activo=1 AND prp.id_sucursal=1");
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
  $input = "<input type='text'  class='cant form-control numeric' style='width:100%;' value='$cantidadN' readOnly>";
?>
  <tr>
    <td class='col-lg-5'> <input type='hidden' class='id_producto' name='' value='<?php echo $id_pro ?>'> <input type='hidden' class='fal' name='' value='<?php echo $fal ?>'>
      <input type='hidden' class="unidad" value='<?php echo $unidadp; ?>'><?php echo $descripcion; ?></td>
    <td class='col-lg-1 text-center'><?php echo $select; ?></td>
    <td class='col-lg-1 text-center descp'><?php echo $descripcionp; ?></td>
    <td class='col-lg-1 text-center precio_compra'><?php echo $costop; ?></td>
    <td class='col-lg-1 text-center precio_venta'><?php echo $preciop; ?></td>
    <td class='col-lg-1 text-center exis'><?php echo $existencia; ?></td>
    <td class='col-lg-1 text-center'><?php echo $input; ?></td>
  </tr><?php
  $filas+=1;
}
}
}
function traerdatos()
{

      $id_producto = $_REQUEST['id_producto'];
      $descripcion = $_REQUEST['descripcion'];
      $id_sucursal=$_SESSION["id_sucursal"];

      $sql_existencia = _query("SELECT sum(stock) as existencia FROM stock WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'");
      $dt_existencia = _fetch_array($sql_existencia);
      $existencia = $dt_existencia["existencia"];
      $sql_p=_query("SELECT presentacion.nombre, prp.descripcion,prp.id_presentacion,prp.unidad,prp.costo,prp.precio
                            FROM presentacion_producto AS prp
                            JOIN presentacion ON presentacion.id_presentacion=prp.presentacion
                            WHERE prp.id_producto='$id_producto'
                            AND prp.activo=1 AND prp.id_sucursal=1");
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
      $input = "<input type='text'  class='cant form-control numeric' style='width:100%;' value=''>";

      $xdatos['id_producto']=$id_producto;
      $xdatos['unidadp']=$unidadp;
      $xdatos['descripcion']=$descripcion;
      $xdatos['select']=$select;
      $xdatos['descripcionp']=$descripcionp;
      $xdatos['costop']=$costop;
      $xdatos['preciop']= $preciop;
      $xdatos['existencia']= $existencia;
      $xdatos['input']= $input;
      echo json_encode($xdatos);


}
function get_sql($start,$limit,$producto_buscar,$origen,$sortBy)
{
  $andSQL='';
  $id_sucursal= $_SESSION["id_sucursal"];
  $whereSQL=" WHERE pr.id_producto=su.id_producto
  AND su.id_sucursal = '$id_sucursal'";
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
  $origen= "";
  $limite=50;
  $whereSQL =$andSQL =  $orderSQL = '';
  if(isset($_POST['page']))
  {
    //Include pagination class file
    include('Pagination.php');
    //get partial values from sql sentence
    $sqlParcial=get_sql($start,$limit,$producto_buscar,$origen,$sortBy);
    //get number of rows
    $sql1="SELECT COUNT(*) as numRecords  FROM producto AS pr, stock AS su where su.id_sucursal='$id_sucursal'and pr.id_sucursal='$id_sucursal'";
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
  $fecha = $_POST['fecha'];
  $total_compras = $_POST['total'];
  $concepto=$_POST['concepto'];
  date_default_timezone_set('America/El_Salvador');
  $hora=date("H:i:s");
  $fecha_movimiento = date("Y-m-d");
  $id_empleado=$_SESSION["id_usuario"];

  $id=$_POST['iden'];

  $id_sucursal = $_SESSION["id_sucursal"];
  $sql_num = _query("SELECT di FROM correlativo WHERE id_sucursal='$id_sucursal'");
  $datos_num = _fetch_array($sql_num);
  $ult = $datos_num["di"]+1;
  $numero_doc=str_pad($ult,7,"0",STR_PAD_LEFT).'_DI';
  $tipo_entrada_salida='DESCARGO DE INVENTARIO';

  _begin();
  $z=1;
  $up=1;

  /*actualizar los correlativos de DI*/
  $corr=1;
  $table="correlativo";
  $form_data = array(
    'di' =>$ult
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
    $concepto='DESCARGO DE INVENTARIO';
  }

  $concepto=$concepto."|".$id;
  $table='movimiento_producto';
  $form_data = array(
    'id_sucursal' => $id_sucursal,
    'correlativo' => $numero_doc,
    'concepto' => $concepto,
    'total' => $total_compras,
    'tipo' => 'SALIDA',
    'proceso' => 'DI',
    'referencia' => $numero_doc,
    'id_empleado' => $id_empleado,
    'fecha' => $fecha,
    'hora' => $hora,
    'id_suc_origen' => $id_sucursal,
    'id_suc_destino' => $id_sucursal,
    'id_proveedor' => 0,
  );
  $insert_mov =_insert($table,$form_data);
  $id_movimiento=_insert_id();
  $lista=explode('#',$datos);
  $j = 1 ;
  $k = 1 ;
  $l = 1 ;
  $m = 1 ;
  $s = 1 ;

  for ($i=0;$i<$cuantos ;$i++)
  {
    list($id_producto,$precio_compra,$precio_venta,$cantidad,$unidades,$fecha_caduca,$id_presentacion,$canfa)=explode('|',$lista[$i]);

    $id_producto;
    $cantidad=$cantidad*$unidades;
    $a_transferir=$cantidad;
    $canfa;
    $sql=_query("SELECT * FROM stock WHERE stock.id_producto=$id_producto AND stock.stock!=0 and stock.id_sucursal='$id_sucursal' ");

    while ($rowsu=_fetch_array($sql)) {
      # code...
      $stock_anterior=$rowsu['stock'];

      if ($a_transferir!=0) {
        # code...

        $transfiriendo=0;
        $nuevo_stock=$stock_anterior-$a_transferir;
        if ($nuevo_stock<0) {
          # code...
          $transfiriendo=$stock_anterior;
          $a_transferir=$a_transferir-$stock_anterior;
          $nuevo_stock=0;
        }
        else
        {
          if ($nuevo_stock>0) {
            # code...
            $transfiriendo=$a_transferir;
            $a_transferir=0;
            $nuevo_stock=$stock_anterior-$transfiriendo;
          }
          else {
            # code...
            $transfiriendo=$stock_anterior;
            $a_transferir=0;
            $nuevo_stock=0;

          }
        }


      }

    }

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

    //aqui inicia
    $sql4="SELECT id_faltante as fal,id_movimiento FROM producto_faltante WHERE id_producto='$id_producto' and fecha<='$fecha_movimiento' AND id_sucursal='$id_sucursal' and faltante!=0 order by fecha desc";
    $stock4=_query($sql4);
    $row4=_fetch_array($stock4);
    $nrow4=_num_rows($stock4);
    $sql5="SELECT  id_examen FROM insumo_examen WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'";
    $stock5=_query($sql5);
    $row5=_fetch_array($stock5);
    $nrow5=_num_rows($stock5);
    if ($nrow4>0)
    {
      $fal=$row4['fal'];
      $id_fal=$row4['id_movimiento'];
    }else{
      $fal="";
      $id_fal="";
    }
    if ($nrow5>0)
    {
      $id_examen=$row5['id_examen'];
    }else {
      $id_examen="";
    }
    if($canfa!=0){

    $table4= 'producto_faltante';
    $form_data4 = array(
      'id_movimiento'=>$id_movimiento,
      'id_producto' => $id_producto,
      'cantidad_fal' => $canfa,
      'faltante' => $canfa,
      'fecha' => $fecha_movimiento,
      'id_sucursal' => $_SESSION["id_sucursal"],
    );
    $insert_fal = _insert($table4,$form_data4);
    if($insert_fal){

      if ($nrow4>0)
      {
        $table6= 'producto_faltante';
        $form_data6 = array(
          'faltante' => 0
        );
        $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'and id_movimiento='$id_fal' and id_faltante='$fal'";
        $insert_fal6 = _update($table6,$form_data6, $where_clause );
      }
      $table5= 'examen_paciente';
      $form_data5 = array(
        'inventariado' => 1
      );
      $where_clause2="WHERE id_examen='$id_examen' and id_sucursal='$id_sucursal";
      $insert_fal2 = _update($table5,$form_data5, $where_clause2 );
    }
  }
  else {
    $table4= 'producto_faltante';
  //  $cant_total4=$existencias-$cantidad;
    $form_data4 = array(
      'faltante' => 0
    );
    $where_clause="WHERE id_producto='$id_producto' and id_sucursal='$id_sucursal'and id_movimiento='$id_fal'";
    $insert_fal = _update($table4,$form_data4, $where_clause );

      $table5= 'examen_paciente';
      $form_data5 = array(
        'inventariado' => 1
      );
      $where_clause2="WHERE id_examen='$id_examen' and id_sucursal='$id_sucursal'";
      $insert_fal2 = _update($table5,$form_data5, $where_clause2 );
  }
  if(!$insert_fal )
  {
    $s = 0;
  }

  }
  if($insert_mov &&$corr &&$z && $j && $k && $l && $m && $s)
  {
    _commit();
    $xdatos['typeinfo']='Success';
    $xdatos['msg']='Registro ingresado con éxito!';
  }
  else
  {
    _rollback();
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Registro de no pudo ser ingresado!';
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

  $sql_perece="SELECT * FROM producto WHERE id_producto='$id_producto'and id_sucursal='$id_sucursal'";
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
    case'recibir':
    recibir();
    break;
  }
}
?>
