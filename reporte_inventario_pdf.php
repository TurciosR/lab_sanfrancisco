<?php
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');


$pdf=new fPDF('L','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddFont("Helvetica","","helvetica.php");
$id_sucursal = $_SESSION["id_sucursal"];
$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'";

$resultado_emp=_query($sql_empresa);
$row_emp=_fetch_array($resultado_emp);
$nombre_a = utf8_decode(Mayu(utf8_decode(trim($row_emp["nombre_lab"]))));
$direccion = utf8_decode(Mayu(utf8_decode(trim($row_emp["direccion"]))));
$tel1 = $row_emp['telefono1'];
$nrc = $row_emp['nrc'];
$nit = $row_emp['nit'];
$logo = $row_emp["logo"];
$telefonos="TEL. ".$tel1;

    $iftike = $_REQUEST["tiket"];
    if($iftike == 1)
    {
      $extra = "";
    }
    else
    {
        $extra = " AND tipo_documento != 'TIK'";
    }
    $min = $_REQUEST["l"];
    $fini = date("Y-m-d");
    $fin = $_REQUEST["ffin"];
    $fini1 = ED($_REQUEST["fini"]);
    $fin1 = ED($_REQUEST["ffin"]);

    $title = $nombre_a;
    $titulo = "REPORTE DE INVENTARIO";
    if($fini!="")
    {
        list($a,$m,$d) = explode("-", $fini);

        $fech="AL $d DE ".meses($m)." DE $a";

    }
    $impress = "REPORTE DE INVENTARIO ".$fech;


    $existenas = "";
    if($min>0)
    {
        $existenas = "CANTIDAD: $min";
    }

    $pdf->AddPage();
    $pdf->SetFont('Helvetica','',10);
    $pdf->Image($logo,9,4,45,18);
    $set_x = 5;
    $set_y = 6;

    //Encabezado General
    //Encabezado General
    $pdf->SetFont('Helvetica','',12);
    $pdf->SetXY($set_x, $set_y);
    $pdf->MultiCell(280,6,$title,0,'C',0);
    $pdf->SetFont('Helvetica','',10);
    $pdf->SetXY($set_x, $set_y+6);
    $pdf->Cell(280,6,$direccion,0,1,'C');
    $pdf->SetXY($set_x, $set_y+11);
    $pdf->Cell(280,6,$telefonos,0,1,'C');
    $pdf->SetXY($set_x, $set_y+16);
    $pdf->Cell(280,6,"NRC: ".$nrc."  NIT: ".$nit,0,1,'C');
    $pdf->SetXY($set_x, $set_y+21);
    $pdf->Cell(280,6,utf8_decode($titulo),0,1,'C');
    $pdf->SetXY($set_x, $set_y+26);
    $pdf->Cell(280,6,$fech,0,1,'C');

    ///////////////////////////////////////////////////////////////////////

    $set_x = 5;
    $set_y = 40;

    $pdf->SetFont('Helvetica','',8);
    $pdf->SetXY($set_x, $set_y);
    $pdf->Cell(20,5,utf8_decode("CODIGO"),1,1,'C',0);
    $pdf->SetXY($set_x+20,$set_y);
    $pdf->Cell(80,5,utf8_decode("PRODUCTO"),1,1,'C',0);
    $pdf->SetXY($set_x+100, $set_y);
    $pdf->Cell(35,5,utf8_decode("PRESENTACIÓN"),1,1,'C',0);
    $pdf->SetXY($set_x+135, $set_y);
    $pdf->Cell(35,5,utf8_decode("DESCRIPCIÓN"),1,1,'C',0);
    $pdf->SetXY($set_x+170, $set_y);
    $pdf->Cell(25,5,utf8_decode("COSTO"),1,1,'C',0);
    $pdf->SetXY($set_x+195, $set_y);
    $pdf->Cell(25,5,utf8_decode("PRECIO"),1,1,'C',0);
    $pdf->SetXY($set_x+220, $set_y);
    $pdf->Cell(25,5,utf8_decode("EXISTENCIA"),1,1,'C',0);
    $pdf->SetXY($set_x+245, $set_y);
    $pdf->Cell(25,5,utf8_decode("TOTAL($)"),1,1,'R',0);
    $pdf->Line($set_x,$set_y+5,$set_x+270,$set_y+5);
    //$pdf->SetTextColor(0,0,0);
    $set_y = 45;
    $linea = 0;
    $linea_acumulada = 0;
    $page = 0;
    $j = 0;
    $total_general = 0;
    $sql_stock = _query("SELECT p.id_producto, p.descripcion, p.barcode, c.nombre_cat, s.stock AS cantidad FROM producto AS p, categoria_p as c, stock AS s, presentacion_producto as pp
    WHERE p.id_categoria=c.id_categoria AND pp.id_producto=p.id_producto AND s.id_producto=p.id_producto AND p.id_sucursal='$id_sucursal' GROUP BY p.id_producto ORDER BY p.descripcion ");
    $contar = _num_rows($sql_stock);
    if($contar > 0)
    {
      while ($row = _fetch_array($sql_stock))
      {
        $id_producto = $row['id_producto'];
        $descripcion=$row["descripcion"];
        $cat = $row['cat'];
        $barcode = $row['barcode'];
        $existencias = $row['cantidad'];
        $sql_pres = _query("SELECT pp.*, p.nombre as descripcion_pr FROM presentacion_producto as pp, presentacion as p WHERE pp.presentacion=p.id_presentacion AND pp.id_producto='$id_producto' AND pp.id_sucursal='$id_sucursal' ORDER BY pp.unidad DESC");
        $npres = _num_rows($sql_pres);


          $exis = 0;
          $n=0;
          $p = 0;
          $s = 0;
          while ($rowb = _fetch_array($sql_pres))
          {
            if($page==0)
            {
                $salto = 160;
            }
            else
            {
                $salto = 195;
            }
            if($linea>=$salto)
            {
              $page++;
              $pdf->AddPage();
              $set_y = 6;
              $set_x = 5;
              //Encabezado General
              $linea=0;
              $j = 0;
                //$pdf->SetFont('Helvetica','',8);
            }
            $unidad = $rowb["unidad"];
            $costo = $rowb["costo"];
            $precio = $rowb["precio"];
            $descripcion_pr = $rowb["descripcion"];
            $presentacion = $rowb["descripcion_pr"];
            if($existencias >= $unidad)
            {
              $exis = intdiv($existencias, $unidad);
              $existencias = $existencias%$unidad;
            }
            else
            {
              $exis =  0;
            }
            $total_costo = round(($costo/1.13) * $exis, 4);
            $total_general += $total_costo;
            $pdf->SetXY($set_x+100, $set_y+$linea+$p);
            $pdf->Cell(35,5,utf8_decode($presentacion),1,1,'L',0);
            $pdf->SetXY($set_x+135, $set_y+$linea+$p);
            $pdf->Cell(35,5,utf8_decode($descripcion_pr),1,1,'L',0);
            $pdf->SetXY($set_x+170, $set_y+$linea+$p);
            $pdf->Cell(25,5,utf8_decode(number_format($costo, 2)),1,1,'C',0);
            $pdf->SetXY($set_x+195, $set_y+$linea+$p);
            $pdf->Cell(25,5,utf8_decode(number_format($precio, 2)),1,1,'C',0);
            $pdf->SetXY($set_x+220, $set_y+$linea+$p);
            $pdf->Cell(25,5,utf8_decode($exis),1,1,'C',0);
            $pdf->SetXY($set_x+245, $set_y+$linea+$p);
            $pdf->Cell(25,5,utf8_decode(number_format($total_costo, 4)),1,1,'R',0);
            $p += 5;
            $s += 1;
          }
          $j++;
          $pdf->SetXY($set_x, $set_y+$linea);
          $pdf->Cell(20,5*$s,utf8_decode($barcode),1,1,'L',0);
          $pdf->SetXY($set_x+20,$set_y+$linea);
          $pdf->Cell(80,5*$s,utf8_decode($descripcion),1,1,'L',0);
          $cc = (5 * $s);
          $linea += (5*$s);
          $linea_acumulada += $linea;
          if($j == 1)
          {
            $pdf->SetXY(4, 210);
            $pdf->Cell(10, 0.4,$impress, 0, 0, 'L');
            $pdf->SetXY(260, 210);
            $pdf->Cell(20, 0.4, 'Pag. '.$pdf->PageNo().' de {nb}', 0, 0, 'R');
          }
        }
        $pdf->Line($set_x,$set_y+$linea,$set_x+270,$set_y+$linea);
        $pdf->SetXY($set_x, $set_y+$linea);
        $pdf->Cell(245,5,utf8_decode("TOTAL"),0,1,'L',0);
        $pdf->SetXY($set_x+245, $set_y+$linea);
        $pdf->Cell(25,5,utf8_decode("$".number_format($total_general, 2)),0,1,'R',0);
    }





ob_clean();
$pdf->Output("reporte_inventario.pdf","I");
