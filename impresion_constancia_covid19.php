<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$id_sucursal=$_SESSION["id_sucursal"];
$id_examen_paciente=$_REQUEST["id_examen_paciente"];

$sqll = _query("SELECT * FROM sucursal where id_sucursal='$id_sucursal'");
$fila = _fetch_array($sqll);
$nombrelab = $fila["nombre_lab"];
$direccion = $fila["direccion"];
$telefono1 = $fila["telefono1"];
$telefono2 = $fila["telefono2"];
$giro = $fila["giro"];
$logo = "img/5be095952d13b_Logo_Laboratorio.png";
$correo = $fila["email"];
$sitio = $fila["website"];

$sqlx = "SELECT CONCAT(paciente.nombre,' ',paciente.apellido) as 'nombre_paciente', paciente.sexo, paciente.fecha_nacimiento, paciente.dui, paciente.pasaporte FROM paciente INNER JOIN examen_paciente on paciente.id_paciente = examen_paciente.id_paciente WHERE examen_paciente.id_examen_paciente = '$id_examen_paciente'";
$paciente_consulta = _query($sqlx);
$rowpaciente = _fetch_array($paciente_consulta);
$dui = $rowpaciente['dui'];
$pasaporte = $rowpaciente['pasaporte'];
$nombre_p = $rowpaciente['nombre_paciente'];
$sexo = $rowpaciente['sexo'];
if($sexo == 'FEMENINO'){
    $sexo="F";
}
if($sexo == 'MASCULINO'){
    $sexo = "M";
}
$sql_doctor = "SELECT examen_paciente.fecha_muestra, examen_paciente.hora_muestra, examen_paciente.fecha_reporte, examen_paciente.hora_reporte, examen_paciente.fecha_cobro, muestra.muestra, examen_paciente.hora_cobro, examen_paciente.fecha_realizado, examen_paciente.hora_realizado, examen_paciente.id_examen_paciente, examen_paciente.fecha_examen, CONCAT(doctor.nombre,' ',doctor.apellido) as 'doctor' FROM examen_paciente LEFT JOIN doctor on doctor.id_doctor = examen_paciente.id_doctor LEFT JOIN cobro on cobro.id_cobro = examen_paciente.id_cobro  INNER JOIN muestra on muestra.id_muestra = examen_paciente.id_muestra WHERE examen_paciente.id_examen_paciente = '$id_examen_paciente'";
$consulta = _query($sql_doctor);
$row_doc = _fetch_array($consulta);
$edad = $rowpaciente['fecha_nacimiento'];
$id_examen_paciente = $row_doc['id_examen_paciente'];
$fecha_realizado = ED($row_doc['fecha_realizado']);
$muestra = $row_doc['muestra'];
$fecha_cobro =ED($row_doc['fecha_cobro']);
$hora_cobro = _hora_media_decode($row_doc['hora_cobro']);
$infoext =  array(
    'id_examen_paciente' => $id_examen_paciente,
    'giro' => $giro,
    'direccion' => $direccion,
    'sitio' => $sitio,
    'nombre_p' => $nombre_p,
    'sexo' => $sexo,
    'edad' => $edad,
    'doctor' => $row_doc['doctor'],
    'fecha_examen'=> $row_doc['fecha_examen'],
    'fecha_realizado' => $fecha_realizado,
    'hora_realizado' => _hora_media_decode($row_doc['hora_realizado']),
    'fecha_cobro' => $fecha_cobro,
    'hora_cobro' =>$hora_cobro,
    'referencia' => $row_doc['nombre'],
    'muestra' => $muestra,
    'dui' => $dui,
    'pasaporte' => $pasaporte,
    'fecha_reporte' => ED($row_doc['fecha_reporte']),
    'hora_reporte' =>_hora_media_decode($row_doc['hora_reporte']),
    'fecha_muestra' =>ED($row_doc['fecha_muestra']),
    'hora_muestra' =>_hora_media_decode($row_doc['hora_muestra'])
);

class PDF extends FPDF{
    function drawTextBox($strText, $w, $h, $align='C', $valign='T', $border=true, $primero)
    {
        $vl = $h/4;
        $xi=$this->GetX();
        $yi=$this->GetY();
        
        $hrow=$this->FontSize;
        $textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
        $maxrows=floor($h/$this->FontSize);
        $rows=min($textrows,$maxrows);
    
        $dy=0;
        if (strtoupper($valign)=='M')
            $dy=($h-$rows*$this->FontSize)/2;
        if (strtoupper($valign)=='B')
            $dy=$h-$rows*$this->FontSize;
            $va = $yi+$dy;
            $v = $xi;
            $calculo = "";
            $this->SetY($yi+$dy);
            $this->SetX($xi);
        
            $this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);
        
            $this->SetY($yi);
            $this->SetX($v+ $w);
    
        if ($border)
            $this->Rect($xi,$yi,$w,$h);
    }
    
    function drawRows($w, $h, $txt, $border=0, $align='C', $fill=false, $maxline=0, $prn=0)
    {
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-4*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    if ($prn==1) $this->_out('0 Tw');
                }
                if ($prn==1) {
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
                }
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        if ($prn==1) $this->_out('0 Tw');
                    }
                    if ($prn==1) {
                        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
                    }
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    if ($prn==1){
                        $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,"C",$fill);
                    }
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            if ($prn==1) $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        if ($prn==1) {
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
        }
        $this->x=$this->lMargin;
        return $nl;
    }
    public function LineWriteB($array,$xss)
    {
      $ygg=0;
      $maxlines=1;
      $array_a_retornar=array();
      $array_max= array();
      foreach ($array as $key => $value) {
        // /Descripcion/
        $nombr=$value[0];
        // /fpdf width/
        $size=$value[1];
        // /fpdf alignt/
        $aling=$value[2];
        $jk=0;
        $w = $size;
        $h  = 0;
        $txt=$nombr;
        $border=0;
        if(!isset($this->CurrentFont))
          $this->Error('No font has been set');
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
          $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
          $nb--;
        $b = 1;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while($i<$nb)
        {
          // Get next character
          $c = $s[$i];
          if($c=="\n")
          {
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;

            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
            continue;
          }
          if($c==' ')
          {
            $sep = $i;
            $ls = $l;
            $ns++;
          }
          $l += $cw[$c];
          if($l>$wmax)
          {
            // Automatic line break
            if($sep==-1)
            {
              if($i==$j)
                $i++;
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;
            }
            else
            {
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;

              $i = $sep+1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
          }
          else
            $i++;
        }
        // Last chunk
        if($this->ws>0)
        {
          $this->ws = 0;
        }
        if($border && strpos($border,'B')!==false)
          $b .= 'B';
        $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
        $array_a_retornar[$ygg]["size"][]=$size;
        $array_a_retornar[$ygg]["aling"][]=$aling;
        $jk++;
        $ygg++;
        if ($jk>$maxlines) {
          // code...
          $maxlines=$jk;
        }
      }

      $ygg=0;
      foreach($array_a_retornar as $keys)
      {
        for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
          // code...
          $array_a_retornar[$ygg]["valor"][]="";
          $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
          $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
        }
        $ygg++;
      }
      $data=$array_a_retornar;
      $total_lineas=count($data[0]["valor"]);
      $total_columnas=count($data);

         
      $he = 4*$total_lineas;   
      for ($i=0; $i < $total_lineas; $i++) {
        // code...
        $y = $this->GetY();
        if($y + $he > 274){
            $this-> AddPage();
        }
        for ($j=0; $j < $total_columnas; $j++) {
          // code...
          $salto=0;
          $abajo="LR";
          if ($i==0) {
            // code...
            $abajo="TLR";
          }
          if ($j==$total_columnas-1) {
            // code...
            $salto=1;
          }
          if ($i==$total_lineas-1) {
            // code...
            $abajo="BLR";
          }
          if ($i==$total_lineas-1&&$i==0) {
            // code...
            $abajo="1";
          }
          // if ($j==0) {
          //   // code...
          //   $abajo="0";
          // }
          $str = $data[$j]["valor"][$i];
          if ($str=="\b")
          {
            $abajo="0";
            $str="";
          }

       
          $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i],1);
          
        }
        if($xss){

          $this->SetX(10);
        }
      }
      /*
      $arreglo_valores = array();
      $hei = 4 * $total_lineas;
        for($i = 0; $i < $total_columnas ; $i++){
            $valor_p="";
            $size_p = 0;
            for($j = 0; $j < $total_lineas; $j++){
                $valor_p.=" ".$data[$i]["valor"][$j];
                $size_p=$data[$i]["size"][$j];
            }
            $arreglo_valores[] = array(
                'valor' => $valor_p,
                'size' => $size_p
            );
        }
        $count = 0;
        $y = $this->GetY();
        if($y + $hei > 274){
            $this-> AddPage();
        }
        foreach ($arreglo_valores as $key => $value) {
            if($count == 0){
                $this->drawTextBox($value['valor'], $value['size'], $hei, "C", 'M',1,1);
            }
            else{

                $this->drawTextBox($value['valor'], $value['size'], $hei, "C", 'M',1,0);
            }
            $count++;
        }

        $this->Ln($hei);
        */
    }
    // Cabecera de página\
    var $infoext =   array();
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('latin','',8);
        // Número de página
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }
    public function Header()
    {
        if ($this->PageNo() == 1){
            // Logo
            $set_x = 10;
            $set_y = 12;
            $this->SetXY(-100, -100);
            $this->SetFillColor(255,0,0);

            $this->Image($this->c,$set_x+15,$set_y+5,30,30);
            $this->SetDrawColor(172,214,226);
            $this->Line(13,13,203,13);
            $this->Image("img/linea_amarilla.jpg",$set_x,$set_y+37,196,7);
            $this->SetDrawColor(0,0,0);
            $set_x = 0;
            $this->AddFont('latin','','latin.php');
            $this->SetFont('latin', '', 13);
            // Movernos a la derecha

            //NOMBRE General
            $set_y = 17;
            $set_x = 31;
            $this->SetXY($set_x, $set_y+7);
            $this->SetTextColor(44, 116, 180);
            $this->SetFont('latin','',17);
            $this->Cell(195,7,utf8_decode("LABORATORIO CLINICO MIGUELEÑO").".",0,1,'C');
            $this->SetFont('latin','',14);
            $this->SetXY($set_x, $set_y+14);
            $this->SetTextColor(255, 45, 45);
            $this->Cell(195,7,"Donde la Calidad y Experiencia Hacen la Diferencia".".",0,1,'C');

            //DATOS CASA MATRIZ
            $this->SetFont('latin','',10);
            $set_x=30;
            $set_y += 0;
            $id_sucursal = $_SESSION["id_sucursal"];
            if($id_sucursal==1)
            {
                $this->SetTextColor(0,0,128);
            }
            else
            {
                $this->SetTextColor(0,0,0);
            }
            $this->SetTextColor(0,0,0);
            $this->SetFont('latin','',12);
            $this->SetXY(95, 40);
            $this->MultiCell(150,4,utf8_decode("labclinicomigueleno@hotmail.com"));
            $this->SetXY(15, 47);
            $this->Cell(195,4,utf8_decode($this->infoext['sitio']),0,0,"C");
            $this->SetXY(13, 55);
            $this->SetFillColor(172,214,226);
            $this->SetTextColor(0,0,0);
            $this->SetFont('latin','',10);
            $this->Cell(90,7.2,"PRUEBA DE ANTIGENO COVID-19",1,0,'C',true);
            $this->Cell(100,7.2,"FECHA DE TOMA DE MUESTRA: ".$this->infoext['fecha_muestra'] ,1,0,'C',true);
            $this->SetXY(13, 70);

            $this->SetFont('latin','',10);
            $array_data = array(
                array("NOMBRE DEL PACIENTE:",90,"C"),
                array("FECHA DE NACIMIENTO:",50,"C"),
                array("EDAD:",25,"C"),
                array("SEXO:",25,"C"),         
            );
            $this->LineWriteB($array_data,0);
            $this->SetFillColor(172,214,226);
            $this->SetFont('latin','',10);
            $array_data = array(
                array(utf8_decode($this->infoext['nombre_p']),90,"C"),
                array(ED($this->infoext['edad']),50,"C"),
                array(edad($this->infoext['edad']),25,"C"),
                array($this->infoext['sexo'],25,"C"),         
            );
            $this->SetFillColor(255,255,255);
            $this->LineWriteB($array_data,0);
            $this->SetFont('latin','',10);
            $array_data = array(
                array("TIPO DE MUESTRA",90,"C"),  
                array("NUMERO DE DUI",50,"C"),
                array("NUMERO DE PASAPORTE",50,"C"),  
            );
            $this->SetFillColor(172,214,226);
            $this->LineWriteB($array_data,0);
            $this->SetFont('latin','',10);
            $array_data = array(
                array($this->infoext['muestra'],90,"C"),  
                array($this->infoext['dui'],50,"C"),
                array($this->infoext['pasaporte'],50,"C"),  
            );
            $this->SetFillColor(255,255,255);
            $this->LineWriteB($array_data,0);
            $y = $this->GetY();
            $this->SetY($y+7);
            $hora_actual = date('H:i:s');
            $hora_actual = _hora_media_decode($hora_actual);
            $this->SetFont('latin','',10);
            $this->SetFillColor(172,214,226);
            $this->Cell(90,10,"INFORMACION ACERCA DE LA MUESTRA",1,1,'C',1);
            $this->SetXY(103,$y+7);
            $this->Cell(60,5,"HORA DE TOMA DE LA MUESTRA",1,1,'C',1);
            $this->SetXY(163,$y+7);
            $this->Cell(40,5,"HORA DE REPORTE",1,1,'C',1);
            $this->SetXY(103,$y+12);
            $this->SetFont('latin','',10);
            $this->Cell(60,5,$this->infoext['hora_muestra'],1,1,'C');
            $this->SetXY(163,$y+12);
            $this->Cell(40,5,$this->infoext['hora_reporte'],1,1,'C');
            
        }
      

    }
    public function set($value,$tel,$logo,$jdas,$pas,$infoext)
    {
      $this->a=$value;
      $this->b=$tel;
      $this->c=$logo;
      $this->d=$jdas;
      $this->e=$pas;
      $this->infoext = $infoext;
    }
    public function headexa($altura,$nn)
    {
      $this->m=$altura;
      $this->v=$catt;

      $this->SetFont('latin','',11);
      $this->SetXY($set_x, $this->m-5);
      $this->SetFillColor(178, 207, 255);
      $this->Cell(215,5,utf8_decode($this->v),0,1,'C',1);
      $this->SetFillColor(255,255,255);
      $this->Line(10,$this->m,205, $this->m);
      $this->SetFont('latin','',10);

      $this->SetXY(70, $this->m);
      $this->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
      $this->SetXY(110, $this->m);
      if($this->e==0)
      {
        $this->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
        $this->SetXY(170, $this->m);
      }
      $this->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
    }
    public function headexaC($altura,$catt)
    {
        $this->m=$altura+175;
        $this->v=$catt+175;

        $this->SetFont('latin','',11);
        $this->SetXY($set_x, $this->m-5);
        $this->SetFillColor(178, 207, 255);
        $this->Cell(215,5,utf8_decode($this->d),0,1,'C',1);
        $this->SetFillColor(255,255,255);
        $this->Line(10,$this->m,205, $this->m);
        $this->SetFont('latin','',10);
        //$this->SetXY(10, $this->m);
        //$this->Cell(50,5,utf8_decode("PARAMETRO"),0,1,'L');
        $this->SetXY(70, $this->m);
        $this->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
        $this->SetXY(110, $this->m);
        if($this->e==0)
        {
          $this->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
          $this->SetXY(170, $this->m);
        }
        $this->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
    }
}

date_default_timezone_set("America/El_Salvador");
$pdf = new PDF('P','mm', 'Letter');
$jdas="";
$pdf->set($nombrelab,$telefono1,$logo,$jdas,1,$infoext);
$pdf->SetMargins(15,15);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(13);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,15);
$pdf->AddFont('Georgia','','georgia.php');
$pdf->AddFont('Calibri','','calibri.php');
$pdf->AddFont('Calibri','B','calibrib.php');
$pdf->AddFont('latin','','latin.php');
$pdf->AddFont('GeorgiaI','','GeorgiaI.php');
$pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
$pdf->AddPage();

//QUERY PARA DATOS DEL PACIENTE
$sql = "SELECT examen.nombre_examen, examen_paciente.id_examen_paciente, examen_paciente.resultados, concat(doctor.nombre,' ',doctor.apellido) as 'nombre_doctor' FROM examen_paciente INNER JOIN examen on examen.id_examen = examen_paciente.id_examen LEFT JOIN doctor on examen_paciente.id_doctor = doctor.id_doctor WHERE examen_paciente.id_examen_paciente = '$id_examen_paciente'  AND examen_paciente.estado_realizado='Hecho'";

    $sql_datos=_query($sql);
    
    while($row = _fetch_array($sql_datos)){
        $nombre_examen = $row['nombre_examen'];
        $resultados = $row['resultados'];
        $nombre_doctor = $row['nombre_doctor'];
        $id_examen_paciente = $row['id_examen_paciente'];
       
        $pdf->SetFillColor(172,214,226);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('latin','',10);
        $pdf->SetXY(40, 110);
        $pdf->SetLineWidth(.1);
        $pdf->SetFont('latin','',9);
        $pdf->Cell(90,7.2,"TEST",1,0,'C',true);
        $pdf->Cell(35,7.2,"RESULTADO",1,0,'C',true);
        $yi=$pdf->GetY();
        $pdf->SetXY(13, $yi+7.2);
        $pdf->SetFillColor(255,255,255);
        $formulario = explode("#", $resultados);
        for($i=0; $i<(count($formulario)-1); $i++)
        {
            
            $campos_valores= explode("|", $formulario[$i]);
            $pdf->SetFont('latin','',9);
            $val = str_replace("*", " \n ", $campos_valores[3]);
            $pdf->SetLineWidth(.1);
            $pdf->SetX(40);
            $array_data = array(
                array(Mayu($campos_valores[0]),90,"C"),
                array(Mayu($campos_valores[1]),35,"C"),   
            );
            $pdf->LineWriteB($array_data,0);
        }
        
    }
    $yi=$pdf->GetY();
    $pdf->SetDrawColor(172,214,226);
    $pdf->Line(13,$yi,203,$yi);
    $pdf->Line(13,$yi,13, 13);
    $pdf->Line(203,$yi,203, 13);

    $pdf->SetXY(140,210);
    $pdf->Cell(60,5,"FIRMA Y SELLO",0,1,'C',1);
    $pdf->Line(13,13,203,13);
    $pdf->Image("img/linea_amarilla.jpg",10,220,196,7);
    $pdf->SetXY(10,230);
    $array_data = array(
      array(utf8_decode("CASA MATRIZ
      Centro Médico Migueleño.
      8ª Calle Poniente Nº 505
      Tel: 2661 – 3982"),63.33333333,"C"),
      array(utf8_decode("SUCURSAL Nº 1
      CLINICA DE ESPECIALIDADES
      MEDICAS “SANTA GERTRUDIS”
      9ª Avenida Sur N° 201.
      Tel: 2661-2450"),63.33333333,"C"),   
      array(utf8_decode("SUCURSAL Nº 2
      Edificio Gasco S.A. de C.V.
      Calle La Paz Nº 5, entre Av. Roosevelt y 9ª Av. Sur frente a Emergencia de Hospital Nuestra. Sra. de la Paz
      Tels: 2661–0310; 2660–2805."),63.33333333,"C"),  
  );
  $pdf->LineWriteB($array_data,1);


  $id_encriptado = md5($id_examen_paciente);
  $nombre_examen_paciente = "reportes_covid/impresion_examen_covid_prueba_".$id_encriptado.".pdf";
  if (!file_exists($nombre_examen_paciente)) {
      // include QR_BarCode class 
      include "QR_BarCode.php"; 

      // QR_BarCode object 
      $qr = new QR_BarCode(); 
      // create text QR code 
      $qr->url("http://labsm.apps-oss.com/".$nombre_examen_paciente); 
      // display QR code image
      //$qr->qrCode();
      $qr->qrCode(350,'imagenes_qr/'.$id_encriptado.".png");
  }
  $pdf->Image(('imagenes_qr/'.$id_encriptado.".png"),35,165,50,50);

//DATOS DEL PACIENTE

ob_clean();
if(!file_exists($nombre_examen_paciente)){
    $pdf->Output($nombre_examen_paciente,"F");
    header('Location: impresion_constancia_covid19.php?id_examen_paciente='.$id_examen_paciente);
}
else{
    $pdf->Output($nombre_examen_paciente,"I");
}

?>
