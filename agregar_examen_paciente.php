<?php
function calcular_edad($fecha)
{
  list($A,$m,$d)=explode("-",$fecha);
  return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
include_once "_core.php";
    
function initial() {
	// Page setup
  $title = 'Agregar Examen Paciente';
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
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';

  $hoy = date("Y-m-d");
	include_once "header.php";
	include_once "main_menu.php";
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);
  date_default_timezone_set('America/El_Salvador');
  $fecha_hoy=date("Y-m-d");
  $proceso= $_REQUEST["proceso"];
  $id_examen_paciente= $_REQUEST["id_examen_paciente"];
  $id_sucursal=$_SESSION["id_sucursal"];
  $query = _query("SELECT e.id_examen as 'examen_id', ep.id_examen_paciente, ep.id_examen, ep.id_paciente,ep.id_doctor,ep.fecha_cobro,ep.hora_cobro,
    p.id_paciente, p.nombre as nombrep, p.apellido as apellidop, p.sexo, p.fecha_nacimiento,  e.nombre_examen,
     e.formulario FROM examen_paciente as ep, paciente as p, examen as e WHERE ep.id_examen_paciente='$id_examen_paciente' AND
      ep.id_paciente=p.id_paciente AND ep.id_examen=e.id_examen and ep.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'and
       p.id_sucursal='$id_sucursal'");
  $datos = _fetch_array($query);
  $nombre_paciente = $datos["nombrep"]." ".$datos["apellidop"];
  $sexo = $datos["sexo"];
  $id_paciente = $datos["id_paciente"];
  $fecha_nacimiento = $datos["fecha_nacimiento"];
  $edad=calcular_edad($fecha_nacimiento);
  $edad_meses=nmeses($fecha_nacimiento,$fecha_hoy);
  $id_doctor=$datos["id_doctor"];
  $fecha_cobro = $datos["fecha_cobro"];
  $hora_cobro = hora($datos["hora_cobro"]);
  $nombre_examen = $datos["nombre_examen"];
  $valores= $datos["formulario"];
  $examen_id = $datos['examen_id'];
  $formulario = explode("#", $valores);
	//permiso del script
	if ($links!='NOT' || $admin=='1' ){
?>

            <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content  animated fadeInRight" >
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h3 class="text-navy"><i class="fa fa-plus-circle fa-1x"></i> <b><?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                          <form name="formulario" id="formulario">
                            <div class="row" >
                                <div class="form-group col-lg-4">
                                    <label>Paciente</label>
                                    <input type="text" placeholder="" class="form-control" id="paciente" name="paciente" value="<?php echo $nombre_paciente ?>" readonly>

                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Sexo</label>
                                    <input type="text" placeholder="" class="form-control" id="sexo" name="sexo" value="<?php echo $sexo ?>" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                  <?php
                                  if ($edad_meses>12) {

                                    ?>
                                    <label>Años</label>
                                    <input type="text" placeholder="" class="form-control" id="edad" name="edad" value="<?php echo $edad ?>" readonly>
                                    <?php
                                  }
                                  else{

                                    ?>
                                    <label>Meses</label>
                                    <input type="text" placeholder="" class="form-control" id="edad" name="edad" value="<?php echo $edad_meses ?>" readonly>
                                    <?php
                                  }

                                  ?>

                                </div>      
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label>Fecha Cobro</label>
                                    <input type="text" placeholder="" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha_cobro?>" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Hora Cobro</label>
                                    <input type="text" placeholder="" class="form-control" id="hora" name="hora" value="<?php echo $hora_cobro?>" readonly>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Examen</label>
                                    <input type="text" placeholder="" class="form-control" id="examen" name="examen" value="<?php echo $nombre_examen?>" readonly>
                                </div>
                              
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label>Referido por</label>
                                      <select class="form-control select" name="referido" id="referido">
                                        <option value="">Seleccione</option>
                                          <?php
                                              $sqld = "SELECT * FROM doctor WHERE id_sucursal='$id_sucursal'";
                                              $resul=_query($sqld);
                                              while($consulta = _fetch_array($resul))
                                              {
                                                  echo "<option value='".$consulta["id_doctor"]."'";
                                                  if($consulta["id_doctor"] == $id_doctor)
                                                  {
                                                      echo " selected ";
                                                  }
                                                  echo">".$consulta["nombre"]." ".$consulta["apellido"]."</option>";
                                              }
                                          ?>
                                        </select>
                                </div>

                               <div class="form-group col-lg-4" >
                                  <label>Realizado por</label>
                                  <select class="form-control select" name="realizado" id="realizado">
                                  <option value="">Seleccionar</option>
                                  <?php
                                            $consulta = _query("SELECT * FROM empleado WHERE id_sucursal='$id_sucursal' AND estado=1 AND id_tipo_empleado=2");
                                            while($row_consulta = _fetch_array($consulta))
                                            {
                                            echo "<option value='".$row_consulta["id_empleado"]."' >".$row_consulta["nombre"]."</option>";
                                            //echo " >".$row_tipo_examen["nombre"]."</option>";
                                            }
                                    ?>
                                  </select>
                                </div>       
                              <div class="form-group col-lg-4" >
                                  <label>Muestra</label>
                                  <select class="form-control select" name="muestra" id="muestra">
                                  <option value="">Seleccionar</option>
                                    <?php
                                              $consulta_m = _query("SELECT * FROM muestra WHERE id_sucursal='$id_sucursal'");
                                              while($row_consulta_m = _fetch_array($consulta_m))
                                              {
                                              echo "<option value='".$row_consulta_m["id_muestra"]."' >".$row_consulta_m["muestra"]."</option>";

                                              }
                                      ?>
                                  </select>
                              </div>
                            </div>
                            <?php
                              if($examen_id == 698 || $examen_id == 699 || $examen_id == 700 ){
                                  ?>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group has-info single-line">
                                                    <label>Hora de Muestra </label>
                                                    <input type="text" placeholder="HH:mm" class="form-control timepicker ui-timepicker-input" id="hora_de_muestra" name="hora_de_muestra" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group has-info single-line">
                                                    <label>Hora de Reporte </label>
                                                    <input type="text" placeholder="HH:mm" class="form-control timepicker ui-timepicker-input" id="hora_de_reporte" name="hora_de_reporte" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  <?php
                              }
                            ?>


                            <div class="row"><br>
                              <div class="col-lg-12">
                             <div class="alert alert-warning text-center">
                               <h3>Resultados del examen</h3>
                             </div>
                              </div>
                            </div>
                            <div >

                              <table class="table table-bordered table-hover" id="tabla">
                             <thead>
                               <tr>
                               <th class="col-lg-3">PARAMETRO</th>
                               <th class="col-lg-3">RESULTADO</th>
                               <th class="col-lg-3">UNIDAD DE MEDIDA</th>
                               <th class="col-lg-3">VALORES DE REFERENCIA</th>
                               </tr>
                             </thead>
                             <tbody id="exa">
                               <?php
                               for($i=0; $i<(count($formulario)-1); $i++)
                               {
                                 $campos_valores= explode("|", $formulario[$i]);
                                 $valor_individuales= explode(";", $campos_valores[2]);
                                 $valor_referencia_sel="";
                                 $tamanio=count($campos_valores);
                                 if($tamanio==5){
                                   if($campos_valores[3]=="s"){
                                     echo "<tr style='height:35px;' class='s info'><td class='seccion' colspan='4'>".$campos_valores[0]."</td></tr>";
                                   }
                                   else{
                                     for($e=0; $e<(count($valor_individuales)-1); $e++)
                                     {

                                       $evaluacion= explode(":", $valor_individuales[$e],4);
                                       if($evaluacion[0]=="" AND $evaluacion[1]=="" AND $evaluacion[2]=="" AND $evaluacion[3]==""){
                                         $valor_referencia_sel="";
                                       }else{
                                         if($evaluacion[0]=="UNISEX"){
                                           if($edad>=$evaluacion[1] && $evaluacion[2]>$edad)
                                           {
                                             $valor_referencia_sel=$evaluacion[3];
                                           }

                                         }
                                         else{
                                           if($evaluacion[0]==$sexo)
                                           {
                                             if($edad>=$evaluacion[1] && $evaluacion[2]>$edad)
                                             {
                                               $valor_referencia_sel=$evaluacion[3];
                                             }

                                           }

                                         }


                                       }

                                     }
                                      echo "<tr style='height:35px;' class='fila p'> <td class='parametro'>".$campos_valores[0]."</td> <td class='tex resultado'>".$campos_valores[4]."</td> <td class='unidades'>".$campos_valores[1]."</td> <td class='valores_referencia'>".$valor_referencia_sel."</td></tr>";

                                   }

                                 }
                                 if($tamanio<=4){
                                   if($campos_valores[3]=="s"){
                                     echo "<tr style='height:35px;' class='s info'><td class='seccion' colspan='4'>".$campos_valores[0]."</td></tr>";
                                   }
                                   else{
                                     for($e=0; $e<(count($valor_individuales)-1); $e++)
                                     {

                                       $evaluacion= explode(":", $valor_individuales[$e],4);
                                       if($evaluacion[0]=="" AND $evaluacion[1]=="" AND $evaluacion[2]=="" AND $evaluacion[3]==""){
                                         $valor_referencia_sel="";
                                       }else{
                                         if($evaluacion[0]=="UNISEX"){
                                           if($edad>=$evaluacion[1] && $evaluacion[2]>$edad)
                                           {
                                             $valor_referencia_sel=$evaluacion[3];
                                           }

                                         }
                                         else{
                                           if($evaluacion[0]==$sexo)
                                           {
                                             if($edad>=$evaluacion[1] && $evaluacion[2]>$edad)
                                             {
                                               $valor_referencia_sel=$evaluacion[3];
                                             }

                                           }

                                         }


                                       }

                                     }
                                      echo "<tr style='height:35px;' class='fila p'> <td class='parametro'>".$campos_valores[0]."</td> <td class='tex resultado'></td> <td class='unidades'>".$campos_valores[1]."</td> <td class='valores_referencia'>".$valor_referencia_sel."</td></tr>";

                                   }

                                 }





                               }
                                ?>

                             </tbody>
                              </table>

                            </div>
                            <div class="row">
                              <div class="form-actions col-lg-12">
                                <input type="hidden" name="process" id="process" value="<?php echo $proceso; ?>">
                                <input type="hidden" name="id_paciente" id="id_paci" value="<?php echo $id_paciente; ?>">
                                <input type="hidden" name="id_examen_paciente" id="id_examen_paciente" value="<?php echo $id_examen_paciente; ?>">
                                <input type="submit" id="sig" name="sig" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>
                                <input type="hidden" name="id_examen_evaluar" id='id_examen_evaluar' value="<?php echo $examen_id; ?>">
                              </div>
                            </div>

                          </form>
                    </div>
                    <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
          						<div class='modal-dialog'>
          							<div class='modal-content'></div><!-- /.modal-content -->
          						</div><!-- /.modal-dialog -->
          					</div><!-- /.modal -->
                </div>
            </div>

        </div>
      </div>

<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_examen_paciente.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}

function agregar_examen_paciente()
{
  $proceso=$_POST["process"];
  $id_examen_paciente = $_POST["id_examen_paciente"];
  $id_paciente = $_POST["id_paciente"];
  $id_examen_evaluar = $_POST['id_examen_evaluar'];
  
  $id_empleado=$_POST["id_empleado"];
  $id_doctor=$_POST["id_doctor"];
  $resultado=$_POST["formulario"];
  $fecha_cobro=$_POST["fecha_cobro"];
  $id_muestra=$_POST["id_muestra"];
  date_default_timezone_set('America/El_Salvador');
  $fecha_examen=date("Y-m-d");
  $hora_examen=date("H:i:s");
  $estado_relizado="Hecho";
  $estado_impresion="Pendiente";
  $id_sucursal=$_SESSION["id_sucursal"];
  if($id_doctor == ""){
    $id_doctor = 0;
  }
  $hoy = date("Y-m-d");
  $hora_de_muestra = _hora_media_encode($_POST['hora_de_muestra']);
  $hora_de_reporte = _hora_media_encode($_POST['hora_de_reporte']);

    $sql_result=_query("SELECT id_examen_paciente FROM examen_paciente WHERE  fecha_cobro='$fecha_cobro' AND fecha_realizado='$fecha_examen' AND hora_realizado='$hora_examen'AND id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);

    $table = 'examen_paciente';
    if($id_examen_evaluar == 698 || $id_examen_evaluar == 699 || $id_examen_evaluar == 700 ){
      $form_data = array (
        'fecha_realizado' => $fecha_examen,
        'hora_realizado' => $hora_examen,
        'id_empleado' => $id_empleado,
        'id_doctor' => $id_doctor,
        'resultados' => $resultado,
        'estado_realizado' => $estado_relizado,
        'estado_impresion' => $estado_impresion,
        'id_sucursal'=>$id_sucursal,
        'id_muestra'=>$id_muestra,
        'fecha_muestra' => $hoy,
        'hora_muestra' => $hora_de_muestra,
        'fecha_reporte' => $hoy,
        'hora_reporte' =>$hora_de_reporte
        );
    }
    else{
      $form_data = array (
        'fecha_realizado' => $fecha_examen,
        'hora_realizado' => $hora_examen,
        'id_empleado' => $id_empleado,
        'id_doctor' => $id_doctor,
        'resultados' => $resultado,
        'estado_realizado' => $estado_relizado,
        'estado_impresion' => $estado_impresion,
        'id_sucursal'=>$id_sucursal,
        'id_muestra'=>$id_muestra
        );
    }
    
    $where_clause = "id_examen_paciente ='".$id_examen_paciente."'and id_sucursal='".$id_sucursal."'";
    if($numrows == 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        //$insertarA = _update($tableA,$form_dataA, $where_clauseA);
        if($insertar)
        {
          $sql_result1=_query("SELECT id_expediente FROM expediente WHERE  id_sucursal='$id_sucursal' and id_paciente='$id_paciente'");
          $numrows1=_num_rows($sql_result1);

          $table1 = 'expediente';
          $form_data1 = array (
          'ultima_visita' => $fecha_examen
          );
          $where_clause1 = "id_paciente ='".$id_paciente."'and id_sucursal='".$id_sucursal."'";
          if($numrows1 == 1)
          {
              $insertar1 = _update($table1,$form_data1, $where_clause1);
              //$insertarA = _update($tableA,$form_dataA, $where_clauseA);
              if($insertar1)
              {

                 $xdatos['typeinfo']='Success';
                 $xdatos['msg']='Examen paciente ingresado correctamente!';
                 $xdatos['process']='insert';
                 $xdatos['hey']=$proceso;
              }else
              {
                 $xdatos['typeinfo']='Error';
                 $xdatos['msg']='Examen paciente no pudo ser ingresado!';
    	        }
          }else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='El paciente no tiene expediente!';

          }
      }
      else
      {
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Examen paciente no pudo ser ingresado!';
      }

    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Examen paciente ya fue ingresado!';
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
          case 'edited_admin':
          	agregar_examen_paciente();
          	break;

          case 'edited_modal':
            agregar_examen_paciente();
            break;
        }
    }
}
?>
