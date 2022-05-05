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

$sql3="SELECT pr.descripcion, pr.id_producto,pre.nombre,pp.descripcion as descrip,SUM(ie.cantidad) as suma  FROM  examen_paciente as ep
inner JOIN examen as e ON (ep.id_examen=e.id_examen )
inner JOIN insumo_examen as ie ON (ep.id_examen=ie.id_examen)
inner JOIN producto as pr ON (ie.id_producto=pr.id_producto )
inner JOIN presentacion as pre ON (ie.id_presentacion=pre.id_presentacion)
inner JOIN presentacion_producto as pp ON (pre.id_presentacion=pp.id_presentacion )
Where ep.id_examen>0  AND ep.examen_paciente_nulo= 0 AND ep.fecha_realizado  between '$desde' and '$hasta' and ep.id_sucursal='$id_sucursal'and
pr.id_sucursal='$id_sucursal'and e.id_sucursal='$id_sucursal'  and pp.id_sucursal='$id_sucursal' and  ie.id_sucursal='$id_sucursal' group by pr.id_producto";

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

$result3 = _query($sql3);

$set_y = 33-$plus;
$pdf->SetFont('courier','',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode("INSUMOS UTILIZADOS"),0,1,'C');
$pdf->SetFont('courier','',10);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

$set_y = 40-$plus;
$set_x = 8;

$pdf->SetFont('courier','',9);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(10,5,utf8_decode("N°"),1,1,'L',0);
$pdf->SetXY($set_x+10, $set_y+5);
$pdf->Cell(80,5,"PRODUCTO",1,1,'L',0);
$pdf->SetXY($set_x+90, $set_y+5);
$pdf->Cell(40,5,utf8_decode("PRESENTACIÓN"),1,1,'L',0);
$pdf->SetXY($set_x+130, $set_y+5);
$pdf->Cell(40,5,utf8_decode("DESCRIPCIÓN"),1,1,'L',0);
$pdf->SetXY($set_x+170, $set_y+5);
$pdf->Cell(30,5,"CANTIDAD",1,1,'L',0);

$set_y = 50 - $plus;
$page = 0;
$j=0;

$result3=_query($sql3);
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
      $pdf->Cell(215,5,utf8_decode("INSUMOS UTILIZADOS"),0,1,'C');
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

      $set_y = 45-$plus;
      $set_x = 8;
      $j=0;
      $mm = 0;
    }
    $producto=$row3["descripcion"];
    $presentacion=$row3["nombre"];
    $descripcion=$row3["descrip"];
    $cantidad=$row3["suma"];

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(10,5,$n,1,1,'L');
    $pdf->SetXY($set_x+10, $set_y+$mm);
    $pdf->Cell(80,5,utf8_decode($producto),1,1,'L');
    $pdf->SetXY($set_x+90, $set_y+$mm);
    $pdf->Cell(40,5,utf8_decode(Mayu($presentacion)),1,1,'L');
    $pdf->SetXY($set_x+130, $set_y+$mm);
    $pdf->Cell(40,5,utf8_decode(Mayu($descripcion)),1,1,'L');
    $pdf->SetXY($set_x+170, $set_y+$mm);
    $pdf->Cell(30,5,utf8_decode(Mayu($cantidad)),1,1,'L');

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
