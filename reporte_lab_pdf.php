<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('memory_limit', '500M');
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


$pdf=new FPDF('L','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
//$pdf->AddFont("courier","","courier.php");
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
$id_sucursal=$_SESSION["id_sucursal"];
///COULTAS
$sql = _query("SELECT su.* FROM sucursal as su WHERE  su.id_sucursal='$id_sucursal' ");
$row = _fetch_array($sql);

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
$pdf->Image($logo,250,2,24,24);
$set_x = 0;
$set_y = 5;
//Encabezado General
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(270,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(270,5,utf8_decode(Mayu(utf8_decode($direccion))),0,1,'C');
$pdf->SetXY($set_x, $set_y+10);
$pdf->Cell(270,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');

$plus=5;
$pdf->SetXY($set_x, $set_y+20-$plus);
$pdf->Cell(270,5,utf8_decode(Mayu("PBX: ".$telefono1." | ".$telefono2)),0,1,'C');
$pdf->SetXY($set_x, $set_y+25-$plus);
$pdf->Cell(270,5,utf8_decode(Mayu("EMAIL: ".$email)),0,1,'C');

$set_y = 28-$plus;
$pdf->Line(10,$set_y+7,270, $set_y+7);
$pdf->Line(10,$set_y+8,270, $set_y+8);


$set_y = 38-$plus;
$pdf->SetFont('courier','',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(270,5,utf8_decode("REGISTRO DIARIO DE CLIENTES"),0,1,'C');
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(270,5,utf8_decode(Mayu($rango)),0,1,'C');

$set_y = 45-$plus;
$set_x = 8;

$pdf->SetFont('courier','',7);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(15,5,utf8_decode("MES"),1,1,'L',0);
$pdf->SetXY($set_x+15, $set_y+5);
$pdf->Cell(15,5,utf8_decode("HORA"),1,1,'L',0);
$pdf->SetXY($set_x+30, $set_y+5);
$pdf->Cell(20,5,utf8_decode("FECHA"),1,1,'L',0);
$pdf->SetXY($set_x+50, $set_y+5);
$pdf->Cell(10,5,utf8_decode("N°"),1,1,'L',0);
$pdf->SetXY($set_x+60, $set_y+5);
$pdf->Cell(20,5,utf8_decode("CORR PACIENTE"),1,1,'L',0);
$pdf->SetXY($set_x+80, $set_y+5);
$pdf->Cell(60,5,utf8_decode("PACIENTE"),1,1,'L',0);
$pdf->SetXY($set_x+140, $set_y+5);
$pdf->Cell(10,5,utf8_decode("EDAD"),1,1,'L',0);
$pdf->SetXY($set_x+150, $set_y+5);
$pdf->Cell(20,5,utf8_decode("PROCEDENCIA"),1,1,'L',0);
$pdf->SetXY($set_x+170, $set_y+5);
$pdf->Cell(40,5,utf8_decode("EXAMENES"),1,1,'L',0);
$pdf->SetXY($set_x+210, $set_y+5);
$pdf->Cell(10,5,utf8_decode("PRECIO"),1,1,'L',0);
$pdf->SetXY($set_x+220, $set_y+5);
$pdf->Cell(10,5,utf8_decode("AUT"),1,1,'L',0);
$pdf->SetXY($set_x+230, $set_y+5);
$pdf->Cell(10,5,utf8_decode("P UNI"),1,1,'L',0);
$pdf->SetXY($set_x+240, $set_y+5);
$pdf->Cell(15,5,utf8_decode("TOTAL"),1,1,'L',0);
$pdf->SetXY($set_x+255, $set_y+5);
$pdf->Cell(10,5,utf8_decode("CAJERO"),1,1,'L',0);

$usuario=$_SESSION["usuario"];
$sql3="SELECT CONCAT(p.nombre,' ',p.apellido) as Paciente, p.fecha_nacimiento, xp.n_expediente,c.fecha,c.hora_cobro, c.id_cobro, usu.usuario
FROM examen_paciente as ep INNER JOIN detalle_cobro as dc ON(ep.id_paciente=dc.id_paciente) INNER JOIN paciente as p ON(p.id_paciente=ep.id_paciente)
INNER JOIN examen as e ON(e.id_examen=ep.id_examen) INNER JOIN cobro as c ON(c.id_cobro=dc.id_cobro) INNER JOIN empleado as em ON(em.id_empleado=c.id_empleado)
INNER JOIN expediente as xp ON(ep.id_paciente=xp.id_paciente) INNER JOIN usuario as usu ON (usu.id_usuario=c.id_usuario)
WHERE c.id_sucursal='$id_sucursal' AND c.fecha BETWEEN '$desde' AND '$hasta' GROUP BY dc.id_cobro";

$set_y = 55 - $plus;
$page = 0;
$j=0;
$linea = 0;
$linea_acumulada = 0;
$subtotal=0;
$result3=_query($sql3);
if(_num_rows($result3)>0)
{
  while($row3 = _fetch_array($result3)){
    $paciente=substr($row3["Paciente"],0,50);
    $edad=edad($row3["fecha_nacimiento"]);
    $n_exp=$row3["n_expediente"];
    $fecha_cobro=ED($row3["fecha"]);
    $mes = substr($fecha_cobro,3,2);
    $month = nombremes($mes);
    $hora_cobro=HORA($row3["hora_cobro"]);
    $id_cobro = $row3["id_cobro"];
    $nombreUSU = substr($row3["usuario"],0,5);
    $sql_cobro =_query("SELECT e.nombre_examen,dc.precio, pe.n_precio FROM examen_paciente as ep
      INNER JOIN detalle_cobro as dc ON(ep.id_paciente=dc.id_paciente)
      INNER JOIN paciente as p ON(p.id_paciente=ep.id_paciente)
      INNER JOIN examen as e ON(e.id_examen=ep.id_examen)
      INNER JOIN cobro as c ON(c.id_cobro=dc.id_cobro)
      INNER JOIN empleado as em ON(em.id_empleado=c.id_empleado)
      INNER JOIN precio_examen as pe ON (pe.id_examen=e.id_examen)
      INNER JOIN expediente as xp ON(ep.id_paciente=xp.id_paciente)
      WHERE dc.precio=pe.precio AND pe.id_sucursal=1 AND dc.id_sucursal=1  AND c.id_cobro='$id_cobro' GROUP BY dc.id_detalle_cobro");
    $npres = _num_rows($sql_cobro);
    $n=0;
    $p = 0;
    $s = 0;
    while ($rowb = _fetch_array($sql_cobro)){
      if($page==0){
      $salto = 120;}
      else{
      $salto = 120;}
    if($linea>=$salto){
      $page++;
      $pdf->AddPage();
      $pdf->Image($logo,250,2,24,24);
      $set_x=0;
      $set_y=5;
      $linea=0;
      $j = 0;
      //Encabezado General
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(270,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(270,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
      $pdf->SetXY($set_x, $set_y+10);
      $pdf->Cell(270,5,utf8_decode(Mayu(utf8_decode($direccion))),0,1,'C');

      $plus=5;
      $pdf->SetXY($set_x, $set_y+20-$plus);
      $pdf->Cell(270,5,utf8_decode(Mayu("PBX: ".$telefono1." | ".$telefono2)),0,1,'C');
      $pdf->SetXY($set_x, $set_y+25-$plus);
      $pdf->Cell(270,5,utf8_decode(Mayu("EMAIL: ".$email)),0,1,'C');
      $set_y = 28-$plus;

      $pdf->Line(10,$set_y+7,270, $set_y+7);
      $pdf->Line(10,$set_y+8,270, $set_y+8);


      $set_y = 38-$plus;
      $pdf->SetFont('courier','',11);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(270,5,utf8_decode("CORTE LABORATORIO"),0,1,'C');
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(270,5,utf8_decode(Mayu($rango)),0,1,'C');

      $set_y = 45-$plus;
      $set_x = 8;
      $pdf->SetFont('courier','',7);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("MES"),1,1,'L',0);
      $pdf->SetXY($set_x+15, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("HORA"),1,1,'L',0);
      $pdf->SetXY($set_x+30, $set_y+5);
      $pdf->Cell(20,5,utf8_decode("FECHA"),1,1,'L',0);
      $pdf->SetXY($set_x+50, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("N°"),1,1,'L',0);
      $pdf->SetXY($set_x+60, $set_y+5);
      $pdf->Cell(20,5,utf8_decode("CORR PACIENTE"),1,1,'L',0);
      $pdf->SetXY($set_x+80, $set_y+5);
      $pdf->Cell(60,5,utf8_decode("PACIENTE"),1,1,'L',0);
      $pdf->SetXY($set_x+140, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("EDAD"),1,1,'L',0);
      $pdf->SetXY($set_x+150, $set_y+5);
      $pdf->Cell(20,5,utf8_decode("PROCEDENCIA"),1,1,'L',0);
      $pdf->SetXY($set_x+170, $set_y+5);
      $pdf->Cell(40,5,utf8_decode("EXAMENES"),1,1,'L',0);
      $pdf->SetXY($set_x+210, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("PRECIO"),1,1,'L',0);
      $pdf->SetXY($set_x+220, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("AUT"),1,1,'L',0);
      $pdf->SetXY($set_x+230, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("P UNI"),1,1,'L',0);
      $pdf->SetXY($set_x+240, $set_y+5);
      $pdf->Cell(15,5,utf8_decode("TOTAL"),1,1,'L',0);
      $pdf->SetXY($set_x+255, $set_y+5);
      $pdf->Cell(10,5,utf8_decode("CAJERO"),1,1,'L',0);
      $set_x = 8;
      $set_y = 50;
    }

    $nombre_examen=substr($rowb["nombre_examen"],0,22);
    $preciou =$rowb["precio"];
    $precioC =$rowb["n_precio"];

    $pdf->SetXY($set_x+170, $set_y+$linea+$p);
    $pdf->Cell(40,5,utf8_decode($nombre_examen),1,1,'L',0);
    $pdf->SetXY($set_x+210, $set_y+$linea+$p);
    $pdf->Cell(10,5,utf8_decode($precioC),1,1,'C',0);

    $pdf->SetXY($set_x+230, $set_y+$linea+$p);
    $pdf->Cell(10,5,utf8_decode($preciou),1,1,'L',0);
    $subtotal = round($preciou,4);
    $total+=$subtotal;

    $p += 5;
    $s += 1;
    $n+=1;
    }
    $j++;
    $pdf->SetXY($set_x, $set_y+$linea);
    $pdf->Cell(15,5*$s,utf8_decode($month),1,1,'L',0);
    $pdf->SetXY($set_x+15, $set_y+$linea);
    $pdf->Cell(15,5*$s,utf8_decode($hora_cobro),1,1,'L',0);
    $pdf->SetXY($set_x+30, $set_y+$linea);
    $pdf->Cell(20,5*$s,utf8_decode($fecha_cobro),1,1,'L',0);
    $pdf->SetXY($set_x+50, $set_y+$linea);
    $pdf->Cell(10,5*$s,utf8_decode($n),1,1,'C',0);
    $pdf->SetXY($set_x+60, $set_y+$linea);
    $pdf->Cell(20,5*$s,utf8_decode($n_exp),1,1,'C',0);
    $pdf->SetXY($set_x+80, $set_y+$linea);
    $pdf->Cell(60,5*$s,utf8_decode($paciente),1,1,'L',0);
    $pdf->SetXY($set_x+140, $set_y+$linea);
    $pdf->Cell(10,5*$s,utf8_decode($edad),1,1,'L',0);
    $pdf->SetXY($set_x+150, $set_y+$linea);
    $pdf->Cell(20,5*$s,utf8_decode(""),1,1,'L',0);
    $pdf->SetXY($set_x+220, $set_y+$linea);
    $pdf->Cell(10,5*$s,utf8_decode(""),1,1,'L',0);
    $pdf->SetXY($set_x+240, $set_y+$linea);
    $pdf->Cell(15,5*$s,utf8_decode($total),1,1,'C',0);
    $pdf->SetXY($set_x+255, $set_y+$linea);
    $pdf->Cell(10,5*$s,utf8_decode($nombreUSU),1,1,'C',0);
    $cc = (5 * $s);
    $linea += (5*$s);
    $linea_acumulada += $linea;
    $subtotal=0;
    $tot+=$total;
    $total=0;
    if($j==1)
    {
      $pdf->SetXY(4, 200);
      $pdf->Cell(10, 0.4,Date("Y-m-d"), 0, 0, 'L');
      $pdf->SetXY(250, 200);
      $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
    }
  }
  $pdf->Line($set_x,$set_y+$linea,$set_x+270,$set_y+$linea);
  $pdf->SetXY($set_x, $set_y+$linea);
  $pdf->Cell(245,5,utf8_decode("TOTAL"),0,1,'L',0);
  $pdf->SetXY($set_x+230, $set_y+$linea);
  $pdf->Cell(25,5,utf8_decode("$".number_format($tot, 2)),0,1,'R',0);
}
ob_clean();
$pdf->Output("reporte_lab.pdf","I");
?>
