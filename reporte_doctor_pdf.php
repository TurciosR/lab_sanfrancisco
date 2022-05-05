<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('memory_limit', '500M');
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


$pdf=new FPDF('P','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
//$pdf->AddFont("arial","","arial.php");
$desde = MD($_REQUEST["desde"]);
$hasta = MD($_REQUEST["hasta"]);
$doctor = $_REQUEST["id_doctor"];
$id_sucursal=$_SESSION["id_sucursal"];
///COULTAS
$sql = _query("SELECT su.* FROM sucursal as su WHERE  su.id_sucursal='$id_sucursal' ");
$row = _fetch_array($sql);
$sql10 = _query("SELECT CONCAT(nombre,' ',apellido) as nombre, comision FROM doctor  WHERE id_doctor='$doctor'");
$row10 = _fetch_array($sql10);
$nom_Doc=$row10["nombre"];
$comi=($row10["comision"])/100;

$logo = $row["logo"];
$telefono1 = $row["telefono1"];
$telefono2 = $row["telefono2"];
$depa = $row["id_departamento"];
$muni = $row["id_municipio"];
$nombre_lab = $row["nombre_lab"];
$direccion=$row["direccion"];
$ncr=$row["nrc"];
$nit=$row["nit"];
$email=$row["email"];

if($desde!="" && $hasta!="")
{
    list($a,$m,$d) = explode("-", $desde);
    list($a1,$m1,$d1) = explode("-", $hasta);
    if($a ==$a1)
    {
        if($m==$m1)
        {
            $rango="DEL $d AL $d1 DE ".meses($m)." DE $a";
        }
        else
        {
            $rango="DEL $d DE ".meses($m)." AL $d1 DE ".meses($m1)." DE $a";
        }
    }
    else
    {
        $rango="DEL $d DE ".meses($m)." DEL $a AL $d1 DE ".meses($m1)." DE $a1";
    }
}
$sql2 = _query("SELECT dep.* FROM departamento as dep WHERE dep.id_departamento='$depa'");
$row2 = _fetch_array($sql2);
$departamento = $row2["nombre_departamento"];

$sql3 = _query("SELECT mun.* FROM municipio as mun WHERE dep.id_municipio='$muni'");
$row3 = _fetch_array($sql3);
$municipio = $row3["nombre_municipio"];


$pdf->AddPage();
$pdf->Image($logo,10,2,24,24);
$set_x = 0;
$set_y = 5;
//Encabezado General
$pdf->SetFont('arial','',10);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
$pdf->SetXY($set_x, $set_y+5);
$pdf->SetFont('arial','',8);
$pdf->Cell(215,5,utf8_decode(Mayu(utf8_decode($direccion))),0,1,'C');
$pdf->SetXY($set_x, $set_y+10);
$pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');

$plus=5;
$pdf->SetXY($set_x, $set_y+20-$plus);
$pdf->Cell(215,5,utf8_decode(Mayu("PBX: ".$telefono1." | ".$telefono2)),0,1,'C');
$pdf->SetXY($set_x, $set_y+25-$plus);
$pdf->Cell(215,5,utf8_decode(Mayu("EMAIL: ".$email)),0,1,'C');

$set_y = 28-$plus;
$pdf->Line(10,$set_y+7,205, $set_y+7);
$pdf->Line(10,$set_y+8,205, $set_y+8);


$set_y = 38-$plus;
$pdf->SetFont('arial','',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode("REPORTE POR DOCTOR"),0,1,'C');
$pdf->SetFont('arial','',10);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');
$pdf->SetXY($set_x+7, $set_y+10);
$pdf->Cell(77,5,utf8_decode(Mayu("NOMBRE: DR. ".$nom_Doc)),0,1,'L');

$set_y = 50-$plus;
$set_x = 8;

$pdf->SetFont('arial','',8);
//$pdf->Cell(15,5,utf8_decode("MES"),1,1,'L',0);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(5,5,utf8_decode("N°"),1,1,'L',0);
$pdf->SetXY($set_x+5, $set_y+5);
$pdf->Cell(15,5,utf8_decode("HORA"),1,1,'L',0);
$pdf->SetXY($set_x+20, $set_y+5);
$pdf->Cell(20,5,utf8_decode("FECHA"),1,1,'L',0);
$pdf->SetXY($set_x+40, $set_y+5);
$pdf->Cell(50,5,utf8_decode("PACIENTE"),1,1,'L',0);
$pdf->SetXY($set_x+90, $set_y+5);
$pdf->Cell(10,5,utf8_decode("EDAD"),1,1,'L',0);
$pdf->SetXY($set_x+100, $set_y+5);
$pdf->Cell(75,5,utf8_decode("EXAMENES"),1,1,'L',0);
$pdf->SetXY($set_x+175, $set_y+5);
$pdf->Cell(10,5,utf8_decode("P UNI"),1,1,'L',0);
$pdf->SetXY($set_x+185, $set_y+5);
$pdf->Cell(15,5,utf8_decode("TOTAL"),1,1,'L',0);

$usuario=$_SESSION["usuario"];
$sql3="SELECT CONCAT(p.nombre,' ',p.apellido) as Paciente, p.fecha_nacimiento,c.fecha,c.hora_cobro, c.id_cobro
FROM cobro as c INNER JOIN paciente as p ON(p.id_paciente=c.id_paciente)
LEFT JOIN examen_paciente as prd ON (prd.id_cobro=c.id_cobro)
WHERE c.id_sucursal='$id_sucursal' AND prd.id_doctor='$doctor' AND c.fecha
BETWEEN '$desde' AND '$hasta' GROUP by Paciente DESC ";


$set_y = 60 - $plus;
$page = 0;
$j=0;
$linea = 0;
$linea_acumulada = 0;
$subtotal=0;

$w=1;
$ws=29;
$result3=_query($sql3);
if(_num_rows($result3)>0)
{
  while($row3 = _fetch_array($result3)){
    $paciente=substr($row3["Paciente"],0,50);
    $edad=edad($row3["fecha_nacimiento"]);
    $fecha_cobro=ED($row3["fecha"]);
    $mes = substr($fecha_cobro,3,2);
    $month = nombremes($mes);
    $hora_cobro=HORA($row3["hora_cobro"]);
    $id_cobro = $row3["id_cobro"];

    $nombreUSU = substr($row3["usuario"],0,5);
    $sql_cobro =_query("SELECT examen.nombre_examen,detalle_cobro.precio,detalle_cobro.n_precio,detalle_cobro.cortesia
    FROM cobro JOIN detalle_cobro ON detalle_cobro.id_cobro=cobro.id_cobro
    JOIN examen ON examen.id_examen=detalle_cobro.id_examen
    WHERE cobro.id_cobro='$id_cobro' AND cobro.id_sucursal='$id_sucursal' AND cobro.id_cobro group by detalle_cobro.id_examen");
    $npres = _num_rows($sql_cobro);
    $n=0;
    $p = 0;
    $s = 0;

    if (($w+$npres)>$ws) {
      // code...
      $page++;
      $pdf->AddPage();
      $pdf->Image($logo,250,2,24,24);
      $set_x=0;
      $set_y=5;
      $linea=0;
      $j = 0;
      $w=1;
      //Encabezado General
      $pdf->SetFont('arial','',10);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->SetFont('arial','',8);
      $pdf->Cell(215,5,utf8_decode(Mayu(utf8_decode($direccion))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+10);
      $pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');

      $plus=5;
      $pdf->SetXY($set_x, $set_y+20-$plus);
      $pdf->Cell(215,5,utf8_decode(Mayu("PBX: ".$telefono1." | ".$telefono2)),0,1,'C');
      $pdf->SetXY($set_x, $set_y+25-$plus);
      $pdf->Cell(215,5,utf8_decode(Mayu("EMAIL: ".$email)),0,1,'C');

      $set_y = 28-$plus;
      $pdf->Line(10,$set_y+7,205, $set_y+7);
      $pdf->Line(10,$set_y+8,205, $set_y+8);


      $set_y = 38-$plus;
      $pdf->SetFont('arial','',11);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode("REPORTE POR DOCTOR"),0,1,'C');
      $pdf->SetFont('arial','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');
      $set_y = 45-$plus;
      $set_x = 8;
      $pdf->SetFont('arial','',7);
      //$pdf->Cell(15,5,utf8_decode("MES"),1,1,'L',0);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(5,5,utf8_decode("N°"),1,1,'L',0);
      $pdf->SetXY($set_x+5, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("HORA"),1,1,'L',0);
      $pdf->SetXY($set_x+20, $set_y+5);
      $pdf->Cell(20,5,utf8_decode("FECHA"),1,1,'L',0);
      $pdf->SetXY($set_x+40, $set_y+5);
      $pdf->Cell(50,5,utf8_decode("PACIENTE"),1,1,'L',0);
      $pdf->SetXY($set_x+90, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("EDAD"),1,1,'L',0);
      $pdf->SetXY($set_x+100, $set_y+5);
      $pdf->Cell(75,5,utf8_decode("EXAMENES"),1,1,'L',0);
      $pdf->SetXY($set_x+175, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("P UNI"),1,1,'L',0);
      $pdf->SetXY($set_x+185, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("TOTAL"),1,1,'L',0);
      $set_x = 8;
      $set_y = 50;

    }

    while ($rowb = _fetch_array($sql_cobro)){
      if($page==0){
      $salto = 42;}
      else{
      $salto = 45;}
    if($w==$ws){
      $page++;
      $pdf->AddPage();
      $pdf->Image($logo,250,2,24,24);
      $set_x=0;
      $set_y=5;
      $linea=0;
      $j = 0;
      $w=1;
      //Encabezado General
      $pdf->SetFont('arial','',10);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->SetFont('arial','',8);
      $pdf->Cell(215,5,utf8_decode(Mayu(utf8_decode($direccion))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+10);
      $pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');

      $plus=5;
      $pdf->SetXY($set_x, $set_y+20-$plus);
      $pdf->Cell(215,5,utf8_decode(Mayu("PBX: ".$telefono1." | ".$telefono2)),0,1,'C');
      $pdf->SetXY($set_x, $set_y+25-$plus);
      $pdf->Cell(215,5,utf8_decode(Mayu("EMAIL: ".$email)),0,1,'C');

      $set_y = 28-$plus;
      $pdf->Line(10,$set_y+7,205, $set_y+7);
      $pdf->Line(10,$set_y+8,205, $set_y+8);


      $set_y = 38-$plus;
      $pdf->SetFont('arial','',11);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode("REPORTE POR DOCTOR"),0,1,'C');
      $pdf->SetFont('arial','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');
      $set_y = 45-$plus;
      $set_x = 8;
      $pdf->SetFont('arial','',7);
      //$pdf->Cell(15,5,utf8_decode("MES"),1,1,'L',0);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(5,5,utf8_decode("N°"),1,1,'L',0);
      $pdf->SetXY($set_x+5, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("HORA"),1,1,'L',0);
      $pdf->SetXY($set_x+20, $set_y+5);
      $pdf->Cell(20,5,utf8_decode("FECHA"),1,1,'L',0);
      $pdf->SetXY($set_x+40, $set_y+5);
      $pdf->Cell(50,5,utf8_decode("PACIENTE"),1,1,'L',0);
      $pdf->SetXY($set_x+90, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("EDAD"),1,1,'L',0);
      $pdf->SetXY($set_x+100, $set_y+5);
      $pdf->Cell(75,5,utf8_decode("EXAMENES"),1,1,'L',0);
      $pdf->SetXY($set_x+175, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("P UNI"),1,1,'L',0);
      $pdf->SetXY($set_x+185, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("TOTAL"),1,1,'L',0);
      $set_x = 8;
      $set_y = 50;
    }

    $nombre_examen=$rowb["nombre_examen"];
    $preciou =$rowb["precio"];
    $pdf->SetXY($set_x+100, $set_y+$linea+$p);
    $pdf->Cell(75,5,utf8_decode($nombre_examen),1,1,'L',0);
    $w++;
    $pdf->SetXY($set_x+175, $set_y+$linea+$p);
    $pdf->Cell(10,5,utf8_decode($preciou),1,1,'L',0);
    $subtotal = round($preciou,4);
      $total+=$subtotal;

    $p += 5;
    $s += 1;
    $n+=1;
    }
    $j++;
    $pdf->SetXY($set_x, $set_y+$linea);
    $pdf->Cell(5,5*$s,utf8_decode($n),1,1,'L',0);
    $pdf->SetXY($set_x+5, $set_y+$linea);
    $pdf->Cell(15,5*$s,utf8_decode($hora_cobro),1,1,'L',0);
    $pdf->SetXY($set_x+20, $set_y+$linea);
    $pdf->Cell(20,5*$s,utf8_decode($fecha_cobro),1,1,'L',0);
    $pdf->SetXY($set_x+40, $set_y+$linea);
    $pdf->Cell(50,5*$s,utf8_decode($paciente),1,1,'L',0);
    $pdf->SetXY($set_x+90, $set_y+$linea);
    $pdf->Cell(10,5*$s,utf8_decode($edad),1,1,'L',0);
    $pdf->SetXY($set_x+185, $set_y+$linea);
    $pdf->Cell(15,5*$s,utf8_decode($total),1,1,'C',0);
    $cc = (5 * $s);
    $linea += (5*$s);
    $linea_acumulada += $linea;
    $subtotal=0;
    $tot+=$total;
    $total=0;

    if($j==1)
    {
      $pdf->SetXY(4, 270);
      $pdf->Cell(10, 0.4,Date("Y-m-d"), 0, 0, 'L');
      $pdf->SetXY(193, 270);
      $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
    }


  }
  $pdf->Line($set_x,$set_y+$linea,$set_x+200,$set_y+$linea);
  $pdf->SetXY($set_x, $set_y+$linea);
  $pdf->Cell(245,5,utf8_decode("TOTAL"),0,1,'L',0);
  $pdf->SetXY($set_x+180, $set_y+$linea);
  $pdf->Cell(20,5,utf8_decode("$".number_format(($tot), 2)),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$linea+5,$set_x+200,$set_y+$linea+5);
  $pdf->SetXY($set_x, $set_y+$linea+5);
  $pdf->Cell(245,5,utf8_decode("COMISION: ".$comi*100 ."%"),0,1,'L',0);
  $pdf->SetXY($set_x+180, $set_y+$linea+5);
  $pdf->Cell(20,5,utf8_decode("$".number_format(($tot*$comi), 2)),0,1,'R',0);
}
ob_clean();
$pdf->Output("reporte_doctor_pdf","I");
?>
