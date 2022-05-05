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
  $sql_apertura = _query("SELECT * FROM apertura_caja WHERE vigente = 1 AND id_sucursal = '$id_sucursal'  AND fecha='$fecha_actual' AND id_empleado = '$id_user'");
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
                    <div class="form-group col-md-2">
                      <label id='buscar_habilitado'>Buscar Examen (Descripci&oacute;n)</label>
                      <input type="text" id="producto_buscar" name="producto_buscar"  class="form-control usage" placeholder="Ingrese Descripcion de producto" data-provide="typeahead" style="border-radius:0px">
                    </div>

                    <div id='form_datos_pro' class="form-group col-md-2">
                      <label> Procedencia</label>
                      <select class="col-md-12 select usage" id="id_procedencia" name="id_procedencia">
                        <option value="">Seleccione</option>
                        <?php
                        $sqld = "SELECT * FROM procedencia where id_sucursal='$id_sucursal'";
                        $resul=_query($sqld);
                        while($depto = _fetch_array($resul))
                        {
                          echo "<option value=".$depto["id_procedencia"];
                          echo">".$depto["nombre"]."</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <?php
                      $sqld = "SELECT * FROM cliente where id_sucursal='$id_sucursal'";
                      $resul=_query($sqld);
                      $depto = _fetch_array($resul);
                      echo "<input type='hidden' value=".$depto["id_cliente"]." id='id_cliente' >";

                    ?>


                    <div  class="form-group col-md-2">
                      <label> Refiere</label>
                      <select class="col-md-12 select usage sel1" id="doctor" name="doctor" style="width:100%;">
                        <option value="">Seleccione</option>
                        <?php
                        $sqld = "SELECT id_doctor, concat(nombre,' ',apellido) as nombre_d FROM doctor where id_sucursal='$id_sucursal'";
                        $resul=_query($sqld);
                        while($depto = _fetch_array($resul))
                        {
                          echo "<option value=".$depto["id_doctor"];
                          echo">".$depto["nombre_d"]."</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group col-md-2" >
                      <div class="form-group has-info">
                        <label>Cod Descuento</label>
                        <input type="text" name="descto" id="descto" placeholder="Cod. Descuento" class="form-control">
                        <input type="hidden" id="id_descuento">
                      </div>
                    </div>
                    <div class="form-group col-md-2" >
                      <div class="form-group has-info">
                        <label>Pasaporte</label>
                        <input type="text" name="pasaporte" id="pasaporte" placeholder="N Pasaporte" class="form-control">
                        <input type="hidden" id="n_pasaporte">
                      </div>
                    </div>
                    <div class="form-group col-md-2" >
                      <div class="form-group has-info">
                        <label>DUI</label>
                        <input type="text" name="dui" id="dui" placeholder="N DUI" class="form-control">
                        <input type="hidden" id="n_pasaporte">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div id='form_datos_cliente' class="form-group col-md-2" id="div_paciente" style="margin-top:-15px;">
                      <label>Paciente</label>
                      <input type="text" id="paciente" name="paciente"  class="form-control usage sel" placeholder="Ingrese Paciente" data-provide="typeahead" autocomplete="off" style="border-radius:0px">
                      <input type="text" id="paciente_replace" name="paciente_replace"  class="form-control usage" hidden readonly autocomplete="off">
                      <input type="hidden" name="pacientee" id="pacientee">
                    </div>
                    <div class="form-group col-lg-1" id="check_nacimiento1" style="margin-top:-15px;">
                      <label>Tipo edad</label>
                      <select class="form-control select" name="check_nacimiento" id="check_nacimiento">
                        <option value="1">Años</option>
                        <option value="2">Meses</option>
                        <option value="3">Fecha</option>
                      </select>
                    </div>
                    <!--
                    <div class="form-group col-lg-2">
                       <div class='checkbox i-checks'>
                          <label id='frentex'><strong> Fecha Nacimiento</strong>
                            <input type='checkbox' id='check_nacimiento' name='check_nacimiento'>
                          </label>
                        </div>
                        <input type='hidden' id='nacimiento_h' name='nacimiento_h' value="0">
                    </div>
                  -->
                    <div class="form-group col-lg-2" id="div_edad" style="margin-top:-15px;">
                      <label id="fecha_nacimiento_label">Fecha Nacimiento</label>
                      <input type="text" class="form-control datepicker" id="fecha_nacimiento" name="fecha_nacimiento">
                      <label id="naci_label">Edad</label>
                      <input type="text" placeholder="Edad" class=" form-control" id="naci" name="fecha_nacimiento">
                    </div>
                    <div class="form-group col-lg-2" id="div_sexo" style="margin-top:-15px;">
                      <label>Sexo</label>
                      <select class="form-control select" name="sexo" id="sexo">
                        <option value="">Seleccionar</option>
                        <option value="FEMENINO">Femenino</option>
                        <option value="MASCULINO">Masculino</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-2" id="div_edad" style="margin-top:-15px;">

                      <label >Teléfono</label>
                      <input type="text" placeholder="Teléfono" class="form-control" id="telefono" name="telefono">
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

                <div class="col-md-3" style="margin-top:-15px;"><br>
                  <a class="btn btn-danger " style="margin-left:3%;" href="dashboard.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                  <button type="button" id="submit1" name="submit1" class="btn btn-primary usage"><i class="fa fa-check"></i> F2 Guardar</button>
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
                                    <th class="success cell100 column20">ID</th>
                                    <th class='success  cell100 column50'>DESCRIPCI&Oacute;N</th>
                                    <th class='success  cell100 column12'>PRECIO</th>
                                    <th class='success  cell100 column10'>CORTESIA</th>
                                    <th class='success  cell100 column8'>ACCI&Oacute;N</th>
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
                                    <td class='cell100 column15 leftt  text-bluegrey ' >N° EXAMENES:</td>
                                    <td class='cell100 column5 text-right text-danger' id='totcant'>0.00</td>
                                    <td class='cell100 column15 leftt  text-bluegrey ' >SUBTOTAL $:</td>
                                    <td class='cell100 column5 text-right text-danger' id='subtotal'>0.00</td>
                                    <td class='cell100 column15 leftt  text-bluegrey ' >DESC. %:</td>
                                    <td class='cell100 column5 text-right text-danger' id='pordescuento'>0.00</td>
                                    <td class='cell100 column15 leftt  text-bluegrey ' >DESCUENTO $:</td>
                                    <td class='cell100 column5 text-right text-danger' id='valdescuento'>0.00</td>
                                    <td class="cell100 column10  leftt text-bluegrey ">TOTAL $:</td>
                                    <td class='cell100 column10 text-right text-green' id='total_gravado'>0.00</td>

                                  </tr>
                                  <tr>
                                    <td class='cell100 column50 text-bluegrey'  id='totaltexto'>&nbsp;</td>
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
                    <input type='hidden' name='tip_impre' id='tip_impre' value='<?php echo $tipo_fa; ?>'>
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
              <div class="modal-container">
                <div class="modal fade" id="procedenciaModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                  <div class="modal-dialog model-sm">
                    <div class="modal-content"> </div>
                  </div>
                </div>
              </div>
              <div class="modal-container">
                <div class="modal fade" id="cliente1Modal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
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

      $id_producto = $_REQUEST['id_producto'];
      $id_usuario=$_SESSION["id_usuario"];
      $id_sucursal=$_SESSION['id_sucursal'];
      $tipo=$_REQUEST['tipo'];
      $nombre_examen = "";
      $id_prods = "";
      $select2 = "";
      $cortesia = "";
      $cuantos = 0;
      if($tipo == "E")
      {
        $xdatos["precio_p"] = 0;

        $sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_producto' AND e.id_sucursal='$id_sucursal'";
        $stock1=_query($sql1);
        $row1=_fetch_array($stock1);
        $nombre_examen = $row1['nombre_examen']."|";
        $id_prods = $row1['id_examen']."|";
        $precio= $row1['precio_examen'];
        //$xdatos["precio_p"]= $row1['precio_examen'];
        $select2 .= $precio."|";
        $cortesia = "<input type='checkbox' id='activar' name='activar' class='checkbox i-checks cort'>|";
        $cuantos = 1;
      }
      else
      {
        $sql_aux = _query("SELECT precio_perfil FROM perfil WHERE id_perfil='$id_producto' AND id_sucursal='$id_sucursal'");
        $dats = _fetch_array($sql_aux);
        $xdatos["precio_p"] = $dats["precio_perfil"];
        $xdatos["cortesia_p"] = "<input type='checkbox' id='activar' name='activar' class='checkbox i-checks cort'>";
        $sql_p = _query("SELECT * FROM examen_perfil WHERE id_perfil='$id_producto' AND id_sucursal='$id_sucursal'");
        $nombre_examen = "";
        $id_prods = "";
        $select2 = "";
        $cortesia = "";
        $cuantos = 0;
        while($row = _fetch_array($sql_p))
        {
          $cuantos++;
          $id_prod = $row["id_examen"];
          $sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_prod' AND e.id_sucursal='$id_sucursal'";
          $stock1=_query($sql1);
          $row1=_fetch_array($stock1);
          $nombre_examen.=$row1['nombre_examen']."|";
          $id_prods .= $row1['id_examen']."|";
          $precio = $row1['precio_examen'];
          $select2 .= "|";
          $cortesia .= "|";
        }
      }
      $xdatos['select2']= $select2;
      $xdatos['cortesia']= $cortesia;
      /*  $xdatos['horas']= $hora;
      $xdatos['fecha']= $fecha;*/
      $xdatos['id_prods']= $id_prods;
      $xdatos['descripcionp']= $nombre_examen;
      $xdatos['cuantos']= $cuantos;

      echo json_encode($xdatos); //Return the JSON Array
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
      $pasaporte = $_POST['pasaporte'];
      $procedencia = 0;
      if($_POST["procedencia"] != ""){
        $procedencia=$_POST["procedencia"];
      }

      //  IMPUESTOS
      $porcentaje_descuento=$_POST["porcentaje_descuento"];
      $total_percepcion= $_POST['total_percepcion'];
      $subtotal=$_POST['subtotal'];
      $total = $_POST['sumas'];
      $id_doct=$_POST["doctor"];
      if($id_doct == ""){
        $id_doct = 0;
      }
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
      $id_paci = 0;
      $turno=$_POST['turno'];
      $caja=$_POST['caja'];
      $tipo_documento=$_POST['tipo_impresion'];
      $tipo_pago=$_POST['tipo_pag'];
      $tipo_rem=$_POST['tipo_rem'];
      $con_pag=$_POST['con_pag'];
      $tipo_impresion=$tipo_documento;




///////////////////////////////inicial paciente/////////////////////////////////
      $id_paci=$_POST['id_pacie'];
      $paciente=$_POST['paciente'];
      $pacientee=$_POST['pacientee'];
      $sexo_paciente=$_POST['sexo_paciente'];
      $edad_paciente=$_POST['edad_paciente'];
      $fecha_nacimiento=$_POST['fecha_nacimiento'];
      $verificar_edad_fecha=$_POST['verificar_edad_fecha'];
      $telefono=$_POST['telefono'];
      $dui = $_POST['dui'];
      $tabla_pa = 'paciente';
      if($dui == ""){
        $form_data_paas = array(
          'pasaporte' => $pasaporte
        );
      }
      else{
        $form_data_paas = array(
          'pasaporte' => $pasaporte,
          'dui' => $dui
        );
      }

      $where = " id_paciente = '$pacientee'";
      $insertar_pasa = _update($tabla_pa, $form_data_paas, $where);
      if($insertar_pasa){

      }
      if($pacientee!=""){
        $id_paci=$pacientee;
        ////chequeo fecha
        if($verificar_edad_fecha==3){
          $fecha_nacimiento=$fecha_nacimiento;

        }
        if($verificar_edad_fecha==1){

          ////utilizo edad anios
          $fechaactual = date('d-m-Y');
          $nuevafecha = strtotime ('-'.$edad_paciente.' year' , strtotime($fechaactual));
          $nuevafecha = date ('Y-m-d',$nuevafecha);
          $fecha_nacimiento = $nuevafecha;

        }
        if($verificar_edad_fecha==2){

          ////utilizo edad mese
          $fechaactual = date('d-m-Y');
          $nuevafecha = strtotime ( '-'.$edad_paciente.' month' , strtotime ($fechaactual) ) ;
          $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
          $fecha_nacimiento = $nuevafecha;

        }
        $table_p="paciente";
        $data_p = array(
          'sexo' => $sexo_paciente,
          'telefono' => $telefono,
        );
        $where_clause_p="id_paciente='$id_paci'";
        $insertar_p = _update($table_p, $data_p, $where_clause_p);
      }else{
        ////chequeo fecha
        if($verificar_edad_fecha==3){
          $fecha_nacimiento=$fecha_nacimiento;

        }
        if($verificar_edad_fecha==1){

          ////utilizo edad anios
          $fechaactual = date('d-m-Y');
          $nuevafecha = strtotime ('-'.$edad_paciente.' year' , strtotime($fechaactual));
          $nuevafecha = date ('Y-m-d',$nuevafecha);
          $fecha_nacimiento = $nuevafecha;

        }
        if($verificar_edad_fecha==2){

          ////utilizo edad mese
          $fechaactual = date('d-m-Y');
          $nuevafecha = strtotime ( '-'.$edad_paciente.' month' , strtotime ($fechaactual) ) ;
          $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
          $fecha_nacimiento = $nuevafecha;

        }
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

        $id_paci_cadena=trim($paciente);


        $id_sucursal=$_SESSION["id_sucursal"];
        $expediente = $numero1;
        $paciente = $numero3;
        $fechaU = date("Y-m-d");
        $fechaC = date("Y-m-d");
        $table_p = 'paciente';
        $form_data_p = array (
          'nombre' => str_replace("   "," ",$id_paci_cadena),
          'apellido' => "",
          'sexo' => $sexo_paciente,
          'fecha_nacimiento' => $fecha_nacimiento,
          'telefono' => $telefono,
          'estado' => 1,
          'id_sucursal'=>$id_sucursal
        );
        $insertar_p = _insert($table_p,$form_data_p);
        $id_paci=_insert_id();
        $table1 = 'expediente';
        $form_data1 = array (
          'n_expediente' => $expediente,
          'id_paciente' => $id_paci,
          'fecha_creada' => $fechaC,
          'ultima_visita' => $fechaU,
          'id_sucursal'=>$id_sucursal
        );
        $insertar1 = _insert($table1,$form_data1);


        $tabla_pa = 'paciente';
        if($dui == ""){
          $form_data_paas = array(
            'pasaporte' => $pasaporte
          );
        }
        else{
          $form_data_paas = array(
            'pasaporte' => $pasaporte,
            'dui' => $dui
          );
        }

        $where = " id_paciente = '$id_paci'";
        $insertar_pasa = _update($tabla_pa, $form_data_paas, $where);
        if($insertar_pasa){

        }

      }
      ///////////////////////////////final paciente/////////////////////////////////

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
        $numero_doc=numero_tiquete($ult_cob, $tipo_impresion);
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
        $saldo=$total;
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
        //'sumas'=>$sumas,
        //'iva' =>$iva,
        'total' => $total,
        'id_usuario'=>$id_empleado,
        'id_empleado' => $id_empleado,
        'id_sucursal' => $id_sucursal,
        'tipo_pago' => $tipo_pago,
        'con_pago' => $con_pag,
        'tipo_rem' => $tipo_rem,
        'porcentaje' => $porcentaje_descuento,
        'num_fact_impresa' => $num_fact_impresa,
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
        'id_paciente'=>$id_paci,
        'procedencia'=>$procedencia,
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
          'total' => $total,
          'abono' => 0,
          'saldo' => $total,
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
      //pago transferencia
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
          $variosE=$fila['varios'];
          $id_paci=$id_paci ;
          $cantidad_real=$cantidad;
          $corte=$fila['corte'];
          $precio_venta=$fila['precio'];
          $n_precio=$fila['nprecio'];
          $tipo=$fila['perfil'];

          if($corte == ""){
            $corte = 0;
        }
          $subtotal = floatval($subtotal);
          $porcentaje_descuento = floatval($porcentaje_descuento);
          if($n_precio>3){
            $n_precio1=4;
          }else {
            $n_precio1=$n_precio;
          }
          $desca=0;
          if($porcentaje_descuento>0)
          {
            $desca = round($subtotal *($porcentaje_descuento/100),2);
            $subtotal=$subtotal-$desca;
            if($porcentaje_descuento ==100)
            {
              $desca = $subtotal;
            }
          }
          if($n_precio1 == ""){
            $n_precio1 = 0;
          }
          $table_fact_det= 'detalle_cobro';
          $data_fact_det = array(
            'id_cobro' => $id_cobro,
            'id_examen' => $id_producto,
            'cantidad' => $cantidad_real,
            'precio' => $precio_venta,
            'n_precio' => $n_precio1,
            'subtotal' => $subtotal,
            'descuento' => $desca,
            'detalles' => $descr,
            'id_paciente' => $id_paci,
            'id_sucursal' => $id_sucursal,
            'cortesia' => $corte,
            'tipo' => $tipo,
            //'fecha' => $fecha_movimiento,
            //'id_presentacion'=> $id_presentacion,
            //'exento' => $exento,
          );
          $insertar_fact_det = _insert($table_fact_det,$data_fact_det );
          if (!$insertar_fact_det) {
            # code...
            $c=0;
          }
          if ($id_cliente !='') {
            $sql8="select * from cliente where id_sucursal='$id_sucursal' and id_cliente='$id_cliente'";
            $result8= _query($sql8);
            $rows8=_fetch_array($result8);
            $nrows8=_num_rows($result8);
            $mesactul=$rows8['mes'];
            $añoactul=$rows8['año'];
            $mesnuevo=date('m');
            $añonuevo=date('y');
            if($mesactul==$mesnuevo){
              $ult_cli=$rows8['cli']+1;
              $mes=$mesactul;

            }else {
              $ult_cli=1;
              $mes=$mesnuevo;

            }
            if($añoactul==$añonuevo){
              $ult_cli1=$rows8['clia']+1;
              $año=$añoactul;

            }else {
              $ult_cli1=1;
              $año=$añonuevo;

            }

            $numero_doc2="";
            $table_numdoc2="cliente";
            $data_numdoc2 = array(
              'cli' => $ult_cli,
              'clia' => $ult_cli1,
              'mes' => $mes,
              'año' => $año,
            );
            $co_cli=numero_tiquete($ult_cli, $tipo_impresion);
            $co_clia=numero_tiquete($ult_cli1, $tipo_impresion);
            $where_clause_n1="id_sucursal='$id_sucursal' and id_cliente='$id_cliente'";
            $insertar_numdoc1 = _update($table_numdoc2, $data_numdoc2, $where_clause_n1);

          }
          if($tipo == "P")
          {
            $sql_p = _query("SELECT * FROM examen_perfil WHERE id_perfil='$id_producto' AND id_sucursal='$id_sucursal'");
            $nombre_examen = "";
            $id_prods = "";
            $cuantos = 0;
            while($row = _fetch_array($sql_p))
            {
              $cuantos++;
              $id_prod = $row["id_examen"];
              $sql1 = "SELECT e.id_examen ,e.nombre_examen, e.precio_examen FROM examen AS e WHERE  e.id_examen ='$id_prod' AND e.id_sucursal='$id_sucursal'";
              $stock1=_query($sql1);
              $row1=_fetch_array($stock1);
              $id_prods = $row1['id_examen'];

              $table1="examen_paciente";
              $form_data1=array(
                'id_examen'=>$id_prods,
                'id_doctor'=>$id_doct,
                'id_paciente'=>$id_paci,
                'fecha_cobro'=>$fecha,
                'hora_cobro'=>$hora,
                'fecha_examen'=>$fecha,
                'hora_examen'=>$hora,
                'estado_realizado'=>"Pendiente",
                'id_sucursal'=>$id_sucursal,
                'id_cobro'=>$id_cobro,
                'correlativo_m'=>$co_cli,
                'procedencia'=>$procedencia,
                'correlativo_a'=>$co_clia,
                'examen_paciente_nulo' => 0
              );
              $insertt1=_insert($table1, $form_data1);
              if (!$insertar_fact_det) {
                # code...
                $x=0;
              }
            }
          }
          else
          {
            $table1="examen_paciente";
            $form_data1=array(
              'id_examen'=>$id_producto,
              'id_doctor'=>$id_doct,
              'id_paciente'=>$id_paci,
              'fecha_cobro'=>$fecha,
              'hora_cobro'=>$hora,
              'fecha_examen'=>$fecha,
              'hora_examen'=>$hora,
              'estado_realizado'=>"Pendiente",
              'id_sucursal'=>$id_sucursal,
              'id_cobro'=>$id_cobro,
              'correlativo_m'=>$co_cli,
              'procedencia'=>$procedencia,
              'correlativo_a'=>$co_clia,
              'examen_paciente_nulo' => 0
            );
            $insertt1=_insert($table1, $form_data1);
            if (!$insertar_fact_det) {
              # code...
              $x=0;
            }
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

      function imprimir_fact()
      {
        $fecha_actual=date("Y-m-d");
        //  $tipo_pago= $_POST['tipo_pag'];
        $con_pago= $_POST['con_pago1'];
        $id_cobro= $_POST['id_cobro'];
        $id_empleado=$_SESSION["id_usuario"];
        $numero_doc = $_POST['numero_doc'];
        $tipo_impresion= $_POST['tipo_impresion'];
        $id_factura= $_POST['id_cobro'];
        $id_apertura_pagada= $_POST['id_apertura'];
        $id_sucursal=$_SESSION['id_sucursal'];
        $numero_factura_consumidor = $_POST['id_cobro'];
        $monto = $_POST['monto'];
        $direccion="San Miguel";

        if (isset($_POST['nombreape'])) {
          $nombreape= $_POST['nombreape'];
        }
        if (isset($_POST['direccion'])) {
          $direccion= $_POST['direccion'];
        }
        if (isset($_POST['nit'])) {
          $nit= $_POST['nit'];
        }
        if (isset($_POST['nrc'])) {
          $nrc= $_POST['nrc'];
        }

        if ($tipo_impresion=='COF') {
          $tipo_entrada_salida="FACTURA CONSUMIDOR";

        }
        if ($tipo_impresion=='TIK') {
          $tipo_entrada_salida="TICKET";
        }
        if ($tipo_impresion=='COB') {
          $tipo_entrada_salida="CONTROL COBRO";
        }
        if ($tipo_impresion=='CCF') {
          $tipo_entrada_salida="CREDITO FISCAL";
          $nit= $_POST['nit'];
          $nrc= $_POST['nrc'];
          $nombreape= $_POST['nombreape'];
        }
        if($con_pago=='CHE'){

          $numero=$_POST['numche'];
          $banco=$_POST['emisor'];
        }elseif($con_pago=='TRA'){

          $banco1=$_POST['banco'];
          $numcuenta=$_POST['numcuenta'];
          $numtrans=$_POST['numtrans'];
        }elseif($con_pago=='TAR'){

          $banco=$_POST['emisor'];
          $numero=$_POST['numtarj'];
          $voucher=$_POST['voucher'];
        }else {
          $banco="";
          $numero="";
          $voucher="";
        }

        if ($con_pago=="TRA") {

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
            'id_cuenta'=>$numcuenta,
            'tipo' => $tipo,
            'alias_tipodoc' => "Transferencia",
            'numero_doc' => $numtrans,
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
          $table_fact= 'cobro';

          if ($tipo_impresion=='TIK') {
            $form_data_fact = array(
              'finalizada' => '1',
              'pagada' => '1',
              'id_apertura_pagado' => $id_apertura_pagada,
              'turno_pagado' => $id_apertura_pagada,
              'banco'=>$banco,
              'num_doc_pago' => $numero,
              'voucher' => $voucher,

            );

            $where_clause="id_cobro='$id_factura'";
            $actualizar = _update($table_fact, $form_data_fact, $where_clause);

          }else {
            $form_data_fact = array(
              'finalizada' => '1',
              'pagada' => '1',
              'id_apertura_pagado' => $id_apertura_pagada,
              'turno_pagado' => $id_apertura_pagada,
              'banco'=>$banco,
              'num_doc_pago' => $numero,
              'voucher' => $voucher,
              'num_fact_impresa'=>$numero_doc,
              'fecha_pago'=>$fecha_actual,
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
        $info_facturas=print_fact($id_factura, $tipo_impresion, $nit, $nrc, $nombreape, $direccion);

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
      if ($tipo_impresion=='TIK' ||$tipo_impresion=='COB') {
        $info_facturas=print_ticket($id_factura, $tipo_impresion);

        $sql_pos="SELECT *  FROM config_pos  WHERE id_sucursal='$id_sucursal' AND alias_tipodoc='TIK'";

        $result_pos=_query($sql_pos);
        $row1=_fetch_array($result_pos);

        $headers=$row1['header1']."|".$row1['header2']."|".$row1['header3']."|".$row1['header4']."|".$row1['header5']."|";
        $headers.=$row1['header6']."|".$row1['header7']."|".$row1['header8']."|".$row1['header9']."|".$row1['header10'];
        $footers=$row1['footer1']."|".$row1['footer2']."|".$row1['footer3']."|".$row1['footer4']."|".$row1['footer5']."|";
        $footers.=$row1['footer6']."|".$row1['footer7']."|".$row1['footer8']."|".$row1['footer8']."|".$row1['footer10']."|";
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
  $fecha_nacimiento = MD($_POST["naci"]);
  $id_sucursal=$_SESSION["id_sucursal"];
  $expediente = $numero1;
  $paciente = $numero3;
  $fechaU = date("Y-m-d");
  $fechaC = date("Y-m-d");

  $sql_result=_query("SELECT id_paciente FROM paciente WHERE nombre='$nombre' and apellido='$apellido'and id_sucursal='$id_sucursal'");
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



  if($numrows == 0)
  {
    $insertar = _insert($table,$form_data);
    $id_cliente=_insert_id();
    $table1 = 'expediente';
    $form_data1 = array (
      'n_expediente' => $expediente,
      'id_paciente' => $id_cliente,
      'fecha_creada' => $fechaC,
      'ultima_visita' => $fechaU,
      'id_sucursal'=>$id_sucursal
    );
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
/*        function agregar_cliente(){
$id_sucursal=$_SESSION["id_sucursal"];
$nombre=$_POST["nombre"];
$sexo = $_POST["sexo"];
$fecha_nacimiento = $_POST["naci"];
$id_sucursal=$_SESSION["id_sucursal"];
$expediente = $numero1;
$paciente = $numero3;
$fechaC = date("Y-m-d");

$sql_result=_query("SELECT id_cliente FROM cliente WHERE nombre='$nombre' and apellido='$apellido' id_sucursal='$id_sucursal'");
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
$xdatos['msg']='Este cliente ya fue ingresado!';
}
echo json_encode($xdatos);

}
*/
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
function agregarcliente1(){
  $nombre=$_POST["nombre"];
  $apellido=$_POST["apellido"];
  $sexo = $_POST["sexo"];
  $id_sucursal=$_SESSION["id_sucursal"];

  $sql_result=_query("SELECT id_cliente FROM cliente WHERE nombre='$nombre' and id_sucursal='$id_sucursal'");
  $numrows=_num_rows($sql_result);

  $table = 'cliente';
  $form_data = array (
    'nombre' => $nombre,
    'direccion' => $apellido,
    'sexo' => $sexo,
    'id_sucursal'=>$id_sucursal
  );

  if($numrows == 0)
  {
    $insertar = _insert($table,$form_data);
    echo _error();
    $id_cliente2=_insert_id();
    if($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='cliente ingresado correctamente!';
      $xdatos['id_client2']=  $id_cliente2;
      $xdatos['process']='insert';

    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Cliente no pudo ser ingresado!';
    }

  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Este cliente ya fue ingresado!';
  }
  echo json_encode($xdatos);

}
function agregar_procedencia(){

  $nombre=$_POST["nombre"];
  $apellido=$_POST["descripcion"];
  $especialidad=$_POST["telefono"];
  $estado=1;
  $fecha_actual=date('y-m-d');
  $id_sucursal=$_SESSION["id_sucursal"];
  $sql_result=_query("SELECT id_procedencia FROM procedencia WHERE nombre='$nombre'and id_sucursal='$id_sucursal'");
  $numrows=_num_rows($sql_result);

  $table = 'procedencia';
  $form_data = array (
    'nombre' => $nombre,
    'direccion' => $apellido,
    'telefono' => $especialidad,
    'estado' => $estado,
    'fecha'=>$fecha_actual,
    'id_sucursal'=>$id_sucursal
  );

  if($numrows == 0)
  {
    $insertar = _insert($table,$form_data);
    $id_doct=_insert_id();
    if($insertar)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Procedencia ingresado correctamente!';
      $xdatos['id_doct']=  $id_doct;
      $xdatos['process']='insert';
    }
    else
    {
      $xdatos['typeinfo']='Error';
      $xdatos['msg']='Procedencia no pudo ser ingresado!';
    }
  }
  else
  {
    $xdatos['typeinfo']='Error';
    $xdatos['msg']='Este procedencia ya fue ingresado!';
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
  $tip_impre = $_POST["tip_impre"];
  $id_sucursal=$_SESSION["id_sucursal"];
  if($tip_impre==1){
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
function pin()
{
  $hash = $_POST["hash"];
  $sql = _query("SELECT id_descuento, porcentaje, aplicado FROM descuento WHERE hash='$hash'");
  if(_num_rows($sql)>0)
  {
    $datos = _fetch_array($sql);
    $xdatos["porcentaje"] = $datos["porcentaje"];
    $xdatos["aplicado"] = $datos["aplicado"];
    if(!$datos["aplicado"])
    {
      $xdatos["typeinfo"] = "Ok";
      $xdatos["id_descuento"] = $datos["id_descuento"];
    }
    else
    {
      $xdatos["typeinfo"] = "Ap";
    }
  }
  else
  {
    $xdatos["typeinfo"] = "No";
  }
  echo json_encode($xdatos);
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
      case 'pin':
        pin();
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
      case 'agregar_cliente1':
        agregarcliente1();
        break;
      case 'agregar_doctor':
        agregar_doctor();
        break;
      case 'agregar_procedencia':
        agregar_procedencia();
        break;
      case 'imprimir_ticket_cobro':
        imprimir_ticket_cobro();
        break;
    }

  }




function imprimir_ticket_cobro(){
  $id_cobro = $_POST['id_cobro'];
  $monto_enviado = $_POST['monto_enviado'];
  $cambio_enviado = $_POST['cambio_enviado'];
  $cadena_enviar = "";
  $cabec = explode("|",cabecera_factura());
  $cadena_enviar.= $cabec[0];
  $cadena_enviar.= $cabec[1];
  $cadena_enviar.= $cabec[2];
  $cadena_enviar.= $cabec[3];
  
  $infoc = explode("|",info_cobro($id_cobro));
  $cadena_enviar.= $infoc[0];
  $cadena_enviar.= $infoc[1];
  $cadena_enviar.= $infoc[2];
  $cadena_enviar.= $infoc[3];
  $cadena_enviar.= $infoc[4];
  $cadena_enviar.= $infoc[5];


  $cadena_enviar.= informacion_examenes($id_cobro, $infoc[6], $monto_enviado, $cambio_enviado);
  $cadena_enviar.="\n";
  $cadena_enviar.= $cabec[4];


  
  $xdatos["datosvale"] = $cadena_enviar;
  $xdatos['id_cobro'] = $id_cobro;
  $info = $_SERVER['HTTP_USER_AGENT'];
  if (strpos($info, 'Windows') == true) {
    $so_cliente='win';
  } else {
    $so_cliente='lin';
  }
  $id_sucursal = $_SESSION['id_sucursal'];
    $sql_dir_print="SELECT *  FROM config_dir WHERE id_sucursal='$id_sucursal'";
    $result_dir_print=_query($sql_dir_print);
    $row_dir_print=_fetch_array($result_dir_print);
    $dir_print=$row_dir_print['dir_print_script'];
    $shared_printer_win=$row_dir_print['shared_printer_matrix'];
    $shared_printer_pos=$row_dir_print['shared_printer_pos'];
    $xdatos['shared_printer_win'] =$shared_printer_win;
    $xdatos['shared_printer_pos'] =$shared_printer_pos;
    $xdatos['dir_print'] =$dir_print;
    $xdatos['sist_ope'] =$so_cliente;
  echo json_encode($xdatos);
}



function cabecera_factura(){
  $id_sucursal = $_SESSION['id_sucursal'];
  $sql = "SELECT * from config_pos where id_sucursal = '$id_sucursal'";
  $query = _query($sql);
  $row = _fetch_array($query);
  $header1 = $row['header1'];
  $header2 = $row['header2'];
  $header3 = $row['header3'];
  $header4 = $row['header4'];
  $header5 = $row['header5'];
  if(strlen($header1 < 40)){
    $header1 = setear_40_string($header1);
  }
  if(strlen($header2 < 40)){
    $header2 = setear_40_string($header2);
  }
  if(strlen($header3 < 40)){
    $header3 = setear_40_string($header3);
  }
  if(strlen($header4 < 40)){
    $header4 = setear_40_string($header4);
  }
  if(strlen($header5 < 40)){
    $header5 = setear_40_string($header5);
  }
  $header1.="\n";
  $header2.="\n";
  $header3.="\n";
  $header4.="\n";
  $header5.="\n";
  return $header1."|".$header2."|".$header3."|".$header4."|".$header5;
}
function info_cobro($id_cobro){
  $sql = "SELECT * FROM cobro WHERE id_cobro = '$id_cobro'";
  $query = _query($sql);
  $row = _fetch_array($query);
  $numero_cobro = $row['id_cobro'];
  $fecha_cobro = $row['fecha'];
  $hora_cobro = $row['hora_cobro'];
  $numero_doc = $row['numero_doc'];
  $numero_caja = $row['caja'];
  $id_paciente = $row['id_paciente'];
  $id_usuario = $row['id_usuario'];
  $total = "$".number_format($row['total'],2);
  $sql2 = "SELECT * from caja where id_caja = '$numero_caja'";
  $query2= _query($sql2);
  $row2= _fetch_array($query2);
  $nombre_caja = $row2['nombre'];


  
  $sql3 = "SELECT * from paciente where id_paciente = '$id_paciente'";
  $query3 = _query($sql3);
  $row3 = _fetch_array($query3);
  $nombre_paciente = utf8_decode($row3['nombre']);
  $apellido_paciente = utf8_decode($row3['apellido']);
  

  $sql_usuario = "SELECT * FROM usuario WHERE id_usuario = '$id_usuario'";
  $query_usuario = _query($sql_usuario);
  $row_usuario = _fetch_array($query_usuario);
  $nombre_usuario = $row_usuario['usuario'];

  $paciente = $nombre_paciente." ".$apellido_paciente;
  $header1 = setear_40_string("FECHA: ".ED($fecha_cobro)." | HORA: "._hora_media_decode($hora_cobro));
  $header1 = $header1."\n";
  $header2 = setear_40_string("NUMERO DE COBRO: ".$numero_cobro);
  $header2 = $header2."\n";
  $header21 = setear_40_string("NUM TICKET.: #".$numero_doc);
  $header21 = $header21."\n";
  $header3 = setear_40_string($nombre_caja);
  $header3 = $header3."\n";
  $header4 = setear_40_string("PACIENTE: ".$paciente);
  $header4.= "\n";
  $header4.= setear_40_string("COBRADO POR: ".$nombre_usuario);
  $header4.="\n";
  return $header1."|".$header2."|".$header21."|".$header3."|".$header4."|".$total;
}
function informacion_examenes($id_cobro, $total, $monto_enviado, $cambio_enviado){
  $sql = "SELECT * from examen_paciente where id_cobro = '$id_cobro'";
  $query = _query($sql);
  $contador_examenes = 1;
  $cadena_retornar="\n";
  $cadena_retornar.= "------------------------------------------";
  $cadena_retornar.="\n";
  $cadena_retornar.= "|N  |    NOMBRE DEL EXAMEN     | PRECIO  |\n";
  $cadena_retornar.= "------------------------------------------";
  $cadena_retornar.="\n";
  while($row = _fetch_array($query)){
      $id_examen = $row['id_examen'];
      $sql2 = "SELECT * FROM examen where id_examen = '$id_examen'";
      $query2 = _query($sql2);
      $row2 = _fetch_array($query2);
      $nombre_examen = $row2['nombre_examen'];
      $precio_examen = "$".number_format($row2['precio_examen'],2);
      $sql3 = "SELECT cortesia FROM detalle_cobro WHERE id_cobro = '$id_cobro' and id_examen = '$id_examen'";
      $query3 = _query($sql3);
      $row3= _fetch_array($query3);
      if($row3['cortesia'] == "1"){
        $precio_examen = "$0.00 *";
      }
      $numero_poner = strval($contador_examenes);
      if(strlen($numero_poner)== 1){
        $cadena_retornar.= " ".$numero_poner."    ";
      }
      if(strlen($numero_poner)== 2){
        $cadena_retornar.= " ".$numero_poner."   ";
      }
      if(strlen($numero_poner)== 3){
        $cadena_retornar.= " ".$numero_poner."  ";
      }
      if(strlen($nombre_examen) >= 25){
        $cadena_retornar.= setear_22_string($nombre_examen, $precio_examen);
      }
      else{
        $cadena_retornar.=$nombre_examen;
        $diff = 27 - strlen($nombre_examen);
        for($a = 0; $a < $diff; $a++ ){
            $cadena_retornar.=" ";
        }
        $cadena_retornar.= $precio_examen;
      }
      $cadena_retornar.="\n";
      $contador_examenes++;
  }
  $monto_enviado = "$".number_format($monto_enviado,2);
  $cambio_enviado = "$".number_format($cambio_enviado,2);
  $cadena_retornar.= "------------------------------------------";
  $cadena_retornar.="\n";
  $cadena_retornar.="                       TOTAL:    ".$total;
  $cadena_retornar.="\n";
  $cadena_retornar.="                       EFECTIVO: ".$monto_enviado;
  $cadena_retornar.="\n";
  $cadena_retornar.="                       CAMBIO:   ".$cambio_enviado;
  $cadena_retornar.="\n";
  $cadena_retornar.="(*) Regalia";
  return $cadena_retornar;
}
function setear_22_string($cadena, $precio_retornar){
  $largo = strlen($cadena);
  $cadena_retornar = "";
  $contador_precio = 0;
  for($a = 1; $a < $largo+1; $a++){
      if($a % 25 == 0){
        $cadena_retornar.="\n";
        for($b = 0; $b < 6 ;$b++){
            $cadena_retornar.=" ";
        }
        $contador_precio = 0;
      }
      $cadena_retornar.=$cadena[$a-1];
      $contador_precio++;
  }
  $contador_final = 27 - $contador_precio;
  
  for($a = 0; $a < $contador_final; $a++){
    $cadena_retornar.=" ";
  }
  $cadena_retornar.=$precio_retornar;
  return $cadena_retornar;
}
function setear_mas_40_string($cadena){
  $largo = strlen($cadena);
  $cadena_nueva = "";
  for($a = 0; $a < 40; $a++){
    $cadena_nueva.=$cadena[$a];
  }
  $cadena_nueva.="\n";
  for($a = 40; $a < $largo; $a++){
    $cadena_nueva.=$cadena[$a];
  }
  return $cadena_nueva;
}
function setear_40_string($cadena){
  $largo = strlen($cadena);
  $residuo_inicio = 0;
  if($largo % 2 != 0){
      $residuo_inicio++;
      $largo--;
  }
  $inicio_final = (40-$largo)/2;
  $cadena_return = "";
  for($a = 0; $a < $inicio_final+$residuo_inicio; $a++){
    $cadena_return.=" ";
  }
  $cadena_return.=$cadena;
  for($a = 0; $a < $inicio_final; $a++){
    $cadena_return.=" ";
  }
  return $cadena_return;
}
  ?>
