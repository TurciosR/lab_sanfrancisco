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
      $this->Cell(80,6,utf8_decode("Tels.: 2661-0310, 2660-2805"),0,0,"C");
      $this->SetTextColor(0,0,0);
        //$this->Ln(5);
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
    public function headexaC($altura,$catt){
        $this->m=$altura;
        $this->v=$catt;

        $this->SetFont('latin','',11);
        $this->SetXY($set_x, $this->m-5);
        $this->SetFillColor(178, 207, 255);
        $this->Cell(215,5,utf8_decode($this->d),0,1,'C',1);
        $this->SetXY($set_x+10, $this->m-5);
        $this->Cell(50,5,utf8_decode($this->v),0,1,'L',1);
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
$pdf = new PDF('P','mm', 'Letter');
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

//QUERY PARA DATOS DEL PACIENTE
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

//DATOS DEL PACIENTE
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
$pdf->SetXY($set_x+153, $set_y);
$pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
$pdf->SetXY($set_x+173, $set_y);
$pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
if($procedencia!="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
    $pdf->SetXY($set_x-5, $set_y+16);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetXY($set_x+135, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    $pdf->SetXY($set_x+23, $set_y+16);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
    $pdf->SetXY($set_x+155, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
}
else if ($procedencia=="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
    $pdf->SetXY($set_x+135, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    $pdf->SetXY($set_x+155, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
}
else if ($procedencia!="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetXY($set_x-5, $set_y+16);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    $pdf->SetXY($set_x+23, $set_y+16);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
}
else if ($procedencia=="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
}

$set_y = 65;
$set_x = 10;
$page = 0;
$primerparametro=1;
$j = 0;
$mm = 0;
$i = 1;
$mu=0;
$pagenew=false;
$cuadroE=0;
$salto=24;

//QUERY PARA LOS EXAMENES
$sqlcobro1=_query("SELECT c.id_cobro,ep.id_examen_paciente,e.id_examen,e.nombre_examen FROM cobro as c
JOIN detalle_cobro as dc ON dc.id_cobro=c.id_cobro
JOIN examen_paciente as ep ON ep.id_cobro=c.id_cobro
JOIN examen as e ON e.id_examen=ep.id_examen
LEFT JOIN examen_perfil epe ON epe.id_examen=e.id_examen
LEFT JOIN perfil as pe ON pe.id_perfil=epe.id_perfil
WHERE c.id_cobro='$id_cobro' AND ep.estado_realizado='Hecho' GROUP BY ep.id_examen_paciente ORDER BY e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC ");

$CE=_num_rows($sqlcobro1);
$de=0;
//RECORRER LOS RESULTADOS DE LA QUERY
while($rowcobro1 = _fetch_array($sqlcobro1)){

    $id_examen_paciente = $rowcobro1["id_examen_paciente"];
    //RESULTADOS INDIVIDUALES
    $sql3=_query("SELECT resultados,examen.nombre_examen,examen.id_examen,categoria.nombre_categoria
    FROM examen_paciente,examen,categoria WHERE examen_paciente.estado_realizado='Hecho' AND examen_paciente.id_examen = examen.id_examen
    AND categoria.id_categoria=examen.id_categoria AND examen_paciente.id_examen_paciente='$id_examen_paciente' ");
    while($rowexa = _fetch_array($sql3)){

        $nombre_examen = $rowexa["nombre_examen"];
        $n_categoria = $rowexa["nombre_categoria"];
        $aidi = $rowexa["id_examen"];
        $valores=$rowexa["resultados"];
        $formulario = explode("#", $valores);
        for($x=0; $x<(count($formulario)-1); $x++){
            $conteoparametros+=1;
        }
        if($pagenew==true){
            if($conteoparametros>15 || $de==1){
                //DATOS DEL PACIENTE
                $pdf->AddPage('P','Letter');
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
                $pdf->SetXY($set_x+153, $set_y);
                $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                $pdf->SetXY($set_x+173, $set_y);
                $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                if($procedencia!="" AND $doctor!=""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                    $pdf->SetXY($set_x-5, $set_y+16);
                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                    $pdf->SetXY($set_x+135, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                    $pdf->SetXY($set_x+23, $set_y+16);
                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                    $pdf->SetXY($set_x+155, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                }
                else if ($procedencia=="" AND $doctor!=""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                    $pdf->SetXY($set_x+135, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                    $pdf->SetXY($set_x+155, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                }
                else if ($procedencia!="" AND $doctor==""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                    $pdf->SetXY($set_x-5, $set_y+16);
                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    $pdf->SetXY($set_x+23, $set_y+16);
                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                }
                else if ($procedencia=="" AND $doctor==""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                }
                $set_y = 70;
                $set_x = 10;
                $j=0;
                $mm=0;
            }
        }
        $mm=0;
        if($conteoparametros==1){
            $de=0;
            if($primerparametro==1){
                $j++;
                $set_y+=8;
                $pas=0;
                if($CE==1)
                  $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
                else
                  $pdf-> set($nombrelab,$telefono1,$logo,$n_categoria,$pas);
                $pdf->headexa($set_y);
                $j++;
                $set_y+=8;
            }

            for($i=0; $i<(count($formulario)-1); $i++){
                if($j==$salto){
                    //DATOS DEL PACIENTE
                    $pdf->AddPage('P','Letter');
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
                    $pdf->SetXY($set_x+153, $set_y);
                    $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                    $pdf->SetXY($set_x+173, $set_y);
                    $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                    if($procedencia!="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if ($procedencia=="" AND $doctor!=""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                        $pdf->SetXY($set_x+155, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if ($procedencia!="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x-5, $set_y+16);
                        $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $pdf->SetXY($set_x+23, $set_y+16);
                        $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                    }
                    else if ($procedencia=="" AND $doctor==""){
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetFont('latin','',10);
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
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
                $pdf->SetXY($set_x+170, $set_y+$mm);
                $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
                $division= explode("*", $campos_valores[3]);

                for($k=0; $k<=(count($division)-1); $k++){
                    if($salto==$j){
                        //DATOS DEL PACIENTE
                        $pdf->AddPage('P','Letter');
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
                        $pdf->SetXY($set_x+153, $set_y);
                        $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                        $pdf->SetXY($set_x+173, $set_y);
                        $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                        if($procedencia!="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                            $pdf->SetXY($set_x-5, $set_y+16);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                            $pdf->SetXY($set_x+135, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+23, $set_y+16);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                            $pdf->SetXY($set_x+155, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        }
                        else if ($procedencia=="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                            $pdf->SetXY($set_x+135, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+155, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        }
                        else if ($procedencia!="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetXY($set_x-5, $set_y+16);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            $pdf->SetXY($set_x+23, $set_y+16);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        }
                        else if ($procedencia=="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        }
                        $set_y = 65;
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
                $restante-=5;
                $j++;
                if($final==true){
                  $m=$m;
                }else{
                  $mm+=8;
                }
            }
            //$cuadroE=0;
            $final=false;
            $primerparametro=0;
        }
        if($conteoparametros>1){
            $set_y+=8;
            $pas = 1;
            $de=0;
            if(strpos($nombre_examen,'GENERAL')!==false){
                $pas = 0;
            }
            if(trim($nombre_examen) == "HEMOGRAMA"){
                $pass=0;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pass);
                $de=1;
                $pdf->headexaC($set_y,$n_categoria);
            }
            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                $nombre_examen="ESPERMOGRAMA";
                $pass=0;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pass);
                $de=1;
                $activar=1;
                $pdf->headexaC($set_y,$n_categoria);
            }
            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                $pas=1;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
                $de=1;
                $mu=1;
                $pdf->headexaC($set_y,$n_categoria);
            }
            else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                $pas=1;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
                $de=1;
                $pdf->headexaC($set_y,$n_categoria);
            }
            else{
                $pass=0;
                $pdf-> set($nombrelab,$telefono1,$logo,$n_categoria,$pass);
                $pdf->headexa($set_y);
            }

            $j++;
            $set_y+=8;
            for($i=0; $i<(count($formulario)-1); $i++){
                $pdf->SetFont('latin','',9);
                $campos_valores= explode("|", $formulario[$i]);
                if($campos_valores[4]=='s'){
                  $pdf->SetFont('latin','',9);
                  $pdf->SetXY($set_x, $set_y+$mm);
                  $pdf->Cell(55,5,utf8_decode(Mayu($campos_valores[0])),1,1,'C');
                  $mm+=8;
                  $restante-=5;
                  $j++;
                }

                else if($campos_valores[4]=='p'){
                    if($j==$salto){
                         //DATOS DEL PACIENTE
                        $pdf->AddPage('P','Letter');
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
                        $pdf->SetXY($set_x+153, $set_y);
                        $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                        $pdf->SetXY($set_x+173, $set_y);
                        $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                        if($procedencia!="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                            $pdf->SetXY($set_x-5, $set_y+16);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                            $pdf->SetXY($set_x+135, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+23, $set_y+16);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                            $pdf->SetXY($set_x+155, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        }
                        else if ($procedencia=="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                            $pdf->SetXY($set_x+135, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+155, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        }
                        else if ($procedencia!="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetXY($set_x-5, $set_y+16);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            $pdf->SetXY($set_x+23, $set_y+16);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                        }
                        else if ($procedencia=="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
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
                    if(count(explode("-",$campos_valores[1]))>=2){
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
                    else{
                        $pdf->Cell(50,3,utf8_decode(Mayu($campos_valores[1])),0,1,'L');
                    }
                    if($mu==1){
                        $pdf->SetXY($set_x+105, $set_y+$mm);
                        $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
                    }else{
                        $pdf->SetXY($set_x+170, $set_y+$mm);
                        $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
                    }

                    if(strpos($nombre_examen,'GENERAL DE HECES')!==falsese){
                        if(Mayu($campos_valores[0])=="HEMATIES" or Mayu($campos_valores[0])=="PIOCITOS"){
                          $pdf->SetXY($set_x+150, $set_y+$mm);
                          $pdf->Cell(30,5,utf8_decode("X Campo"),0,1,'L');
                        }
                    }
                    if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                        if(Mayu($campos_valores[0])=="CILINDROS" or Mayu($campos_valores[0])=="LEUCOCITOS" or Mayu($campos_valores[0])=="HEMATIES"){
                            $pdf->SetXY($set_x+150, $set_y+$mm);
                            $pdf->Cell(30,5,utf8_decode("X Campo"),0,1,'L');
                        }
                    }
                    if($pas){
                        if(trim($campos_valores[3]) =="4,000,000 - 5,500,000"){
                            if($j==$salto){
                                //DATOS DEL PACIENTE
                                $pdf->AddPage('P','Letter');
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
                                $pdf->SetXY($set_x+153, $set_y);
                                $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                                $pdf->SetXY($set_x+173, $set_y);
                                $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                                if($procedencia!="" AND $doctor!=""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                    $pdf->SetXY($set_x-5, $set_y+16);
                                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                    $pdf->SetXY($set_x+135, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                    $pdf->SetXY($set_x+23, $set_y+16);
                                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                    $pdf->SetXY($set_x+155, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                }
                                else if ($procedencia=="" AND $doctor!=""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                    $pdf->SetXY($set_x+135, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                    $pdf->SetXY($set_x+155, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                }
                                else if ($procedencia!="" AND $doctor==""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                    $pdf->SetXY($set_x-5, $set_y+16);
                                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    $pdf->SetXY($set_x+23, $set_y+16);
                                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                }
                                else if ($procedencia=="" AND $doctor==""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
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
                        else{
                            $division= explode("*", $campos_valores[3]);
                            if(count($division)>1){
                                for($k=0; $k<(count($division)-1); $k++){
                                    if($j==$salto){
                                         //DATOS DEL PACIENTE
                                        $pdf->AddPage('P','Letter');
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
                                        $pdf->SetXY($set_x+153, $set_y);
                                        $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                                        $pdf->SetXY($set_x+173, $set_y);
                                        $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                                        if($procedencia!="" AND $doctor!=""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                            $pdf->SetXY($set_x-5, $set_y+16);
                                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                            $pdf->SetXY($set_x+135, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                            $pdf->SetXY($set_x+23, $set_y+16);
                                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                            $pdf->SetXY($set_x+155, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        }
                                        else if ($procedencia=="" AND $doctor!=""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                            $pdf->SetXY($set_x+135, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                            $pdf->SetXY($set_x+155, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        }
                                        else if ($procedencia!="" AND $doctor==""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetXY($set_x-5, $set_y+16);
                                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                            $pdf->SetXY($set_x+23, $set_y+16);
                                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                        }
                                        else if ($procedencia=="" AND $doctor==""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
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
                            }
                            else{
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
                    $j++;
                    $final=false;
                }
            }
        }
        if($activar==1){
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
        $activar=0;
        $set_y+=$mm;
        $conteoparametros=0;
        $mu=0;
    }
    $t+=1;
    $pagenew=true;
}

ob_clean();
$pdf->Output("impresion_examen.pdf","I");
?>
