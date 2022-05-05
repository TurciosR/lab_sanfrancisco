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
//$antes = $_REQUEST["desde"];
//$hoy = $_REQUEST["hasta"];
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
$pdf->SetFont('Arial','',7.5);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(215,5,utf8_decode(Mayu("Laboratorio Clinico ".utf8_decode($nombre_lab))),0,1,'C');
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(215,5,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,1,'C');
$pdf->SetXY($set_x, $set_y+10);
$pdf->Cell(215,5,utf8_decode(Mayu("Direccion:  ".$direccion)),0,1,'C');
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
$pdf->SetFont('Times','',9);
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
$pdf->SetFont('Helvetica','B',11);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(195,5,utf8_decode("PERFILES"),0,1,'C', True);
$set_y = 50 - $plus;
$page = 0;
$j=0;

$sql4="SELECT p.id_perfil, p.nombre_perfil FROM perfil as p RIGHT JOIN examen_perfil as ep ON(ep.id_perfil=p.id_perfil) WHERE p.id_perfil>0 GROUP BY p.id_perfil ORDER BY p.id_perfil";
$result3=_query($sql4);
$pdf->SetFont('Helvetica','B',7);
$set_y = 55 - $plus;
$filas=195;
$cat=0;
$col=0;
if(_num_rows($result3)>0)
{
  $n = 1;
  while($row3 = _fetch_array($result3))
  {
    $id_cat = $row3["id_perfil"];
    $nombre_c = $row3["nombre_perfil"];
    $sql_pres = _query("SELECT p.id_perfil,e.nombre_examen from examen_perfil as ep INNER JOIN examen as e ON(e.id_examen=ep.id_examen)
                        INNER JOIN perfil as p ON(p.id_perfil=ep.id_perfil) WHERE ep.id_perfil='$id_cat' AND ep.id_sucursal='$id_sucursal' ORDER BY e.nombre_examen ");
    $npres = _num_rows($sql_pres);

    $pdf->SetXY($set_x, $set_y+$mm+$cat);
    $pdf->Cell(48.75,5,utf8_decode(Mayu($nombre_c)),0,1,'C', True);
    $pdf->SetFont('Helvetica','',5);
    while ($rowb = _fetch_array($sql_pres)){
      $div=5;
      $nombre_exa = $rowb["nombre_examen"];
      $pdf->SetXY($set_x+0.7, $set_y+$mm+$cat+$div+1);
      $pdf->Cell(3,3,"",1,1,'L');
      $pdf->SetXY($set_x+5, $set_y+$mm+$cat+$div);
      $pdf->Cell(38.5,5,utf8_decode(Mayu($nombre_exa)),0,1,'L');
      $mm+=5;
      $j++;
      $filas-=5;
      if($filas<=10){
        $set_y=50 - $plus;
        $cat=0;
        $mm=0;
        $filas=205;
        $col+=1;
        switch ($col) {
          case 1:
              $set_x=58.75;
            break;
          case 2:
              $set_x=107.5;
            break;
          case 3:
              $set_x=156.25;
            break;

          default:
            // code...
            break;
        }
      }
    }
    $cat+=10;
    if($filas==205){
      $mm=$mm-5;
    }else{
      $pdf->Line($set_x+5,$set_y+$mm+$cat+$div-7.5,$set_x+45, $set_y+$mm+$cat+$div-7.5);
    }
    $filas-=5;
    $pdf->SetFont('Helvetica','B',7);
    //$pdf->Line(15,$set_y+$mm+$cat+$linea,55, $set_y+$mm+$cat+$linea);
    //$linea+=2;
    //$nc+=1;
    /*if($page==0)
    $salto = 42;
    else
    $salto = 45;
    if($j==$salto){
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
      $pdf->Cell(215,5,utf8_decode("REPORTE DE EXAMENES PENDIENTES"),0,1,'C');
      $pdf->SetFont('courier','',10);
      $pdf->SetXY($set_x, $set_y+5);
      $pdf->Cell(215,5,utf8_decode(Mayu($rango)),0,1,'C');

      $set_y = 45-$plus;
      $set_x = 8;
      $j=0;
      $mm = 0;
    }*/


    /*if($j==1)
    {
      //Fecha de impresion y numero de pagina
      $pdf->SetXY(4, 270);
      $pdf->Cell(10, 0.4,Date("Y-m-d"), 0, 0, 'L');
      $pdf->SetXY(193, 270);
      $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
    }*/
  }

}
$set_x = 10;
$set_y = 50 - $plus;
$pdf->Line(10,$set_y+5,10, $set_y+225);
$pdf->Line(58.75,$set_y+5,58.75, $set_y+225);
$pdf->Line(107.5,$set_y+5,107.5, $set_y+225);
$pdf->Line(156.25,$set_y+5,156.25, $set_y+225);
$pdf->Line(205,$set_y+5,205, $set_y+225);
$pdf->Line(10,$set_y+5,205, $set_y+5);
$pdf->Line(10,$set_y+225,205, $set_y+225);
ob_clean();
$pdf->Output("reporte_insumos2.pdf","I");
?>
