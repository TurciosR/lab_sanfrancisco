<?php
include_once "_core.php";

function initial() {
	// Page setup
  $title = 'Agregar Cobro';
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
    date_default_timezone_set('America/El_Salvador');
    $hora=Date("H:i:s");
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
                    <div class="ibox">
                      <div class="ibox-title">
                          <h3></i> <b><?php echo $title;?></b></h3>
                      </div>
                        <div class="ibox-content">
                            <form name="formulario" id="formulario">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <label> Cliente</label>

                                             <select class="col-md-12 select" id="responsable" name="responsable">
                                                  <option value="">Seleccione</option>
                                                  <?php
                                                      $sqld = "SELECT * FROM paciente";
                                                      $resul=_query($sqld);
                                                      while($depto = _fetch_array($resul))
                                                      {
                                                          echo "<option value=".$depto["id_paciente"];
                                                          echo">".$depto["nombre"]." ". $depto["apellido"]."</option>";
                                                      }
                                                  ?>
                                              </select>
                                          </div>
                                        <div class="form-group col-lg-3">
                                            <label>Fecha</label>
                                            <input type="text" class="form-control datepicker" id="fecha" name="fecha" value="<?php echo date("d-m-y");?>">
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Tipo de documento</label>
                                            <select class="col-md-12 select" id="tipo_doc" name="tipo_doc" >
                                              <option value="">Selecionar</option>
                                              <option value="tk">Ticket</option>
                                              <option value="fc">Factura</option>
                                              <option value="cf">Credito fical</option>

                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label>Numero de documento</label>
                                            <input type="text" placeholder="Ingrese numero" class="form-control" id="n_doc" name="n_doc"  >
                                        </div>


                                </div>
                                <div class="row">

                                  <div class="form-group col-lg-4">
                                      <label>Condicion de pago</label>
                                      <select class="col-md-12 select" id="con_pag" name="con_pag">
                                        <option value="">Selecionar</option>
                                        <option value="1">Credito</option>
                                        <option value="0">Contado</option>
                                      </select>
                                  </div>
                                  <div class="form-group col-lg-4" id="div_con">
                                      <label>N° dias</label>
                                      <input type="text" autocomplete="off" placeholder="Ingresa numero de dias " class="form-control" id="dias" name="dias">
                                  </div>

                                </div>
                                <div class="row">
                                  <div class="form-group col-lg-4">
                                      <label>Examen</label>
                                      <select class="col-md-12 select " id="examen" name="examen">
                                          <option value="">Seleccione</option>
                                          <?php
                                              $sqld = "SELECT * FROM examen";
                                              $resul=_query($sqld);
                                              while($examen = _fetch_array($resul))
                                              {
                                                  //$examen1=$examen["nombre"]+" "+$examen["apellido"];
                                                  echo "<option value='".$examen["id_examen"].",".$examen["precio_examen"].",".$examen["nombre_examen"];
                                                  echo"'>".$examen["nombre_examen"]."</option>";

                                                }
                                          ?>
                                      </select>

                                  </div>
                                  <div class="form-group col-lg-3">
                                            <label>Referido por</label>
                                            <select class="col-md-12 select" id="doctor" name="doctor">
                                                <option value="">Seleccione</option>
                                                <?php
                                                    $sqld = "SELECT * FROM doctor";
                                                    $resul=_query($sqld);
                                                    while($doctor = _fetch_array($resul))
                                                    {
                                                        //$doctor1=$doctor["nombre"]+" "+$doctor["apellido"];
                                                        echo "<option value='".$doctor["id_doctor"].",".$doctor["nombre"].",". $doctor["apellido"];
                                                        echo"'>".$doctor["nombre"]." ". $doctor["apellido"]."</option>";

                                                      }
                                                ?>
                                            </select>
                                  </div>
                                  <div class="form-group col-lg-3">
                                            <label> Selecione Paciente</label>
                                           <select class="col-md-12 select" id="paciente" name="paciente">
                                                <option value="">Seleccione</option>
                                                <?php
                                                    $sqld = "SELECT * FROM paciente";
                                                    $resul=_query($sqld);
                                                    while($paciente = _fetch_array($resul))
                                                    {
                                                        echo "<option value='".$paciente["id_paciente"].",".$paciente["nombre"].",". $paciente["apellido"];
                                                        echo"'>".$paciente["nombre"]." ". $paciente["apellido"]."</option>";
                                                    }
                                                ?>
                                            </select>
                                  </div>
                                  <div class="form-group col-lg-2">
                                      <br>
                                      <a class="btn btn-primary" id='app'><i class="fa fa-plus"></i> Agregar</a>
                                  </div>
                                </div>

                                <div class="row">
                                    <div class=" col-lg-12">
                                    <table class="table table-striped table-bordered table-hover" id="example">
                                      <thead>
                                        <tr>
                                          <th class="col-sm-1" >ID</th>
                                          <th class="col-sm-2">DESCRIPCIÓN</th>
                                          <th class="col-sm-2">PACIENTE</th>
                                          <th class="col-sm-2">DOCTOR</th>
                                          <th class="col-sm-1">PRECIO</th>
                                          <th class="col-sm-1">DES %</th>
                                          <th class="col-sm-1">VAL. DES</th>
                                          <th class="col-sm-1">SUBTOTAL</th>
                                          <th class="col-sm-1">ACCI&Oacute;N</th>
                                        </tr>
                                      </thead>
                                      <tbody id="nom">
                                      </tbody>
                                      <tfoot>
                                        <th colspan="3" style="text-align: center;"><b>TOTAL</b></th>
                                        <th id="tot_can" colspan="1">0</th>
                                        <th id="" colspan="1"></th>
                                        <th id="" colspan=""></th>
                                        <th id="tot_des" colspan="1">$0.00</th>
                                        <th id="tot" >$0.00</th>
                                      </tfoot>
                                    </table>
                                  </div>

                                </div>

                                <div class="row">
                                    <div class="form-actions col-lg-12">
                                        <input type="hidden" name="process" id="process" value="insert">
                                        <input type="hidden" class="form-control" nombre="hora" id="hora" value="<?php echo  $hora?>">
                                        <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_cobro.js'></script>";


} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
  //  include_once ("footer.php");
	}
}


function insertar(){
    $cuantos=$_POST["cuantos"];
    $fecha=MD($_POST["fecha"]);
    $hora=$_POST["hora"];
    $data = $_POST["data"];
    $total = $_POST["total"];
    $paciente = $_POST["paciente"];
    $n_dias= $_POST["num_dia"];
    $n_doc= $_POST["num_doc"];
    $tipo_d=$_POST["tipo_doc"];
    $con_pa= $_POST["con_pago"];
    $tot_des=$_POST["total_des"];
    date_default_timezone_set('America/El_Salvador');
    $fecha_examen=date("Y-m-d");
    $hora_examen=date("H:i:s");

    $sll = _query("SELECT * FROM cobro WHERE numero_doc='$n_doc'");
    if(_num_rows($sll)>0)
    {
      $xdatos["typeinfo"]="Error";
      $xdatos["msg"]="Esta factura ya ha sido registrada";
    }
    else
    {
      if($con_pa==1){
        $motop=$total;
        $estado="Pendiente";
      }else {
        $motop=0;
        $estado="Cancelado";
      }
      _begin();
      $table = 'cobro';
      $form_data = array(
      'cliente' => $paciente,
      'total' => $total,
      'fecha' => $fecha,
      'hora_cobro' => $hora,
      'numero_doc' => $n_doc,
      'estado' => $estado,
      'tipo_doc' => $tipo_d,
      'numero_dias' => $n_dias,
      'monto_pendiente' => $motop,
      'total_des' => $tot_des,
      'credito' => $con_pa
      );
      $insertar = _insert($table,$form_data);
      if($insertar)
      {
        $id_cobro = _insert_id();
        $filas = explode("|", $data);
        $iteracion = 0;
        for ($i=0; $i<$cuantos; $i++)
        {
            $datos = explode(",", $filas[$i]);
            $descripcion = $datos[0];
            $precio = $datos[2];
            $cantidad = $datos[1];
            $descuento1= $datos[3];
            $descuento2 = $datos[4];
            $subtotal = $datos[5];
            $id_paci = $datos[6];
            $id_exam = $datos[7];
            $id_doct = $datos[8];
            $tablee="detalle_cobro";
            $form_dataa=array(
              'id_cobro'=>$id_cobro,
              'detalles'=>$descripcion,
              'cantidad'=>$cantidad,
              'id_examen'=>$id_exam,
              'precio'=>$precio,
              'subtotal'=>$subtotal,
              'descuento'=>$descuento1,
              'val_descuento'=>$descuento2,

              );
              $table1="examen_paciente";
              $form_data1=array(
                'id_examen'=>$id_exam,
                'id_doctor'=>$id_doct,
                'id_paciente'=>$id_paci,
                'fecha_cobro'=>$fecha,
                'hora_cobro'=>$hora,
                'fecha_examen'=>$fecha_examen,
                'hora_examen'=>$hora_examen,
                'estado_realizado'=>"Pendiente",

                );
            $insertt=_insert($tablee, $form_dataa);
            $insertt1=_insert($table1, $form_data1);
            if($insertt )
            {
              if($insertt1){
                  $iteracion++;
              }

            }

        }

        if($iteracion == $cuantos)
        {
            _commit();
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro insertado con exito!';
            $xdatos['process']='insert';
        }
        else
        {
          _rollback();
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Registro no pudo ser insertado !';
  		  }
      }
      else
      {
          _rollback();
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Registro no pudo ser insertado !';
      }
    }
  echo json_encode($xdatos);
}

if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process'])){
switch ($_POST['process']) {
	case 'insert':
		insertar();
		break;

	}
}
}
?>
