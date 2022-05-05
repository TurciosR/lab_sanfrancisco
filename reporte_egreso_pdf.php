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
$titulo = "REPORTE EGRESOS";
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
$sql = "SELECT  *FROM mov_caja
WHERE salida='1'
AND  date(fecha) BETWEEN '$fini' AND '$ffin'
AND  id_sucursal='$id_sucursal'
AND tipo_doc LIKE '%VAL%'";

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
$pdf->SetXY($set_x-3, $set_y-5);
$pdf->Cell(18,5,utf8_decode("VALES"),0,1,'L',0);
$pdf->SetFont('Arial','',9);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(9,5,utf8_decode("N°"),'B',1,'L',0);
$pdf->SetXY($set_x+9, $set_y);
$pdf->Cell(17,5,"FECHA",'B',1,'L',0);
$pdf->SetXY($set_x+26, $set_y);
$pdf->Cell(44,5,"RECIBE",'B',1,'L',0);
$pdf->SetXY($set_x+70, $set_y);
$pdf->Cell(16,5,"TIP. DOC",'B',1,'C',0);
$pdf->SetXY($set_x+86, $set_y);
$pdf->Cell(18,5,utf8_decode("NÚM DOC."),'B',1,'L',0);
$pdf->SetXY($set_x+104, $set_y);
$pdf->Cell(56,5,"CONCEPTO",'B',1,'L',0);
$pdf->SetXY($set_x+160, $set_y);
$pdf->Cell(15,5,"CAJA",'B',1,'C',0);
$pdf->SetXY($set_x+175, $set_y);
$pdf->Cell(20,5,"APERTURA",'B',1,'C',0);
$pdf->SetXY($set_x+195, $set_y);
$pdf->Cell(15,5,"TURNO",'B',1,'C',0);
$pdf->SetXY($set_x+210, $set_y);
$pdf->Cell(14,5,"MONTO",'B',1,'R',0);
$pdf->SetXY($set_x+224, $set_y);
$pdf->Cell(16,5,"IVA",'B',1,'R',0);
$pdf->SetXY($set_x+240, $set_y);
$pdf->Cell(18,5,"SIN IVA",'B',1,'R',0);
//$pdf->SetTextColor(0,0,0);
$set_y = 55;
$page = 0;
$j=0;
$mm = 0;
$i = 0;
$salto=28;
$result = _query($sql);
if(_num_rows($result)>0)
{
  $entrada = 0;
  $salida = 0;
  $init = 1;
  $suma_m=0;
  $suma_iva=0;
  $suma_v=0;
  $count=1;
  while($row = _fetch_array($result))
  {
    if($page==0)
    $salto = 28;//28
    else
    $salto = 30;
    if($j>=$salto)
    {
      $page++;
      $pdf->AddPage();
      $pdf->SetFont('Latin','',10);
      $pdf->Image($logo,9,4,25,25);
      $set_x = 0;
      $set_y = 5;
      $mm=0;
      //Encabezado General
      $pdf->SetFont('Arial','',9);
      $pdf->SetXY($set_x-3, $set_y-5);
      $pdf->Cell(18,5,utf8_decode("VALES"),0,1,'L',0);
      $pdf->SetFont('Arial','',9);
      $pdf->SetXY($set_x, $set_y);
      $pdf->Cell(9,5,utf8_decode("N°"),'B',1,'L',0);
      $pdf->SetXY($set_x+9, $set_y);
      $pdf->Cell(17,5,"FECHA",'B',1,'L',0);
      $pdf->SetXY($set_x+26, $set_y);
      $pdf->Cell(44,5,"RECIBE",'B',1,'L',0);
      $pdf->SetXY($set_x+70, $set_y);
      $pdf->Cell(16,5,"TIP. DOC",'B',1,'C',0);
      $pdf->SetXY($set_x+86, $set_y);
      $pdf->Cell(18,5,utf8_decode("NÚM DOC."),'B',1,'L',0);
      $pdf->SetXY($set_x+104, $set_y);
      $pdf->Cell(56,5,"CONCEPTO",'B',1,'L',0);
      $pdf->SetXY($set_x+160, $set_y);
      $pdf->Cell(15,5,"CAJA",'B',1,'C',0);
      $pdf->SetXY($set_x+175, $set_y);
      $pdf->Cell(20,5,"APERTURA",'B',1,'C',0);
      $pdf->SetXY($set_x+195, $set_y);
      $pdf->Cell(15,5,"TURNO",'B',1,'C',0);
      $pdf->SetXY($set_x+210, $set_y);
      $pdf->Cell(14,5,"MONTO",'B',1,'R',0);
      $pdf->SetXY($set_x+224, $set_y);
      $pdf->Cell(16,5,"IVA",'B',1,'R',0);
      $pdf->SetXY($set_x+240, $set_y);
      $pdf->Cell(18,5,"SIN IVA",'B',1,'R',0);
      $set_x = 10;
      $set_y = 10;
      $j=0;
      $i=0;
      $pdf->SetFont('Arial','',8);
    }
    $id = $row["correlativo"];
    $fecha = $row["fecha"];

    $ultcosto = "cuenta";
    $empleado = $row["nombre_recibe"];
    $tipo_doc = $row["tipo_doc"];
    $concepto = $row["concepto"];
    if(strlen($concepto)>40)
    {
      $h=ceil($concepto/40);
      $concep=divtextlin($concepto,40,$h);
      $nn = 0;
      foreach ($concep as $val)
      {
        $pdf->SetXY($set_x+104, $set_y+$mm+$nn);
        $pdf->Cell(56,5,utf8_decode(ucFirst(strtolower($val))),0,1,'L',0);
        $nn += 5;
        $j++;
      }
      $lwidth = $nn;
      $pdf->SetXY($set_x+104, $set_y+$mm);
      $pdf->Cell(56,$lwidth,"",0,1,'C',0);
    }
    else
    {
      $lwidth=5;
      $pdf->SetXY($set_x+104, $set_y+$mm);
      $pdf->Cell(56,5,utf8_decode(ucFirst(strtolower($concepto))),0,1,'L',0);
      $j++;
    }
    $proveedor = $row["nombre_proveedor"];
    $monto = $row["valor"];
    $numero_doc = $row["numero_doc"];
    $caja = $row["caja"];
    $apertura = $row["id_apertura"];
    $turno = $row["turno"];
    $iva = $row["iva"];
    $pdf->SetFont('Arial','',8);
    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(9,$lwidth,$count,0,1,'L',0);
    $pdf->SetXY($set_x+9, $set_y+$mm);
    $pdf->Cell(17,$lwidth,ED($fecha),0,1,'L',0);
    $pdf->SetXY($set_x+26, $set_y+$mm);
    $pdf->Cell(44,$lwidth,utf8_decode(ucFirst(strtolower($empleado))),0,1,'L',0);
    $pdf->SetXY($set_x+70, $set_y+$mm);
    if($tipo_doc=="COF"){
      $pdf->Cell(16,$lwidth,"FAC",0,1,'L',0);
    }
    else{
      $pdf->Cell(16,$lwidth,$tipo_doc,0,1,'L',0);

    }
    $pdf->SetXY($set_x+86, $set_y+$mm);
    $pdf->Cell(18,$lwidth,$numero_doc,0,1,'L',0);
    $pdf->SetXY($set_x+160, $set_y+$mm);
    $pdf->Cell(15,$lwidth,$caja,0,1,'C',0);
    $pdf->SetXY($set_x+175, $set_y+$mm);
    $pdf->Cell(20,$lwidth,$apertura,0,1,'C',0);
    $pdf->SetXY($set_x+195, $set_y+$mm);
    $pdf->Cell(15,$lwidth,$turno,0,1,'C',0);

    $pdf->SetXY($set_x+210, $set_y+$mm);
    if($tipo_doc=="CCF"){
      $valor=$monto-$iva;
    }else{
      $valor=$monto-$iva;
    }
    $pdf->Cell(14,$lwidth,"$".number_format($monto,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+224, $set_y+$mm);
    $pdf->Cell(16,$lwidth,"$".number_format($iva,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+240, $set_y+$mm);
    $pdf->Cell(18,$lwidth,"$".number_format($valor,2,".",","),0,1,'R',0);
    //$pdf->SetXY($set_x+243, $set_y+$mm);
    //$pdf->Cell(25,5,$valor,1,1,'C',0);
    $pdf->line($set_x, $set_y+$mm,$set_x+258, $set_y+$mm);
    $count+=1;
    $mm+=$lwidth;
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
    $suma_m+=$monto;
    $suma_iva+=$iva;
    $suma_v+=$valor;
  }
  $pdf->line($set_x, $set_y+$mm,$set_x+258, $set_y+$mm);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(210,6,"TOTAL",0,1,'C',0);
  $pdf->SetXY($set_x+210, $set_y+$mm);
  $pdf->Cell(14,6,"$".number_format($suma_m,2,".",","),0,1,'R',0);
  $pdf->SetXY($set_x+224, $set_y+$mm);
  $pdf->Cell(16,6,"$".number_format($suma_iva,2,".",","),0,1,'R',0);
  $pdf->SetXY($set_x+240, $set_y+$mm);
  $pdf->Cell(18,6,"$".number_format($suma_v,2,".",","),0,1,'R',0);
  $mm+=10;
  $j+=2;
}else{
  $mm+= 10;
  $j+=2;
}
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

//-----------------------------
//-----------------------------
//-----------------------------
//VIATICOS---------------------
//-----------------------------
//Comienzan los viaticos
$pdf->SetFont('Arial','',11);
$pdf->SetXY($set_x, $set_y+$mm-5);
$pdf->Cell(18,5,utf8_decode("VIATICOS"),0,1,'C',0);
$j++;
$pdf->SetFont('Arial','',9);
$pdf->SetXY($set_x, $set_y+$mm);
$pdf->Cell(15,5,utf8_decode("Nº"),'B',1,'L',0);
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
//$pdf->SetTextColor(0,0,0);

$resul = _query("SELECT  * FROM mov_caja WHERE viatico='1' AND  date(fecha) BETWEEN '$fini' AND '$ffin' AND  id_sucursal='$id_sucursal'");
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
if(_num_rows($resul)>0)
{
  $suma_m2=0;
  $count2=1;
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
      $pdf->Image($logo,9,4,25,25);
      $set_x = 10;
      $set_y = 25;
      $mm=0;
      //Encabezado General
      $pdf->SetFont('Arial','',9);
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
      $set_y = 30;
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
        $pdf->SetXY($set_x+119, $set_y+$mm+$nn2);
        $pdf->Cell(70,5,utf8_decode(ucFirst(strtolower($val2))),0,1,'L',0);
        $nn2 += 5;
        $j++;

      $lwidth2 = $nn2;
      $pdf->SetXY($set_x+119, $set_y+$mm);
      $pdf->Cell(70,$lwidth2,"",0,1,'C',0);
    }
    else
    {
      $lwidth2=5;
      $pdf->SetXY($set_x+119, $set_y+$mm);
      $pdf->Cell(70,$lwidth2,utf8_decode(ucFirst(strtolower($concepto2))),0,1,'L',0);
      $j++;
    }
    $monto2 = $row2["valor"];
    $caja2 = $row2["caja"];
    $apertura2 = $row2["id_apertura"];
    $turno2 = $row2["turno"];
    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(15,$lwidth2,$count2,0,1,'L',0);
    $pdf->SetXY($set_x+15, $set_y+$mm);
    $pdf->Cell(18,$lwidth2,ED($fecha2),0,1,'L',0);
    $pdf->SetXY($set_x+33, $set_y+$mm);
    $pdf->Cell(66,$lwidth2,utf8_decode(ucFirst(strtolower($empleado2))),0,1,'L',0);
    $pdf->SetXY($set_x+99, $set_y+$mm);
    $pdf->Cell(20,$lwidth2,ucFirst($diligencia2),0,1,'L',0);

$pdf->SetXY($set_x+189, $set_y+$mm);
$pdf->Cell(16,$lwidth2,$caja2,0,1,'C',0);
$pdf->SetXY($set_x+205, $set_y+$mm);
$pdf->Cell(20,$lwidth2,$apertura2,0,1,'C',0);
$pdf->SetXY($set_x+225, $set_y+$mm);
$pdf->Cell(15,$lwidth2,$turno2,0,1,'C',0);
$pdf->SetXY($set_x+240, $set_y+$mm);
$pdf->Cell(18,$lwidth2,"$".number_format($monto2,2,".",","),0,1,'R',0);
$count2+=1;
//$mm+=5;
$mm+=$lwidth2;
//$j++;
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
$suma_m2+=$monto2;
}
$pdf->SetXY($set_x, $set_y+$mm);
$pdf->Cell(240,5,"TOTAL",0,1,'C',0);
$pdf->SetXY($set_x+240, $set_y+$mm);
$pdf->Cell(18,5,"$".number_format($suma_m2,2,".",","),0,1,'R',0);
$mm+= 5 ;
$j+= 1 ;
}

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
$pdf->SetXY($set_x+220, $set_y+$mm);
$pdf->Cell(18,5,"VALES:",0,1, 'R',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".number_format($suma_m,2,".",",")." +",0,1,'R',0);
$mm+= 5;
$j+=1;
$pdf->SetXY($set_x+220, $set_y+$mm);
$pdf->Cell(18,5,"VIATICOS:",0,1,'C',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".number_format($suma_m2,2,".",",")." =",0,1,'R',0);
$mm+= 5;
$j+=1;
$pdf->Line($set_x+240, $set_y+$mm,$set_x+260 , $set_y+$mm);
$pdf->SetXY($set_x+198, $set_y+$mm);
$pdf->Cell(40,5,"TOTAL DESEMBOLSO:",0,1,'C',0);
$pdf->SetXY($set_x+239, $set_y+$mm);
$pdf->Cell(23,5,"$".(number_format($suma_m+$suma_m2,2,".",",")),0,1,'C',0);
$mm+= 5;
$j+=1;
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
$pdf->Output("reporte_egreso.pdf","I");
