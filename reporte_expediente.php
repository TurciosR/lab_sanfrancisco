<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


$pdf=new FPDF('P','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,5);
  //$pdf->AddFont("courier","","courier.php");
$id_expediente = $_REQUEST["id_expediente"];
$id_sucursal=$_SESSION["id_sucursal"];
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
///COULTAS

$sql = _query("SELECT pa.*, xp.n_expediente,xp.fecha_creada, xp.ultima_visita,xp.id_paciente , mu.logo, mu.telefono1, mu.telefono2, mu.id_municipio, mu.id_departamento, mu.nombre_lab
  FROM paciente as pa, expediente as xp, sucursal as mu WHERE pa.id_paciente=xp.id_paciente AND xp.id_expediente='$id_expediente' and pa.id_sucursal='$id_sucursal' and xp.id_sucursal='$id_sucursal' and mu.id_sucursal='$id_sucursal' ");
$row = _fetch_array($sql);
function calcular_edad($fecha)
{
  list($A,$m,$d)=explode("-",$fecha);
  return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
$n_expediente = $row['n_expediente'];
$fechaC = $row["fecha_creada"];
$fechaU = $row["ultima_visita"];
$logo = $row["logo"];
$id_paciente = $row["id_paciente"];

$telefono1 = $row["telefono1"];
$telefono2 = $row["telefono2"];
$nombre = $row["nombre"];
$apellido = $row["apellido"];
$direccion = $row["direccion"];
$telefono = $row["telefono"];
$sexo = $row["sexo"];
$dui = $row["dui"];
$fecha_nacimiento = $row["fecha_nacimiento"];
$correo = $row["correo"];

$depa = $row["id_departamento"];
$muni = $row["id_municipio"];
$nombre_lab = $row["nombre_lab"];

if(@empty($row["foto"])){
  $foto = "img/default.png";
}
else{
  $foto = $row["foto"];
}

$sql2 = _query("SELECT dep.* FROM departamento as dep WHERE dep.id_departamento='$depa'");
$row2 = _fetch_array($sql2);
$departamento = $row2["nombre_departamento"];

$sql3 = _query("SELECT mun.* FROM municipio as mun WHERE dep.id_municipio='$muni'");
$row3 = _fetch_array($sql3);
$municipio = $row3["nombre_municipio"];

if($correo != ""){
  $correo_x = utf8_decode(Mayu("Correo Electronico: ").$correo);
  }
else{
  $correo_x = "";
  }
if($fecha_nacimiento!=""){
  list($a,$m,$d) = explode("-", $fecha_nacimiento);
  $rango=num2letras($d)." DE ".meses($m)." DE ".num2letras($a);
  }
if($fechaC!=""){
  list($a,$m,$d) = explode("-", $fechaC);
  $rango1=num2letras($d)." DE ".meses($m)." DE ".num2letras($a);
  }
if($fechaU!=""){
  list($a,$m,$d) = explode("-", $fechaU);
  $rango2=num2letras($d)." DE ".meses($m)." DE ".num2letras($a);
  }


  $pdf->AddPage();
  $set_x = 10;
  $mm=0;
  $set_y = 8;
  $pdf->SetFont('courier','',10);
  $set_x = 180;
  $pdf->Image($logo,$set_x+2,$set_y+$cc,24,24);
  $set_x = 0;
  $set_y = 12;
  //Encabezado General
  $pdf->SetFont('courier','',13);
  $pdf->SetXY($set_x, $set_y-5);
  $pdf->Cell(215,6,utf8_decode("LABORATORIO CLINICO MIGUELEÑO"),0,1,'C');
  $pdf->SetXY($set_x, $set_y);
  $pdf->Cell(215,6,utf8_decode($nombre_lab),0,1,'C');
  $pdf->SetFont('courier','',11);
  $pdf->SetXY($set_x, $set_y+5);
  $pdf->Cell(215,6,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
  $pdf->SetFont('courier','',10);
  $pdf->SetXY($set_x, $set_y+10);
  $pdf->Cell(215,6,utf8_decode(Mayu("Telefono(s): ".$telefono1.", ".$telefono2)),0,1,'C');
  $pdf->SetXY($set_x, $set_y+15);
  $pdf->Cell(215,6,$email_x,0,1,'C');
  $set_x = 10;
  $pdf->Line($set_x,$set_y+21,$set_x+195, $set_y+21);
  $pdf->Line($set_x,$set_y+22,$set_x+195, $set_y+22);

  $set_y = 41;
  $set_x = 10;
  $pdf->SetFont('courier','',14);
  $pdf->SetXY($set_x, $set_y);
  $pdf->Line(10,47,205,47);//Linea debajo del nombre del expediente
  $pdf->Cell(195,6,utf8_decode(Mayu("Expediente del paciente")),0,1,'C');
  $pdf->Line(49,47,49,97);//Linea par de foto
  $pdf->Image($foto,$set_x+2,$set_y+10,35,45);//Imagen persona
  $pdf->SetFont('courier','',10);
  $pdf->SetXY($set_x+70, $set_y+8);
  $pdf->Line(49,55,205,55);//Linea par de foto
  $pdf->Cell(80,5,utf8_decode("N° de Expediente: ".$n_expediente),0,1,'L');
  $pdf->SetXY($set_x+40, $set_y+16);
  $pdf->Cell(80,5,utf8_decode("Nombre: ".Mayu($nombre."".$apellido)),0,1,'L');
  $pdf->SetXY($set_x+40, $set_y+23);
  $pdf->Cell(80,5,utf8_decode("Genero: ".Mayu($sexo)),0,1,'L');
  $pdf->SetXY($set_x+110, $set_y+23);
  $pdf->Cell(80,5,utf8_decode("Telefono: ".Mayu($telefono)),0,1,'L');
  $pdf->SetXY($set_x+40, $set_y+30);
  $pdf->Cell(80,5,utf8_decode("Dui: ".Mayu($dui)),0,1,'L');
  $pdf->SetXY($set_x+110, $set_y+30);
  $pdf->Cell(80,5,utf8_decode("Correo: ".Mayu($correo)),0,1,'L');
  $pdf->SetXY($set_x+40, $set_y+37);
  $pdf->Cell(80,5,utf8_decode("Fecha de Nacimiento: ".ED($fecha_nacimiento)),0,1,'L');
  $pdf->SetXY($set_x+110, $set_y+37);
  $pdf->Cell(80,5,utf8_decode("Edad: ".calcular_edad($fecha_nacimiento)." años"),0,1,'L');
  $pdf->SetXY($set_x+40, $set_y+44);
  $pdf->MultiCell(155,5,utf8_decode("Direccion: ".Mayu($direccion)),0,'L',0);
  $pdf->Line(10,97,205,97);//Linea abajo de foto arriba
  $pdf->Line(10,104,205,104);//Linea abajo de foto abajo
  $pdf->Line(110,97,110,104);//Linea medio
  $pdf->SetXY(12, 98);
  $pdf->Cell(80,5,utf8_decode("Fecha de Creacion: ".ED($fechaC)),0,1,'L');
  $pdf->SetXY(112, 98);
  $pdf->Cell(80,5,utf8_decode("Ultima visita: ".ED($fechaU)),0,1,'L');
  $pdf->Line(10,40,10,104);//Linea cuadro 1 izquierda
  $pdf->Line(205,40,205,104);//Linea cuadro 1 derecha
  $pdf->Line(10,40,205,40);//Linea cuadro 1 suoerior
  $pdf->Line(10,104,205,104);//Linea cuadro 1 inferior

  $pdf->Line(10,110,205,110);//Linea cuadro 2 superior
  $pdf->Line(10,110,10,124);//Linea cuadro 2 izquierda
  $pdf->Line(205,110,205,124);//Linea cuadro 2 derecha
  $pdf->Line(10,124,205,124);//Linea cuadro 2 inferior

  $set_y = 112;
  $set_x = 10;
  $pdf->SetFont('courier','',14);
  $pdf->SetXY($set_x, $set_y);
  $pdf->Cell(195,5,utf8_decode("EXÁMENES REALIZADOS"),0,1,'C');
  $pdf->SetFont('courier','',8.7);


  $pdf->SetFont('courier','',9);
  $pdf->SetXY($set_x, $set_y+7);
  $pdf->Cell(10,5,utf8_decode("N°"),1,1,'L');
  $pdf->SetXY($set_x+10, $set_y+7);
  $pdf->Cell(45,5,"EXAMEN",1,1,'L');
  $pdf->SetXY($set_x+55, $set_y+7);
  $pdf->Cell(25,5,"FECHA",1,1,'L');
  $pdf->SetXY($set_x+80, $set_y+7);
  $pdf->Cell(25,5,"HORA",1,1,'L');
  $pdf->SetXY($set_x+105, $set_y+7);
  $pdf->Cell(40,5,"RESPONSABLE",1,1,'L');
  $pdf->SetXY($set_x+145, $set_y+7);
  $pdf->Cell(50,5,"DOCTOR",1,1,'L');

  $set_y = 117;
  $page = 0;
  $j=0;
  $mm = 7;
  $i = 1;
  $number=1;
  $sql4="SELECT ep.fecha_examen,ep.hora_examen, CONCAT(dr.nombre,' ',dr.apellido) as doctor, CONCAT(em.nombre,' ',em.apellido) as empleado,e.nombre_examen
  FROM examen_paciente as ep JOIN examen as e ON(e.id_examen=ep.id_examen) LEFT JOIN doctor as dr ON(dr.id_doctor=ep.id_doctor)
  LEFT JOIN empleado as em ON (em.id_empleado=ep.id_empleado) LEFT JOIN paciente as pa ON(pa.id_paciente=ep.id_paciente)
  WHERE ep.id_paciente='$id_paciente'  AND ep.estado_realizado='Hecho' AND ep.id_sucursal='$id_sucursal' ";
  $result4=_query($sql4);

  if(_num_rows($result4)>0){
      while($row4 = _fetch_array($result4))
      {
        if($page==0)
            $salto = 27;
        else
            $salto = 43;
        if($number==$salto){
          $page++;
          $pdf->AddPage();
          $set_x = 10;
          $mm=30;
          $set_y = 8;
          $pdf->SetFont('courier','',10);
          $set_x = 180;
          $pdf->Image($logo,$set_x+2,$set_y+$cc,24,24);
          $set_x = 0;
          $set_y = 12;
          //Encabezado General
          $pdf->SetFont('courier','',13);
          $pdf->SetXY($set_x, $set_y);
          $pdf->Cell(215,6,utf8_decode(Mayu("Laboratorio Clinico")),0,1,'C');
          $pdf->SetFont('courier','',11);
          $pdf->SetXY($set_x, $set_y+5);
          $pdf->Cell(215,6,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
          $pdf->SetFont('courier','',10);
          $pdf->SetXY($set_x, $set_y+10);
          $pdf->Cell(215,6,utf8_decode(Mayu("Telefono(s): ".$telefono1.", ".$telefono2)),0,1,'C');
          $pdf->SetXY($set_x, $set_y+15);
          $pdf->Cell(215,6,$email_x,0,1,'C');
          $set_x = 10;
          $pdf->Line($set_x,$set_y+21,$set_x+195, $set_y+21);
          $pdf->Line($set_x,$set_y+22,$set_x+195, $set_y+22);
          $set_x = 10;
          $set_y = 12;
          $j=0;
          //  $primera=true;
        }

        $examen=$row4["nombre_examen"];
        $fechaE=ED($row4["fecha_examen"]);
        $horaE=hora($row4["hora_examen"]);
        $responsablen=$row4["empleado"];
        $docn=$row4["doctor"];

        /*if($primera==true){
          $pdf->Line(10,41,205,41);//Linea debajo numero
        }*/
        $pdf->SetFont('courier','',7);
        $pdf->Cell(10,5,$number,1,1,'L');
        $pdf->SetXY($set_x+10, $set_y+$mm);
        $pdf->Cell(45,5,substr(utf8_decode($examen),0,30),1,1,'L');
        $pdf->SetXY($set_x+55, $set_y+$mm);
        $pdf->Cell(25,5,$fechaE,1,1,'C');
        $pdf->SetXY($set_x+80, $set_y+$mm);
        $pdf->Cell(25,5,utf8_decode($horaE),1,1,'C');
        $pdf->SetXY($set_x+105, $set_y+$mm);
        $pdf->Cell(40,5,utf8_decode(Mayu($responsablen)),1,1,'L');
        $pdf->SetXY($set_x+145, $set_y+$mm);
        $pdf->Cell(50,5,substr(utf8_decode(Mayu($docn)),0,32),1,1,'L');

        /*$num = strlen(utf8_decode($examen));
        $mma = ceil($num/30) * 5;
        $num1 = strlen(utf8_decode(Mayu($docn)));
        $mma1 = ceil($num1/32) * 5;
        if($mma>$mma1){
          if($mma!=5){
            $mm+=$mma;
          }
          else{
            $mm+=5;
          }
        }
        else if($mma>$mma1){
          if($mma1!=5){
            $mm+=$mma1;
          }
          else{
            $mm+=5;
          }
        }
        else if($mma==$mma1){
          $mm+=$mma1;
        }
        else{
          $mm+=5;
          if($mma1==10 or $mma==10){
            $j++;
          }
          if($mma1==15 or $mma==15){
            $j+=2;
          }
          if($mma1==20 or $mma==20){
            $j+=3;
          }
        } */
        $mm+=5;
        //s$j+=1;
        $number++;
        if($j==1)
          {
              //Fecha de impresion y numero de pagina
              $pdf->SetXY(4, 270);
              $pdf->Cell(10, 0.4,Date("Y-m-d"), 0, 0, 'L');
              $pdf->SetXY(193, 270);
              $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
          }
        }
      }
ob_clean();
$pdf->Output("reporte_expediente.pdf","I");
?>
