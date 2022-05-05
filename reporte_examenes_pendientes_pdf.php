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
$antes = $_REQUEST["desde"];
$hoy = $_REQUEST["hasta"];
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
if($antes!="" && $hoy!="")
{
    list($a,$m,$d) = explode("-", $antes);
    list($a1,$m1,$d1) = explode("-", $hoy);
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

$sql4="SELECT e.nombre_examen , p.nombre as p1, p.apellido as p2, ep.fecha_examen, ep.hora_examen, em.nombre, em.apellido
From examen_paciente as ep LEFT JOIN paciente as p ON(p.id_paciente=ep.id_paciente)
LEFT JOIN examen as e ON(e.id_examen=ep.id_examen)
LEFT JOIN empleado as em ON(em.id_empleado=ep.id_empleado)
WHERE ep.id_examen>0 AND ep.estado_realizado='Pendiente' and ep.id_sucursal='$id_sucursal' AND ep.examen_paciente_nulo= 0 AND ep.fecha_examen BETWEEN '$antes' AND '$hoy'  ";


$pdf->AddPage();
$pdf->Image($logo,180,2,24,24);
$set_x = 0;
$set_y = 5;
//Encabezado General
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode("LABORATORIO CLÍNICO MIGUELEÑO"),0,1,'C');
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,Mayu(utf8_decode($nombre_lab)),0,1,'C');
$pdf->SetXY($set_x, $set_y+10);
$pdf->Cell(215,5,utf8_decode(Mayu("Direccion:  ".$direccion.", ".$departamento)),0,1,'C');
//datos ficales
if ($nit!="" || $ncr!="")
{
  $dui_nit = "";
  if ($nit!="")
  {
    $dui_nit = "NIT: ".$nit;

  }
  if ($ncr!="")
  {
    if($dui_nit !="")
    {
      $dui_nit.= "   NRC: ".$ncr;
    }
    else
    {
      $dui_nit = "NRC: ".$ncr;
    }
  }
  $pdf->SetXY($set_x, $set_y+15);
  $pdf->Cell(215,5,Mayu($dui_nit),0,1,'C');
  $plus = 0;
}
else
{
  $plus=5;
}

$pdf->SetXY($set_x, $set_y+20-$plus);
$pdf->Cell(215,5,utf8_decode(Mayu("Telefono(s): ".$telefono1.", ".$telefono2)),0,1,'C');
$set_y = 23-$plus;

$pdf->Line(10,$set_y+7,205, $set_y+7);
$pdf->Line(10,$set_y+8,205, $set_y+8);

$set_y = 33-$plus;
$pdf->SetFont('courier','',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode("REPORTE DE EXAMENES PENDIENTES"),0,1,'C');
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

$set_y = 40-$plus;
$set_x = 8;

$pdf->SetFont('courier','',8);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(10,5,utf8_decode("N"),1,1,'C',0);
$pdf->SetXY($set_x+10, $set_y+5);
$pdf->Cell(50,5,"EXAMEN",1,1,'C',0);
$pdf->SetXY($set_x+60, $set_y+5);
$pdf->Cell(50,5,utf8_decode("PACIENTE"),1,1,'C',0);
$pdf->SetXY($set_x+110, $set_y+5);
$pdf->Cell(50,5,"ENCARGADO",1,1,'C',0);
$pdf->SetXY($set_x+160, $set_y+5);
$pdf->Cell(20,5,"FECHA",1,1,'C',0);
$pdf->SetXY($set_x+180, $set_y+5);
$pdf->Cell(20,5,"HORA",1,1,'C',0);
$pdf->SetFont('courier','',8);
$set_y = 50 - $plus;
$page = 0;
$j=0;

$result3=_query($sql4);
$result4=_query($sql5);
if(_num_rows($result3)>0)
{
  $n = 1;
  while($row3 = _fetch_array($result3))
  {
    if($page==0)
    $salto = 42;
    else
    $salto = 45;
    if($j==$salto)
    {
      $page++;
      $pdf->AddPage();
      $pdf->Image($logo,180,2,24,24);
      $set_x = 0;
      $set_y = 5;
      //Encabezado General
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode("LABORATORIO CLÍNICO MIGUELEÑO"),0,1,'C');
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,Mayu(utf8_decode($nombre_lab)),0,1,'C');
      $pdf->SetXY($set_x, $set_y+10);
      $pdf->Cell(215,5,utf8_decode(Mayu("Direccion:  ".$direccion.", ".$departamento)),0,1,'C');
      //datos ficales
      if ($nit!="" || $ncr!="")
      {
        $dui_nit = "";
        if ($nit!="")
        {
          $dui_nit = "NIT: ".$nit;

        }
        if ($ncr!="")
        {
          if($dui_nit !="")
          {
            $dui_nit.= "   NRC: ".$ncr;
          }
          else
          {
            $dui_nit = "NRC: ".$ncr;
          }
        }
        $pdf->SetXY($set_x, $set_y+15);
        $pdf->Cell(215,5,Mayu($dui_nit),0,1,'C');
        $plus = 0;
      }
      else
      {
        $plus=5;
      }
      $pdf->SetXY($set_x, $set_y+20-$plus);
      $pdf->Cell(215,5,utf8_decode(Mayu("Telefono: ".$telefono1)),0,1,'C');
      $set_y = 23-$plus;

      $pdf->Line(10,$set_y+7,205, $set_y+7);
      $pdf->Line(10,$set_y+8,205, $set_y+8);

      //$result3 = _query($sql3);

      $set_y = 33-$plus;
      $pdf->SetFont('courier','',11);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode("REPORTE DE EXAMENES PENDIENTES"),0,1,'C');
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

      $set_y = 45-$plus;
      $set_x = 8;
      $j=0;
      $mm = 0;
      $pdf->SetFont('courier','',7);
    }
    $examen = $row3["nombre_examen"];
    $paciente = $row3["p1"]." ".$row3["p2"];
    $empleado = $row3["n1"]." ".$row3["n2"];
    $fecha_examen = $row3["fecha_examen"];
    $hora_examen = $row3["hora_examen"];
    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(10,5,$n,1,1,'L');
    $pdf->SetXY($set_x+10, $set_y+$mm);
    $pdf->Cell(50,5,utf8_decode(Mayu(substr($examen,0,32))),1,1,'L');
    $pdf->SetXY($set_x+60, $set_y+$mm);
    $pdf->Cell(50,5,utf8_decode(substr($paciente,0,32)),1,1,'L');
    $pdf->SetXY($set_x+110, $set_y+$mm);
    $pdf->Cell(50,5,utf8_decode(substr($empleado,0,32)),1,1,'L');
    $pdf->SetXY($set_x+160, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(ED($fecha_examen)),1,1,'L');
    $pdf->SetXY($set_x+180, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(hora($hora_examen)),1,1,'L');

    $mm+=5;
    $n++;
    $j++;
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
$pdf->Output("reporte_insumos2.pdf","I");
?>
