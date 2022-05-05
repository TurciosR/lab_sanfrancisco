<?php
/**
 * This file is part of the OpenPyme1.
 * 
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 * 
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$pdf=new FPDF('P','mm', 'Legal');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,5);

//EXTRAYENDO DATOS DE LA EMPRESA
$id_sucursal=$_SESSION["id_sucursal"];
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


$sql2 = _query("SELECT dep.* FROM departamento as dep WHERE dep.id_departamento='$depa'");
$row2 = _fetch_array($sql2);
$departamento = $row2["nombre_departamento"];

$sql3 = _query("SELECT mun.* FROM municipio as mun WHERE mun.id_municipio='$muni'");
$row3 = _fetch_array($sql3);
$municipio = $row3["nombre_municipio"];



$pdf->AddPage();
$pdf->Image($logo,180,2,24,24);
$set_x = 0;
$set_y = 5;
//Encabezado General
$pdf->SetFont('Arial','',10);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
$pdf->SetFont('Arial','',7.5);
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

$set_y = 35-$plus;
$pdf->SetFont('Arial','',9);
$pdf->SetXY($set_x+10, $set_y);
$pdf->Cell(20,5,utf8_decode("Paciente:"),0,1,'L');
$pdf->Line(30,$set_y+3.5,153, $set_y+3.5);
$pdf->SetXY($set_x+155, $set_y);
$pdf->Cell(20,5,utf8_decode("Edad:"),0,1,'L');
$pdf->Line(167,$set_y+3.5,205, $set_y+3.5);
$pdf->SetXY($set_x+10, $set_y+7);
$pdf->Cell(20,5,utf8_decode("Médico:"),0,1,'L');
$pdf->Line(25,$set_y+10,120, $set_y+10);
$pdf->SetXY($set_x+120, $set_y+7);
$pdf->Cell(20,5,utf8_decode("Día:"),0,1,'L');
$pdf->Line(130,$set_y+10,150, $set_y+10);
$pdf->SetXY($set_x+150, $set_y+7);
$pdf->Cell(20,5,utf8_decode("Mes:"),0,1,'L');
$pdf->Line(160,$set_y+10,180, $set_y+10);
$pdf->SetXY($set_x+180, $set_y+7);
$pdf->Cell(20,5,utf8_decode("Año:"),0,1,'L');
$pdf->Line(190,$set_y+10,205, $set_y+10);

$set_x = 10;
$set_y = 50 - $plus;
$pdf->SetFillColor(224,224,224);
$pdf->SetFont('Arial','B',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(195,5,utf8_decode("EXAMENES INDIVIDUALES"),0,1,'C', True);
$set_y = 50 - $plus;
$page = 0;
$j=0;



$sql4="SELECT c.id_categoria, c.nombre_categoria FROM categoria as c
INNER JOIN examen as e ON(e.id_categoria=c.id_categoria)
WHERE e.id_sucursal='$id_sucursal' AND e.id_examen>0 GROUP BY c.nombre_categoria ORDER BY c.nombre_categoria ASC";
$result3=_query($sql4);
$pdf->SetFont('Arial','',6);
$set_y = 50 - $plus;
$pdf->Line(10,$set_y+5,10, $set_y+295);
$pdf->Line(58.75,$set_y+5,58.75, $set_y+295);
$pdf->Line(107.5,$set_y+5,107.5, $set_y+295);
$pdf->Line(156.25,$set_y+5,156.25, $set_y+295);
$pdf->Line(205,$set_y+5,205, $set_y+295);
$pdf->Line(10,$set_y+5,205, $set_y+5);
$pdf->Line(10,$set_y+295,205, $set_y+295);
$filas=300;
$cat=0;
$col=0;
$set_x = 10;
$set_y = 55 - $plus;

if(_num_rows($result3)>0)
{
  $n = 1;
  while($row3 = _fetch_array($result3))
  {

    $id_cat = $row3["id_categoria"];
    $nombre_c = $row3["nombre_categoria"];
    $sql_pres = _query("SELECT e.id_examen, e.nombre_examen from examen as e
                        INNER JOIN categoria as c ON(c.id_categoria=e.id_categoria)
                        WHERE e.id_categoria='$id_cat' AND e.id_sucursal='$id_sucursal' ORDER BY e.nombre_examen ASC");
    $npres = _num_rows($sql_pres);
    $pdf->SetXY($set_x, $set_y+$mm+$cat);
    $pdf->Cell(48.75,5,utf8_decode(Mayu(($nombre_c))),0,1,'L', True);
    while ($rowb = _fetch_array($sql_pres)){
      $salto = 222;
      if($j==$salto)
      {
        $page++;
        $pdf->AddPage();
        $pdf->Image($logo,180,2,24,24);
        $set_x = 10;
        $set_y = 50 - $plus;
        $pdf->Line(10,$set_y+5,10, $set_y+295);
        $pdf->Line(58.75,$set_y+5,58.75, $set_y+295);
        $pdf->Line(107.5,$set_y+5,107.5, $set_y+295);
        $pdf->Line(156.25,$set_y+5,156.25, $set_y+295);
        $pdf->Line(205,$set_y+5,205, $set_y+295);
        $pdf->Line(10,$set_y+5,205, $set_y+5);
        $pdf->Line(10,$set_y+295,205, $set_y+295);
        $set_x = 0;
        $set_y = 5;
        //Encabezado General
        $pdf->SetFont('Arial','B',10);
        $pdf->SetXY($set_x, $set_y);
        $pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
        $pdf->SetFont('Arial','',7.5);
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

        $set_y = 35-$plus;
        $pdf->SetFont('Arial','',9);
        $pdf->SetXY($set_x+10, $set_y);
        $pdf->Cell(20,5,utf8_decode("Paciente:"),0,1,'L');
        $pdf->Line(30,$set_y+3.5,153, $set_y+3.5);
        $pdf->SetXY($set_x+155, $set_y);
        $pdf->Cell(20,5,utf8_decode("Edad:"),0,1,'L');
        $pdf->Line(167,$set_y+3.5,205, $set_y+3.5);
        $pdf->SetXY($set_x+10, $set_y+7);
        $pdf->Cell(20,5,utf8_decode("Médico:"),0,1,'L');
        $pdf->Line(25,$set_y+10,120, $set_y+10);
        $pdf->SetXY($set_x+120, $set_y+7);
        $pdf->Cell(20,5,utf8_decode("Día:"),0,1,'L');
        $pdf->Line(130,$set_y+10,150, $set_y+10);
        $pdf->SetXY($set_x+150, $set_y+7);
        $pdf->Cell(20,5,utf8_decode("Mes:"),0,1,'L');
        $pdf->Line(160,$set_y+10,180, $set_y+10);
        $pdf->SetXY($set_x+180, $set_y+7);
        $pdf->Cell(20,5,utf8_decode("Año:"),0,1,'L');
        $pdf->Line(190,$set_y+10,205, $set_y+10);

        $set_x = 10;
        $set_y = 50 - $plus;
        $pdf->SetFillColor(224,224,224);
        $pdf->SetFont('Arial','B',11);
        $pdf->SetXY($set_x, $set_y);
        $pdf->Cell(195,5,utf8_decode("EXAMENES INDIVIDUALES"),0,1,'C', True);
        $pdf->SetFont('Arial','',6);
        $col=0;
        $j=0;
        $mm=0;
        $filas=300;
      }
      $div=5;
      $nombre_exa = $rowb["nombre_examen"];
      $pdf->SetXY($set_x+0.7, $set_y+$mm+$cat+$div+1);
      $pdf->Cell(3,3,"",1,1,'L');
      $pdf->SetXY($set_x+4, $set_y+$mm+$cat+$div);
      $pdf->Cell(38.5,5,utf8_decode(Mayu(substr($nombre_exa,0,33))),0,1,'L');
      $mm+=5;
      $j++;
      $filas-=5;
      if($filas<=10){
        $set_y=50 - $plus;
        $cat=0;
        $mm=0;
        $filas=300;
        $col+=1;
        if($col==1){
          $set_x=58.75;
        }
        if($col==2){
          $set_x=107.5;
        }
        if($col==3){
          $set_x=156.25;
        }
      }
    }
    $cat+=10;
    if($filas==300){
      $mm=$mm-5;
    }else{
      $pdf->Line($set_x+5,$set_y+$mm+$cat+$div-7.5,$set_x+45, $set_y+$mm+$cat+$div-7.5);
    }
    $filas-=10;
  }

}
ob_clean();
$pdf->Output("reporte_examen_categoria.pdf","I");
?>
