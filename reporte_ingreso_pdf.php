<?php
//error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


$pdf=new fPDF('L','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddFont("latin","","latin.php");
$id_sucursal = $_SESSION["id_sucursal"];
$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";

$resultado_emp=_query($sql_empresa);
$row_emp=_fetch_array($resultado_emp);
$tel1 = $row_emp['telefono1'];
$telefonos="TEL. ".$tel1;
$nrc = $row_emp['nrc'];
$nit = $row_emp['nit'];
$nombre_lab = utf8_decode(Mayu(utf8_decode(trim($row_emp["nombre_lab"]))));

$direccion = utf8_decode(Mayu(trim($row_emp["direccion"])));
$email=$row_emp["email"];
$depa = $row_emp["id_departamento"];
$muni = $row_emp["id_municipio"];
$telefono1 = $row_emp["telefono1"];
$telefono2 = $row_emp["telefono2"];
//$fecha_r = MD($_REQUEST["fecha"]);
//$turno_r = $_REQUEST["turno"];
$sql2 = _query("SELECT dep.* FROM departamento as dep WHERE dep.id_departamento='$depa'");
$row2 = _fetch_array($sql2);
$departamento = $row2["nombre_departamento"];

$sql3 = _query("SELECT mun.* FROM municipio as mun WHERE mun.id_municipio='$muni'");
$row3 = _fetch_array($sql3);
$municipio = $row3["nombre_municipio"];
//$id_producto = $_REQUEST["id_producto"];
$fini = $_REQUEST["desde"];
$ffin = $_REQUEST["hasta"];

$logo =  $row_emp["logo"];//"img/logo_sys.jpg";
$impress = "Impreso: ".date("d/m/Y");
//$title = $nombre_a;
$titulo = "REPORTE INGRESOS";
if($fini!="" && $ffin!="")
{
  list($a,$m,$d) = explode("-", $fini);
  list($a1,$m1,$d1) = explode("-", $ffin);
  if($a ==$a1)
  {
    if($m==$m1)
    {
      if($d==$d1){
        $fech="$d DE ".meses($m)." DEL $a";
      }
      else{
        $fech="DEL $d AL $d1 DE ".meses($m)." DE $a";
      }

    }
    else
    {
      $fech="DEL $d DE ".meses($m)." AL $d1 DE ".meses($m1)." DE $a";
    }
  }
  else
  {
    $fech="DEL $d DE ".meses($m)." DEL $a AL $d1 DE ".meses($m1)." DE $a1";
  }
}
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->Image($logo,9,4,35,35);
$set_x = 0;
$set_y = 6;

//Encabezado General
$pdf->SetFont('Arial','',16);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(280,5,utf8_decode("LABORATORIO CLÍNICO ".$nombre_lab),0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->SetFont('Arial','',10);
//$pdf->Cell(280,5,utf8_decode(ucwords(Minu("Depto. ".utf8_decode($departamento)))),0,1,'C');
if($id_sucursal==3){
  $pdf->SetXY($set_x+65, $set_y+10);
  $pdf->Multicell(150,5,str_replace(" Y ", " y ",ucwords(utf8_decode($direccion))).", San Miguel",0,'C',0);
  $pdf->SetXY($set_x, $set_y+19);
  $pdf->Cell(280,3.5,Mayu("PBX: ".$telefono1),0,1,'C');
  $pdf->SetXY($set_x, $set_y+22);
  $pdf->Cell(280,5,utf8_decode("E-mail: ".$email),0,1,'C');
  $pdf->SetFont('Arial','',14);
  $pdf->SetXY($set_x, $set_y+28);
  $pdf->Cell(280,6,$titulo,0,1,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->SetXY($set_x, $set_y+33);
  $pdf->Cell(280,6,$fech,0,1,'C');
}else{
  $pdf->SetXY($set_x+160, $set_y+10);
  $pdf->Cell(75,2,str_replace(" Y ", " y ",ucwords(utf8_decode($direccion))).", San Miguel",0,'C',0);
  $pdf->SetXY($set_x, $set_y+14);
  $pdf->Cell(280,3.5,Mayu("PBX: ".$telefono1),0,1,'C');
  $pdf->SetXY($set_x, $set_y+18);
  $pdf->Cell(280,5,utf8_decode("E-mail: ".$email),0,1,'C');
  $pdf->SetFont('Arial','',14);
  $pdf->SetXY($set_x, $set_y+24);
  $pdf->Cell(280,6,$titulo,0,1,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->SetXY($set_x, $set_y+28);
  $pdf->Cell(280,6,$fech,0,1,'C');
}




$set_y = 50;
$set_x = 10;
$pdf->SetFont('Arial','',11);
$pdf->SetXY($set_x, $set_y-5);
$pdf->Cell(18,5,utf8_decode("INGRESOS"),0,1,'C',0);
$pdf->SetFont('Arial','',9);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(15,5,utf8_decode("N°"),'B',1,'L',0);
$pdf->SetXY($set_x+15, $set_y);
$pdf->Cell(18,5,"FECHA",'B',1,'L',0);
$pdf->SetXY($set_x+33, $set_y);
$pdf->Cell(156,5,"CONCEPTO",'B',1,'L',0);
$pdf->SetXY($set_x+189, $set_y);
$pdf->Cell(16,5,"CAJA",'B',1,'C',0);
$pdf->SetXY($set_x+205, $set_y);
$pdf->Cell(20,5,"APERTURA",'B',1,'C',0);
$pdf->SetXY($set_x+225, $set_y);
$pdf->Cell(15,5,"TURNO",'B',1,'C',0);
$pdf->SetXY($set_x+240, $set_y);
$pdf->Cell(18,5,"MONTO",'B',1,'R',0);
$set_y = 55;
$page = 0;
$j=0;
$mm = 0;
$i = 0;
$salto=28;
//$result = _query($sql);
//$pdf->SetTextColor(0,0,0);
$sqll = "SELECT * FROM mov_caja WHERE entrada='1' AND  date(fecha) BETWEEN '$fini' AND '$ffin' AND idtransace='0' AND id_sucursal='$id_sucursal'";
//$j+=1;
//$mm+=5;
if($j==1)
{
  //Fecha de impresion y numero de pagina
  $pdf->SetXY(4, 210);
  $pdf->Cell(10, 0.4,$titulo, 0, 0, 'L');
  $pdf->SetXY(70, 210);
  $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
  $pdf->SetXY(258, 210);
  $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
}
//$set_y = 55;
$resul = _query($sqll);
if(_num_rows($resul)>0)
  {
    $suma_m3=0;
    $count3=1;
    while($row2 = _fetch_array($resul))
    {
      if($page==0)
      $salto = 28;
      else
      $salto = 30;
      if($j>=$salto)
      {
        $page++;
        $pdf->AddPage();
        $pdf->SetFont('Latin','',10);
        $pdf->Image($logo,9,4,30,30);
        //$pdf->Image($logo1,245,8,24.5,24.5);
        $set_x = 0;

        $set_y = 5;
        $mm=0;
        //Encabezado General
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(15,5,utf8_decode("N°"),'B',1,'L',0);
        $pdf->SetXY($set_x+15, $set_y+$mm);
        $pdf->Cell(18,5,"FECHA",'B',1,'L',0);
        $pdf->SetXY($set_x+33, $set_y+$mm);
        $pdf->Cell(66,5,"RECIBE",'B',1,'L',0);
        $pdf->SetXY($set_x+99, $set_y+$mm);
        $pdf->Cell(20,5,"DILIGENCIA",'B',1,'C',0);
        $pdf->SetXY($set_x+119, $set_y+$mm);
        $pdf->Cell(70,5,"CONCEPTO",'B',1,'L',0);
        $pdf->SetXY($set_x+189, $set_y+$mm);
        $pdf->Cell(16,5,"CAJA",'B',1,'C',0);
        $pdf->SetXY($set_x+205, $set_y+$mm);
        $pdf->Cell(20,5,"APERTURA",'B',1,'C',0);
        $pdf->SetXY($set_x+225, $set_y+$mm);
        $pdf->Cell(15,5,"TURNO",'B',1,'C',0);
        $pdf->SetXY($set_x+240, $set_y+$mm);
        $pdf->Cell(18,5,"MONTO",'B',1,'R',0);
        $set_x = 10;
        $set_y = 10;
        $j=0;
        $i=0;
        $pdf->SetFont('Arial','',8);
      }
      $fecha2 = $row2["fecha"];
      $ultcosto2 = "cuenta";
      $empleado2 = $row2["nombre_recibe"];
      $diligencia2 = $row2["tipo_delige"];
      $concepto2 = $row2["concepto"];
      $pdf->SetFont('Arial','',8);
      if(strlen($concepto2)>50)
      {
        $h2=ceil($concepto2/50);
        $concep2=divtextlin($concepto2,50,$h2);
        $nn2 = 0;
        foreach ($concep2 as $val2)
        {
          if($j>=$salto)
          {
            $page++;
            $pdf->AddPage();
            $set_x = 10;
            $set_y = 10;
            $i=0;
            $j=0;
            $pdf->SetFont('Arial','',8);
          }
        }
          $pdf->SetXY($set_x+33, $set_y+$mm+$nn2);
          $pdf->Cell(156,5,utf8_decode(ucFirst(strtolower($val2))),0,1,'L',0);
          $nn2 += 5;
          $j++;

        $lwidth2 = $nn2;
        $pdf->SetXY($set_x+119, $set_y+$mm);
        $pdf->Cell(70,$lwidth2,"",0,1,'C',0);
      }
      else
      {
        $lwidth2=5;
        $pdf->SetXY($set_x+33, $set_y+$mm);
        $pdf->Cell(156,$lwidth2,utf8_decode(ucFirst(strtolower($concepto2))),0,1,'L',0);
        $j++;
      }
      $monto3 = $row2["valor"];
      $caja2 = $row2["caja"];
      $apertura2 = $row2["id_apertura"];
      $turno2 = $row2["turno"];
      $pdf->SetXY($set_x, $set_y+$mm);
      $pdf->Cell(15,$lwidth2,$count3,0,1,'L',0);
      $pdf->SetXY($set_x+15, $set_y+$mm);
      $pdf->Cell(18,$lwidth2,ED($fecha2),0,1,'L',0);

      $pdf->SetXY($set_x+189, $set_y+$mm);
      $pdf->Cell(16,$lwidth2,$caja2,0,1,'C',0);
      $pdf->SetXY($set_x+205, $set_y+$mm);
      $pdf->Cell(20,$lwidth2,$apertura2,0,1,'C',0);
      $pdf->SetXY($set_x+225, $set_y+$mm);
      $pdf->Cell(15,$lwidth2,$turno2,0,1,'C',0);
      $pdf->SetXY($set_x+240, $set_y+$mm);
      $pdf->Cell(18,$lwidth2,"$".number_format($monto3,2,".",","),0,1,'R',0);
      $count3+=1;
      $mm+=$lwidth2;
      $pdf->line($set_x, $set_y+$mm,$set_x+258, $set_y+$mm);
      if($j==1)
      {
      //Fecha de impresion y numero de pagina
      $pdf->SetXY(4, 210);
      $pdf->Cell(10, 0.4,$titulo, 0, 0, 'L');
      $pdf->SetXY(70, 210);
      $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
      $pdf->SetXY(258, 210);
      $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
      }
      $suma_m3+=$monto3;
    }
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(240,5,"TOTAL",0,1,'C',0);
  $pdf->SetXY($set_x+240, $set_y+$mm);
  $pdf->Cell(18,5,"$".number_format($suma_m3,2,".",","),0,1,'R',0);
  $mm+= 10 ;
  $j+= 2 ;
}else{
  $mm+= 10 ;
  $j+= 2 ;
}
/////////////////FINALIZA LOS INGRESOS

//INICIA VENTAS CONTADO EXAMENES

$sqlv=_query("SELECT f.tipo_doc, f.anulada, f.num_fact_impresa, f.total, f.anulada, f.hora_cobro,
               c.nombre AS cliente, e.nombre AS cajero,f.fecha,f.turno,f.caja
               FROM cobro AS f
               LEFT JOIN cliente AS c ON f.cliente=c.id_cliente
               LEFT JOIN usuario AS e ON f.id_empleado=e.id_usuario
               WHERE f.finalizada=1
               AND f.tipo_pago!='CRE'
               AND f.tipo_pago='CON'
               AND f.tipo_pago NOT LIKE '%PEN%'
               AND f.id_sucursal='$id_sucursal'
               AND f.fecha BETWEEN '$fini' AND '$ffin'
               ORDER BY f.tipo_doc ASC, f.num_fact_impresa ASC");

 $pdf->SetFont('Arial','',11);
 $pdf->SetXY($set_x+7, $set_y-5+$mm);
 $pdf->Cell(18,5,utf8_decode("VENTAS CONTADO"),0,1,'C',0);
 $pdf->SetFont('Arial','',9);
 $pdf->SetXY($set_x, $set_y+$mm);
 $pdf->Cell(15,5,utf8_decode("N°"),'B',1,'L',0);
 $pdf->SetXY($set_x+15, $set_y+$mm);
 $pdf->Cell(20,5,"FECHA",'B',1,'L',0);
 $pdf->SetXY($set_x+35, $set_y+$mm);
 $pdf->Cell(20,5,"HORA",'B',1,'L',0);
 $pdf->SetXY($set_x+55, $set_y+$mm);
 $pdf->Cell(20,5,"TIPO DOC",'B',1,'L',0);
 $pdf->SetXY($set_x+75, $set_y+$mm);
 $pdf->Cell(20,5,"NUM DOC",'B',1,'L',0);
 $pdf->SetXY($set_x+95, $set_y+$mm);
 $pdf->Cell(105,5,"CLIENTE",'B',1,'L',0);
 $pdf->SetXY($set_x+200, $set_y+$mm);
 $pdf->Cell(20,5,"CAJA",'B',1,'C',0);
 $pdf->SetXY($set_x+220, $set_y+$mm);
 $pdf->Cell(20,5,"TURNO",'B',1,'C',0);
 $pdf->SetXY($set_x+240, $set_y+$mm);
 $pdf->Cell(18,5,"MONTO",'B',1,'R',0);

 $j+=1;
 $mm+=5;
 if($j==1)
 {
   //Fecha de impresion y numero de pagina
   $pdf->SetXY(4, 210);
   $pdf->Cell(10, 0.4,$titulo, 0, 0, 'L');
   $pdf->SetXY(70, 210);
   $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
   $pdf->SetXY(258, 210);
   $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
 }
 if(_num_rows($sqlv)>0)
 {
   $suma_m=0;
   $count=1;
   while($row = _fetch_array($sqlv))
   {
     if($page==0)
     $salto = 28;
     else
     $salto = 30;
     if($j>=$salto){
       $page++;
       $pdf->AddPage();
       $pdf->SetFont('Latin','',10);
       $pdf->Image($logo,9,4,25,25);
       $set_x = 10;
       $set_y = 10;
       $pdf->SetFont('Arial','',16);
       $pdf->SetXY($set_x, $set_y);
       $pdf->Cell(280,5,utf8_decode("LABORATORIO CLÍNICO MIGUELEÑO"),0,1,'C');
       $pdf->SetFont('Arial','',12);
       $pdf->SetXY($set_x, $set_y+5);
       $pdf->Cell(280,6,$titulo,0,1,'C');
       $pdf->SetFont('Arial','',10);
       $pdf->SetXY($set_x, $set_y+10);
       $pdf->Cell(280,6,$fech,0,1,'C');
       $set_x = 10;
       $set_y = 30;
       $mm=0;
       //Encabezado General
       $pdf->SetFont('Arial','',9);
       $pdf->SetXY($set_x, $set_y+$mm);
       $pdf->Cell(15,5,utf8_decode("N°"),'B',1,'L',0);
       $pdf->SetXY($set_x+15, $set_y+$mm);
       $pdf->Cell(20,5,"FECHA",'B',1,'L',0);
       $pdf->SetXY($set_x+35, $set_y+$mm);
       $pdf->Cell(20,5,"HORA",'B',1,'L',0);
       $pdf->SetXY($set_x+55, $set_y+$mm);
       $pdf->Cell(20,5,"TIPO DOC",'B',1,'L',0);
       $pdf->SetXY($set_x+75, $set_y+$mm);
       $pdf->Cell(20,5,"NUM DOC",'B',1,'L',0);
       $pdf->SetXY($set_x+95, $set_y+$mm);
       $pdf->Cell(105,5,"CLIENTE",'B',1,'L',0);
       $pdf->SetXY($set_x+200, $set_y+$mm);
       $pdf->Cell(20,5,"CAJA",'B',1,'C',0);
       $pdf->SetXY($set_x+220, $set_y+$mm);
       $pdf->Cell(20,5,"TURNO",'B',1,'C',0);
       $pdf->SetXY($set_x+240, $set_y+$mm);
       $pdf->Cell(18,5,"MONTO",'B',1,'R',0);
       $set_x = 10;
       $set_y = 35;
       $j=0;
       $i=0;
       $pdf->SetFont('Arial','',8);
     }
     $fecha = $row["fecha"];
     $hora = $row["hora_cobro"];
     $tipo = $row["tipo_doc"];
     $num_doc = $row["num_fact_impresa"];
     $cliente = $row["cliente"];
     $box = $row["caja"];
     $turn = $row["turno"];
     $mont = $row["total"];
     $pdf->SetFont('Arial','',8);
     $pdf->SetXY($set_x, $set_y+$mm);
     $pdf->Cell(15,5,$count,0,1,'L',0);
     $pdf->SetXY($set_x+15, $set_y+$mm);
     $pdf->Cell(20,5,ED($fecha),0,1,'L',0);
     $pdf->SetXY($set_x+35, $set_y+$mm);
     $pdf->Cell(20,5,hora($hora),0,1,'L',0);
     $pdf->SetXY($set_x+55, $set_y+$mm);
     $pdf->Cell(20,5,$tipo,0,1,'L',0);
     $pdf->SetXY($set_x+75, $set_y+$mm);
     $pdf->Cell(25,5,$num_doc,0,1,'L',0);
     $pdf->SetXY($set_x+95, $set_y+$mm);
     $pdf->Cell(105,5,$cliente,0,1,'L',0);
     $pdf->SetXY($set_x+200, $set_y+$mm);
     $pdf->Cell(20,5,$box,0,1,'C',0);
     $pdf->SetXY($set_x+220, $set_y+$mm);
     $pdf->Cell(20,5,$turn,0,1,'C',0);
     $pdf->SetXY($set_x+240, $set_y+$mm);
     $pdf->Cell(18,5,"$".number_format($mont,2,".",","),0,1,'R',0);
     $count+=1;
     $mm+=5;
     $pdf->line($set_x, $set_y+$mm,$set_x+258, $set_y+$mm);
     if($j==1)
     {
       //Fecha de impresion y numero de pagina
       $pdf->SetXY(4, 210);
       $pdf->Cell(10, 0.4,$titulo, 0, 0, 'L');
       $pdf->SetXY(70, 210);
       $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
       $pdf->SetXY(258, 210);
       $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
     }
      $suma_m+=$mont;
      $j++;
   }
   $pdf->SetXY($set_x, $set_y+$mm);
   $pdf->Cell(240,5,"TOTAL",0,1,'C',0);
   $pdf->SetXY($set_x+240, $set_y+$mm);
   $pdf->Cell(18,5,"$".number_format($suma_m,2,".",","),0,1,'R',0);
   $mm+= 5;
   $j+= 1;
 }

//FINALIZA VENTAS CONTADO EXAMENES


///////////INICIA LOS TOTALES
$mm+= 10;
$j+=2;
if($j>=$salto)
{
  $page++;
  $pdf->AddPage();
  $pdf->SetFont('Latin','',10);
  $pdf->Image($logo,9,4,25,25);
  $set_x = 0;
  $set_y = 6;
  //Encabezado General
  $pdf->SetFont('Arial','',16);
  $pdf->SetXY($set_x, $set_y);
  $pdf->Cell(280,5,utf8_decode("LABORATORIO CLÍNICO MIGUELEÑO"),0,1,'C');
  $pdf->SetFont('Arial','',12);
  $pdf->SetXY($set_x, $set_y+5);
  $pdf->Cell(280,6,$titulo,0,1,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->SetXY($set_x, $set_y+10);
  $pdf->Cell(280,6,$fech,0,1,'C');
  $set_x = 10;
  $set_y = 25;
  $i=0;
  $j=0;
  $mm=0;
}
$pdf->SetFont('Arial','',10);
$pdf->SetXY($set_x+222, $set_y+$mm);
$pdf->Cell(18,5,"INGRESOS CAJA:",0,1, 'R',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".number_format($suma_m3,2,".",",")." +",0,1,'R',0);
$mm+= 5;
$j+=1;
$pdf->SetXY($set_x+222, $set_y+$mm);
$pdf->Cell(18,5,"VENTAS CONTADO:",0,1,'R',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".number_format($suma_m,2,".",",")." =",0,1,'R',0);
$mm+= 5;
$j+=1;
$pdf->Line($set_x+240, $set_y+$mm,$set_x+260 , $set_y+$mm);
$pdf->SetXY($set_x+200, $set_y+$mm);
$pdf->Cell(40,5,"TOTAL INGRESO:",0,1,'R',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".(number_format($suma_m+$suma_m3,2,".",",")),0,1,'C',0);
/*$mm+= 5;
$j+=1;*/
if($j==1)
{
  //Fecha de impresion y numero de pagina
  $pdf->SetXY(4, 210);
  $pdf->Cell(10, 0.4,$titulo, 0, 0, 'L');
  $pdf->SetXY(70, 210);
  $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
  $pdf->SetXY(258, 210);
  $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
}
ob_clean();
$pdf->Output("reporte_ingreso.pdf","I");
