<?php
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$pdf=new fPDF('P','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddFont("latin","","latin.php");
$id_sucursal = $_SESSION["id_sucursal"];
$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";
$nombre_laboratorio=utf8_decode(Mayu(utf8_decode(trim("Laboratorio Clínico Migueleño"))));
$resultado_emp=_query($sql_empresa);
$row_emp=_fetch_array($resultado_emp);
$nombre_a = utf8_decode(Mayu(utf8_decode(trim($row_emp["nombre_lab"]))));
$direccion = utf8_decode(Mayu(utf8_decode(trim($row_emp["direccion"]))));
$tel1 = $row_emp['telefono1'];
$nrc = $row_emp['nrc'];
$nit = $row_emp['nit'];
$telefonos="TEL. ".$tel1;

$id_corte = $_REQUEST["id_corte"];
$sql_user = _query("SELECT usuario.nombre, id_apertura, hora_corte as hora, fecha_corte, turno ,cashinicial FROM controlcaja JOIN usuario ON controlcaja.id_empleado=usuario.id_usuario WHERE id_corte='$id_corte'");
$row_user = _fetch_array($sql_user);
$id_apertura = $row_user["id_apertura"];
$turno = $row_user["turno"];
$cajero = utf8_decode($row_user["nombre"]);
$hora_c = hora($row_user["hora"]);
$fecha_corte = $row_user["fecha_corte"];
$caja_chica = $row_user["cashinicial"];
$sql_hora_ap = _query("SELECT hora FROM detalle_apertura WHERE id_apertura='$id_apertura' AND turno='$turno'");
$row_hora_ap = _fetch_array($sql_hora_ap);
$hora_ic = hora($row_hora_ap["hora"]);
$logo = $row_emp['logo'];
$impress = "Impreso: ".date("d/m/Y").' '.hora(date("H:i:s"));
$title = $nombre_a;
$titulo = "CORTE DE CAJA";

list($a,$m,$d) = explode("-", $fecha_corte);
$fech="$d DE ".meses($m)." DE $a";


$pdf->AddPage();
$pdf->SetFont('Latin','',10);
$pdf->Image($logo,10,10,30,30);
$set_x = 0;
$set_y = 10;

//Encabezado General
$pdf->SetFont('Latin','',12);
$pdf->SetXY($set_x, $set_y);
$pdf->Cell(220,5,$nombre_laboratorio,0,1,'C');
$pdf->SetXY($set_x, $set_y+6);
$pdf->Cell(220,5,$title,0,1,'C');
$pdf->SetFont('Latin','',10);
if($id_sucursal==3){
  $pdf->SetXY($set_x+34, $set_y+11);
  $pdf->MultiCell(150,5,$direccion,0,'C',0);
}else{
  $pdf->SetXY($set_x, $set_y+11);
  $pdf->Cell(220,5,$direccion,0,1,'C');
  $set_y-=5;
}

$pdf->SetXY($set_x, $set_y+21);
$pdf->Cell(220,5,$telefonos,0,1,'C');
$pdf->SetXY($set_x, $set_y+26);
$pdf->SetFont('Latin','',11);
$pdf->Cell(220,5,utf8_decode($titulo),0,1,'C');
$pdf->SetXY($set_x, $set_y+31);
$pdf->SetFont('Latin','',10);
$pdf->Cell(220,5,$fech,0,1,'C');

$set_x = 5;
$set_y = 45;

$pdf->SetXY($set_x, $set_y);
$pdf->Cell(205,5,"CAJERO: ".$cajero,0,1,'L',0);
$pdf->SetXY($set_x, $set_y+5);
$pdf->Cell(30,5,"INICIO: ".$hora_ic,0,1,'L',0);
$pdf->SetXY($set_x+30, $set_y+5);
$pdf->Cell(30,5,"FIN:   ".$hora_c,0,1,'L',0);

$mm = 0;
$page = 1;
$j=0;
$set_y+=10;
$salto=41;

/**VENTA CONTADO**/
$sql = _query("SELECT f.tipo_doc, f.anulada, f.num_fact_impresa, f.total, f.anulada, f.hora_cobro,
               c.nombre AS cliente, e.nombre AS cajero
               FROM cobro AS f
               LEFT JOIN cliente AS c ON f.cliente=c.id_cliente
               LEFT JOIN usuario AS e ON f.id_empleado=e.id_usuario
               WHERE f.finalizada=1
               AND f.tipo_pago!='CRE'
               AND f.tipo_pago='CON'
               AND f.tipo_pago NOT LIKE '%PEN%'
               AND f.id_apertura='$id_apertura'
               AND f.turno='$turno'
               ORDER BY f.tipo_doc ASC, f.num_fact_impresa ASC");
$num = _num_rows($sql);
$tot_contado = 0;
if($num > 0)
{
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y);
  $pdf->Cell(205,5,"VENTAS DE CONTADO",0,1,'C',0);
  $set_y+=5;
  $pdf->Line($set_x,$set_y,$set_x+205,$set_y);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y);
  $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
  $pdf->SetXY($set_x+20, $set_y);
  $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
  $pdf->SetXY($set_x+40, $set_y);
  $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
  $pdf->SetXY($set_x+130, $set_y);
  $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
  $pdf->SetXY($set_x+170, $set_y);
  $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
  $pdf->SetXY($set_x+190, $set_y);
  $pdf->Cell(15,5,"ANULADO",0,1,'C',0);
  $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
  $set_y+=5;

  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y);
        $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
        $pdf->SetXY($set_x+20, $set_y);
        $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
        $pdf->SetXY($set_x+40, $set_y);
        $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
        $pdf->SetXY($set_x+130, $set_y);
        $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
        $pdf->SetXY($set_x+170, $set_y);
        $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
        $pdf->SetXY($set_x+190, $set_y);
        $pdf->Cell(15,5,"ANULADO",0,1,'C',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $tipo_doc = $row["tipo_doc"];
    $numero_doc = $row["num_fact_impresa"];
    $cliente = utf8_decode($row["cliente"]);

    $vendedor = $row["cajero"];
    $dven = explode(" ",$row["cajero"]);
    if(count($dven)>3)
    $vendedor = $dven[0]." ".$dven[2];

    $cajero = $row["cajero"];
    $dcaj = explode(" ",$row["cajero"]);
    if(count($dcaj)>3)
    $cajero = $dcaj[0]." ".$dcaj[2];

    $total = $row["total"];
    $anulada = $row["anulada"];
    $hora = hora($row["hora"]);
    if($anulada)
    {
      $anulado = "SI";
      $cliente = "**** ANULADA ****";
      $pdf->setTextColor(255,0,0);
    }
    else
    {
      $anulado = "NO";
      $pdf->setTextColor(0,0,0);
      $tot_contado+=$total;
    }
    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(20,5,$tipo_doc,0,1,'C',0);
    $pdf->SetXY($set_x+20, $set_y+$mm);
    $pdf->Cell(20,5,$numero_doc,0,1,'C',0);
    $pdf->SetXY($set_x+40, $set_y+$mm);
    $pdf->Cell(90,5,$cliente,0,1,'L',0);
    $pdf->SetXY($set_x+130, $set_y+$mm);
    $pdf->Cell(40,5,$vendedor,0,1,'L',0);
    $pdf->SetXY($set_x+170, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+190, $set_y+$mm);
    $pdf->Cell(15,5,$anulado,0,1,'C',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(170,5,"TOTAL CONTADO",0,1,'C',0);
  $pdf->SetXY($set_x+170, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_contado,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}


/**VENTA AL CREDITO**/
$sql = _query("SELECT f.tipo_doc, f.num_fact_impresa, f.total, f.anulada, f.hora_cobro,
               c.nombre AS cliente, e.nombre AS cajero
               FROM cobro AS f
               LEFT JOIN cliente AS c ON f.cliente=c.id_cliente
               LEFT JOIN usuario AS e ON f.id_empleado=e.id_usuario
               WHERE f.finalizada=1
               AND f.tipo_pago='CRE'
               AND f.id_apertura='$id_apertura'
               AND f.turno='$turno'
               ORDER BY f.tipo_doc ASC, f.num_fact_impresa ASC");
$num = _num_rows($sql);
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"VENTAS AL CREDITO",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
  $pdf->SetXY($set_x+20, $set_y+$mm);
  $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
  $pdf->SetXY($set_x+40, $set_y+$mm);
  $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
  $pdf->SetXY($set_x+130, $set_y+$mm);
  $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
  $pdf->SetXY($set_x+170, $set_y+$mm);
  $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
  $pdf->SetXY($set_x+190, $set_y+$mm);
  $pdf->Cell(15,5,"ANULADO",0,1,'C',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;
  $tot_cred = 0;
  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y);
        $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
        $pdf->SetXY($set_x+20, $set_y);
        $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
        $pdf->SetXY($set_x+40, $set_y);
        $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
        $pdf->SetXY($set_x+130, $set_y);
        $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
        $pdf->SetXY($set_x+170, $set_y);
        $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
        $pdf->SetXY($set_x+190, $set_y);
        $pdf->Cell(15,5,"ANULADO",0,1,'C',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $tipo_doc = $row["tipo_doc"];
    $numero_doc = $row["num_fact_impresa"];
    $cliente = utf8_decode($row["cliente"]);

    $vendedor = $row["cajero"];
    $dven = explode(" ",$row["cajero"]);
    if(count($dven)>3)
    $vendedor = $dven[0]." ".$dven[2];

    $cajero = $row["cajero"];
    $dcaj = explode(" ",$row["cajero"]);
    if(count($dcaj)>3)
    $cajero = $dcaj[0]." ".$dcaj[2];

    if($vendedor == "")
    {
      $vendedor = $cajero;
    }
    $total = $row["total"];
    $anulada = $row["anulada"];
    $hora = hora($row["hora"]);
    if($anulada)
    {
      $cliente = "**** ANULADA ****";
      $anulado = "SI";
      $pdf->setTextColor(255,0,0);
    }
    else
    {
      $anulado = "NO";
      $pdf->setTextColor(0,0,0);
      $tot_cred+=$total;
    }
    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(20,5,$tipo_doc,0,1,'C',0);
    $pdf->SetXY($set_x+20, $set_y+$mm);
    $pdf->Cell(20,5,$numero_doc,0,1,'C',0);
    $pdf->SetXY($set_x+40, $set_y+$mm);
    $pdf->Cell(90,5,$cliente,0,1,'L',0);
    $pdf->SetXY($set_x+130, $set_y+$mm);
    $pdf->Cell(40,5,$vendedor,0,1,'L',0);
    $pdf->SetXY($set_x+170, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+190, $set_y+$mm);
    $pdf->Cell(15,5,$anulado,0,1,'C',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(170,5,"TOTAL VENTA AL CREDITO",0,1,'C',0);
  $pdf->SetXY($set_x+170, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_cred,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

/**RECUPERACION**/
$sql = _query("SELECT f.fecha, f.tipo_doc, f.num_fact_impresa, f.total, f.anulada, f.hora_cobro,
               c.nombre AS cliente,e.nombre AS cajero
               FROM cobro AS f
               LEFT JOIN cliente AS c ON f.cliente=c.id_cliente
               LEFT JOIN usuario AS e ON f.id_empleado=e.id_usuario
               WHERE f.finalizada=1
               AND f.tipo_pago LIKE '%PEN|%'
               AND f.id_apertura_pagado='$id_apertura'
               AND f.turno='$turno'
               ORDER BY f.tipo_doc ASC, f.num_fact_impresa ASC");
$num = _num_rows($sql);
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"RECUPERACION",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(20,5,"FACTURADO",0,1,'C',0);
  $pdf->SetXY($set_x+20, $set_y+$mm);
  $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
  $pdf->SetXY($set_x+40, $set_y+$mm);
  $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
  $pdf->SetXY($set_x+60, $set_y+$mm);
  $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
  $pdf->SetXY($set_x+150, $set_y+$mm);
  $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
  $pdf->SetXY($set_x+190, $set_y+$mm);
  $pdf->Cell(15,5,"TOTAL",0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;
  $tot_pend_pag = 0;
  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(20,5,"FACTURADO",0,1,'C',0);
        $pdf->SetXY($set_x+20, $set_y+$mm);
        $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
        $pdf->SetXY($set_x+40, $set_y+$mm);
        $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
        $pdf->SetXY($set_x+60, $set_y+$mm);
        $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
        $pdf->SetXY($set_x+150, $set_y+$mm);
        $pdf->Cell(40,5,"VENDEDOR",0,1,'L',0);
        $pdf->SetXY($set_x+190, $set_y+$mm);
        $pdf->Cell(15,5,"TOTAL",0,1,'R',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $tipo_doc = $row["tipo_doc"];
    $numero_doc = $row["num_fact_impresa"];
    $cliente = utf8_decode($row["cliente"]);

    $vendedor = $row["cajero"];
    $dven = explode(" ",$row["cajero"]);
    if(count($dven)>3)
    $vendedor = $dven[0]." ".$dven[2];

    $cajero = $row["cajero"];
    $dcaj = explode(" ",$row["cajero"]);
    if(count($dcaj)>3)
    $cajero = $dcaj[0]." ".$dcaj[2];

    if($vendedor == "")
    {
      $vendedor = $cajero;
    }
    $total = $row["total"];
    $fecha = ED($row["fecha"]);
    $anulada = $row["anulada"];
    $hora = hora($row["hora"]);
    $tot_pend_pag+=$total;


    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(20,5,$fecha,0,1,'C',0);
    $pdf->SetXY($set_x+20, $set_y+$mm);
    $pdf->Cell(20,5,$tipo_doc,0,1,'C',0);
    $pdf->SetXY($set_x+40, $set_y+$mm);
    $pdf->Cell(20,5,$numero_doc,0,1,'C',0);
    $pdf->SetXY($set_x+60, $set_y+$mm);
    $pdf->Cell(90,5,$cliente,0,1,'L',0);
    $pdf->SetXY($set_x+150, $set_y+$mm);
    $pdf->Cell(40,5,$vendedor,0,1,'L',0);
    $pdf->SetXY($set_x+190, $set_y+$mm);
    $pdf->Cell(15,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(190,5,"TOTAL RECUPERACION",0,1,'C',0);
  $pdf->SetXY($set_x+190, $set_y+$mm);
  $pdf->Cell(15,5,"$".number_format($tot_pend_pag,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

/**ABONOS A CREDITO**/
$sql = _query("SELECT m.tipo_doc, m.numero_doc, cre.saldo, cre.total, m.hora,
               c.nombre AS cliente, m.valor AS abono, cre.fecha
               FROM mov_caja AS m
               INNER JOIN abono_credito AS ac ON m.idtransace=ac.id_abono_credito
               INNER JOIN credito AS cre ON cre.id_credito = ac.id_credito
               LEFT JOIN cliente AS c ON cre.id_cliente=c.id_cliente
               WHERE m.idtransace!=''
               AND m.concepto='POR ABONO A CREDITO'
               AND m.id_apertura='$id_apertura'
               AND m.turno='$turno'
               ORDER BY m.tipo_doc ASC, m.numero_doc ASC");
$num = _num_rows($sql);
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"ABONOS A CREDITO",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(15,5,"FECHA",0,1,'C',0);
  $pdf->SetXY($set_x+15, $set_y+$mm);
  $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
  $pdf->SetXY($set_x+35, $set_y+$mm);
  $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
  $pdf->SetXY($set_x+55, $set_y+$mm);
  $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
  $pdf->SetXY($set_x+145, $set_y+$mm);
  $pdf->Cell(20,5,"MONTO",0,1,'R',0);
  $pdf->SetXY($set_x+165, $set_y+$mm);
  $pdf->Cell(20,5,"ABONO",0,1,'R',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"SALDO",0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;
  $tot_abonos = 0;
  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(15,5,"FECHA",0,1,'C',0);
        $pdf->SetXY($set_x+15, $set_y+$mm);
        $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
        $pdf->SetXY($set_x+35, $set_y+$mm);
        $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
        $pdf->SetXY($set_x+55, $set_y+$mm);
        $pdf->Cell(90,5,"CLIENTE",0,1,'L',0);
        $pdf->SetXY($set_x+145, $set_y+$mm);
        $pdf->Cell(20,5,"MONTO",0,1,'R',0);
        $pdf->SetXY($set_x+165, $set_y+$mm);
        $pdf->Cell(20,5,"ABONO",0,1,'R',0);
        $pdf->SetXY($set_x+185, $set_y+$mm);
        $pdf->Cell(20,5,"SALDO",0,1,'R',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $tipo_doc = $row["tipo_doc"];
    $numero_doc = $row["numero_doc"];
    $cliente = utf8_decode($row["cliente"]);

    $total = $row["total"];
    $abono = $row["abono"];
    $saldo = $row["saldo"];
    $fecha = ED($row["fecha"]);
    if($saldo == 0)
      $saldo = $total;
    $hora = hora($row["hora"]);
    $tot_abonos+=$abono;

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(15,5,$fecha,0,1,'C',0);
    $pdf->SetXY($set_x+15, $set_y+$mm);
    $pdf->Cell(20,5,$tipo_doc,0,1,'C',0);
    $pdf->SetXY($set_x+35, $set_y+$mm);
    $pdf->Cell(20,5,$numero_doc,0,1,'C',0);
    $pdf->SetXY($set_x+55, $set_y+$mm);
    $pdf->Cell(90,5,$cliente,0,1,'L',0);
    $pdf->SetXY($set_x+145, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($abono,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+165, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($abono,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+185, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($saldo - $abono,2,".",","),0,1,'R',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(165,5,"TOTAL ABONO CREDITO",0,1,'C',0);
  $pdf->SetXY($set_x+165, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_abonos,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

/**VALES**/
$sql = _query("SELECT m.numero_doc, m.tipo_doc, m.iva, m.concepto,
               m.valor, m.hora
               FROM mov_caja AS m
               WHERE m.salida =1
               AND m.id_apertura='$id_apertura'
               AND m.turno='$turno'
               ORDER BY m.hora ASC");
$num = _num_rows($sql);
$tot_vales = 0;
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"VALES",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(15,5,"HORA",0,1,'C',0);
  $pdf->SetXY($set_x+15, $set_y+$mm);
  $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
  $pdf->SetXY($set_x+35, $set_y+$mm);
  $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
  $pdf->SetXY($set_x+55, $set_y+$mm);
  $pdf->Cell(90,5,"CONCEPTO",0,1,'L',0);
  $pdf->SetXY($set_x+145, $set_y+$mm);
  $pdf->Cell(20,5,"MONTO",0,1,'R',0);
  $pdf->SetXY($set_x+165, $set_y+$mm);
  $pdf->Cell(20,5,"IVA",0,1,'R',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;

  $tot_iva = 0;
  $tot_sum = 0;
  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(15,5,"HORA",0,1,'C',0);
        $pdf->SetXY($set_x+15, $set_y+$mm);
        $pdf->Cell(20,5,"TIPO DOC",0,1,'C',0);
        $pdf->SetXY($set_x+35, $set_y+$mm);
        $pdf->Cell(20,5,utf8_decode("NÚMERO"),0,1,'C',0);
        $pdf->SetXY($set_x+55, $set_y+$mm);
        $pdf->Cell(90,5,"CONCEPTO",0,1,'L',0);
        $pdf->SetXY($set_x+145, $set_y+$mm);
        $pdf->Cell(20,5,"MONTO",0,1,'R',0);
        $pdf->SetXY($set_x+165, $set_y+$mm);
        $pdf->Cell(20,5,"IVA",0,1,'R',0);
        $pdf->SetXY($set_x+185, $set_y+$mm);
        $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $tipo_doc = $row["tipo_doc"];
    $numero_doc = $row["numero_doc"];
    $concepto = utf8_decode($row["concepto"]);

    $total = $row["valor"];
    $iva = $row["iva"];
    $monto = $total - $iva;
    $hora = hora($row["hora"]);
    $tot_vales+=$total;
    $tot_sum+=$monto;
    $tot_iva+=$iva;

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(15,5,$hora,0,1,'C',0);
    $pdf->SetXY($set_x+15, $set_y+$mm);
    $pdf->Cell(20,5,$tipo_doc,0,1,'C',0);
    $pdf->SetXY($set_x+35, $set_y+$mm);
    $pdf->Cell(20,5,$numero_doc,0,1,'C',0);
    $pdf->SetXY($set_x+55, $set_y+$mm);
    $pdf->Cell(90,5,Mayu($concepto),0,1,'L',0);
    $pdf->SetXY($set_x+145, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($monto,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+165, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($iva,2,".",","),0,1,'R',0);
    $pdf->SetXY($set_x+185, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(145,5,"TOTAL VALES",0,1,'C',0);
  $pdf->SetXY($set_x+145, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_sum,2,".",","),0,1,'R',0);
  $pdf->SetXY($set_x+165, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_iva,2,".",","),0,1,'R',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_vales,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

/**INGRESO**/
$sql = _query("SELECT m.numero_doc, m.tipo_doc, m.concepto, m.nombre_recibe,
               m.valor, m.hora, m.tipo_delige
               FROM mov_caja AS m
               WHERE m.entrada =1
               AND m.id_apertura='$id_apertura'
               AND m.turno='$turno'
               ORDER BY m.hora ASC");
$num = _num_rows($sql);
$tot_ingreso = 0;
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"INGRESOS",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(20,5,"HORA",0,1,'C',0);
  $pdf->SetXY($set_x+40, $set_y+$mm);
  $pdf->Cell(75,5,"CONCEPTO",0,1,'L',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;

  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(20,5,"HORA",0,1,'C',0);

        $pdf->SetXY($set_x+50, $set_y+$mm);
        $pdf->Cell(75,5,"CONCEPTO",0,1,'L',0);
        $pdf->SetXY($set_x+185, $set_y+$mm);
        $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $concepto = utf8_decode($row["concepto"]);
    $nombre_recibe = utf8_decode($row["nombre_recibe"]);

    $tipo = ucfirst($row["tipo_delige"]);
    $total = $row["valor"];
    $hora = hora($row["hora"]);
    $tot_ingreso+=$total;

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(20,5,$hora,0,1,'C',0);
    $pdf->SetXY($set_x+40, $set_y+$mm);
    $pdf->Cell(75,5,Mayu($concepto),0,1,'L',0);
    $pdf->SetXY($set_x+185, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(185,5,"TOTAL INGRESOS",0,1,'C',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_ingreso,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

/**VIATICO**/
$sql = _query("SELECT m.numero_doc, m.tipo_doc, m.concepto, m.nombre_recibe,
               m.valor, m.hora, m.tipo_delige
               FROM mov_caja AS m
               WHERE m.viatico =1
               AND m.id_apertura='$id_apertura'
               AND m.turno='$turno'
               ORDER BY m.hora ASC");
$num = _num_rows($sql);
$tot_viatico = 0;
if($num > 0)
{
  $mm+=10;
  $j+=2;
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetFont('Latin','',9);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(205,5,"VIATICOS",0,1,'C',0);
  $mm+=5;
  $j++;
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $pdf->SetFont('Latin','',8);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(20,5,"HORA",0,1,'C',0);
  $pdf->SetXY($set_x+20, $set_y+$mm);
  $pdf->Cell(30,5,"TIPO",0,1,'C',0);
  $pdf->SetXY($set_x+50, $set_y+$mm);
  $pdf->Cell(60,5,"RECIBE",0,1,'L',0);
  $pdf->SetXY($set_x+110, $set_y+$mm);
  $pdf->Cell(75,5,"CONCEPTO",0,1,'L',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm+5,$set_x+205,$set_y+$mm+5);
  $mm+=5;
  $j++;

  while ($row = _fetch_array($sql))
  {
    if($page)
        $salto = 41;
    else
        $salto = 52;
    if($j>=$salto)
    {
        $page=0;
        $pdf->AddPage();
        $set_x = 5;
        $set_y = 5;
        $pdf->SetFont('Latin','',8);
        $pdf->SetXY($set_x, $set_y+$mm);
        $pdf->Cell(20,5,"HORA",0,1,'C',0);
        $pdf->SetXY($set_x+20, $set_y+$mm);
        $pdf->Cell(30,5,"TIPO",0,1,'C',0);
        $pdf->SetXY($set_x+50, $set_y+$mm);
        $pdf->Cell(60,5,"RECIBE",0,1,'L',0);
        $pdf->SetXY($set_x+110, $set_y+$mm);
        $pdf->Cell(75,5,"CONCEPTO",0,1,'L',0);
        $pdf->SetXY($set_x+185, $set_y+$mm);
        $pdf->Cell(20,5,"TOTAL",0,1,'R',0);
        $pdf->Line($set_x,$set_y+5,$set_x+205,$set_y+5);
        $set_y = 10;
        $mm=0;
        $j=0;
    }
    $concepto = utf8_decode($row["concepto"]);
    $nombre_recibe = utf8_decode($row["nombre_recibe"]);

    $tipo = ucfirst($row["tipo_delige"]);
    $total = $row["valor"];
    $hora = hora($row["hora"]);
    $tot_viatico+=$total;

    $pdf->SetXY($set_x, $set_y+$mm);
    $pdf->Cell(20,5,$hora,0,1,'C',0);
    $pdf->SetXY($set_x+20, $set_y+$mm);
    $pdf->Cell(30,5,Mayu($tipo),0,1,'C',0);
    $pdf->SetXY($set_x+50, $set_y+$mm);
    $pdf->Cell(60,5,mayu($nombre_recibe),0,1,'L',0);
    $pdf->SetXY($set_x+110, $set_y+$mm);
    $pdf->Cell(75,5,Mayu($concepto),0,1,'L',0);
    $pdf->SetXY($set_x+185, $set_y+$mm);
    $pdf->Cell(20,5,"$".number_format($total,2,".",","),0,1,'R',0);
    $mm+=5;
    $j++;
  }
  if($j>=$salto)
  {
      $page=0;
      $pdf->AddPage();
      $set_y = 10;
      $mm=0;
      $j=0;
  }
  $pdf->setTextColor(0,0,0);
  $pdf->SetXY($set_x, $set_y+$mm);
  $pdf->Cell(185,5,"TOTAL VIATICOS",0,1,'C',0);
  $pdf->SetXY($set_x+185, $set_y+$mm);
  $pdf->Cell(20,5,"$".number_format($tot_viatico,2,".",","),0,1,'R',0);
  $pdf->Line($set_x,$set_y+$mm,$set_x+205,$set_y+$mm);
  $mm+=5;
  $j++;
}

$mm+=10;
$j+=2;
if($j>=$salto)
{
  $page=0;
  $pdf->AddPage();
  $set_y = 10;
  $mm=0;
  $j=0;
}
/////primera fila
$pdf->SetXY($set_x, $set_y+$mm);
$pdf->Cell(95,5,"APERTURA CAJA:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm);
$pdf->Cell(20,5,"$".number_format(($caja_chica),2,".",","),0,1,'R',0);


$pdf->SetXY($set_x+80, $set_y+$mm);
$pdf->Cell(95,5,"TOTAL INGRESOS.",0,1,'L',0);

$pdf->SetXY($set_x+160, $set_y+$mm);
$pdf->Cell(95,5,"TOTAL EGRESOS.",0,1,'L',0);


////segunda
$pdf->SetXY($set_x, $set_y+$mm+3);
$pdf->Cell(95,5,"TOTAL CONTADO:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm+3);
$pdf->Cell(20,5,"$".number_format($tot_contado,2,".",","),0,1,'R',0);


$pdf->SetXY($set_x+80, $set_y+$mm+3);
$pdf->Cell(95,5,"CONTADO:",0,1,'L',0);
$pdf->SetXY($set_x+105, $set_y+$mm+3);
$pdf->Cell(20,5,"$".number_format($tot_contado,2,".",","),0,1,'R',0);

$pdf->SetXY($set_x+160, $set_y+$mm+3);
$pdf->Cell(95,5,"VALES:",0,1,'L',0);
$pdf->SetXY($set_x+185, $set_y+$mm+3);
$pdf->Cell(20,5,"$".number_format($tot_vales,2,".",","),0,1,'R',0);


////tercera
$pdf->SetXY($set_x, $set_y+$mm+6);
$pdf->Cell(95,5,"TOTAL INGRESOS:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm+6);
$pdf->Cell(20,5,"$".number_format($tot_ingreso,2,".",","),0,1,'R',0);

$pdf->SetXY($set_x+80, $set_y+$mm+6);
$pdf->Cell(95,5,"INGRESOS:",0,1,'L',0);
$pdf->SetXY($set_x+105, $set_y+$mm+6);
$pdf->Cell(20,5,"$".number_format($tot_ingreso,2,".",","),0,1,'R',0);


$pdf->SetXY($set_x+160, $set_y+$mm+6);
$pdf->Cell(95,5,"VIATICOS:",0,1,'L',0);
$pdf->SetXY($set_x+185, $set_y+$mm+6);
$pdf->Cell(20,5,"$".number_format($tot_viatico,2,".",","),0,1,'R',0);


////cuarta
$pdf->SetXY($set_x, $set_y+$mm+9);
$pdf->Cell(95,5,"TOTAL VALES:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm+9);
$pdf->Cell(20,5,"$".number_format($tot_vales,2,".",","),0,1,'R',0);

$pdf->SetXY($set_x+80, $set_y+$mm+9);
$pdf->Cell(95,5,"TOTAL:",0,1,'L',0);
$pdf->SetXY($set_x+105, $set_y+$mm+9);
$pdf->Cell(20,5,"$".number_format($tot_ingreso+$tot_contado,2,".",","),0,1,'R',0);

$pdf->SetXY($set_x+160, $set_y+$mm+9);
$pdf->Cell(95,5,"TOTAL:",0,1,'L',0);
$pdf->SetXY($set_x+185, $set_y+$mm+9);
$pdf->Cell(20,5,"$".number_format($tot_viatico+$tot_vales,2,".",","),0,1,'R',0);


////quinta
$pdf->SetXY($set_x, $set_y+$mm+12);
$pdf->Cell(95,5,"TOTAL VIATICOS:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm+12);
$pdf->Cell(20,5,"$".number_format($tot_viatico,2,".",","),0,1,'R',0);

$efectivo_caja=($caja_chica+$tot_ingreso+$tot_contado)-$tot_vales-$tot_viatico;
////sexta
$pdf->SetXY($set_x, $set_y+$mm+15);
$pdf->Cell(95,5,"EFECTIVO EN CAJA:",0,1,'L',0);
$pdf->SetXY($set_x+25, $set_y+$mm+15);
$pdf->Cell(20,5,"$".number_format($efectivo_caja,2,".",","),0,1,'R',0);






ob_clean();
$pdf->Output("corte_caja.pdf","I");
