<?php
include_once "_core.php";
include('num2letras.php');
include('facturacion_funcion_imprimir.php');
function initial()
{

  //$id_factura=$_REQUEST["id_factura"];
  $title="Venta";
  $_PAGE = array();
  $_PAGE ['title'] = $title;
  $_PAGE ['links'] = null;
  $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/bootstrap-checkbox/bootstrap-checkbox.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/plugins/perfect-scrollbar/perfect-scrollbar.css">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util.css">';
  $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main.css">';
  include_once "header.php";
  //include_once "main_menu.php";
  date_default_timezone_set('America/El_Salvador');
  $fecha_actual = date('Y-m-d');

  $id_sucursal=$_SESSION['id_sucursal'];
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'  AND fecha='$fecha_actual'  AND id_empleado = '$id_user'");
  $cuenta = _num_rows($sql_apertura);

  $turno_vigente=0;

  if ($cuenta>0) {
    $row_apertura = _fetch_array($sql_apertura);
    $id_apertura = $row_apertura["id_apertura"];
    $turno = $row_apertura["turno"];
    $caja = $row_apertura["caja"];
    $fecha_apertura = $row_apertura["fecha"];
    $hora_apertura = $row_apertura["hora"];
    $turno_vigente = $row_apertura["vigente"];
  }

  //impuestos
  $sql_iva="SELECT tipo_facturacion,tipo_pag FROM sucursal WHERE id_sucursal='$id_sucursal'";
  $result_IVA=_query($sql_iva);
  $row_IVA=_fetch_array($result_IVA);
  $tipo_fa=$row_IVA["tipo_facturacion"];
  $tipo_pag=$row_IVA["tipo_pag"];

  $iva=0.13;
  $monto_retencion1=100;
  $monto_retencion10=100;
  $monto_percepcion=100;
  /////////////////////////////////////////////////////
  $admin=$_SESSION["admin"];
  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user, $filename);
  $id_usuario=$id_user;

  $fecha_actual=date("Y-m-d");
  ?>


  <div class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox ">
            <?php
            //permiso del script
            if ($links!='NOT' || $admin=='1') {
              if ($turno_vigente=='1' ){
                ?>
                <div class="ibox-content">
                  <input type="hidden" id="fecha" value="<?php echo $fecha_actual; ?>">
                    <div class="row focuss"><br>
                      <div class="form-group col-md-5">
                        <label id='buscar_habilitado'>Buscar Producto (Descripci&oacute;n)</label>
                        <input type="text" id="producto_buscar" name="producto_buscar"  class="form-control usage" placeholder="Ingrese Descripcion de producto" data-provide="typeahead" style="border-radius:0px">
                      </div>
                      <div class="col-md-7"><br>
                        <a class="btn btn-danger pull-right" style="margin-left:3%;" href="dashboard.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                        <button type="button" id="submit1" name="submit1" class="btn btn-primary pull-right usage"><i class="fa fa-check"></i> F2 Guardar</button>
                      </div>
                    </div>
                    <div class="row">
                    <div id='form_datos_cliente' class="form-group col-md-3">
                        <label> Cliente</label>
                             <select class="col-md-12 select usage" id="id_cliente" name="id_cliente">
                               <option value="">Seleccione</option>
                                  <?php
                                      $sqld = "SELECT * FROM cliente where id_sucursal='$id_sucursal'";
                                      $resul=_query($sqld);
                                      while($depto = _fetch_array($resul))
                                      {
                                          echo "<option value=".$depto["id_cliente"];
                                          echo">".$depto["nombre"]."</option>";
                                      }
                                  ?>
                              </select>
                    </div>

                    <?php
                    if($tipo_fa==1)
                    {
                    ?>
                    <div  class="form-group col-md-2">
                      <div class="form-group has-info">
                        <label>Tipo Impresi&oacuten</label>
                        <select name='tipo_impresion' id='tipo_impresion' class='col-md-12 select form-control usage'>
                               <option value="">Seleccione Cliente primero</option>
                               <?php
                                /*   $sqld = "SELECT * FROM tipo_impresion where estado=1 and id_sucursal='$id_sucursal'";
                                   $resul=_query($sqld);
                                   while($depto = _fetch_array($resul))
                                   {
                                       echo "<option value=".$depto["abreviatura"];
                                       echo">". $depto["descripcion"]."</option>";
                                   }*/
                               ?>
                        </select>
                      </div>
                    </div>
                    <?php
                  }  else {
                  ?>
                        <input type="hidden" name='tipo_impresion' id='tipo_impresion' class='form-control' value="COB">


                  <?php
                }

                if($tipo_pag==1)
                  {
                     ?>
                     <div  class="form-group col-md-2" >
                       <div class="form-group has-info">
                         <label>Tipo de Pago</label>
                         <select name='tipo_pago' id='tipo_pago' class='col-md-12 select form-control usage'>
                                <option value="">Seleccione</option>
                                <?php
                                  /*  $sqld = "SELECT * FROM tipo_pago where estado=1 and id_sucursal='$id_sucursal'";
                                    $resul=_query($sqld);
                                    while($depto = _fetch_array($resul))
                                    {
                                        echo "<option value=".$depto["abreviatura"];
                                        echo">". $depto["descripcion"]."</option>";
                                    }*/
                                ?>
                         </select>
                       </div>
                     </div>

                    <?php
                    }
                    else {
                      $sql_tipo = _query("SELECT * FROM tipo_pago where estado=1 and id_sucursal='$id_sucursal'");
                      $cue = _num_rows($sql_tipo);
                      if ($cue>=2) {

                      ?>
                      <div  class="form-group col-md-2" >
                        <div class="form-group has-info">
                          <label>Tipo de Pago</label>
                          <select name='tipo_pago' id='tipo_pago' class='col-md-12 select form-control usage'>
                                 <option value="">Seleccione</option>
                                 <?php
                                     /*$sqld = "SELECT * FROM tipo_pago where estado=1 and id_sucursal='$id_sucursal'";
                                     $resul=_query($sqld);
                                     while($depto = _fetch_array($resul))
                                     {
                                         echo "<option value=".$depto["abreviatura"];
                                         echo">". $depto["descripcion"]."</option>";
                                     }*/
                                 ?>
                          </select>
                        </div>
                      </div><?php
                    }else {

                       ?>

                      <input type="hidden" name='tipo_pago' id='tipo_pago' class='form-control' value="CON">
                    <?php
                  }

                    }
                     ?>

                     <div hidden class="col-md-3" id="mostrarcon" >
                       <div class="form-group has-info">
                         <label>Condicion de pago</label><br>
                         <select name='con_pago' id='con_pago' class='col-md-12 select form-control usage'>
                                  <?php
                                      $sqld = "SELECT * FROM condicion_pago where estado=1 and id_sucursal='$id_sucursal'";
                                      $resul=_query($sqld);
                                      while($depto = _fetch_array($resul))
                                      {
                                          echo "<option value=".$depto["abreviatura"];
                                          echo">". $depto["descripcion"]."</option>";
                                      }
                                  ?>

                         </select>
                       </div>
                     </div>

                   </div>
                  <!--load datables estructure html-->
                  <header>
                    <section>
                      <input type='hidden' name='porc_iva' id='porc_iva' value='<?php echo $iva; ?>'>
                      <input type='hidden' name='monto_retencion1' id='monto_retencion1' value='<?php echo $monto_retencion1 ?>'>
                      <input type='hidden' name='monto_retencion10' id='monto_retencion10' value='<?php echo $monto_retencion10 ?>'>
                      <input type='hidden' name='monto_percepcion' id='monto_percepcion' value='100'>
                      <input type='hidden' name='porc_retencion1' id='porc_retencion1' value=0>
                      <input type='hidden' name='porc_retencion10' id='porc_retencion10' value=0>
                      <input type='hidden' name='porc_percepcion' id='porc_percepcion' value=0>
                      <input type='hidden' name='porcentaje_descuento' id='porcentaje_descuento' value=0>

                      <div class="">
                        <div class="row">
                          <div class="col-md-9">
                            <div class="wrap-table1001">
                              <div class="table100 ver1 m-b-10">
                                <div class="table100-head">
                                  <table id="inventable1">
                                    <thead>
                                      <tr class="row100 head">
                                        <th class="success cell100 column5">ID</th>
                                        <th class='success  cell100 column20'>DESCRIPCI&Oacute;N</th>
                                        <th class='success  cell100 column20'>PACIENTE</th>
                                        <th class='success  cell100 column16'>REFERIDO</th>
                                        <th class='success  cell100 column12'>FECHA</th>
                                        <th class='success  cell100 column12'>HORA</th>
                                        <th class='success  cell100 column8'>PRECIO</th>
                                        <th class='success  cell100 column7'>ACCI&Oacute;N</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                                <div class="table100-body js-pscroll">
                                  <table>
                                    <tbody id="inventable"></tbody>
                                  </table>
                                </div>
                                <div class="table101-body">
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td class='cell100 column50 text-bluegrey'  id='totaltexto'>&nbsp;</td>
                                        <td class='cell100 column15 leftt  text-bluegrey ' >CANT. EXAM:</td>
                                        <td class='cell100 column10 text-right text-danger' id='totcant'>0.00</td>
                                        <td class="cell100 column10  leftt text-bluegrey ">TOTALES $:</td>
                                        <td class='cell100 column15 text-right text-green' id='total_gravado'>0.00</td>

                                      </tr>

                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="wrap-table1001">
                              <div class="table100 ver1 m-b-10">
                                <div class="table100-head">
                                  <table id="inventable1">
                                    <thead>
                                      <tr class="row100 head">
                                        <th class="success cell100 column100 text-center">PAGO Y CAMBIO</th>
                                        </tr>
                                    </thead>
                                  </table>
                                </div>
                                <div class="table101-body">
                                  <table>
                                    <tbody>
                                      <tr>
                                        <td class='cell100 column70 text-success'>CORRELATIVO:</td>
                                        <td class='cell100 column30'><input type="text" id="corr_in" class="txt_box2"  value="" readOnly></td>
                                      </tr>
                                      <tr>
                                        <td class='cell100 column70 text-success'>TOTAL: $</td>
                                        <td class='cell100 column30'><input type="text" id="tot_fdo" class="txt_box2"   value="" readOnly></td>
                                      </tr>
                                      <tr>
                                        <td class='cell100 column70 text-success'>NUM. DOC: </td>
                                        <td class='cell100 column30'><input type="text" id="numdoc" class="txt_box2"   value="" readOnly></td>
                                      </tr>
                                      <tr>
                                        <td class='cell100 column70 text-success'>CLIENTE: </td>
                                        <td class='cell100 column30'><input type="text" id="nomcli" class="txt_box2"  value="" readOnly></td>
                                      </tr>
                                      <tr>
                                        <td class='cell100 column70 text-success'>NIT: </td>
                                        <td class='cell100 column30'><input type="text" id="nitcli" class="txt_box2"    value="" readOnly></td>
                                      </tr>
                                      <tr>
                                        <td class='cell100 column70 text-success'>NRC: </td>
                                        <td class='cell100 column30'><input type="text" id="nrccli" class="txt_box2"   value="" readOnly></td>
                                      </tr>
                                      <tr hidden id="vernumche">
                                        <td class='cell100 column70 text-success'>NUM. CHE:</td>
                                        <td class='cell100 column30'> <input type="text" id="numche" class="txt_box2"   value=""readOnly> </td>
                                      </tr>
                                      <tr hidden id="verntarj">
                                        <td class='cell100 column70 text-success'>NUM. TAR:</td>
                                        <td class='cell100 column30'> <input type="text" id="numtarj" class="txt_box2"   value=""readOnly> </td>
                                      </tr>
                                      <tr hidden id="veremisor">
                                        <td class='cell100 column70 text-success'>BANCO:</td>
                                        <td class='cell100 column30'> <input type="text" id="emisor" class="txt_box2"   value="" readOnly> </td>
                                      </tr>
                                      <tr hidden id="vervouch">
                                        <td class='cell100 column70 text-success'>VOUCHER:</td>
                                        <td class='cell100 column30'> <input type="text" id="voucher" class="txt_box2"   value="" readOnly> </td>
                                      </tr>
                                      <tr hidden id="verbanco">
                                        <td class='cell100 column70 text-success'>BANCO: </td>
                                        <td class='cell100 column30'>
                                          <select name='banco' id='banco' class='txt_box2 select banco'style="width:135px;" disabled>
                                            <option value="">Seleccione</option>
                                                   <?php
                                                       $sqlB = "SELECT * FROM banco where id_sucursal='$id_sucursal'";
                                                       $resulB=_query($sqlB);
                                                       while($bank = _fetch_array($resulB))
                                                       {
                                                           echo "<option value=".$bank["id_banco"];
                                                           echo">". $bank["nombre"]."</option>";
                                                       }
                                                   ?>
                                          </select>
                                         </td>
                                      </tr>

                                      <tr hidden id="vernumcue">
                                        <td class='cell100 column70 text-success'>CUENTA BAN: </td>
                                        <td class='cell100 column30'>
                                          <select name='banco' id='numcuenta' class='txt_box2 select cuentaba 'style="width:135px;" disabled>
                                            <option value="">Seleccione primero banco</option>

                                          </select>
                                        </td>
                                      </tr>
                                      <tr hidden id="vertrans">
                                        <td class='cell100 column70 text-success'>NUM. TRANS: </td>
                                        <td class='cell100 column30'> <input type="text" id="numtrans" class="txt_box2"   value="" readOnly> </td>
                                      </tr>
                                      <tr id="verefectivo">
                                        <td class='cell100 column70 text-success'>EFECTIVO: $</td>
                                        <td class='cell100 column30'> <input type="text" id="efectivov" class="txt_box2"   value="" > </td>
                                      </tr>

                                      <tr>
                                        <td class='cell100 column70 text-success'>CAMBIO: $</td>
                                        <td class='cell100 column30'><input type="text" id="cambiov" class="txt_box2"   value="" readOnly></td>
                                      </tr>

                                    </tbody>
                                  </table>
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                        <?php

                        echo "<input type='hidden' name='id_empleado' id='id_empleado' >";
                        echo "<input type='hidden' name='numero_doc' id='numero_doc' >";
                        echo "<input type='hidden' name='id_factura' id='id_factura' >";
                        echo "<input type='hidden' name='urlprocess' id='urlprocess' value='$filename'>"; ?>
                        <input type='hidden' name='totalfactura' id='totalfactura' value='0'>

                        <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
                        <input type='hidden' name='turno' id='turno' value='<?php echo $turno; ?>'>
                        <input type='hidden' name='caja' id='caja' value='<?php echo $caja; ?>'>
                      </div>
                      <!--div class="table-responsive m-t"-->
                    </section>

                  </div>
                  <!--div class='ibox-content'-->
                  <!-- Modal -->
                  <div class="modal-container">
                    <div class="modal fade" id="clienteModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                      <div class="modal-dialog model-sm">
                        <div class="modal-content"> </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-container">
                    <div class="modal fade" id="doctorModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                      <div class="modal-dialog model-sm">
                        <div class="modal-content"> </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--<div class='ibox float-e-margins' -->
              </div>
              <!--div class='col-lg-12'-->
            <!--div class='row'-->
            <!--div class='wrapper wrapper-content  animated fadeInRight'-->

            <?php
          }   //apertura de caja
          else {
            echo "<br><br><div class='alert alert-warning'><h3 class='text-danger'> No Hay Apertura de Caja vigente para este turno!!!</h3></div></div></div></div></div>";
            include_once("footer.php");
          }  //apertura de caja
          include_once("footer.php");

          echo "<script src='js/funciones/venta.js'></script>";
          echo "<script src='js/plugins/arrowtable/arrow-table.js'></script>";
          echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
          echo "<script src='js/plugins/datetime/bootstrap-datetimepicker.js'></script>";
          echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
          <script src="js/funciones/main.js"></script>';
          echo "<script src='js/funciones/util.js'></script>";
        } //permiso del script
        else {
          echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
          include_once("footer.php");
        }
      }
      function consultar_stock()
        {
          date_default_timezone_set('America/El_Salvador');

          $id_producto = $_REQUEST['id_producto'];
          $id_usuario=$_SESSION["id_usuario"];
          $id_sucursal=$_SESSION['id_sucursal'];
          $id_factura=$_REQUEST['id_factura'];
          $precio=0;
          $categoria="";

          $sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_producto' AND e.id_sucursal='$id_sucursal'";
          $stock1=_query($sql1);
          $row1=_fetch_array($stock1);
          $nrow1=_num_rows($stock1);
          if ($nrow1>0)
          {
            $hoy=date("Y-m-d");
            $nombre_examen=$row1['nombre_examen'];
            $id_producto = $row1['id_examen'];
            $precio = $row1["precio_examen"];

            $i=0;
            $unidadp=0;
            $preciop=0;
            $descripcionp=0;
            $fecha="";
            $hora="";
            $select_rank="<select class='sel_r form-control'>";
            $sql_p=_query("SELECT id_paciente, concat(nombre,' ',apellido) as nombre_c FROM paciente
              WHERE id_sucursal='$id_sucursal' and estado=1");
            $select="<select class='sel form-control' id='paciente'>";
            $select.="<option value=''>Seleccione";
            while ($row=_fetch_array($sql_p))
            {
                $select.="<option value='$row[id_paciente]'>$row[nombre_c]</option>";
                $i=$i+1;
            }
            $select.="</select>";
            $sql_p=_query("SELECT id_doctor, concat(nombre,' ',apellido) as nombre_d FROM doctor
              WHERE id_sucursal='$id_sucursal' and estado=1");
            $select1="<select class='sel1 form-control' id='doctor'>";
            $select1.="<option value=''>Seleccione";
            while ($row=_fetch_array($sql_p))
            {
                $select1.="<option value='$row[id_doctor]'>$row[nombre_d]</option>";
                $i=$i+1;
            }
            $select1.="</select>";
            $fecha.="<input type='text' class='form-control datepicker fecha' id='fecha' name='fecha' value='".date('d-m-y')."' style='width:95px;'>";
            $hora.="<input type='text' class='form-control datetime hora' id='hora' name='fecha' value='".date('H:i')."' style='width:95px;'>";



              $xdatos['select']= $select;
              $xdatos['select1']= $select1;
              $xdatos['horas']= $hora;
              $xdatos['fecha']= $fecha;
              $xdatos['preciop']= $precio;
              $xdatos['descripcionp']= $nombre_examen;

              echo json_encode($xdatos); //Return the JSON Array
            }
          }

          function total_texto()
          {
            $total=$_REQUEST['total'];
            list($entero, $decimal)=explode('.', $total);
            $enteros_txt=num2letras($entero);
            $decimales_txt=num2letras($decimal);

            if ($entero>1) {
              $dolar=" dolares";
            } else {
              $dolar=" dolar";
            }
            $cadena_salida= "Son: <strong>".$enteros_txt.$dolar." con ".$decimal."/100 ctvs.</strong>";
            echo $cadena_salida;
          }

          function numero_tiquete($ult_doc, $tipo)
          {
            $ult_doc=trim($ult_doc);
            $len_ult_valor=strlen($ult_doc);
            $long_num_fact=10;
            $long_increment=$long_num_fact-$len_ult_valor;
            $valor_txt="";
            if ($len_ult_valor<$long_num_fact) {
              for ($j=0;$j<$long_increment;$j++) {
                $valor_txt.="0";
              }
            } else {
              $valor_txt="";
            }
            $valor_txt=$valor_txt.$ult_doc;
            return $valor_txt;
          }

          function insertar()
          {
            date_default_timezone_set('America/El_Salvador');
            $fecha_movimiento= $_POST['fecha_movimiento'];
            $id_cliente=$_POST['id_cliente'];
            $id_vendedor=$_POST['id_vendedor'];
            $cuantos = $_POST['cuantos'];
            $array_json=$_POST['json_arr'];
            $fecha=date("Y-m-d");
            //  IMPUESTOS
            $total_percepcion= $_POST['total_percepcion'];
            $subtotal=$_POST['subtotal'];
            $sumas=$_POST['sumas'];
            $suma_gravada=$_POST['suma_gravada'];
            $iva= $_POST['iva'];
            $retencion= $_POST['retencion'];
            $venta_exenta= $_POST['venta_exenta'];
            $total_menos_retencion=$_POST['total'];
            $total = $retencion+$_POST['total'];
            $id_doct=$_POST["doctor"];

            $id_empleado=$_SESSION["id_usuario"];
            if($id_vendedor == "")
            {
              $id_vendedor = $id_empleado;
            }
            $id_sucursal=$_SESSION["id_sucursal"];
            $fecha_actual = date('Y-m-d');
            $tipoprodserv = "Examen";
            $credito=$_POST['credito'];
            $id_apertura=$_POST['id_apertura'];
            $turno=$_POST['turno'];
            $caja=$_POST['caja'];
            $tipo_documento=$_POST['tipo_impresion'];
            $tipo_pago=$_POST['tipo_pag'];
            $tipo_rem=$_POST['tipo_rem'];
            $con_pag=$_POST['con_pag'];
            $tipo_impresion=$tipo_documento;

            //pago cheque
            if ($con_pag=="CHE") {
              $numero_cheque=$_POST['num_che'];
              $banco=$_POST['banco'];
              $monto_che=$_POST['monto_che'];
            }else {
              $numero_cheque="";
              $banco="";
              $monto_che="";
            }
            $insertar_fact=false;
            $insertar_fact_dett=true;
            $insertar_numdoc =false;

            $hora=date("H:i:s");
            _begin();

            $a=1;
            $b=1;
            $c=1;
            $z=1;
            $ch=1;
            $j = 1 ;
            $k = 1 ;
            $l = 1 ;
            $x=1;
            $tipo_entrada_salida='';

            $sql="select * from correlativo WHERE id_sucursal=$id_sucursal";
            $result= _query($sql);
            $rows=_fetch_array($result);
            $nrows=_num_rows($result);
            $ult_ccf=$rows['ccf']+1;
            $ult_cof=$rows['cof']+1;
            $ult_cob=$rows['cob']+1;

            $numero_doc="";
            $num_fact_impresa='';

            $table_numdoc="correlativo";
            $data_numdoc="";

          if ($tipo_impresion =='COF') {
              $tipo_entrada_salida='FACTURA CONSUMIDOR';
              $data_numdoc = array(
                'cof' => $ult_cof
              );
              $numero_doc=numero_tiquete($ult_cof, $tipo_impresion);
            }
            if ($tipo_impresion =='COB') {
                $tipo_entrada_salida='CONTROL FACTURA';
                $data_numdoc = array(
                  'cob' => $ult_cob
                );
                $numero_doc=numero_tiquete($ult_cof, $tipo_impresion);
                $num_fact_impresa=$numero_doc;
              }
            if ($tipo_impresion =='TIK') {
              $sql_corre = _query("SELECT * FROM caja WHERE id_caja = '$caja'");
              $row_corre = _fetch_array($sql_corre);
              $correlativo_dispo = $row_corre["correlativo_dispo"];
              $tipo_entrada_salida='TICKET';
              $data_numdoc = array(
                'correlativo_dispo' => $correlativo_dispo+1,
              );
              $num_fact_impresa=$correlativo_dispo;
              $numero_doc=numero_tiquete($correlativo_dispo, $tipo_impresion);
            }
            if ($tipo_impresion =='CCF') {
              $tipo_entrada_salida='CREDITO FISCAL';
              $data_numdoc = array(
                'ccf' => $ult_ccf
              );
              $numero_doc=numero_tiquete($ult_ccf, $tipo_impresion);
            }

            if($tipo_impresion != "TIK")
            {
              $where_clause_n=" WHERE id_sucursal='$id_sucursal'";
              $insertar_numdoc = _update($table_numdoc, $data_numdoc, $where_clause_n);
            }
            else
            {
              $tab = 'caja';
              $where_clause_c=" WHERE id_caja='$caja'";
              $insertar_numdoc = _update($tab, $data_numdoc, $where_clause_c);
            }
            $abono=0;
            $saldo=0;
            $serie="";
            $ultimo=0;

            if ($tipo_impresion == "TIK") {
              # code...
              $sql_corre = _query("SELECT * FROM caja WHERE id_caja = '$caja'");
              $row_corre = _fetch_array($sql_corre);
              $serie = $row_corre["serie"];

            }
            elseif ($tipo_impresion == "COF") {
              # code...
              $swl =_fetch_array(_query("SELECT * FROM sucursal where id_sucursal=$id_sucursal "));
              //$serie=$swl['serie_cof'];


              $sql_ult=_query("SELECT MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as ultimo FROM cobro WHERE id_sucursal=$id_sucursal AND tipo_doc='COF' ");

              $num_rows_ul=_num_rows($sql_ult);
              if ($num_rows_ul>0) {
                # code...
                $ul=_fetch_array($sql_ult);
                $ultimo=$ul['ultimo'];
              }

            }
            elseif ($tipo_impresion == "COB") {
              # code...
              $swl =_fetch_array(_query("SELECT * FROM sucursal where id_sucursal=$id_sucursal "));
              //$serie=$swl['serie_cof'];


              $sql_ult=_query("SELECT MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as ultimo FROM cobro WHERE id_sucursal=$id_sucursal AND tipo_doc='COB' ");

              $num_rows_ul=_num_rows($sql_ult);
              if ($num_rows_ul>0) {
                # code...
                $ul=_fetch_array($sql_ult);
                $ultimo=$ul['ultimo'];
              }

            }
            else {
              # code...
              $swl =_fetch_array(_query("SELECT * FROM sucursal where id_sucursal=$id_sucursal "));
              //$serie=$swl['serie_ccf'];

              $sql_ult=_query("SELECT MAX(CONVERT(num_fact_impresa,UNSIGNED INTEGER)) as ultimo FROM cobro WHERE id_sucursal=$id_sucursal AND tipo_doc='CCF' ");

              $num_rows_ul=_num_rows($sql_ult);
              if ($num_rows_ul>0) {
                # code...
                $ul=_fetch_array($sql_ult);
                $ultimo=$ul['ultimo'];
              }

            }
            if($credito=="CRE")
            {
              $saldo=$total_menos_retencion;
              $estado="Pendiente";
              $credito1=1;
            }
            else {

              $estado="Cancelado";
              $credito1=0;

            }

              $table_fact= 'cobro';
              $form_data_fact = array(
                'cliente' => $id_cliente,
                'fecha' => $fecha_movimiento,
                'numero_doc' => $numero_doc,
                //'subtotal' => $subtotal,
                'sumas'=>$sumas,
                'iva' =>$iva,
                'total' => $total,
                'id_usuario'=>$id_empleado,
                'id_empleado' => $id_empleado,
                'id_sucursal' => $id_sucursal,
                'tipo_pago' => $tipo_pago,
                'con_pago' => $con_pag,
                'tipo_rem' => $tipo_rem,
               //'serie' => $serie,
               //'num_fact_impresa' => $num_fact_impresa,
                'hora_cobro' => $hora,
               'estado' => $estado,
                'abono'=>$abono,
                //'banco'=>$banco,
                //'num_doc_pago' => $numero_cheque,
                //'saldo' => $saldo,
                'tipo_doc' => $tipo_documento,
                'id_apertura' => $id_apertura,
                'id_apertura_pagado' => $id_apertura,
                'caja' => $caja,
                'credito' => $credito1,
                'turno' => $turno,
                'turno_pagado' => $turno,
                'finalizada'=>1,
                'pagada'=>1,
              );

              $insertar_fact = _insert($table_fact,$form_data_fact );
             echo _error();
              $id_cobro= _insert_id();
              if (!$insertar_fact) {
                $b=0;

              }
            $cre=1;
            if($credito=="CRE")
            {
              $table="credito";
              $form_data = array(
                'id_cobro' => $id_cobro,
                'fecha' => $fecha_movimiento,
                'tipo_doc' => $tipo_documento,
                'numero_doc' => $numero_doc,
                'id_cliente' => $id_cliente,
                'dias' =>  '30',
                'total' => $total_menos_retencion,
                'abono' => 0,
                'saldo' => $total_menos_retencion,
                'finalizada' => 0,
                'id_sucursal' => $id_sucursal,
              );
              $insert=_insert($table,$form_data);
              if ($insert) {
                # code...
              }
              else {
                # code...
                $cre=0;
              }
            }

            /*//pago transferencia
            if ($con_pag=="TRA") {
                $num_trans=$_POST['num_trans'];
                $id_cuenta=$_POST['cuenta_banco'];
                $monto_tans=$_POST['monto_trans'];

                  $monto=$total;

                  $sql_res=_fetch_array(_query("SELECT id_empleado FROM cobro WHERE cobro.id_cobro = $id_cobro "));
                  $responsable="";
                  if ($sql_res['id_empleado']==-1) {
                    # code...
                    $sql_res_name=_fetch_array(_query("SELECT empleado.nombre FROM empleado WHERE id_empleado=$sql_res[id_empleado]"));
                    $responsable=$sql_res_name['nombre'];
                  }
                  else {
                    $sql_res_name=_fetch_array(_query("SELECT empleado.nombre FROM empleado WHERE id_empleado=$sql_res[id_empleado]"));
                    $responsable=$sql_res_name['nombre'];
                  }

                  $tipo="Ingreso";
                	$sql = _query("SELECT (SUM(mov_cta_banco.entrada)-SUM(mov_cta_banco.salida)) AS saldo FROM mov_cta_banco WHERE id_sucursal='$id_sucursal' AND id_cuenta='$id_cuenta'");
                	$row = _fetch_array($sql);
                	$saldo = $row["saldo"];
                  $saldo=round($saldo,2);

                	$nalc = 0;
                	if($tipo == "Ingreso")
                	{
                		$entrada = $monto;
                		$salida = 0;
                		$nsal = $saldo + $monto;
                	}

                	$tabla_mov = 'mov_cta_banco';
                	$form_data_mov = array (
                    'id_cuenta'=>$id_cuenta,
                		'tipo' => $tipo,
                		'alias_tipodoc' => "Transferencia",
                		'numero_doc' => $num_trans,
                		'entrada' => $entrada,
                		'salida' => $salida,
                		'saldo' => $nsal,
                		'fecha' => $fecha_movimiento,
                		'responsable' => $responsable,
                		'concepto' => "INGRESO POR VENTA",
                		'id_sucursal' => $id_sucursal,
                    'procesado' => 0,
                    'id_factura' => $id_cobro,
                	);
                  $insertar = _insert($tabla_mov,$form_data_mov);


            }*/

            $fecha_re="";
            $fecha_re1="";
           if ($cuantos>0)
            {

              $array = json_decode($array_json, true);
              foreach ($array as $fila)
              {

                $id_producto=$fila['id'];
                $descr=$fila['descripcion'];
                $subtotal=$fila['subtotal'];
                $cantidad=$fila['cantidad'];
                $id_paci=$fila['id_paciente'];
                $fecha_re=MD($fila['fecha']);
                $hora_re=$fila['hora'];
                $cantidad_real=$cantidad;
                //$exento=$fila['exento'];
                $precio_venta=$fila['precio'];
                $separar = explode(" ",$hora_re);
                $separar1 = explode(":",$separar[0]);
                $hora_realizar=$separar[1];
                if($hora_realizar=="PM"){
                  $hora_re1=$separar1[0]+12;
                  $hora_reali=$hora_re1+":".$separar1[1]+":"."00";
                }else {
                    $hora_reali=$separar[0];
                }
                $table_fact_det= 'detalle_cobro';
                $data_fact_det = array(
                  'id_cobro' => $id_cobro,
                  'id_examen' => $id_producto,
                  'cantidad' => $cantidad_real,
                  'precio' => $precio_venta,
                  'subtotal' => $subtotal,
                  'detalles' => $descr,
                  //'id_empleado' => $id_empleado,
                  'id_sucursal' => $id_sucursal,
                  //'fecha' => $fecha_movimiento,
                  //'id_presentacion'=> $id_presentacion,
                  //'exento' => $exento,
                );
                $insertar_fact_det = _insert($table_fact_det,$data_fact_det );
                if (!$insertar_fact_det) {
                  # code...
                  $c=0;
                }
                $table1="examen_paciente";
                $form_data1=array(
                  'id_examen'=>$id_producto,
                  'id_doctor'=>$id_doct,
                  'id_paciente'=>$id_paci,
                  'fecha_cobro'=>$fecha,
                  'hora_cobro'=>$hora,
                  'fecha_examen'=>$fecha_re,
                  'hora_examen'=>$hora_reali,
                  'estado_realizado'=>"Pendiente",
                  'id_sucursal'=>$id_sucursal
                  );
              $insertt1=_insert($table1, $form_data1);
              if (!$insertar_fact_det) {
                # code...
                $x=0;
              }
                } //foreach ($array as $fila){
                  if ($a&&$b&&$c&&$z&&$cre&&$x&&$ch)
                  {
                    _commit(); // transaction is committed
                    $nit="";
                    $ncr="";
                    $nombre="";
                    $id_cliente="";
                    $sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_cobro' and id_sucursal='$id_sucursal'";
                    $result_fact=_query($sql_fact);
                    $row_fact=_fetch_array($result_fact);
                    $nrows_fact=_num_rows($result_fact);
                    if ($nrows_fact>0) {
                      $id_cliente=$row_fact['cliente'];
                  }

                  $sql_clie="SELECT * FROM cliente WHERE id_cliente='$id_cliente' and id_sucursal='$id_sucursal'";
                  $result_clie=_query($sql_clie);
                  $row_clie=_fetch_array($result_clie);
                  $nrows_clie=_num_rows($result_clie);
                  if ($nrows_clie>0) {
                    $nit=$row_clie['nit'];
                    $ncr=$row_clie['nrc'];
                    $nombre=$row_clie['nombre'];

                }

                    $xdatos['typeinfo']='Success';
                    $xdatos['msg']='Registro  Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
                    $xdatos['numdoc']=$numero_doc;
                    $xdatos['datos']=$fecha_re;
                    $xdatos['id_cobro']=$id_cobro;
                    $xdatos['cliente']=$nombre;
                    $xdatos['nit']=$nit;
                    $xdatos['ncr']=$ncr;
                    $xdatos['datos1']=$separar1;
                    $xdatos['ultimo']=$ultimo+1;
                  }
                  else
                  {
                    _rollback(); // transaction rolls back
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c."-".$z."-".$k."-".$l."-".$x;
                  }
                }
                echo json_encode($xdatos);
              }
              function actua_fac()
              {
                $nombreape= "";
                $direccion= "";
                $nit= "";
                $nrc="";
                $fecha_actual=date("Y-m-d");
                $numero_doc = $_POST['numero_doc'];
                $tipo_impresion= $_POST['tipo_impresion'];
                $tipo_pago= $_POST['tipo_pag'];
                $id_cobro= $_POST['id_cobro'];
                $id_sucursal=$_SESSION['id_sucursal'];
                $id_empleado=$_SESSION["id_usuario"];


                //  $nombreape= $_POST['nomcli'];
                  //$direccion= $_POST['direccion'];
                  //$nit= $_POST['nitcli'];
                  //$nrc= $_POST['nrccli'];
                //apertura caja
                $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'
                  /*AND fecha='$fecha_actual' */AND id_empleado = '$id_empleado'");
                  $cuenta = _num_rows($sql_apertura);

                  $turno_vigente=0;
                  if ($cuenta>0) {
                    $row_apertura = _fetch_array($sql_apertura);
                    $id_apertura = $row_apertura["id_apertura"];
                    $turno = $row_apertura["turno"];
                    $caja = $row_apertura["caja"];
                    $fecha_apertura = $row_apertura["fecha"];
                    $hora_apertura = $row_apertura["hora"];
                    $turno_vigente = $row_apertura["vigente"];
                    $id_apertura_pagada=$id_apertura;
                  }
                  //factura que ya existe
                  $sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_cobro' and id_sucursal='$id_sucursal'";
                  $result_fact=_query($sql_fact);
                  $row_fact=_fetch_array($result_fact);
                  $nrows_fact=_num_rows($result_fact);
                  if ($nrows_fact>0) {
                    $fecha_movimiento=$row_fact['fecha'];
                    $total_venta=$row_fact['total'];
                  //  $hora=$row_fact['hora'];
                    $numero_doc=$row_fact['numero_doc'];
                    if($tipo_impresion=='TIK'){

                      $numero_doc_print=  $numero_doc;
                    }
                    $pagada=1;
                    $fecha_pago =$fecha_actual;
                    $sql_serie = _query("SELECT * FROM sucursal WHERE id_sucursal = '$id_sucursal'");
                    $cuenta_serie = _num_rows($sql_serie);
                    $serie = "";
                    if($cuenta_serie > 0)
                    {
                      $row_serie = _fetch_array($sql_serie);
                      if($tipo_impresion == "COF")
                      {
                        $serie = $row_serie["serie_cof"];
                      }
                      else if($tipo_impresion == "CCF")
                      {
                        $serie = $row_serie["serie_ccf"];
                      }
                    }
                    if($tipo_impresion == "TIK")
                    {
                      $sql_saerie_caja = _query("SELECT serie FROM caja WHERE id_caja = '$caja'");
                      $cuenta_cc = _num_rows($sql_saerie_caja);
                      if($cuenta_cc > 0)
                      {
                        $row_cc = _fetch_array($sql_saerie_caja);
                        $serie = $row_cc["serie"];
                      }
                    }
                  /*  if ($tipo_pago=="TAR") {
                      if (isset($_POST['numero_tarjeta'])) {
                        $numero_tarjeta=$_POST['numero_tarjeta'];
                      }
                      if (isset($_POST['emisor'])) {
                        $emisor=$_POST['emisor'];
                      }
                      if (isset($_POST['voucher'])) {
                        $voucher=$_POST['voucher'];
                    }*/
                    $table_fact= 'cobro';
                    $form_data_fact = array(
                      'finalizada' => '1',
                      'num_fact_impresa'=>$numero_doc_print,
                      'id_apertura' => $id_apertura,
                      'id_apertura_pagada' => $id_apertura_pagada,
                      'turno' => $turno,
                      'caja' => $caja,
                      'pagada' =>$pagada,
                      'fecha_pago'=>$fecha_pago,
                      //'dias_credito'=>  $dias_credito,
                      //'serie' => $serie,
                    );
                    $where_clause="WHERE id_cobro='$id_cobro'and id_sucursal='$id_sucursal'";
                    $actualizar = _update($table_fact, $form_data_fact, $where_clause);
                  //  echo_error();
                    if($actualizar){

                      $datox['typeinfo']='factura completada';
                    }else {
                      $datox['typeinfo']='Error';
                    }
                    echo json_encode($datox);

                  }
                  //pago tarjeta
                  /*if ($tipo_pago=="TAR") {
                    if (isset($_POST['numero_tarjeta'])) {
                      $numero_tarjeta=$_POST['numero_tarjeta'];
                    }
                    if (isset($_POST['emisor'])) {
                      $emisor=$_POST['emisor'];
                    }
                    if (isset($_POST['voucher'])) {
                      $voucher=$_POST['voucher'];
                      // SELECT id_pago_tarjeta, idtransace, alias_tipodoc, fecha, voucher, numero_tarjeta, emisor, monto FROM pago_tarjeta WHERE 1
                      $sql_pt="SELECT * FROM pago_tarjeta  WHERE id_factura='$id_factura'";
                      $result_pt=_query($sql_pt);
                      $row_pt=_fetch_array($result_pt);
                      $nrows_pt=_num_rows($result_pt);
                      if ($nrows_pt==0) {
                        $table_pt= 'pago_tarjeta';
                        $form_data_pt = array(
                          'id_factura' => $id_factura,
                          'voucher' => $voucher,
                          'fecha' => $fecha_movimiento,
                          'hora'=>$hora,
                          'emisor' => $emisor,
                          'numero_tarjeta' => $numero_tarjeta,
                          'monto' => $total_venta,
                        );
                        $where_clause="WHERE id_factura='$id_factura'";
                        $actualizar = _insert($table_pt, $form_data_pt);
                        $id_pago= _insert_id();
                      }
                    }
                  }
                  //pago cheque
                  if ($tipo_pago=="CHE") {
                    if (isset($_POST['numero_cheque'])) {
                      $numero_cheque=$_POST['numero_cheque'];
                    }
                    if (isset($_POST['banco'])) {
                      $banco=$_POST['banco'];
                    }
                    if (isset($_POST['monto_cheque'])) {
                      $monto_cheque=$_POST['monto_cheque'];
                      $sql_pt="SELECT * FROM pago_cheque  WHERE id_factura='$id_factura'";
                      $result_pt=_query($sql_pt);
                      $row_pt=_fetch_array($result_pt);
                      $nrows_pt=_num_rows($result_pt);
                      if ($nrows_pt==0) {
                        $table_pt= 'pago_cheque';
                        $form_data_pt = array(
                          'fecha' => $fecha_movimiento,
                          'hora'=>$hora,
                          'emisor' => $banco,
                          'numero_cheque' => $numero_cheque,
                          'monto' => $total,
                        );
                        $where_clause="WHERE id_factura='$id_factura'";
                        $actualizar = _insert($table_pt, $form_data_pt);
                        $id_pago= _insert_id();
                      }
                    }
                  }
                  //pago transferencia
                  if ($tipo_pago=="TRA") {
                    if (isset($_POST['numero_transferencia'])) {
                      $numero_transferencia=$_POST['numero_transferencia'];
                    }
                    if (isset($_POST['id_cuenta_banco'])) {
                      $id_cuenta=$_POST['id_cuenta_banco'];
                    }
                    if (isset($_POST['monto_transferencia'])) {
                      $monto_tansferencia=$_POST['monto_transferencia'];
                      //SELECT `id_pago_transferencia`, `id_factura`, `tipo_documento`, `fecha`, `hora`, `numero_transferencia`, `id_cuenta`, `monto` FROM `pago_transferencia` WHERE 1
                      $sql_pt="SELECT * FROM pago_transferencia  WHERE id_factura='$id_factura'";
                      $result_pt=_query($sql_pt);
                      $row_pt=_fetch_array($result_pt);
                      $nrows_pt=_num_rows($result_pt);
                      if ($nrows_pt==0) {
                        $table_pt= 'pago_transferencia';
                        $form_data_pt = array(
                          'id_factura' => $id_factura,
                          'fecha' => $fecha_movimiento,
                          'hora'=>$hora,
                          'id_cuenta' => $id_cuenta,
                          'numero_transferencia' => $numero_transferencia,
                          'monto' => $total_venta,
                        );
                        $where_clause="WHERE id_factura='$id_factura'";
                        $actualizar = _insert($table_pt, $form_data_pt);
                        $id_pago= _insert_id();

                        $monto=$total_venta;

                        $sql_res=_fetch_array(_query("SELECT id_empleado, id_vendedor FROM factura WHERE factura.id_factura = $id_factura "));
                        $responsable="";
                        if ($sql_res['id_vendedor']==-1) {
                          # code...
                          $sql_res_name=_fetch_array(_query("SELECT empleados.nombre FROM empleados WHERE id_empleado=$sql_res[id_empleado]"));
                          $responsable=$sql_res_name['nombre'];
                        }
                        else {
                          $sql_res_name=_fetch_array(_query("SELECT empleados.nombre FROM empleados WHERE id_empleado=$sql_res[id_vendedor]"));
                          $responsable=$sql_res_name['nombre'];
                        }

                        $tipo="Ingreso";
                      	$sql = _query("SELECT (SUM(mov_cta_banco.entrada)-SUM(mov_cta_banco.salida)) AS saldo FROM mov_cta_banco WHERE id_sucursal='$id_sucursal' AND id_cuenta='$id_cuenta'");
                      	$row = _fetch_array($sql);
                      	$saldo = $row["saldo"];
                        $saldo=round($saldo,2);

                      	$nalc = 0;
                      	if($tipo == "Ingreso")
                      	{
                      		$entrada = $monto;
                      		$salida = 0;
                      		$nsal = $saldo + $monto;
                      	}

                      	$tabla_mov = 'mov_cta_banco';
                      	$form_data_mov = array (
                      		'id_cuenta' => $id_cuenta,
                      		'tipo' => $tipo,
                      		'alias_tipodoc' => "Transferencia",
                      		'numero_doc' => $numero_transferencia,
                      		'entrada' => $entrada,
                      		'salida' => $salida,
                      		'saldo' => $nsal,
                      		'fecha' => $fecha_movimiento,
                      		'responsable' => $responsable,
                      		'concepto' => "INGRESO POR VENTA",
                      		'id_sucursal' => $id_sucursal,
                          'procesado' => 0,
                          'id_factura' => $id_factura,
                      	);
                        $insertar = _insert($tabla_mov,$form_data_mov);
                      }
                    }
                  }*/

                }

                function imprimir_fact()
                  {
                    $fecha_actual=date("Y-m-d");
                  //  $tipo_pago= $_POST['tipo_pag'];
                    $id_cobro= $_POST['id_cobro'];
                    $id_empleado=$_SESSION["id_usuario"];
                    $numero_doc = $_POST['numero_doc'];
                    $tipo_impresion= $_POST['tipo_impresion'];
                    $id_factura= $_POST['id_cobro'];
                    $id_sucursal=$_SESSION['id_sucursal'];
                    $numero_factura_consumidor = $_POST['id_cobro'];
                    if ($tipo_impresion=='COF') {
                      $tipo_entrada_salida="FACTURA CONSUMIDOR";
                    }
                    if ($tipo_impresion=='TIK') {
                      $tipo_entrada_salida="TICKET";
                    }
                    if ($tipo_impresion=='CCF') {
                      $tipo_entrada_salida="CREDITO FISCAL";
                      $nit= $_POST['nit'];
                      $nrc= $_POST['nrc'];
                      $nombreape= $_POST['nombreape'];
                    }
                    //Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
                    $info = $_SERVER['HTTP_USER_AGENT'];
                    if (strpos($info, 'Windows') == true) {
                      $so_cliente='win';
                    } else {
                      $so_cliente='lin';
                    }

                    $sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_cobro'";
                    $result_fact=_query($sql_fact);
                    $nrows_fact=_num_rows($result_fact);
                    if ($nrows_fact>0) {
                      $table_fact= 'factura';

                      if ($tipo_impresion=='TIK') {
                        $form_data_fact = array(
                          'finalizada' => '1',
                          'pagada' => '1',
                        );

                        $where_clause="id_cobro='$id_factura'";
                        $actualizar = _update($table_fact, $form_data_fact, $where_clause);

                      }else {
                        # code...
                        $form_data_fact = array(
                          'finalizada' => '1',
                          'impresa' => '1',
                          'num_fact_impresa'=>$numero_factura_consumidor,
                        );

                        $where_clause="id_cobro='$id_factura'";
                        $actualizar = _update($table_fact, $form_data_fact, $where_clause);
                      }

                    }
                    //cambiar numero documento impreso para mostrar en reporte kardex
                    /*$where_clause1="WHERE
                    tipo_entrada_salida='$tipo_entrada_salida'
                    AND numero_doc='$numero_doc'
                    AND fecha_movimiento='$fecha_movimiento'
                    ";

                    $table1= 'movimiento_producto';
                    $form_data1 = array(
                    'numero_doc'=>$id_factura,
                  );

                  $insertar1 = _update($table1, $form_data1, $where_clause1);*/

                  if ($tipo_impresion=='COF') {
                    $info_facturas=print_fact($id_factura, $tipo_impresion,"","");
                  }
                  if ($tipo_impresion=='ENV') {
                    $info_facturas=print_envio($id_factura, $tipo_impresion);
                  }

                  if ($tipo_impresion=='CCF') {
                    $info_facturas=print_ccf($id_factura, $tipo_impresion, $nit, $nrc, $nombreape,"");
                  }
                  //directorio de script impresion cliente
                  $headers="";
                  $footers="";
                  if ($tipo_impresion=='TIK') {
                    $info_facturas=print_ticket($id_factura, $tipo_impresion);
                    $sql_pos="SELECT *  FROM sucursal  WHERE id_sucursal='$id_sucursal' ";
                    $result_pos=_query($sql_pos);
                    $row1=_fetch_array($result_pos);
                    $headers=$row1['nombre_lab']."|".Mayu($row1['direccion'])."|".$row1['giro']."|";
                    $footers="GRACIAS POR SU COMPRA, VUELVA PRONTO......"."|";
                  }

                  $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
                  $result_dir_print=_query($sql_dir_print);
                  $row_dir_print=_fetch_array($result_dir_print);
                  $dir_print=$row_dir_print['dir_print_script'];
                  $shared_printer_win=$row_dir_print['shared_printer_matrix'];
                  $shared_printer_pos=$row_dir_print['shared_printer_pos'];
                  $nreg_encode['shared_printer_win'] =$shared_printer_win;
                  $nreg_encode['shared_printer_pos'] =$shared_printer_pos;
                  $nreg_encode['dir_print'] =$dir_print;
                  $nreg_encode['facturar'] =$info_facturas;
                  $nreg_encode['sist_ope'] =$so_cliente;
                  $nreg_encode['headers'] =$headers;
                  $nreg_encode['footers'] =$footers;

                  echo json_encode($nreg_encode);
                }
                /*
                function agregar_cliente()
                {
                  //$id_cliente=$_POST["id_cliente"];
                  $nombre=$_POST["nombress"];
                  $dui=$_POST["dui"];
                  $tel1=$_POST["tel1"];
                  $tel2=$_POST["tel2"];


                  $var1=preg_match('/\x{27}/u', $nombre);
                  $var2=preg_match('/\x{22}/u', $nombre);
                  if ($var1==true || $var2==true) {
                    $nombre =stripslashes($nombre);
                  }
                  $sql_result=_query("SELECT * FROM cliente WHERE nombre='$nombre'");
                  $numrows=_num_rows($sql_result);
                  $row_update=_fetch_array($sql_result);
                  $id_cliente=$row_update["id_cliente"];
                  $name_cliente=$row_update["nombre"];


                  //'id_cliente' => $id_cliente,
                  $table = 'cliente';
                  $form_data = array(
                    'nombre' => $nombre,
                    'dui' => $dui,
                    'telefono1' => $tel1,
                    'telefono2' => $tel2,
                  );

                  if ($numrows == 0 && trim($nombre)!='') {
                    $insertar = _insert($table, $form_data);
                    $id_cliente=_insert_id();
                    if ($insertar) {
                      $xdatos['typeinfo']='Success';
                      $xdatos['msg']='Registro insertado con exito!';
                      $xdatos['process']='insert';
                      $xdatos['id_client']=  $id_cliente;
                    } else {
                      $xdatos['typeinfo']='Error';
                      $xdatos['msg']='Registro no insertado !';
                    }
                  } else {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Registro no insertado !';
                  }
                  echo json_encode($xdatos);
                }*/
//}


          function agregar_cliente(){
            $id_sucursal=$_SESSION["id_sucursal"];
            $query_user = _query("SELECT n_expediente FROM expediente WHERE id_sucursal='$id_sucursal' ORDER BY n_expediente DESC LIMIT 1");
            $datos_user = _fetch_array($query_user);
            $numero = $datos_user["n_expediente"];
            $query_user1 = _query("SELECT id_paciente FROM paciente WHERE id_sucursal='$id_sucursal'ORDER BY id_paciente DESC LIMIT 1");
            $datos_user1 = _fetch_array($query_user1);
            $numero2 = $datos_user1["id_paciente"];

            $numero1 = (int)$numero;

            if($numero1 == ""){
              $numero1 = 201800000;
            }else{
              $numero1+=1;
            }
            $numero3 = (int)$numero2 +1;

            $nombre=$_POST["nombre"];
            $apellido=$_POST["apellido"];
            $sexo = $_POST["sexo"];
            $fecha_nacimiento = $_POST["naci"];
            $id_sucursal=$_SESSION["id_sucursal"];
            $expediente = $numero1;
            $paciente = $numero3;
            $fechaU = date("Y-m-d");
            $fechaC = date("Y-m-d");

              $sql_result=_query("SELECT id_paciente FROM paciente WHERE nombre='$nombre' and apellido='$apellido' id_sucursal='$id_sucursal'");
              $numrows=_num_rows($sql_result);

              $table = 'paciente';
              $form_data = array (
              'nombre' => $nombre,
              'apellido' => $apellido,
              'sexo' => $sexo,
              'fecha_nacimiento' => $fecha_nacimiento,
              'estado' => 1,
              'id_sucursal'=>$id_sucursal
              );

              $table1 = 'expediente';
              $form_data1 = array (
              'n_expediente' => $expediente,
              'id_paciente' => $paciente,
              'fecha_creada' => $fechaC,
              'ultima_visita' => $fechaU,
              'id_sucursal'=>$id_sucursal
              );

              if($numrows == 0)
              {
                  $insertar = _insert($table,$form_data);
                  $id_cliente=_insert_id();
                  if($insertar)
                  {
                    $insertar1 = _insert($table1,$form_data1);
                    if($insertar1){
                     $xdatos['typeinfo']='Success';
                     $xdatos['msg']='Paciente ingresado correctamente!';
                     $xdatos['id_client']=  $id_cliente;
                     $xdatos['process']='insert';
                   }
                  }
                  else
                  {
                     $xdatos['typeinfo']='Error';
                     $xdatos['msg']='Paciente no pudo ser ingresado!';
              	}

            }
              else
              {
                  $xdatos['typeinfo']='Error';
                  $xdatos['msg']='Este paciente ya fue ingresado!';
              }
          	echo json_encode($xdatos);

          }
          function agregar_doctor(){

                $nombre=$_POST["nombre"];
                $apellido=$_POST["apellido"];
                $especialidad=$_POST["especialidad"];
                $estado=1;
                $id_sucursal=$_SESSION["id_sucursal"];
                $sql_result=_query("SELECT id_doctor FROM doctor WHERE nombre='$nombre'and id_sucursal='$id_sucursal'");
                $numrows=_num_rows($sql_result);

                $table = 'doctor';
                $form_data = array (
                'nombre' => $nombre,
                'apellido' => $apellido,
                'especialidad' => $especialidad,
                'estado' => $estado,
                'id_sucursal'=>$id_sucursal
                );

                if($numrows == 0)
                {
                    $insertar = _insert($table,$form_data);
                    $id_doct=_insert_id();
                    if($insertar)
                    {
                       $xdatos['typeinfo']='Success';
                       $xdatos['msg']='Doctor ingresado correctamente!';
                       $xdatos['id_doct']=  $id_doct;
                       $xdatos['process']='insert';
                    }
                    else
                    {
                       $xdatos['typeinfo']='Error';
                       $xdatos['msg']='Doctor no pudo ser ingresado!';
                	}
                }
                else
                {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Este doctor ya fue ingresado!';
                }
            	echo json_encode($xdatos);
          }
          function cuenta()
          {
            $id_banco = $_POST["id_banco"];
            $option = "";
            $sql_mun = _query("SELECT id_cuenta,concat(numero_cuenta,' ',nombre_cuenta) as cuenta  FROM cuenta_banco WHERE id_banco='$id_banco'");
            while($mun_dt=_fetch_array($sql_mun))
            {
              $option .= "<option value='".$mun_dt["id_cuenta"]."'>".$mun_dt["cuenta"]."</option>";
            }
            echo $option;
          }
          function tipoimpre()
          {
            $id_cliente = $_POST["id_cliente"];
            $id_sucursal=$_SESSION["id_sucursal"];
            $option = "";
            if($id_cliente==1){
              $sqlcli="SELECT * FROM tipo_impresion WHERE descripcion!='CREDITO FISCAL' and id_sucursal='$id_sucursal' ";
            }else {
              $sqlcli="SELECT * FROM tipo_impresion WHERE id_sucursal='$id_sucursal' ";
            }
            $sql_mun = _query($sqlcli);
            while($mun_dt=_fetch_array($sql_mun))
            {
              $option .= "<option value='".$mun_dt["abreviatura"]."'>".$mun_dt["descripcion"]."</option>";
            }
            echo $option;
          }
          function tipopago()
          {
            $id_cliente = $_POST["id_cliente"];
            $id_sucursal=$_SESSION["id_sucursal"];
            $option = "";
            $sqld = "SELECT remision FROM cliente where id_cliente='$id_cliente' and id_sucursal='$id_sucursal'";
            $resul=_query($sqld);
            $remisiones="";
            while($depto = _fetch_array($resul))
            {
                $remisiones=$depto["remision"];
            }

            if($remisiones==0){
              $sqlcli="SELECT * FROM tipo_pago WHERE descripcion!='REMISIONES' and id_sucursal='$id_sucursal' ";
            }else {
              $sqlcli="SELECT * FROM tipo_pago WHERE id_sucursal='$id_sucursal' ";
            }
            $sql_mun = _query($sqlcli);
            while($mun_dt=_fetch_array($sql_mun))
            {
              $option .= "<option value='".$mun_dt["abreviatura"]."'>".$mun_dt["descripcion"]."</option>";
            }
            echo $option;
          }

          //functions to load
          if (!isset($_REQUEST['process'])) {
            initial();
          }
          //else {
          if (isset($_REQUEST['process'])) {
            switch ($_REQUEST['process']) {
              case 'formEdit':
                initial();
                break;
                case 'insert':
                insertar();
                break;
                case 'insertar_venta':
                insertar_venta();
                break;
                case 'consultar_stock':
                consultar_stock();
                break;
                case 'cargar_empleados':
                cargar_empleados();
                break;
                case 'cuenta':
                cuenta();
                break;
                case 'tipoimpre':
                tipoimpre();
                break;
                case 'tipopago':
                tipopago();
                break;
                case 'total_texto':
                total_texto();
                break;
                case 'actua_fac':
                actua_fac();
                break;
                case 'imprimir_fact':
                imprimir_fact();
                break;
                case 'print2':
                print2(); //Generacion de los datos de factura que se retornan para otro script que imprime!!!
                break;
                case 'mostrar_numfact':
                mostrar_numfact();
                break;
                case 'reimprimir':
                reimprimir();
                break;
                case 'agregar_cliente':
                agregar_cliente();
                break;
                case 'agregar_doctor':
                agregar_doctor();
                break;
              }

            }
            ?>
