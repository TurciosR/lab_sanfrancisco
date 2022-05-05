<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$id_sucursal=$_SESSION["id_sucursal"];

$id_cobro=$_REQUEST["id_cobro"];

$sqll = _query("SELECT * FROM sucursal where id_sucursal='$id_sucursal'");
$fila = _fetch_array($sqll);
$nombrelab = $fila["nombre_lab"];
$direccion = $fila["direccion"];
$telefono1 = $fila["telefono1"];
$telefono2 = $fila["telefono2"];
$logo = $fila["logo"];
$correo = $fila["email"];
$sitio = $fila["website"];

class PDF extends FPDF{
    // Cabecera de página\
    public function Header()
    {
      // Logo
      $set_x = 10;
      $set_y = 12;
      $this->Image($this->c,$set_x-3,$set_y-5,27,30);
      $set_x = 0;
      $this->AddFont('latin','','latin.php');
      $this->SetFont('latin', '', 13);
      // Movernos a la derecha

      //NOMBRE General
      $this->SetTextColor(51, 51, 153);
      $this->SetFont('GeorgiaBI','',17);
      $this->SetXY($set_x+3, $set_y);
      $this->Cell(215,6,utf8_decode('LABORATORIO CLINICO "MIGUELEÑO"'),0,1,'C');

      //DATOS CASA MATRIZ
      $this->SetFont('latin','',10);
      $set_x=30;
      $set_y += 3;
      $id_sucursal = $_SESSION["id_sucursal"];
      if($id_sucursal==1)
      {
        $this->SetTextColor(51, 51, 153);
      }
      else
      {
        $this->SetTextColor(0,0,0);
      }
      $this->SetXY($set_x, $set_y+7);
      $this->Cell(50,6,utf8_decode("CASA MATRIZ"),0,0,"C");
      $this->SetXY($set_x, $set_y+11);
      $this->SetFont('latin','',10);
      $this->Cell(50,6,utf8_decode("Laboratorio Clínico Migueleño."),0,0,"C");
      $this->SetXY($set_x, $set_y+15);
      $this->Cell(50,6,utf8_decode("8a. Calle Poniente No.505"),0,0,"C");
      $this->SetXY($set_x, $set_y+19);
      $this->Cell(50,6,utf8_decode("Tel: 2661-3982"),0,0,"C");

      //DATOS SUCURSAL 1
      $set_x=70;
      $this->SetFont('latin','',10);
      if($id_sucursal==2)
      {
        $this->SetTextColor(51, 51, 153);
      }
      else
      {
        $this->SetTextColor(0,0,0);
      }
      $this->SetXY($set_x, $set_y+7);
      $this->Cell(80,6,utf8_decode("SUCURSAL No.1"),0,0,"C");
      $this->SetXY($set_x, $set_y+11);
      $this->Cell(80,6,utf8_decode("Clinica de Especialidades"),0,0,"C");
      $this->SetXY($set_x, $set_y+15);
      $this->Cell(80,6,utf8_decode('Medicas "SANTA GERTRUDIS"'),0,0,"C");
      $this->SetFont('latin','',10);
      $this->SetXY($set_x, $set_y+19);
      $this->Cell(80,6,utf8_decode("9a. Avenida No.201"),0,0,"C");
      $this->SetXY($set_x, $set_y+23);
      $this->Cell(80,6,utf8_decode("Tel:2661-2450"),0,0,"C");

      //DATOS SUCURSAL 2
      $set_x=135;
      if($id_sucursal==3)
      {
        $this->SetTextColor(51, 51, 153);
      }
      else
      {
        $this->SetTextColor(0,0,0);
      }
      $this->SetFont('latin','',10);
      $this->SetXY($set_x, $set_y+7);
      $this->Cell(80,6,utf8_decode("SUCURSAL No.2"),0,0,"C");
      $this->SetXY($set_x, $set_y+11);
      $this->Cell(80,6,utf8_decode("EDIFICIO GASCO S.A. de C.V."),0,0,"C");
      $this->SetXY($set_x, $set_y+15);
      $this->SetFont('latin','',10);
      $this->Cell(84,6,utf8_decode("Av. Roosevelt y 9a Av. Sur, Fte a Emergencias"),0,0,"C");
      $this->SetXY($set_x, $set_y+19);
      $this->Cell(80,6,utf8_decode("de Hospital Nuestra Señora de la Paz."),0,0,"C");
      $this->SetXY($set_x, $set_y+23);
      $this->Cell(80,6,utf8_decode("Tel:2660-2805"),0,0,"C");
      $this->SetTextColor(0,0,0);
        //$this->Ln(5);
    }

    public function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetXY(10,-10);
        // latin italic 8
        $this->SetFont('latin', '', 9);
        // Número de página requiere $pdf->AliasNbPages();
        //utf8_decode() de php que convierte nuestros caracteres a ISO-8859-1
        $this-> Cell(40, 10, utf8_decode(date('d/m/Y')), 0, 0, 'L');
        $this->SetXY(180,-10);
        $this->Cell(50,5,utf8_decode(Mayu("Firma y Sello.")),0,1,'L');
        $this->Line(50,-10,60, -10);
        //$this->Cell(156, 10, utf8_decode('Pag. ').$this->PageNo().' de {nb}', 0, 0, 'R');
    }
    public function set($value,$tel,$logo,$jdas,$pas)
    {
      $this->a=$value;
      $this->b=$tel;
      $this->c=$logo;
      $this->d=$jdas;
      $this->e=$pas;
    }
    public function headexa($altura){
      $this->m=$altura;

      $this->SetFont('latin','',11);
      $this->SetXY($set_x, $this->m-5);
      $this->SetFillColor(178, 207, 255);
      $this->Cell(215,5,utf8_decode($this->d),0,1,'C',1);
      $this->SetFillColor(255,255,255);
      $this->Line(10,$this->m,205, $this->m);
      $this->SetFont('latin','',10);
      $this->SetXY(10, $this->m);
      $this->Cell(50,5,utf8_decode("PARAMETRO"),0,1,'L');
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
$sqlt =_query("SELECT e.id_examen as examen FROM cobro as c JOIN examen_paciente as ep ON ep.id_cobro=c.id_cobro
JOIN examen as e ON ep.id_examen=e.id_examen WHERE c.id_cobro='$id_cobro' AND ep.estado_realizado='Hecho' AND c.id_sucursal='1'
ORDER BY e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC LIMIT 1");

$rowf = _fetch_array($sqlt);
$valor = $rowf["examen"];

if($valor==8 or $valor==11 or $valor==7 or $valor==9 or $valor==2){
  $pdf = new PDF('P','mm', 'Letter');
  $examenv=true;
}
else{
  $pdf=new PDF('P','mm', 'Letter');
}

$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,5);
$pdf->AddFont('Georgia','','georgia.php');
$pdf->AddFont('latin','','latin.php');
$pdf->AddFont('GeorgiaI','','GeorgiaI.php');
$pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
$jdas="";
$pdf -> set($nombrelab,$telefono1,$logo,$jdas,1);

$pdf->AddPage();

$sqlcobro=_query("SELECT mu.muestra,c.id_cobro,CONCAT(p.nombre,' ',p.apellido) as paciente,p.sexo,p.fecha_nacimiento,ep.id_examen_paciente,CONCAT(dr.nombre,' ',dr.apellido) as doctor,pr.nombre as procedencia
  FROM cobro as c
  JOIN detalle_cobro as dc ON dc.id_cobro=c.id_cobro
  JOIN examen_paciente as ep ON ep.id_cobro=c.id_cobro
  JOIN paciente as p ON p.id_paciente=ep.id_paciente
  LEFT JOIN doctor as dr ON dr.id_doctor=ep.id_doctor
  JOIN muestra as mu ON mu.id_muestra=ep.id_muestra
  LEFT JOIN procedencia as pr ON pr.id_procedencia=ep.procedencia
  WHERE c.id_cobro='$id_cobro' AND ep.estado_realizado='Hecho' GROUP BY ep.id_examen_paciente");
  $rowcobro = _fetch_array($sqlcobro);
  $paciente = $rowcobro["paciente"];
  $doctor = $rowcobro["doctor"];
  $procedencia = Mayu($rowcobro["procedencia"]);
  $edad= edad($rowcobro["fecha_nacimiento"]);
  $sexo = $rowcobro["sexo"];
  $muestra = $rowcobro["muestra"];
  if($sexo=="FEMENINO"){
    $sexo="F";
  }
  else if($sexo=="MASCULINO"){
    $sexo="M";
  }
  if($edad==1){
    $edad=$edad." AÑO";
  }
  if($edad>1){
    $edad=$edad." AÑOS";
  }
  //-----------------------------------------------------------------------
  //--------------------DATOS PACIENTE-------------------------------------
  //-----------------------------------------------------------------------
  $set_y = 45;
  $set_x = 13;
  $pdf->SetFont('latin','',10);
  $pdf->SetXY($set_x-5, $set_y);
  $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
  $pdf->SetXY($set_x+135, $set_y);
  $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
  $pdf->SetXY($set_x+160, $set_y);
  $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

  $pdf->SetFont('latin','',10);
  $pdf->SetXY($set_x+16, $set_y);
  $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
  //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
  $pdf->SetXY($set_x+153, $set_y);
  $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
  //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
  $pdf->SetXY($set_x+173, $set_y);
  $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
  //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

  if($procedencia!="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
    $pdf->SetXY($set_x-5, $set_y+16);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetXY($set_x+135, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    $pdf->SetXY($set_x+23, $set_y+16);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
    //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
    $pdf->SetXY($set_x+155, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
  }
  else if ($procedencia=="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
    $pdf->SetXY($set_x+135, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+12, $set_y+8);
    //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    $pdf->SetXY($set_x+155, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
  }
  else if ($procedencia!="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetXY($set_x-5, $set_y+16);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    $pdf->SetXY($set_x+23, $set_y+16);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
    //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
  }
  else if ($procedencia=="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
  //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

  }
  $set_y = 65;
  $primerparametro=1;
  $set_x = 10;
  $page = 0;
  $j=0;
  $mm = 0;
  $i = 1;
  $restante=90;
  $pagenew=false;
  $cuadroE=0;
$sqlcobro1=_query("SELECT c.id_cobro,ep.id_examen_paciente,e.id_examen,e.nombre_examen FROM cobro as c
  JOIN detalle_cobro as dc ON dc.id_cobro=c.id_cobro
  JOIN examen_paciente as ep ON ep.id_cobro=c.id_cobro
  JOIN examen as e ON e.id_examen=ep.id_examen
  LEFT JOIN examen_perfil epe ON epe.id_examen=e.id_examen
  LEFT JOIN perfil as pe ON pe.id_perfil=epe.id_perfil
  WHERE c.id_cobro='$id_cobro' AND ep.estado_realizado='Hecho' GROUP BY ep.id_examen_paciente ORDER BY e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC ");
  while($rowcobro1 = _fetch_array($sqlcobro1)){
  $id_examen_paciente = $rowcobro1["id_examen_paciente"];
  $sql3=_query("SELECT resultados,examen.nombre_examen,examen.id_examen,categoria.nombre_categoria
    FROM examen_paciente,examen,categoria WHERE examen_paciente.estado_realizado='Hecho' AND examen_paciente.id_examen = examen.id_examen
    AND categoria.id_categoria=examen.id_categoria AND examen_paciente.id_examen_paciente='$id_examen_paciente' ");
    while($rowexa = _fetch_array($sql3)){
      $nombre_examen = $rowexa["nombre_examen"];
      $n_categoria = $rowexa["nombre_categoria"];
      $aidi = $rowexa["id_examen"];
      $valores=$rowexa["resultados"];
      $formulario = explode("#", $valores);
      if($pagenew==true){
        $page++;
        for($x=0; $x<(count($formulario)-1); $x++){
          $conteoparametros+=1;
        }
        if($conteoparametros>5){
          if($aidi==16 or $aidi==22 or $aidi==14 or $aidi==18){
            $pdf->AddPage('P','Letter');
            $pagec=true;
            $pagel=false;
          }
          else
          {
            $pdf->AddPage('P','Letter');
            $pagel=true;
            $pagec=false;
          }

            //-----------------------------------------------------------------------
              //--------------------DATOS PACIENTE-------------------------------------
              //-----------------------------------------------------------------------
              $set_y = 45;
              $set_x = 13;
              $pdf->SetFont('latin','',10);
              $pdf->SetXY($set_x-5, $set_y);
              $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
              $pdf->SetXY($set_x+135, $set_y);
              $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
              $pdf->SetXY($set_x+160, $set_y);
              $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');
              $pdf->SetXY($set_x-5, $set_y);
              $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
              $pdf->SetXY($set_x+135, $set_y);
              $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
              $pdf->SetXY($set_x+160, $set_y);
              $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');
              $pdf->SetXY($set_x-5, $set_y);
              $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
              $pdf->SetXY($set_x+135, $set_y);
              $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
              $pdf->SetXY($set_x+160, $set_y);
              $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

              $pdf->SetFont('latin','',10);
              $pdf->SetXY($set_x+16, $set_y);
              $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
              //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
              $pdf->SetXY($set_x+153, $set_y);
              $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
              //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
              $pdf->SetXY($set_x+173, $set_y);
              $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
              //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

              if($procedencia!="" AND $doctor!=""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                $pdf->SetXY($set_x+23, $set_y+16);
                $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                $pdf->SetXY($set_x+155, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
              }
              else if ($procedencia=="" AND $doctor!=""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+12, $set_y+8);
                //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                $pdf->SetXY($set_x+155, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
              }
              else if ($procedencia!="" AND $doctor==""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                $pdf->SetXY($set_x+23, $set_y+16);
                $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
              }
              else if ($procedencia=="" AND $doctor==""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
              //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

              }
              $set_y = 65;
            $set_x = 10;
            $j=0;
            $mm=0;
        }
      }
      $mm=0;
      $conteoparametros=0;
      for($i=0; $i<(count($formulario)-1); $i++){
        $conteoparametros+=1;
      }
      if($conteoparametros==1){
        if($cuadroE==1){
          $page++;
          $pdf->AddPage();
          //-----------------------------------------------------------------------
          //--------------------DATOS PACIENTE-------------------------------------
          //-----------------------------------------------------------------------
          $set_y = 45;
          $set_x = 13;
          $pdf->SetFont('latin','',10);
          $pdf->SetXY($set_x-5, $set_y);
          $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
          $pdf->SetXY($set_x+135, $set_y);
          $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
          $pdf->SetXY($set_x+160, $set_y);
          $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

          $pdf->SetFont('latin','',10);
          $pdf->SetXY($set_x+16, $set_y);
          $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
          //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
          $pdf->SetXY($set_x+153, $set_y);
          $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
          //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
          $pdf->SetXY($set_x+173, $set_y);
          $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
          //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

          if($procedencia!="" AND $doctor!=""){
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x-5, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
            $pdf->SetXY($set_x-5, $set_y+16);
            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
            $pdf->SetXY($set_x+135, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
            $pdf->SetFont('latin','',10);
            //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
            $pdf->SetXY($set_x+15, $set_y+8);
            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
            $pdf->SetXY($set_x+23, $set_y+16);
            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
            //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
            $pdf->SetXY($set_x+155, $set_y+8);
            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
            //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
          }
          else if ($procedencia=="" AND $doctor!=""){
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x-5, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
            $pdf->SetXY($set_x+135, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x+12, $set_y+8);
            //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
            $pdf->SetXY($set_x+155, $set_y+8);
            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
            //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
          }
          else if ($procedencia!="" AND $doctor==""){
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x-5, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
            $pdf->SetXY($set_x-5, $set_y+16);
            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
            $pdf->SetFont('latin','',10);
            //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
            $pdf->SetXY($set_x+15, $set_y+8);
            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
            $pdf->SetXY($set_x+23, $set_y+16);
            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
            //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
          }
          else if ($procedencia=="" AND $doctor==""){
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x-5, $set_y+8);
            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
            $pdf->SetFont('latin','',10);
            $pdf->SetXY($set_x+15, $set_y+8);
            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
          //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

          }
          $set_y = 65;
          $set_x = 10;
          $j=0;
          $mm=0;
        }
        if($primerparametro==1){
          $j++;
          $set_y+=8;
          $pas=0;
          $pdf-> set($nombrelab,$telefono1,$logo,$n_categoria."(".$nombre_examen.")",$pas);
          $pdf->headexa($set_y);
          $j++;
          $set_y+=8;
          $restante-=5;
        }
        for($i=0; $i<(count($formulario)-1); $i++){
          if($page==0)
            $salto = 25;
          else
            $salto = 25;
          if($j==$salto){
              $page++;
              $pdf->AddPage();
              //-----------------------------------------------------------------------
              //--------------------DATOS PACIENTE-------------------------------------
              //-----------------------------------------------------------------------
              $set_y = 45;
              $set_x = 13;
              $pdf->SetFont('latin','',10);
              $pdf->SetXY($set_x-5, $set_y);
              $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
              $pdf->SetXY($set_x+135, $set_y);
              $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
              $pdf->SetXY($set_x+160, $set_y);
              $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

              $pdf->SetFont('latin','',10);
              $pdf->SetXY($set_x+16, $set_y);
              $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
              //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
              $pdf->SetXY($set_x+153, $set_y);
              $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
              //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
              $pdf->SetXY($set_x+173, $set_y);
              $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
              //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

              if($procedencia!="" AND $doctor!=""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                $pdf->SetXY($set_x+23, $set_y+16);
                $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                $pdf->SetXY($set_x+155, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
              }
              else if ($procedencia=="" AND $doctor!=""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+12, $set_y+8);
                //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                $pdf->SetXY($set_x+155, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
              }
              else if ($procedencia!="" AND $doctor==""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetXY($set_x-5, $set_y+16);
                $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                $pdf->SetXY($set_x+23, $set_y+16);
                $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
              }
              else if ($procedencia=="" AND $doctor==""){
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+15, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
              //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

              }
              $set_y = 65;
              $set_x = 10;
              $j=0;
              $mm=0;
          }
          $pdf->SetFont('latin','',9);
          $campos_valores= explode("|", $formulario[$i]);
          $pdf->SetXY($set_x, $set_y+$mm);
          $pdf->MultiCell(50,3,utf8_decode(Mayu($nombre_examen)),0,1,'L');
          $pdf->SetXY($set_x+60, $set_y+$mm);
          $pdf->Cell(50,3,utf8_decode(Mayu($campos_valores[1])),0,1,'L');
          //$pdf->SetXY($set_x+102, $set_y+$mm);
          $pdf->SetXY($set_x+170, $set_y+$mm);
          $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
          //$pdf->Cell(40,3,Mayu(utf8_decode($campos_valores[3])),0,1,'L');
          //$division = $campos_valores[3];//= explode(",", $campos_valores[3]);
          $division= explode("*", $campos_valores[3]);
          for($k=0; $k<=(count($division)-1); $k++){
            if($page==0)
                $salto = 25;

            else
              $salto = 25;
              if($salto==$j){
              if($aidi==16 or $aidi==22 or $aidi==14 or $aidi==18){
                  $pdf->AddPage('P','Letter');
                }
                else
                {
                  $pdf->AddPage('P','Letter');
                }
                //-----------------------------------------------------------------------
                //--------------------DATOS PACIENTE-------------------------------------
                //-----------------------------------------------------------------------
                $set_y = 45;
                $set_x = 15;
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y);
                $pdf->Cell(20,5,utf8_decode("Paciente:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y);
                $pdf->Cell(20,5,utf8_decode("Género:"),0,1,'L');
                $pdf->SetXY($set_x+160, $set_y);
                $pdf->Cell(20,5,utf8_decode("Edad:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+9, $set_y);
                $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
                $pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
                $pdf->SetXY($set_x+150, $set_y);
                $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                $pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
                $pdf->SetXY($set_x+170, $set_y);
                $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                $pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);
                if($procedencia!="" AND $doctor!=""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Muestra:"),0,1,'L');
                  $pdf->SetXY($set_x+45, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Procedencia:"),0,1,'L');
                  $pdf->SetXY($set_x+130, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Médico:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                  $pdf->SetXY($set_x+13, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  $pdf->SetXY($set_x+65, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                  $pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                  $pdf->SetXY($set_x+142, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                  $pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
                  $set_y = 65;
                }
                else if ($procedencia=="" AND $doctor!=""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Muestra:"),0,1,'L');
                  $pdf->SetXY($set_x+95, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Médico:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x+13, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  $pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                  $pdf->SetXY($set_x+115, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                  $pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
                  $set_y = 65;
                }
                else if ($procedencia!="" AND $doctor==""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Muestra:"),0,1,'L');
                  $pdf->SetXY($set_x+80, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Procedencia:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                  $pdf->SetXY($set_x+13, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  $pdf->SetXY($set_x+100, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                  $pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
                  $set_y = 65;
                }
                else if ($procedencia=="" AND $doctor==""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("Muestra:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x+13, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);
                  $set_y = 65;
                }
                $set_x = 10;
                $j=0;
                $mm=0;
            }
            $pdf->SetXY($set_x+102, $set_y+$mm);
            $pdf->MultiCell(60,3,utf8_decode($division[$k]),0,1,'L');
            $mm+=8;
            $final=true;
            $j++;
          }
          //$mm+=8;
          $restante-=5;
          $j++;
          if($final==true){
            $m=$m;
          }else{
            $mm+=8;
          }

        }

        $cuadroE=0;
        $final=false;
        $primerparametro=0;
      }
      if($conteoparametros>1){
       $set_y+=8;
        $pas = 1;
        if(strpos($nombre_examen,'GENERAL')!==false)
        {
          $pas = 0;
        }
        if(trim($nombre_examen) == "HEMOGRAMA")
        {
          $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
        }
        else
        {
          $pdf-> set($nombrelab,$telefono1,$logo,$n_categoria."(".$nombre_examen.")",$pas);
        }
        $pdf->headexa($set_y);
        $j++;
        $set_y+=8;
        $restante-=10;
        for($i=0; $i<(count($formulario)-1); $i++){
          $pdf->SetFont('latin','',9);
          $campos_valores= explode("|", $formulario[$i]);
          if($campos_valores[4]=='s'){
            $pdf->SetFont('latin','',9);
            $pdf->SetXY($set_x, $set_y+$mm);
            $pdf->Cell(55,5,utf8_decode(Mayu($campos_valores[0])),1,1,'C');
            $mm+=6;
            $restante-=5;
            $j++;
          }
          else if($campos_valores[4]=='p'){
            $final=false;
            if($page==0){
              $salto = 25;
            }
            else{
              $salto = 25;
            }
            if($j==$salto){
                $page++;
              if($aidi==16 or $aidi==22 or $aidi==14 or $aidi==18){
                  $pdf->AddPage('P','Letter');
                }
              else
                {
                  $pdf->AddPage('P','Letter');
                }
                //-----------------------------------------------------------------------
                //--------------------DATOS PACIENTE-------------------------------------
                //-----------------------------------------------------------------------
                $set_y = 45;
                $set_x = 13;
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x-5, $set_y);
                $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
                $pdf->SetXY($set_x+135, $set_y);
                $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
                $pdf->SetXY($set_x+160, $set_y);
                $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+16, $set_y);
                $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
                //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
                $pdf->SetXY($set_x+153, $set_y);
                $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
                $pdf->SetXY($set_x+173, $set_y);
                $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

                if($procedencia!="" AND $doctor!=""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                  $pdf->SetXY($set_x-5, $set_y+16);
                  $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                  $pdf->SetXY($set_x+135, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                  $pdf->SetXY($set_x+15, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                  $pdf->SetXY($set_x+23, $set_y+16);
                  $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                  //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                  $pdf->SetXY($set_x+155, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
                }
                else if ($procedencia=="" AND $doctor!=""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                  $pdf->SetXY($set_x+135, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x+12, $set_y+8);
                  //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                  $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                  $pdf->SetXY($set_x+155, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
                }
                else if ($procedencia!="" AND $doctor==""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                  $pdf->SetXY($set_x-5, $set_y+16);
                  $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                  $pdf->SetXY($set_x+15, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                  $pdf->SetXY($set_x+23, $set_y+16);
                  $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                  //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
                }
                else if ($procedencia=="" AND $doctor==""){
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x-5, $set_y+8);
                  $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x+15, $set_y+8);
                  $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

                }
                $set_y = 65;
                $set_x = 10;
                $j=0;
                $mm=0;
            }
            $pdf->SetFont('latin','',9);
            $pdf->SetXY($set_x, $set_y+$mm);
            $pdf->MultiCell(55,3,utf8_decode(Mayu($campos_valores[0])),0,1,'L');
            $pdf->SetXY($set_x+60, $set_y+$mm);
            //if(intval($campos_valores[1]) >= 1000000)
            //{
              if(count(explode(",",$campos_valores[1]))>2)
              {
                $dats_print = explode(",",$campos_valores[1]);
                $pdf->SetFont('latin','',10);
                $pdf->Cell(2,3,$dats_print[0],0,1,'L');
                $pdf->SetXY($set_x+62, $set_y+$mm+1);
                $pdf->SetFont('latin','',6);
                $pdf->Cell(1,3,"1",0,1,'L');
                $pdf->SetXY($set_x+63, $set_y+$mm);
                $pdf->SetFont('latin','',10);
                $pdf->Cell(3,3,$dats_print[1].",".$dats_print[2],0,1,'L');
              }
              else
              {
                  $pdf->Cell(50,3,utf8_decode(Mayu($campos_valores[1])),0,1,'L');
              }
            $pdf->SetXY($set_x+170, $set_y+$mm);
            $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
            if($aidi==9){
              if(Mayu($campos_valores[0])=="HEMATIES" or Mayu($campos_valores[0])=="PIOCITOS"){
                $pdf->SetXY($set_x+150, $set_y+$mm);
                $pdf->Cell(30,5,utf8_decode("X Campo"),0,1,'L');
              }
            }
            if($aidi==7){
              if(trim(Mayu($campos_valores[0]))=="CILINDROS" or trim(Mayu($campos_valores[0]))=="LEUCOCITOS" or trim(Mayu($campos_valores[0]))=="HEMATIES"){
                $pdf->SetXY($set_x+150, $set_y+$mm);
                $pdf->Cell(30,5,utf8_decode("X Campo"),0,1,'L');
              }
            }
            if($pas)
            {
              if(trim($campos_valores[3]) =="4,000,000 - 5,500,000")
              {
                    if($salto==$j){
                    if($aidi==16 or $aidi==22 or $aidi==14 or $aidi==18){
                        $pdf->AddPage('P','Letter');
                      }
                      else
                      {
                        $pdf->AddPage('P','Letter');
                      }
                      //-----------------------------------------------------------------------
                      //--------------------DATOS PACIENTE-------------------------------------
                      //-----------------------------------------------------------------------
                      $set_y = 45;
                      $set_x = 8;
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x-5, $set_y);
                      $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
                      $pdf->SetXY($set_x+135, $set_y);
                      $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
                      $pdf->SetXY($set_x+160, $set_y);
                      $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+16, $set_y);
                      $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
                      //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
                      $pdf->SetXY($set_x+153, $set_y);
                      $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                      //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
                      $pdf->SetXY($set_x+173, $set_y);
                      $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                      //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

                      if($procedencia!="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
                      }
                      else if ($procedencia=="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+12, $set_y+8);
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
                      }
                      else if ($procedencia!="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
                      }
                      else if ($procedencia=="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

                      }
                      $set_y = 65;
                      $set_x = 10;
                      $j=0;
                      $mm=0;
                  }
                  $pdf->SetXY($set_x+102, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->Cell(2,3,"4",0,1,'L');
                  $pdf->SetXY($set_x+104, $set_y+$mm+1);
                  $pdf->SetFont('latin','',6);
                  $pdf->Cell(1,3,"1",0,1,'L');
                  $pdf->SetXY($set_x+105, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->Cell(3,3,"000,000",0,1,'L');
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY($set_x+120, $set_y+$mm);
                  $pdf->Cell(2,3,"-",0,1,'L');
                  $pdf->SetXY($set_x+123, $set_y+$mm);
                  $pdf->Cell(2,3,"5",0,1,'L');
                  $pdf->SetXY($set_x+125, $set_y+$mm+1);
                  $pdf->SetFont('latin','',6);
                  $pdf->Cell(1,3,"1",0,1,'L');
                  $pdf->SetXY($set_x+126, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->Cell(3,3,"500,000",0,1,'L');
                }
                else
                {
                $division= explode("*", $campos_valores[3]);
                if(count($division)>1){
                for($k=0; $k<(count($division)-1); $k++){
                  if($page==0){
                    $salto = 25;
                  }
                  else{
                    $salto = 25;
                  }
                    if($salto==$j){
                    if($aidi==16 or $aidi==22 or $aidi==14 or $aidi==18){
                        $pdf->AddPage('P','Letter');
                      }
                      else
                      {
                        $pdf->AddPage('P','Letter');
                      }
                      //-----------------------------------------------------------------------
                      //--------------------DATOS PACIENTE-------------------------------------
                      //-----------------------------------------------------------------------
                      $set_y = 45;
                      $set_x = 8;
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x-5, $set_y);
                      $pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
                      $pdf->SetXY($set_x+135, $set_y);
                      $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
                      $pdf->SetXY($set_x+160, $set_y);
                      $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');

                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+16, $set_y);
                      $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
                      //$pdf->Line($set_x+9,$set_y+4,$set_x+135, $set_y+4);
                      $pdf->SetXY($set_x+153, $set_y);
                      $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                      //$pdf->Line($set_x+147,$set_y+4,$set_x+160, $set_y+4);
                      $pdf->SetXY($set_x+173, $set_y);
                      $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                      //$pdf->Line($set_x+170,$set_y+4,$set_x+185, $set_y+4);

                      if($procedencia!="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        //$pdf->Line($set_x+65,$set_y+12,$set_x+125, $set_y+12);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+40, $set_y+12);
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        //$pdf->Line($set_x+143,$set_y+12,$set_x+190, $set_y+12);
                      }
                      else if ($procedencia=="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+12, $set_y+8);
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+90, $set_y+12);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        //$pdf->Line($set_x+110,$set_y+12,$set_x+190, $set_y+12);
                      }
                      else if ($procedencia!="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        //$pdf->Line($set_x+100,$set_y+12,$set_x+190, $set_y+12);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        //$pdf->Line($set_x+10,$set_y+12,$set_x+79, $set_y+12);
                      }
                      else if ($procedencia=="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      //  $pdf->Line($set_x+10,$set_y+12,$set_x+185, $set_y+12);

                      }
                      $set_y = 65;
                      $set_x = 10;
                      $j=0;
                      $mm=0;
                  }
                  $pdf->SetXY($set_x+102, $set_y+$mm);
                  $pdf->Cell(30,3,utf8_decode(trim($division[$k])),0,1,'L');
                  $mm+=8;
                  $final=true;
                  $j++;
                  }
                }else{
                  $pdf->SetXY($set_x+102, $set_y+$mm);
                  $pdf->Cell(30,3,utf8_decode($campos_valores[3]),0,1,'L');
                }
              }
          }
            if($final==true){
              $m=$m;
            }else{
              $mm+=8;
            }
            //$restante-=3;
            $j++;
            $final=false;
          }
        }
      }
      $conteoparametros=0;
      $cuadroE=0;
      if(Mayu($nombre_examen)=="ESPERMOGRAMA 1"){
        $set_x=20;
        $pdf->SetXY($set_x, $set_y+50);
        $pdf->Cell(60,5,utf8_decode("PARAMETRO"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+50);
        $pdf->Cell(60,5,utf8_decode("RESULTADO"),1,1,'C');
        $pdf->SetXY($set_x+120, $set_y+50);
        $pdf->Cell(60,5,utf8_decode("INFORME"),1,1,'C',true);
        $pdf->SetXY($set_x, $set_y+55);
        $pdf->Cell(60,20,utf8_decode("VOLUMEN"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+55);
        $pdf->Cell(60,5,utf8_decode("NORMAL"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+60);
        $pdf->Cell(60,5,utf8_decode("ALTO"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+65);
        $pdf->Cell(60,5,utf8_decode("BAJO"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+70);
        $pdf->Cell(60,5,utf8_decode("AUSENCIA"),1,1,'L');
        $pdf->SetXY($set_x, $set_y+75);
        $pdf->Cell(60,30,utf8_decode("RECUENTO"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+75);
        $pdf->Cell(60,5,utf8_decode("NORMAL"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+55);
        $pdf->Cell(60,5,utf8_decode("Normospermia"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+80);
        $pdf->Cell(60,5,utf8_decode("ALTO"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+60);
        $pdf->Cell(60,5,utf8_decode("Hiperesperma"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+85);
        $pdf->Cell(60,5,utf8_decode("BAJO"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+65);
        $pdf->Cell(60,5,utf8_decode("Hipoesperma"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+90);
        $pdf->Cell(60,5,utf8_decode("AUSENCIA"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+70);
        $pdf->Cell(60,5,utf8_decode("Aspermia"),1,1,'L');

        $pdf->SetXY($set_x+120, $set_y+75);
        $pdf->Cell(60,5,utf8_decode("Normozoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+80);
        $pdf->Cell(60,5,utf8_decode("Polizoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+85);
        $pdf->Cell(60,5,utf8_decode("Oligozoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+90);
        $pdf->Cell(60,5,utf8_decode("Azoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+95);
        $pdf->Cell(60,10,utf8_decode("Criptozoospermia"),1,1,'L');


        $pdf->SetXY($set_x+120, $set_y+105);
        $pdf->Cell(60,5,utf8_decode("Astenozoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+110);
        $pdf->Cell(60,5,utf8_decode("Teratozoospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+115);
        $pdf->Cell(60,5,utf8_decode("Necrozoospermia"),1,1,'L');


        $pdf->SetXY($set_x+120, $set_y+120);
        $pdf->Cell(60,5,utf8_decode(""),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+125);
        $pdf->Cell(60,5,utf8_decode("    Oligo-"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+130);
        $pdf->Cell(60,5,utf8_decode("         asteno-"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+135);
        $pdf->Cell(60,5,utf8_decode("               terato-"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+140);
        $pdf->Cell(60,5,utf8_decode("                     Zoospermia-"),1,1,'L');

        $pdf->SetXY($set_x+120, $set_y+145);
        $pdf->Cell(60,5,utf8_decode("Hemos o  Hematospermia"),1,1,'L');
        $pdf->SetXY($set_x+120, $set_y+150);
        $pdf->Cell(60,5,utf8_decode("Leucos, Leucocito o Piospermia"),1,1,'L');



        $pdf->SetXY($set_x+60, $set_y+95);
        $pdf->MultiCell(60,5,utf8_decode("AUSENCIA en fresco y PRESENCIA en el sedimento"),1,'L');
        $pdf->SetXY($set_x, $set_y+105);
        $pdf->Cell(60,5,utf8_decode("MOVILIDAD"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+105);
        $pdf->Cell(60,5,utf8_decode("BAJO"),1,1,'L');
        $pdf->SetXY($set_x, $set_y+110);
        $pdf->Cell(60,5,utf8_decode("MORFOLOGIA"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+110);
        $pdf->Cell(60,5,utf8_decode("BAJO"),1,1,'L');
        $pdf->SetXY($set_x, $set_y+115);
        $pdf->Cell(60,5,utf8_decode("VITALIDAD"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+115);
        $pdf->Cell(60,5,utf8_decode("BAJO"),1,1,'L');
        $pdf->SetXY($set_x, $set_y+120);
        $pdf->Cell(60,5,utf8_decode("COMBINACIONES"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+120);
        $pdf->Cell(60,25,utf8_decode("BAJOS"),1,1,'L');
        $pdf->SetXY($set_x, $set_y+125);
        $pdf->Cell(60,5,utf8_decode("Recuento"),1,1,'C');
        $pdf->SetXY($set_x, $set_y+130);
        $pdf->Cell(60,5,utf8_decode("Movilidad"),1,1,'C');
        $pdf->SetXY($set_x, $set_y+135);
        $pdf->Cell(60,10,utf8_decode("Morfologia"),1,1,'C');
        $pdf->SetXY($set_x, $set_y+145);
        $pdf->Cell(60,10,utf8_decode("OTROS ELEMENTOS FORMES"),1,1,'C');
        $pdf->SetXY($set_x+60, $set_y+145);
        $pdf->Cell(60,5,utf8_decode("HEMATIES"),1,1,'L');
        $pdf->SetXY($set_x+60, $set_y+150);
        $pdf->Cell(60,5,utf8_decode("LEUCOCITOS"),1,1,'L');
        $cuadroE=1;
      }
      $set_y+=$mm;

    }
    $t+=1;
    $pagenew=true;
    $pagec=false;
    $pagel=false;
}

ob_clean();
$pdf->Output("impresion_examen.pdf","I");
?>
