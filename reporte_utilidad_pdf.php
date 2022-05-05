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

$sql4="SELECT COUNT(e.id_examen) as Cantidad, e.nombre_examen, e.precio_examen FROM examen as e
INNER JOIN examen_paciente as ep ON(e.id_examen=ep.id_examen)
WHERE ep.id_examen>0 AND ep.examen_paciente_nulo= 0 AND ep.estado_realizado='Hecho' AND ep.fecha_realizado BETWEEN '$antes' AND '$hoy' GROUP BY e.nombre_examen ORDER BY e.id_examen ASC ";

$sql5="SELECT ie.id_examen, SUM(pp.costo) as costo FROM insumo_examen as ie
INNER JOIN producto as p ON(ie.id_producto=p.id_producto)
INNER JOIN presentacion_producto as pp ON(pp.id_producto=ie.id_producto)
GROUP BY ie.id_examen ORDER BY ie.id_examen ASC ";

$pdf->AddPage();
$pdf->Image($logo,180,2,24,24);
$set_x = 0;
$set_y = 5;
//Encabezado General
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
$pdf->SetXY($set_x, $set_y+10);
$pdf->Cell(215,5,utf8_decode(Mayu("Direccion:  ".utf8_decode($direccion))),0,1,'C');
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
$pdf->Cell(215,5,utf8_decode("REPORTE DE COSTOS DE UTILIDAD"),0,1,'C');
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

$set_y = 40-$plus;
$set_x = 8;

$pdf->SetFont('courier','',8);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(10,5,utf8_decode("N"),1,1,'C',0);
$pdf->SetXY($set_x+10, $set_y+5);
$pdf->Cell(75,5,"PRODUCTO",1,1,'C',0);
$pdf->SetXY($set_x+85, $set_y+5);
$pdf->Cell(15,5,utf8_decode("CANTIDAD"),1,1,'C',0);
$pdf->SetXY($set_x+100, $set_y+5);
$pdf->Cell(20,5,"COSTO U",1,1,'C',0);
$pdf->SetXY($set_x+120, $set_y+5);
$pdf->Cell(20,5,"PRECIO U",1,1,'C',0);
$pdf->SetXY($set_x+140, $set_y+5);
$pdf->Cell(20,5,"VENTA",1,1,'C',0);
$pdf->SetXY($set_x+160, $set_y+5);
$pdf->Cell(20,5,"UTILIDAD",1,1,'C',0);
$pdf->SetXY($set_x+180, $set_y+5);
$pdf->Cell(20,5,"% UTIL",1,1,'C',0);


$set_y = 50 - $plus;
$page = 0;
$j=0;

$result3=_query($sql4);
$result4=_query($sql5);
if(_num_rows($result3)>0)
{
  $n = 1;
  while($row3 = _fetch_array($result3) AND $row4 = _fetch_array($result4))
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
      $pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
      $pdf->SetXY($set_x, $set_y+10);
      $pdf->Cell(215,5,utf8_decode(Mayu("Direccion:  ".utf8_decode($direccion))),0,1,'C');
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

      //$result3 = _query($sql3);

      $set_y = 33-$plus;
      $pdf->SetFont('courier','',11);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(215,5,utf8_decode("REPORTE DE COSTOS DE UTILIDAD"),0,1,'C');
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

      $set_y = 45-$plus;
      $set_x = 8;
      $j=0;
      $mm = 0;
    }
    $cantidad=$row3["Cantidad"];
    $nombre=$row3["nombre_examen"];
    $venta=$row3["precio_examen"];
    $ventaT= $venta*$cantidad;
    $costo1=$row4["costo"];
    $costo=round($costo1, 2, PHP_ROUND_HALF_UP);
    $costoT= $costo*$cantidad;
    $utilidad = $ventaT-$costoT;
    $util1 = ($utilidad/$costoT)*100;
    $util=round($util1, 2, PHP_ROUND_HALF_UP);

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(10,5,$n,1,1,'L');
    $pdf->SetXY($set_x+10, $set_y+$mm);
    $pdf->Cell(75,5,utf8_decode(Mayu( $nombre)),1,1,'L');
    $pdf->SetXY($set_x+85, $set_y+$mm);
    $pdf->Cell(15,5,utf8_decode(Mayu($cantidad)),1,1,'C');
    $pdf->SetXY($set_x+100, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(Mayu($costo)),1,1,'L');
    $pdf->SetXY($set_x+120, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(Mayu($venta)),1,1,'L');
    $pdf->SetXY($set_x+140, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(Mayu($ventaT)),1,1,'L');
    $pdf->SetXY($set_x+160, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(Mayu($utilidad)),1,1,'L');
    $pdf->SetXY($set_x+180, $set_y+$mm);
    $pdf->Cell(20,5,utf8_decode(Mayu($util)),1,1,'C');

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
