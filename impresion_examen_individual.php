<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// error_reporting(E_ERROR | E_PARSE);
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
$logo = $fila["logo"];
$correo = $fila["email"];
$sitio = $fila["website"];

class PDF extends FPDF{

    protected $extgstates = array();

    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_put('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }
    // Cabecera de página\
    public function Header()
    {
      $id_sucursal=$_SESSION["id_sucursal"];
      $sqll = _query("SELECT * FROM sucursal where id_sucursal='$id_sucursal'");
      $fila = _fetch_array($sqll);
      $nombrelab = $fila["nombre_lab"];
      $depa = $fila["id_departamento"];
      $muni = $fila["id_municipio"];
      $direccion = $fila["direccion"];
      $telefono1 = $fila["telefono1"];
      $telefono2 = $fila["telefono2"];

      $sql2 = _query("SELECT dep.* FROM departamento as dep WHERE dep.id_departamento='$depa'");
      $row2 = _fetch_array($sql2);
      $departamento = $row2["nombre_departamento"];

      $sql3 = _query("SELECT mun.* FROM municipio as mun WHERE mun.id_municipio='$muni'");
      $row3 = _fetch_array($sql3);
      $municipio = $row3["nombre_municipio"];

      // Logo
      $set_x = 10;
      $set_y = 12;;
      $this->Image($this->c,$set_x-2,$set_y+5,30,15);
      
      // set alpha to semi-transparency
      $this->SetAlpha(0.3);
      $this->SetFillColor(255,0,0);
      $this->Image($this->c,25,80,170,100);
      $this->SetDrawColor(172,214,226);
      // set alpha to semi-transparency
      $this->SetAlpha(1);

      $set_x = 0;
      $this->AddFont('latin','','latin.php');
      $this->SetFont('latin', '', 13);
      // Movernos a la derecha

      //NOMBRE General
      $this->SetTextColor(51, 51, 153);
      $this->SetFont('GeorgiaBI','',17);
      $this->SetXY($set_x+3, $set_y);
      $this->Cell(215,6,Mayu(utf8_decode('LABORATORIO CLINICO '.$nombrelab)),0,1,'C');

      //DATOS CASA MATRIZ
      $set_x=30;
      $set_y += 3;
      

      //DATOS SUCURSAL 1
      $set_x=70;
      $this->SetFont('latin','',10);
      $this->SetTextColor(0,0,0);
      // $this->SetXY($set_x, $set_y+7);
      // $this->Cell(80,6,utf8_decode("SUCURSAL No.1"),0,0,"C");
      $this->SetXY($set_x, $set_y+5);
      $this->Cell(80,6,utf8_decode(Mayu("Departamento de ".utf8_decode($departamento).", El salvador, C.A.")),0,0,"C");
      // $this->SetXY($set_x, $set_y+15);
      // $this->Cell(80,6,utf8_decode('Medicas "SANTA GERTRUDIS"'),0,0,"C");
      $this->SetFont('latin','',10);
      $this->SetXY($set_x, $set_y+10);
      $this->MultiCell(80,4,utf8_decode(Mayu("Direccion:  ".$direccion)),0,'C');
      $this->SetXY($set_x, $set_y+23);
      $this->Cell(80,6,utf8_decode(Mayu("Tel: ".$telefono1)),0,0,"C");

      
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
     // $this->SetXY(10, $this->m);
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
    public function headexaC($altura,$catt)
    {
        $this->m=$altura;
        $this->v=$catt;

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
$sqlcobro=_query("SELECT mu.muestra,CONCAT(p.nombre,' ',p.apellido) as paciente,p.sexo,p.fecha_nacimiento,ep.id_examen_paciente,CONCAT(dr.nombre,' ',dr.apellido) as doctor,pr.nombre as procedencia,ep.fecha_realizado,e.nombre_examen
FROM cobro as c JOIN detalle_cobro as dc ON dc.id_cobro=c.id_cobro
JOIN examen_paciente as ep ON ep.id_cobro=c.id_cobro
JOIN paciente as p ON p.id_paciente=ep.id_paciente
JOIN examen as e ON e.id_examen=ep.id_examen
LEFT JOIN doctor as dr ON dr.id_doctor=ep.id_doctor
JOIN muestra as mu ON mu.id_muestra=ep.id_muestra
LEFT JOIN procedencia as pr ON pr.id_procedencia=ep.procedencia
WHERE ep.id_examen_paciente='$id_examen_paciente' AND ep.estado_realizado='Hecho' GROUP BY ep.id_examen_paciente ORDER BY e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC ");
$rowcobro = _fetch_array($sqlcobro);
$paciente = utf8_decode(Mayu($rowcobro["paciente"]));
$doctor = $rowcobro["doctor"];
$procedencia = Mayu($rowcobro["procedencia"]);
$edad= edad($rowcobro["fecha_nacimiento"]);
$sexo = $rowcobro["sexo"];
$nombre_e=$rowcobro["nombre_examen"];
$muestra = $rowcobro["muestra"];
$fecha_realizado = ED($rowcobro["fecha_realizado"]);
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

$mostrarm=_num_rows($sqlcobro);
$pagc=0;

//DATOS DEL PACIENTE
$set_y = 45;
$set_x = 13;
$pdf->SetFont('latin','',10);
$pdf->SetXY($set_x-5, $set_y);
$pdf->Cell(20,5,utf8_decode("PACIENTE:"),0,1,'L');
$pdf->SetXY($set_x+140, $set_y);
$pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
$pdf->SetXY($set_x+165, $set_y);
$pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');
$pdf->SetFont('latin','',10);
$pdf->SetXY($set_x+16, $set_y);
$pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
$pdf->SetXY($set_x+158, $set_y);
$pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
$pdf->SetXY($set_x+180, $set_y);
$pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
$pdf->SetXY(180,268);
$pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');
$pdf->SetXY($set_x+160, $set_y+8);
$pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
$pdf->SetXY($set_x+178, $set_y+8);
$pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
if($procedencia!="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

    $pdf->SetXY($set_x-5, $set_y+16);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    $pdf->SetXY($set_x+23, $set_y+16);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');

    if($mostrarm<=1){
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    }
    else if(strpos($nombre_e,'HEMOGRAMA')!==false){
        $pagc=1;
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    }
    else if(strpos($nombre_e,'GENERAL DE ORINA')!==false){
        $pagc=1;
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    }
    else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
        $pagc=1;
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    }
    else if(strpos($nombre_e,'ESPERMOGRAMA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
    }
    $set_y = 72;
}
else if ($procedencia=="" AND $doctor!=""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+15, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
    if($mostrarm<=1){
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'HEMOGRAMA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+110, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+130, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE ORINA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+110, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+130, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE HECES')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+110, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+130, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'ESPERMOGRAMA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+110, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+130, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else{
        $set_y=65;
    }

}
else if ($procedencia!="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x-5, $set_y+8);
    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
    $pdf->SetFont('latin','',10);
    $pdf->SetXY($set_x+23, $set_y+8);
    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
    if($mostrarm<=1){
        $pdf->SetXY($set_x+115, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+135, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'HEMOGRAMA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+120, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+140, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE ORINA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+120, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+140, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE HECES')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+120, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+140, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else if(strpos($nombre_e,'ESPERMOGRAMA')!==false){
      $pagc=1;
        $pdf->SetXY($set_x+120, $set_y+8);
        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
        $pdf->SetXY($set_x+140, $set_y+8);
        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
        $set_y=65;
    }
    else{
        $set_y=65;
    }

}
else if ($procedencia=="" AND $doctor==""){
    $pdf->SetFont('latin','',10);
    if($mostrarm<=1){
      $pdf->SetXY($set_x-5, $set_y+8);
      $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
      $pdf->SetXY($set_x+20, $set_y+8);
      $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
      $set_y=65;
    }
    else if(strpos($nombre_e,'HEMOGRAMA')!==false){
      $pagc=1;
      $pdf->SetXY($set_x-5, $set_y+8);
      $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
      $pdf->SetXY($set_x+20, $set_y+8);
      $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
      $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE ORINA')!==false){
      $pagc=1;
      $pdf->SetXY($set_x-5, $set_y+8);
      $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
      $pdf->SetXY($set_x+20, $set_y+8);
      $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
      $set_y=65;
    }
    else if(strpos($nombre_e,'GENERAL DE HECES')!==false){
      $pagc=1;
      $pdf->SetXY($set_x-5, $set_y+8);
      $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
      $pdf->SetXY($set_x+20, $set_y+8);
      $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
      $set_y=65;
    }
    else if(strpos($nombre_e,'ESPERMOGRAMA')!==false){
      $pagc=1;
      $pdf->SetXY($set_x-5, $set_y+8);
      $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
      $pdf->SetXY($set_x+20, $set_y+8);
      $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
      $set_y=65;
    }
    else{
      $set_y=65;
    }
}


$set_x = 10;
$page = 0;
$primerparametro=1;
$j = 0;
$mm = 0;
$i = 1;
$mu=0;
$pagenew=false;
$cuadroE=0;
$salto=28;
$de=0;
$acti=0;

$sqlcat=_query("SELECT cat.id_categoria,cat.nombre_categoria,ep.id_examen_paciente
  FROM examen_paciente as ep JOIN examen as e ON e.id_examen=ep.id_examen
  JOIN categoria as cat ON cat.id_categoria=e.id_categoria
  LEFT JOIN examen_perfil epe ON epe.id_examen=e.id_examen
  LEFT JOIN perfil as pe ON pe.id_perfil=epe.id_perfil
  WHERE ep.id_examen_paciente='$id_examen_paciente' AND ep.estado_realizado='Hecho'
  GROUP BY cat.id_categoria
  ORDER BY cat.id_categoria ASC,e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC");

while($rowcat = _fetch_array($sqlcat)){
    $n_categoria = $rowcat["nombre_categoria"];
    $id_categoria = $rowcat["id_categoria"];
    $n_cat=1;

    //QUERY PARA LOS EXAMENES
    $sqlcobro1=_query("SELECT e.id_examen,e.nombre_examen,ep.resultados,mu.muestra
      FROM examen_paciente as ep JOIN examen as e ON e.id_examen=ep.id_examen
      LEFT JOIN examen_perfil epe ON epe.id_examen=e.id_examen
      LEFT JOIN perfil as pe ON pe.id_perfil=epe.id_perfil
      JOIN categoria as cat ON cat.id_categoria=e.id_categoria
      LEFT JOIN muestra as mu ON mu.id_muestra=ep.id_muestra
      WHERE ep.id_examen_paciente='$id_examen_paciente' AND ep.estado_realizado='Hecho'
      AND cat.id_categoria='$id_categoria' GROUP BY ep.id_examen_paciente
      ORDER BY e.prioridad ASC, CHARACTER_LENGTH(ep.resultados) ASC  ");

    //RECORRER LOS RESULTADOS DE LA QUERY
    while($rowcobro1 = _fetch_array($sqlcobro1)){
        $nombre_examen = $rowcobro1["nombre_examen"];
        $aidi = $rowcobro1["id_examen"];
        $muestra=$rowcobro1["muestra"];
        $valores=$rowcobro1["resultados"];
        $primerparametro=1;
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
                $pdf->SetXY($set_x+140, $set_y);
                $pdf->Cell(20,5,utf8_decode("GÉNERO:"),0,1,'L');
                $pdf->SetXY($set_x+165, $set_y);
                $pdf->Cell(20,5,utf8_decode("EDAD:"),0,1,'L');
                $pdf->SetFont('latin','',10);
                $pdf->SetXY($set_x+16, $set_y);
                $pdf->Cell(100,5,utf8_decode($paciente),0,1,'L');
                $pdf->SetXY($set_x+158, $set_y);
                $pdf->Cell(20,5,utf8_decode($sexo),0,1,'L');
                $pdf->SetXY($set_x+180, $set_y);
                $pdf->Cell(20,5,utf8_decode($edad),0,1,'L');
                $pdf->SetXY(180,268);
                $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');
                $pdf->SetXY($set_x+160, $set_y+8);
                $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                $pdf->SetXY($set_x+178, $set_y+8);
                $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                if($procedencia!="" AND $doctor!=""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                    $pdf->SetXY($set_x-5, $set_y+16);
                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                    $pdf->SetXY($set_x+23, $set_y+16);
                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                    if($mostrarm<=1){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                    }
                  $set_y = 65;
                }
                else if ($procedencia=="" AND $doctor!=""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+15, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                    if($mostrarm<=1){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                        $pdf->SetXY($set_x+110, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+130, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                        $pdf->SetXY($set_x+110, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+130, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                        $pdf->SetXY($set_x+110, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+130, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                        $pdf->SetXY($set_x+110, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+130, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else{
                        $set_y=58;
                    }

                }
                else if ($procedencia!="" AND $doctor==""){
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x-5, $set_y+8);
                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY($set_x+23, $set_y+8);
                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                    if($mostrarm<=1){
                        $pdf->SetXY($set_x+115, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+135, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                        $pdf->SetXY($set_x+120, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+140, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                        $pdf->SetXY($set_x+120, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+140, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                        $pdf->SetXY($set_x+120, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+140, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                        $pdf->SetXY($set_x+120, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+140, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else{
                        $set_y=58;
                    }

                }
                else if ($procedencia=="" AND $doctor==""){
                    $pdf->SetFont('latin','',10);
                    if($mostrarm<=1){
                        $pdf->SetXY($set_x-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                        $pdf->SetXY($set_x+-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                        $pdf->SetXY($set_x+-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                        $pdf->SetXY($set_x+-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                        $pdf->SetXY($set_x+-5, $set_y+8);
                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                        $pdf->SetXY($set_x+15, $set_y+8);
                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                        $set_y=65;
                    }
                    else{
                        $set_y=58;
                    }
                }
                $set_x = 10;
                $j=0;
                if($de==1 AND strpos($nombre_examen,'GENERAL DE HECES')!==false){
                  $set_y+=5;
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y,205, $set_y);
                  $pdf->SetFont('latin','',10);
                  /*$pdf->SetXY(70, $set_y);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');*/
                  $acti=1;
                  $mm=0;
                  //}
                }
                else if($de==1 AND strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                  $set_y+=5;
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y,205, $set_y);
                  $pdf->SetFont('latin','',10);
                  /*$pdf->SetXY(70, $set_y);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');*/
                  $acti=1;
                  $mm=0;
                //}else if($de==1){
                }else if($de==1){
                  $set_y+=5;
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y,205, $set_y);
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY(70, $set_y);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                  $acti=1;
                  $mm=8;
                }
                else{
                  $mm=0;
                }
            }
        }
        $de=0;
        if($conteoparametros==1){
          if($primerparametro==1){
              $pas=0;
              if($CE==1){
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5+$mm);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($nombre_examen),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY(70, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y+$mm);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                  $mm+=8;
                  $j+=2;
              }
              else {
               if($n_cat==1 AND $pagenew==true AND $acti==0){
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5+$mm);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY(70, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y+$mm);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                  $mm+=8;
                  $j+=2;
                }else if ($n_cat==1 AND $pagenew==false AND $acti==0){
                  $pdf->SetFont('latin','',11);
                  $pdf->SetXY($set_x-10, $set_y-5+$mm);
                  $pdf->SetFillColor(178, 207, 255);
                  $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                  $pdf->SetFillColor(255,255,255);
                  $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                  $pdf->SetFont('latin','',10);
                  $pdf->SetXY(70, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                  $pdf->SetXY(110, $set_y+$mm);
                  $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                  $pdf->SetXY(170, $set_y+$mm);
                  $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                  $mm+=8;
                  $j+=2;
                }
              }
          }
          for($i=0; $i<(count($formulario)-1); $i++){
              if($j==$salto){
                  //DATOS DEL PACIENTE
                  $pdf->AddPage('P','Letter');
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
                  $pdf->SetXY(180,268);
                  $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');

                  if($procedencia!="" AND $doctor!=""){
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x-5, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                      $pdf->SetXY($set_x+160, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                      $pdf->SetXY($set_x-5, $set_y+16);
                      $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+15, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                      $pdf->SetXY($set_x+23, $set_y+16);
                      $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                      $pdf->SetXY($set_x+175, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                      if($mostrarm<=1){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      }
                      else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      }
                      else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      }
                      else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      }
                      else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                      }
                  $set_y = 72;
                  }
                  else if ($procedencia=="" AND $doctor!=""){
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x-5, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                      $pdf->SetXY($set_x+160, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+15, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                      $pdf->SetXY($set_x+175, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                      if($mostrarm<=1){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                          $pdf->SetXY($set_x+110, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+130, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                          $pdf->SetXY($set_x+110, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+130, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                          $pdf->SetXY($set_x+110, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+130, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                          $pdf->SetXY($set_x+110, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+130, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else{
                          $set_y=58;
                      }

                  }
                  else if ($procedencia!="" AND $doctor==""){
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x-5, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');

                      $pdf->SetXY($set_x+160, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+23, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                      $pdf->SetXY($set_x+175, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                      if($mostrarm<=1){
                          $pdf->SetXY($set_x+115, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+135, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                          $pdf->SetXY($set_x+120, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+140, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                          $pdf->SetXY($set_x+120, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+140, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                          $pdf->SetXY($set_x+120, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+140, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                          $pdf->SetXY($set_x+120, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+140, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else{
                          $set_y=58;
                      }

                  }
                  else if ($procedencia=="" AND $doctor==""){
                      $pdf->SetFont('latin','',10);

                      $pdf->SetXY($set_x+50, $set_y+8);
                      $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                      $pdf->SetFont('latin','',10);
                      $pdf->SetXY($set_x+65, $set_y+8);
                      $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                      if($mostrarm<=1){
                          $pdf->SetXY($set_x-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                          $pdf->SetXY($set_x+-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                          $pdf->SetXY($set_x+-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                          $pdf->SetXY($set_x+-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                          $pdf->SetXY($set_x+-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          $set_y=65;
                      }
                      else{
                          $set_y=58;
                      }
                  }
                  $set_x = 10;
                  $j=0;
                  $mm=0;
              }
              $pdf->SetFont('latin','',9);
              $campos_valores= explode("|", $formulario[$i]);
              $pdf->SetXY($set_x, $set_y+$mm);
              $pdf->MultiCell(50,3,utf8_decode(Mayu($nombre_examen)),0,'L');
              $pdf->SetXY($set_x+60, $set_y+$mm);
              $pdf->MultiCell(120,3,utf8_decode(str_replace('@','+',$campos_valores[1])),0,1,'L');
              $pdf->SetXY($set_x+170, $set_y+$mm);
              $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
              $campos_valores[3] = str_replace("**", " \n ", $campos_valores[3]);
              $division= explode("*", $campos_valores[3]);
              for($k=0; $k<=(count($division)-1); $k++){
                  if($j==$salto){
                      //DATOS DEL PACIENTE
                      $pdf->AddPage('P','Letter');
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
                      $pdf->SetXY(180,268);
                      $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');

                      if($procedencia!="" AND $doctor!=""){
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                          $pdf->SetXY($set_x+160, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                          $pdf->SetXY($set_x-5, $set_y+16);
                          $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                          $pdf->SetXY($set_x+23, $set_y+16);
                          $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                          $pdf->SetXY($set_x+175, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                          if($mostrarm<=1){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          }
                          else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          }
                          else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          }
                          else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          }
                          else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                          }
                      $set_y = 72;
                      }
                      else if ($procedencia=="" AND $doctor!=""){
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                          $pdf->SetXY($set_x+160, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x+15, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                          $pdf->SetXY($set_x+175, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                          if($mostrarm<=1){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                              $pdf->SetXY($set_x+110, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+130, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                              $pdf->SetXY($set_x+110, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+130, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                              $pdf->SetXY($set_x+110, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+130, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                              $pdf->SetXY($set_x+110, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+130, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else{
                              $set_y=58;
                          }

                      }
                      else if ($procedencia!="" AND $doctor==""){
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x-5, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');

                          $pdf->SetXY($set_x+160, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x+23, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                          $pdf->SetXY($set_x+175, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                          if($mostrarm<=1){
                              $pdf->SetXY($set_x+115, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+135, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                              $pdf->SetXY($set_x+120, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+140, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                              $pdf->SetXY($set_x+120, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+140, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                              $pdf->SetXY($set_x+120, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+140, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                              $pdf->SetXY($set_x+120, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+140, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else{
                              $set_y=58;
                          }

                      }
                      else if ($procedencia=="" AND $doctor==""){
                          $pdf->SetFont('latin','',10);

                          $pdf->SetXY($set_x+50, $set_y+8);
                          $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                          $pdf->SetFont('latin','',10);
                          $pdf->SetXY($set_x+65, $set_y+8);
                          $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                          if($mostrarm<=1){
                              $pdf->SetXY($set_x-5, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+15, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                              $pdf->SetXY($set_x+-5, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+15, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                              $pdf->SetXY($set_x+-5, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+15, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                              $pdf->SetXY($set_x+-5, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+15, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                              $pdf->SetXY($set_x+-5, $set_y+8);
                              $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                              $pdf->SetXY($set_x+15, $set_y+8);
                              $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                              $set_y=65;
                          }
                          else{
                              $set_y=58;
                          }
                      }
                      $set_x = 10;
                      $j=0;
                      $mm=0;
                  }
                  if(strpos(Mayu($campos_valores[0]),'TOXOPLASMA')!==false){
                    $pdf->SetFont('latin','',11);
                  }else{
                    $pdf->SetFont('latin','',9);
                  }
                  $pdf->SetXY($set_x+102, $set_y+$mm);
                  $pdf->MultiCell(60,3,utf8_decode($division[$k]),0,'L');
                  $mm+=8;
                  $final=true;
                  $j++;
                  $pdf->SetFont('latin','',9);
              }
              if($final==true){
                  $mm=$mm;
              }else{
                  $mm+=8;

              }
                  $j++;
          }
          $final=false;
        }
        $primerparametro=0;
        if($conteoparametros>1){
            $pas = 1;
            if(strpos($nombre_examen,'GENERAL')!==false){
                $pas = 0;
            }
            if(trim($nombre_examen) == "HEMOGRAMA"){
                $pass=0;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pass);
                $pdf->headexaC($set_y,$n_categoria);
                $mm+=8;
                $de=1;
            }
            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                $nombre_examen="ESPERMOGRAMA";
                $pass=0;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pass);
                $de=1;
                $activar=1;
                $pdf->headexaC($set_y,$n_categoria);
                $mm+=8;
            }
            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                $pas=1;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
                $de=1;
                $mu=1;
                $pdf->headexaC($set_y,$n_categoria);
                $mm+=8;
            }
            else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                $pas=1;
                $pdf-> set($nombrelab,$telefono1,$logo,$nombre_examen,$pas);
                $de=1;
                $mu=1;
                $pdf->headexaC($set_y,$n_categoria);
                $mm+=8;
            }
            else{
                if($CE==1){
                    $pdf->SetFont('latin','',11);
                    $pdf->SetXY($set_x-10, $set_y-5+$mm);
                    $pdf->SetFillColor(178, 207, 255);
                    $pdf->Cell(215,5,utf8_decode($nombre_examen),0,1,'C',1);
                    $pdf->SetFillColor(255,255,255);
                    $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY(70, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                    $pdf->SetXY(110, $set_y+$mm);
                    $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                    $pdf->SetXY(170, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                    $mm+=8;
                }
                else if($n_cat==1 AND $pagenew==false AND $acti==0){
                    $pdf->SetFont('latin','',11);
                    $pdf->SetXY($set_x-10, $set_y-5+$mm);
                    $pdf->SetFillColor(178, 207, 255);
                    $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                    $pdf->SetFillColor(255,255,255);
                    $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY(70, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                    $pdf->SetXY(110, $set_y+$mm);
                    $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                    $pdf->SetXY(170, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                    $mm+=8;
                }
                else if($n_cat==1 AND $pagenew==true AND $acti==0){
                    $pdf->SetFont('latin','',11);
                    $pdf->SetXY($set_x-10, $set_y-5+$mm);
                    $pdf->SetFillColor(178, 207, 255);
                    $pdf->Cell(215,5,utf8_decode($n_categoria),0,1,'C',1);
                    $pdf->SetFillColor(255,255,255);
                    $pdf->Line(10,$set_y+$mm,205, $set_y+$mm);
                    $pdf->SetFont('latin','',10);
                    $pdf->SetXY(70, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
                    $pdf->SetXY(110, $set_y+$mm);
                    $pdf->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
                    $pdf->SetXY(170, $set_y+$mm);
                    $pdf->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
                    $mm+=8;
                }
            }

            $j++;
            //$set_y+=8;
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
                        $pdf->AddPage('P','Letter');
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
                        $pdf->SetXY(180,268);
                        $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');

                        if($procedencia!="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                            $pdf->SetXY($set_x+160, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                            $pdf->SetXY($set_x-5, $set_y+16);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+23, $set_y+16);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                            $pdf->SetXY($set_x+175, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                            if($mostrarm<=1){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            }
                            else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            }
                            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            }
                            else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            }
                            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                            }
                        $set_y = 72;
                        }
                        else if ($procedencia=="" AND $doctor!=""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                            $pdf->SetXY($set_x+160, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+15, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                            $pdf->SetXY($set_x+175, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                            if($mostrarm<=1){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                $pdf->SetXY($set_x+110, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+130, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                $pdf->SetXY($set_x+110, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+130, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                $pdf->SetXY($set_x+110, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+130, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                $pdf->SetXY($set_x+110, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+130, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else{
                                $set_y=58;
                            }

                        }
                        else if ($procedencia!="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x-5, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');

                            $pdf->SetXY($set_x+160, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+23, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                            $pdf->SetXY($set_x+175, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                            if($mostrarm<=1){
                                $pdf->SetXY($set_x+115, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+135, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                $pdf->SetXY($set_x+120, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+140, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                $pdf->SetXY($set_x+120, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+140, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                $pdf->SetXY($set_x+120, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+140, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                $pdf->SetXY($set_x+120, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+140, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else{
                                $set_y=58;
                            }

                        }
                        else if ($procedencia=="" AND $doctor==""){
                            $pdf->SetFont('latin','',10);

                            $pdf->SetXY($set_x+50, $set_y+8);
                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                            $pdf->SetFont('latin','',10);
                            $pdf->SetXY($set_x+65, $set_y+8);
                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                            if($mostrarm<=1){
                                $pdf->SetXY($set_x-5, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+15, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                $pdf->SetXY($set_x+-5, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+15, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                $pdf->SetXY($set_x+-5, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+15, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                $pdf->SetXY($set_x+-5, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+15, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                $pdf->SetXY($set_x+-5, $set_y+8);
                                $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                $pdf->SetXY($set_x+15, $set_y+8);
                                $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                $set_y=65;
                            }
                            else{
                                $set_y=58;
                            }
                        }
                        $set_x = 10;
                        $j=0;
                        $mm=0;
                    }
                    $pdf->SetFont('latin','',9);
                    $pdf->SetXY($set_x, $set_y+$mm);
                    $pdf->MultiCell(55,3,utf8_decode(Mayu(utf8_decode($campos_valores[0]))),0,'L');
                    $pdf->SetXY($set_x+60, $set_y+$mm);
                    $dats_print = explode(",",$campos_valores[1]);
                    if(count($dats_print)>=2)
                    {
                    if(intval(str_replace(",","",$campos_valores[1]))>1000000){
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
                        $pdf->Cell(50,3,utf8_decode(str_replace('@','+',$campos_valores[1])),0,1,'L');
                    }
                    }
                    else{
                        $pdf->Cell(50,3,utf8_decode(str_replace('@','+',$campos_valores[1])),0,1,'L');
                    }
                    if($mu==1){
                      $pdf->SetXY($set_x+105, $set_y+$mm);
                      $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
                    }else{
                        $pdf->SetXY($set_x+170, $set_y+$mm);
                        $pdf->Cell(50,3,utf8_decode($campos_valores[2]),0,1,'L');
                    }

                    if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
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
                                $pdf->SetXY(180,268);
                                $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');

                                if($procedencia!="" AND $doctor!=""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                                    $pdf->SetXY($set_x+160, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                    $pdf->SetXY($set_x-5, $set_y+16);
                                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                    $pdf->SetXY($set_x+23, $set_y+16);
                                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                    $pdf->SetXY($set_x+175, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                    if($mostrarm<=1){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    }
                                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    }
                                    else if(strpos($nombrenombre_examen_e,'GENERAL DE HECES')!==false){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    }
                                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                    }
                                $set_y = 72;
                                }
                                else if ($procedencia=="" AND $doctor!=""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');

                                    $pdf->SetXY($set_x+160, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+15, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                    $pdf->SetXY($set_x+175, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                    if($mostrarm<=1){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+110, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+130, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                        $pdf->SetXY($set_x+110, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+130, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                        $pdf->SetXY($set_x+110, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+130, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+110, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+130, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else{
                                        $set_y=58;
                                    }

                                }
                                else if ($procedencia!="" AND $doctor==""){
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x-5, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');

                                    $pdf->SetXY($set_x+160, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+23, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                    $pdf->SetXY($set_x+175, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                    if($mostrarm<=1){
                                        $pdf->SetXY($set_x+115, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+135, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+120, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+140, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                        $pdf->SetXY($set_x+120, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+140, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                        $pdf->SetXY($set_x+120, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+140, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+120, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+140, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else{
                                        $set_y=58;
                                    }

                                }
                                else if ($procedencia=="" AND $doctor==""){
                                    $pdf->SetFont('latin','',10);

                                    $pdf->SetXY($set_x+50, $set_y+8);
                                    $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                    $pdf->SetFont('latin','',10);
                                    $pdf->SetXY($set_x+65, $set_y+8);
                                    $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                    if($mostrarm<=1){
                                        $pdf->SetXY($set_x-5, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+15, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'HEMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+-5, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+15, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE ORINA')!==false){
                                        $pdf->SetXY($set_x+-5, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+15, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'GENERAL DE HECES')!==false){
                                        $pdf->SetXY($set_x+-5, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+15, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else if(strpos($nombre_examen,'ESPERMOGRAMA')!==false){
                                        $pdf->SetXY($set_x+-5, $set_y+8);
                                        $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                        $pdf->SetXY($set_x+15, $set_y+8);
                                        $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                        $set_y=65;
                                    }
                                    else{
                                        $set_y=58;
                                    }
                                }
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
                            $campos_valores[3] = str_replace("**", " \n ", $campos_valores[3]);
                            $division= explode("*", $campos_valores[3]);
                            if(count($division)>1){
                                for($k=0; $k<=(count($division)-1); $k++){
                                    if($j==$salto){
                                            //DATOS DEL PACIENTE
                                        $pdf->AddPage('P','Letter');
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
                                        $pdf->SetXY(180,268);
                                        $pdf->Cell(20,5,utf8_decode("FIRMA Y SELLO"),0,1,'L');

                                        if($procedencia!="" AND $doctor!=""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                            $pdf->SetXY($set_x+115, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetXY($set_x+160, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                            $pdf->SetXY($set_x-5, $set_y+16);
                                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                            $pdf->SetXY($set_x+23, $set_y+16);
                                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                            $pdf->SetXY($set_x+135, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                            $pdf->SetXY($set_x+175, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                            $set_y = 65;
                                        }
                                        else if ($procedencia=="" AND $doctor!=""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MÉDICO:"),0,1,'L');
                                            $pdf->SetXY($set_x+115, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetXY($set_x+160, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($doctor),0,1,'L');
                                            $pdf->SetXY($set_x+135, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                            $pdf->SetXY($set_x+175, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                            $set_y = 58;
                                        }
                                        else if ($procedencia!="" AND $doctor==""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("PROCEDENCIA:"),0,1,'L');
                                            $pdf->SetXY($set_x+115, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetXY($set_x+160, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+23, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($procedencia),0,1,'L');
                                            $pdf->SetXY($set_x+135, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                            $pdf->SetXY($set_x+175, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                            $set_y = 58;
                                        }
                                        else if ($procedencia=="" AND $doctor==""){
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x-5, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("MUESTRA:"),0,1,'L');
                                            $pdf->SetXY($set_x+80, $set_y+8);
                                            $pdf->Cell(40,5,utf8_decode("FECHA:"),0,1,'L');
                                            $pdf->SetFont('latin','',10);
                                            $pdf->SetXY($set_x+15, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($muestra),0,1,'L');
                                            $pdf->SetXY($set_x+95, $set_y+8);
                                            $pdf->Cell(95,5,utf8_decode($fecha_realizado),0,1,'L');
                                            $set_y=58;
                                        }
                                        $set_x = 10;
                                        $j=0;
                                        $mm=0;
                                    }
                                    if(strpos(Mayu($campos_valores[0]),'TOXOPLASMA')!==false){
                                      $pdf->SetFont('latin','',11);
                                    }else{
                                      $pdf->SetFont('latin','',9);
                                    }
                                    $pdf->SetXY($set_x+102, $set_y+$mm);
                                    $pdf->Cell(30,3,utf8_decode(trim($division[$k])),0,1,'L');
                                    $mm+=8;
                                    $final=true;
                                    $j++;
                                    $pdf->SetFont('latin','',9);
                                }
                            }
                            else{
                              if(strpos(Mayu($campos_valores[0]),'TOXOPLASMA')!==false){
                                $pdf->SetFont('latin','',11);
                              }else{
                                $pdf->SetFont('latin','',9);
                              }
                              $pdf->SetXY($set_x+102, $set_y+$mm);
                              $campos_valores[3] = str_replace("**", " \n ", $campos_valores[3]);

                              $pdf->Cell(30,3,utf8_decode($campos_valores[3]),0,1,'L');
                              $pdf->SetFont('latin','',9);
                            }
                        }
                    }
                    if($final==true){
                        $mm=$mm;
                    }else{
                        $mm+=8;
                    }
                    $j++;
                    $final=false;
                }
            }
        }
        if(false)
        {
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
        $conteoparametros=0;
        $mu=0;
        $n_cat=0;
        $acti=0;
        $t+=1;
        $pagenew=true;
    }
}

ob_clean();
$pdf->Output("impresion_examen_individual.pdf","I");
?>
