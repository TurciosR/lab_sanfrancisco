<?php

function print_ticket($id_factura){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
  $datos_empresa=datos_empresa();
	$field= json_decode($datos_empresa, true);
	$nite=$field['nit'];
	$nrce=$field['nrc'];
	$empresa1=$field['empresa'];
	$razonsocial1=$field['razonsocial'];
	$giro1=$field['giro'];
	$nombre_sucursal1=datos_sucursal($id_sucursal);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|".$nite."|".$nrce."|";
	//Sucursal
	//Obtener informacion de tabla Factura
	$result_fact=datos_factura($id_factura);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['cliente'];
		$id_factura = $row_fact['id_cobro'];
		$id_usuario=$row_fact['id_usuario'];
		$id_vendedor=$row_fact['id_empleado'];
		$fecha=$row_fact['fecha'];
		$hora=$row_fact['hora_cobro'];
		$caja=$row_fact['caja'];
		$turno=$row_fact['turno'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
		$descuent=0;//$row_fact['descuento'];
		$porcentaje=0;//$row_fact['porcentaje'];
		$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
		$dats_caja = _fetch_array($sql_caja);
		$fehca = ED($dats_caja["fecha"]);
		$resolucion = $dats_caja["resolucion"];
		$serie = $dats_caja["serie"];
		$desde = $dats_caja["desde"];
		$hasta = $dats_caja["hasta"];
		$len_numero_doc=strlen($numero_doc);
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado usuario y vendedor
		$result_emp= datos_empleado($id_usuario,$id_vendedor);
		list($empleado,$vendedor)=explode('|',$result_emp);
		//Datos del Cliente
		$result=datos_cliente($id_cliente);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",1);
		$esp_precios=espacios_izq(" ",10);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",23);
		$nombre_ape=texto_espacios($nombres,32);
		$dir_txt=texto_espacios($direccion,30);
		$total_final=0;
		//Datos del cliente
		$info_factura.=$esp_init.$empresa1."\n";
		//$info_factura.=$esp_init.$razonsocial1."\n";
		$giros = explode(";", $giro1);
		for ($ni = 0; $ni < (count($giros)); $ni++)
		{
			$info_factura.=$esp_init.trim($giros[$ni])."\n";
		}
		$info_factura.=$esp_init."# ".$num_fact."|";
		$info_factura.=$esp_init."FECHA: ".$fecha_fact." ".hora($hora)."\n|";
		$info_factura.=$esp_init."CAJA : ".$caja. "  TURNO: ".$turno."\n|";
		$info_factura.=$esp_init."CLIENTE: ".$nombre_ape."\n|";
		$info_factura.="CANT.       DESCRIPCION\n|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$result_fact_det=datos_fact_det($id_factura);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_examen'];
			$descripcion =$row_fact_det['nombre_examen'];
			//descripcion presentacion
			$exento=0;//$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_detalle_cobro'];
		//	$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio'];
			$descuento =0;//$row_fact_det['descuento'];
			$subt=$row_fact_det['subtotal'];
			//$subt = $subt - $descuento;
			//$id_empleado =$row_fact_det['id_u'];
			$tipo_prod_serv ="SERVICIO";//$row_fact_det['tipo_prod_serv'];
			//presentacion producto
			$sql_uus=_fetch_array(_query("SELECT PE.precio  FROM precio_examen AS PE WHERE PE.id_examen='$id_producto'"));
			$precio_p=$sql_uus['precio'];
			$cantidad=$cantidad;
			//linea a linea
			$descrip=texto_espacios($descripcion,22);
			$descpresenta1=texto_espacios("",7);
			$descpre1=texto_espacios("",30);



			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subt);
				$total_gravado=$subt_gravado+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subt);
				$total_exento=$subt_exento+$total_exento;
			}
			$esp_init=len_num($cantidad,8);
			$esp_col2=len_num($precio_unit,6);
			$esp_col3=len_num($subtotal,7);
			$esp_col4=len_num($descuento,11);
			$info_factura.=$esp_init.$cantidad." ".$descrip."\n";
			//$info_factura.="PRESENT: ".$descpre1."\n";
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}
	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	//$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);
	$esp_totales=len_num($total_value,8);
	$esp_init2=espacios_izq(" ",25);
	$tt_fin = $total_value_fin - $descuent;
	//$esp_totales=espacios_izq(" ",$sp3);
	$esp_d1=len_num($total_value_gravado,3);
	$esp_d2=len_num($total_value_exento,3);
	$esp_d3=len_num($total_value_fin,3);
	$vals = 3;
	if(strlen($descuent)>3)
	{
		$vals = 2;
	}
	$esp_d4=len_num($descuent,$vals);
	$vals = 3;
	if(strlen($porcentaje)>3)
	{
		$vals = 2;
	}
	$esp_d6=len_num($porcentaje,$vals);
	$esp_d5=len_num($tt_fin,2);
	$info_factura.="|TOTAL            ".$esp_totales."  $ ".$esp_d3.$total_value_fin."\n";
	$info_factura.="|DESCUENTO       ".$esp_totales."".$esp_d6.$porcentaje."%\n";
	$info_factura.="|TOTAL DESCUENTO  ".$esp_totales."  $ ".$esp_d4.sprintf("%.2f",$descuent)."\n";
	$info_factura.="|A PAGAR          ".$esp_totales."  $ ".$esp_d5.sprintf("%.2f",$tt_fin)."\n";
	$info_factura.="|".$cadena_salida_txt."\n";
	return ($info_factura);
}

function print_fact($id_factura,$tipo_id,$nitcte,$nrccte,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	$info_factura="";
	//Obtener informacion de tabla Factura
	$result_fact=datos_factura($id_factura);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);

	if($nrows_fact>0){
		$id_cliente=$row_fact['cliente'];
		$id_factura = $row_fact['id_cobro'];
		$id_usuario=$row_fact['id_usuario'];
		$id_vendedor=$row_fact['id_empleado'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
		$caja=$row_fact['caja'];
		$turno=$row_fact['turno'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado usuario y vendedor
		$result_emp= datos_empleado($id_usuario,$id_vendedor);
		list($empleado,$vendedor)=explode('|',$result_emp);
		//Datos del Cliente
		$result=datos_cliente($id_cliente);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$giro=$row1['giro'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$esp_init=espacios_izq(" ",9);
		$esp_init2=espacios_izq(" ",48);
		$esp_init3=espacios_izq(" ",49);
		$nombre_ape=texto_espacios($nombreapecte,60);
		$dir_txt=texto_espacios($direccion,60);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<10;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init3."  ".$diaa."       ".$mess."        ".$anio."|";

		//Datos del cliente
		//$info_factura.="\n";
		$info_factura.=$esp_init." ".$nombre_ape."|";
		$info_factura.=$esp_init."  ".$direccion."|";
		$info_factura.=$esp_init2.$dui."|";
		$info_factura.=$esp_init.$nitcte."|";
		//$info_factura.=$esp_init2.$giro."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$result_fact_det=datos_fact_det($id_factura);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		//$info_factura.="\n";
		$espacios1=espacios_izq(" ",1);
		$espacios2=espacios_izq(" ",2);
		$espacios3=espacios_izq(" ",3);
		$espacios4=espacios_izq(" ",4);
		$espacios5=espacios_izq(" ",5);
		$espacios6=espacios_izq(" ",6);
		$espacios7=espacios_izq(" ",7);
		$espacios8=espacios_izq(" ",8);
		$espacios9=espacios_izq(" ",9);
		$espacios10=espacios_izq(" ",10);
		$espacios11=espacios_izq(" ",11);
		$espacios12=espacios_izq(" ",12);
		$espacios15=espacios_izq(" ",15);
		//$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada
		$info_factura.= "\n";
		$info_factura.= "\n";
		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_examen'];
			$descripcion =trim($row_fact_det['nombre_examen']);

			//descripcion presentacion
			//$id_presentacion =$row_fact_det['id_presentacion'];
			//$descpre =trim($row_fact_det['descpre']);
			//$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=0;//$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_detalle_cobro'];
			//$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio'];
			$subt =$row_fact_det['subtotal'];
		//	$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='SERVICIO';

			$descrip=texto_espacios($descripcion,38);
			//presentacion producto
			$sql_uus=_fetch_array(_query("SELECT * FROM precio_examen WHERE id_examen=$id_producto"));
			$precio_p=$sql_uus['precio'];
			$cantidad=$cantidad;

			$subt=$precio_venta*$cantidad;

			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}
			$col2=2;
			$sp1=len_espacios($cantidad,5);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_unit,8);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subtotal,9);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",6);
			//imprimir productos
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
			}
			if ($exento==0){
				$info_factura.=$esp_col1.$cantidad.$espacios4.$descrip.$esp_col3.$precio_unit.$espacios7.$esp_col4.$subtotal."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt=$enteros_txt." DLS CON ".$decimales_txt."/100 CTVS";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);

	//totales
	$lineas_faltantes=13-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 30,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);
	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$splentot1=len_espacios($total_value_exento,10);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,10);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$splentot3=len_espacios($total_value_gravado,7);
	$esp_lentot3=espacios_izq(" ",$splentot3);

	//imprimir totales

	$totaltexto=trim($tmplinea[0]);
	$len_totaltexto=50-strlen($totaltexto);
	$esp_totaltexto=espacios_izq(" ",$len_totaltexto);
	$esp_init=espacios_izq(" ",8);
	$info_factura.=$esp_init.$totaltexto.$esp_totaltexto.$espacios6.$esp_lentot3.$total_value_gravado."\n";
	if($ln>1){
		$totaltexto1=trim($tmplinea[1]);
		$len_totaltexto1=50-strlen($totaltexto1);
		$esp_totaltexto1=espacios_izq(" ",$len_totaltexto1);
		$info_factura.=$esp_init.$totaltexto1."\n";
	}
	else{
		$info_factura.="\n";
	}

	$esp_totales_g=espacios_izq(" ",66);

	$info_factura.=$esp_totales_g.$total_value_gravado."\n";

	$splentot2=len_espacios($total_final_format,10);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$info_factura.="\n";
	$info_factura.="\n";
	$info_factura.=$esp_totales_g.$total_final_format;

	// retornar valor generado en funcion
	return ($info_factura);

}

function print_ccf($id_fact,$tipo_id,$nitcte,$nrccte,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_factura=$id_fact;
	$tipo_id=$tipo_id;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	//Empresa
	$datos_empresa=datos_empresa();
	$field= json_decode($datos_empresa, true);
	$nit=$field['nit'];
	$nrc=$field['nrc'];
	$empresa1=$field['empresa'];
	$razonsocial1=$field['razonsocial'];
	$giro1=$field['giro'];
	//impuestos
	//$result_IVA=datos_impuesto();
//	$row_IVA=_fetch_array($result_IVA);
	$iva=0.13;///$row_IVA['iva']/100;
	$monto_retencion1=100;//$row_IVA['monto_retencion1'];
	$monto_retencion10=100;//$row_IVA['monto_retencion10'];
	$monto_percepcion=100;//$row_IVA['monto_percepcion'];
	//Sucursal
	$nombre_sucursal1=datos_sucursal($id_sucursal);

	//inicio datos
	$info_factura="";

	//Obtener informacion de tabla Factura
	$result_fact=datos_factura($id_factura);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['cliente'];
		$id_factura = $row_fact['id_cobro'];
		$id_usuario=$row_fact['id_usuario'];
		$id_vendedor=$row_fact['id_empleado'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];
		$caja=$row_fact['caja'];
		$turno=$row_fact['turno'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado usuario y vendedor
		$result_emp= datos_empleado($id_usuario,$id_vendedor);
		list($empleado,$vendedor)=explode('|',$result_emp);
		//Datos del Cliente
		$result=datos_cliente($id_cliente);
		$count=_num_rows($result);
		if ($count > 0) {
			$row1 = _fetch_array($result);
			$nombre=$row1["nombre"];
			$nit=$row1["nit"];
			$dui=$row1["dui"];
			$telefono1=$row1["telefono1"];
			$giro_cte=$row1["giro"];
			$nombres=$row1['nombre'];
			$percibe=0;//$row1['percibe'];
		}
		//Columnas y posiciones base
		$base1=7;
		$col0=1;
		$col1=4;
		$col2=3;
		$col3=13;
		$col4=5;
		$sp1=2;
		$sp_prec=15;
		$sp=espacios_izq(" ",$sp1);
		$sp2=espacios_izq(" ",12);

		$esp_init=espacios_izq(" ",5);
		$esp_init2=espacios_izq(" ",9);
		$esp_init4=espacios_izq(" ",13);
		$esp_init3=espacios_izq(" ",51);
		$nombre_ape=texto_espacios($nombres,44);
		$dir_txt=texto_espacios($direccion,43);
		$giro_cte1=texto_espacios($giro_cte,18);
		$total_final=0;
		$imprimir="";

		//Datos encabezado factura
		$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
		//$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada

		$imprimir="";
		for($s=0;$s<7;$s++){
			$imprimir.="\n";
		}

		$info_factura.=$imprimir;
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init3.$diaa."      ".$mess."       ".$anio."|";
		//Datos del cliente

		$nombreapecte=trim($nombreapecte);
		$info_factura.="\n";
		$info_factura.=$esp_init2."".$nombre_ape."  ".$giro_cte1."|";
		$info_factura.=$esp_init4." ".$direccion."|";
		//$info_factura.="\n";
		$info_factura.=$esp_init2." ".$nitcte.$esp_init2.$nrccte."|";

		for($p=0;$p<1;$p++){
			$info_factura.="\n";
		}

		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		//Obtener informacion de tabla Factura_detalle y producto
		$result_fact_det=datos_fact_det($id_factura);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		$espacios1=espacios_izq(" ",1);
		$espacios2=espacios_izq(" ",2);
		$espacios3=espacios_izq(" ",3);
		$espacios4=espacios_izq(" ",4);
		$espacios5=espacios_izq(" ",5);
		$espacios6=espacios_izq(" ",6);
		$espacios7=espacios_izq(" ",7);
		$espacios8=espacios_izq(" ",8);
		$espacios9=espacios_izq(" ",9);
		$espacios10=espacios_izq(" ",10);
		$espacios11=espacios_izq(" ",11);
		$espacios12=espacios_izq(" ",12);
		$espacios15=espacios_izq(" ",15);
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_examen'];
			$descripcion =trim($row_fact_det['nombre_examen']);
			//descripcion presentacion
			//$id_presentacion =$row_fact_det['id_presentacion'];
			//$descpre =trim($row_fact_det['descpre']);
			//$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=0;//$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_detalle_cobro'];
			//$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio'];
			$subt =$row_fact_det['subtotal'];
			//$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ="SERVICIO";//$row_fact_det['tipo_prod_serv'];
			//presentacion producto
			$sql_uus=_fetch_array(_query("SELECT precio FROM precio_examen WHERE id_examen=$id_producto"));
			$precio_p=$sql_uus['precio'];
			$cantidad=$cantidad;

			//linea a linea

			$descrip=texto_espacios($descripcion,37);
			$subt=$precio_venta*$cantidad;
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$precio_sin_iva =round(($precio_venta/(1+($iva))),6);

				$subt_sin_iva=round(($precio_sin_iva*$cantidad),6);
				$subt_gravado=sprintf("%.2f",$subt_sin_iva);
				$total_gravado=$subt_sin_iva+$total_gravado;
			}
			else{
				$e_g="E";
				$precio_sin_iva =round($precio_venta,6);
				$subt_sin_iva=round(($precio_sin_iva*$cantidad),6);
				$subt_exento=sprintf("%.2f",$subt_sin_iva);
				$total_exento=$subt_sin_iva+$total_exento;

			}
			$precio_sin_iva_print=sprintf("%.4f",$precio_sin_iva);

			$subt_sin_iva_print=sprintf("%.4f",$subt_sin_iva);

			$sp1=len_espacios($cantidad,5);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_sin_iva_print,7);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subt_sin_iva_print,8);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",4);
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$precio_sin_iva_print.$esp_col3."".$subt_sin_iva_print."\n";
			}
			if ($exento==0){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col4.$precio_sin_iva_print.$espacios11.$esp_col3.$subt_sin_iva_print."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$total_iva=round($iva*$total_gravado,5);
	//$total_iva=	$total_final/(1+$iva);
	$total_iva_format=sprintf("%.2f",$total_iva);
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if($entero=='100' && $decimal=='00'){
		$enteros_txt="CIEN";
	}
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}
	$cadena_salida_txt=$enteros_txt." DLS CON ".$decimales_txt."/100 CTVS";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$porcentaje_percepcion=0.01;
	$total_fin=$total_exento+$total_gravado;
	$total_percepcion=0;
	if($total_fin>$monto_percepcion && $percibe==1){
		$total_percepcion=$total_fin*$porcentaje_percepcion;
	}

	$total_exento_print=sprintf("%.2f",$total_exento);
	$total_gravado_print=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);
	$total_value_percepcion=sprintf("%.2f",$total_percepcion);
	//totales y n lineas
	$lineas_faltantes=13-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$imprimir.="\n";
	}
	$info_factura.=$imprimir;
	//$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt,50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}

	$totaltexto=trim($tmplinea[0]);
	$len_totaltexto=50-strlen($totaltexto);
	$esp_totaltexto=espacios_izq(" ",$len_totaltexto);
	$esp_init=espacios_izq(" ",8);
	//sumas y total texto
	$splentot1=len_espacios($total_exento_print,10);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_gravado_print,10);
	$esp_lentot2=espacios_izq(" ",$splentot2);

	$info_factura.=$esp_init.$totaltexto.$esp_totaltexto.$espacios5.$esp_lentot2.$total_gravado_print."\n";
	if($ln>1){
		$totaltexto1=trim($tmplinea[1]);
		$len_totaltexto1=50-strlen($totaltexto1);
		$esp_totaltexto1=espacios_izq(" ",$len_totaltexto1);
		$info_factura.=$esp_init.$totaltexto1;//."\n";
	}
	else{
		//$info_factura.="\n";
	}
	//total IVA
	$esp_totales_g=espacios_izq(" ",63);
	$splentot_iva=len_espacios($total_iva_format,10);
	$esp_tot_iva=espacios_izq(" ",$splentot_iva);
	$info_factura.=$esp_totales_g.$esp_tot_iva.$total_iva_format."\n";
	//$esp_init=espacios_izq(" ",5);

	//$info_factura.=$esp_totales_g.$esp_tot_iva.$total_iva_format."\n";
	//total gravado con iva
	/*$total_gravado_iva=round($total_gravado+$total_iva,2);
	$total_gravado_iva_print=sprintf("%.2f",$total_gravado_iva);
	$splentot_g_iva=len_espacios($total_gravado_iva_print,10);
	$esp_tot_g_iva=espacios_izq(" ",$splentot_g_iva);
	$info_factura.=$esp_totales_g.$esp_tot_g_iva.$total_gravado_iva_print."\n";
	*/
	//total percepcion
	/*$total_percepcion_print=sprintf("%.2f",$total_percepcion);
	$splentot_percepcion=len_espacios($total_percepcion_print,10);
	$esp_tot_percepcion=espacios_izq(" ",$splentot_percepcion);
	$info_factura.=$esp_totales_g.$esp_tot_percepcion.$total_percepcion_print."\n";
	$info_factura.="\n";
	*/
	// imprime total Final
	$info_factura.="\n";
	$total_final_todos=round(($total_exento+$total_gravado+$total_iva)-$total_percepcion,2);
	$total_final_todos_print=sprintf("%.2f",$total_final_todos);
	$splentot_fin=len_espacios($total_final_todos_print,10);
	$esp_tot_fin=espacios_izq(" ",$splentot_fin);
	$info_factura.=$esp_totales_g.$esp_tot_fin.$total_final_todos_print;

	// retornar valor generado en funcion
	return ($info_factura);
}
function print_fact3($id_factura,$tipo_id,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	$info_factura="";
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['cliente'];
		$id_factura = $row_fact['id_cobro'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$esp_init=espacios_izq(" ",10);
		$esp_init2=espacios_izq(" ",60);
		$nombre_ape=texto_espacios($nombreapecte,32);
		$dir_txt=texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<8;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."   ".$mess."   ".$anio."|";

		//Datos del cliente
		$info_factura.=$esp_init."".$nombre_ape."|";
		$info_factura.=$esp_init.$direccion."|";
		$info_factura.=$esp_init2.$dui."|";
		$info_factura.=$esp_init2.$nit."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		/*
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		*/
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion_pr,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		$info_factura.="\n";
		//$info_factura.="\n";
		//$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada
		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';

			//linea por linea de productos
			//$descrip=texto_espacios($descripcion,22);
			//$descpresenta1 =texto_espacios($descpre,7);
			$descripcion1=substr($descpresenta,0,7).", ".substr($descripcion,0,22)." ".substr($descpre,0,10);
			$descrip=texto_espacios($descripcion1,35);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}
			//$precio_sin_iva_print=sprintf("%.2f",$precio_sin_iva);
			$col2=2;
			$espacios1=espacios_izq(" ",1);
			$espacios2=espacios_izq(" ",2);
			$espacios3=espacios_izq(" ",3);
			$espacios4=espacios_izq(" ",4);
			$espacios5=espacios_izq(" ",5);
			$espacios6=espacios_izq(" ",6);
			$espacios7=espacios_izq(" ",7);
			$sp1=len_espacios($cantidad,5);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_unit,7);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subtotal,8);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",6);
			//imprimir productos
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
			}
			if ($exento==0){
				//	$sp3=$sp3+11;
				//	$esp_col3=espacios_izq(" ",$sp3);
				$info_factura.=$espacios1.$esp_col1.$cantidad.$espacios5.$descrip.$espacios3.$esp_col3.$precio_unit.$espacios7.$esp_col4.$subtotal."\n";
				//	$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3.$subtotal."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);

	//totales
	$lineas_faltantes=10-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
	//$info_factura.="\n";
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",56);
	$splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,12);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$len_desc=50-strlen(trim($tmplinea[0]));
	$esp_desc=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",5);
	$espacios=espacios_izq(" ",18);

	//imprimir totales
	//$linea0=strlen(trim($tmplinea[0]));
	//	$len_desc=50-$linea0;
	//$esp_totales=espacios_izq(" ",$len_desc);
	$esp_totales=espacios_izq(" ",62);
	$info_factura.=$esp_totales.$esp_lentot2.$total_value_gravado."\n";
	$esp_init=espacios_izq(" ",7);
	$info_factura.=$esp_init.$tmplinea[0]."\n";
	//	$info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_tot_iva.$total_iva_format."\n";

	if($ln>1){
		$esp_init=espacios_izq(" ",6);
		$len_desc=76-strlen(trim($tmplinea[1]));
		$esp_totales=espacios_izq(" ",$len_desc);
		$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2." "."\n";
		for($x=0;$x<2;$x++){
			$info_factura.="\n";
		}
	}
	//	$info_factura.="\n";
	$esp_totales_g=espacios_izq(" ",62);

	$info_factura.=$esp_totales_g.$esp_lentot2.$total_value_gravado."\n";

	$esp_totales=espacios_izq(" ",62);
	for($x=0;$x<2;$x++){
		$info_factura.="\n";
	}
	$splentot2=len_espacios($total_final_format,12);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
	$info_factura.="|".$esp_totales.$esp_lentot2.$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);

}

function print_envio($id_factura,$tipo_id,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	//Sucursal
	$empresa1=texto_espacios($empresa,30);
	$razonsocial1=texto_espacios($razonsocial,30);
	$giro1=texto_espacios($giro,30);
	//inicio datos
	$info_factura="";
	//Obtener informacion de tabla Factura
	//fecha  arriba 1 linea, direccion 3 esp der , descripcion 2 esp der , qitar prec unit
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base

		$esp_init=espacios_izq(" ",12);
		$esp_init2=espacios_izq(" ",76);
		$nombre_ape=texto_espacios($nombres,32);
		$dir_txt=texto_espacios($direccion,30);
		$total_final=0;
		for($h=0;$h<3;$h++){
			$info_factura.="\n";
		}
		$nombre_ape=texto_espacios($nombreapecte,40);

		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$esp_init2=espacios_izq(" ",60);
		$info_factura.=$esp_init2.$diaa."   ".$mess."   ".$anio."|";
		//$info_factura.="\n";
		//Datos del cliente
		$info_factura.=$esp_init."   ".$nombre_ape."|";
		$info_factura.="\n";
		$info_factura.=$esp_init."   ".$direccion."|";
		$info_factura.=$esp_init2.$dui."|";
		$info_factura.=$esp_init2.$nit."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		/*
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		"; */
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion_pr,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		//$info_factura.="\n";
		for ($i = 0; $i<3; $i++) {
			$info_factura.= chr(10); //Line Feed
		}
		$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';
			//agregar query para presentaciones y agregarlo a descripcion
			//linea por linea de productos
			//$descrip=texto_espacios($descripcion,60);
			$descripcion1=substr($descpresenta,0,8).", ".substr($descripcion,0,30)." ".substr($descpre,0,15);
			$descrip=texto_espacios($descripcion1,50);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}

			$col2=2;
			$esp1=len_espacios($cantidad,6);
			$esp_col1=espacios_izq(" ",$esp1);
			$esp2=len_espacios($precio_venta,8);
			$esp_col2=espacios_izq(" ",$esp2);
			$esp3=len_espacios($subtotal,8);
			$esp_col3=espacios_izq(" ",$esp3);
			$esp_desc=espacios_izq(" ",2);
			$sp1=espacios_izq(" ",1);
			$sp2=espacios_izq(" ",5);
			$sp3=espacios_izq(" ",3);
			$sp4=espacios_izq(" ",2);
			$sp5=espacios_izq(" ",5);
			$info_factura.=$sp1.$esp_col1.$cantidad.$sp2.$descrip.$sp1.$esp_col3.$subtotal."\n";

			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= "    ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);

	//totales
	$lineas_faltantes=19-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada

	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",50);
	$splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);


	//imprimir totales

	$linea0=strlen(trim($tmplinea[0]));
	$len_desc=40-$linea0;
	//$esp_totales=espacios_izq(" ",$len_desc);
	$esp_desc=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",12);
	$espacios=espacios_izq(" ",10);
	$info_factura.="\n";
	$info_factura.="\n";
	$splentot2=len_espacios($total_final_format,10);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_lentot2.$total_final_format."\n";
	if($ln>1){
		$esp_init=espacios_izq(" ",6);
		$len_desc=76-strlen(trim($tmplinea[1]));
		$esp_totales=espacios_izq(" ",$len_desc);
		$info_factura.=$esp_init.$tmplinea[1]." \n";
		for($x=0;$x<2;$x++){
			$info_factura.="\n";
		}
	}
	$info_factura.="\n";
	$esp_totales_g=espacios_izq(" ",83);
	$esp_totales=espacios_izq(" ",83);
	for($x=0;$x<1;$x++){
		$info_factura.="\n";
	}
	// retornar valor generado en funcion
	return ($info_factura);

}
function print_fact_dia($id_fact,$tipo_id){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_factura=$id_fact;
	$tipo_id=$tipo_id;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	//Sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	$nombre_sucursal1=texto_espacios($nombre_sucursal,30);
	$empresa1=texto_espacios($empresa,30);
	$razonsocial1=texto_espacios($razonsocial,30);
	$giro1=texto_espacios($giro,30);
	//inicio datos
	$info_factura="";
	$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|";

	$sql_fact="SELECT * FROM factura_dia WHERE id_factura_dia='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura_dia'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$total=$row_fact['total'];
		$num_fact=$id_factura;
		$numfact=espacios_izq($num_fact,10);

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$base1=7;
		$col0=1;
		$col1=4;
		$col2=3;
		$col3=13;
		$col4=5;
		$sp1=2;
		$sp_prec=15;
		$sp=espacios_izq(" ",$sp1);
		$sp2=espacios_izq(" ",12);
		$esp_init=espacios_izq(" ",12);
		$esp_precios=espacios_izq(" ",$sp_prec);
		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",70);
		$nombre_ape=texto_espacios($nombres,32);
		$dir_txt=texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<8;$h++){
			$imprimir.="\n";
		}
		$info_factura.=$imprimir;
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."       ".$mess."           ".$anio."|";
		$info_factura.=$esp_init."FACTURA CONSUMIDOR DIARIA# ".$num_fact."|";
		//Datos del cliente
		$info_factura.=$esp_init."   ".$nombre_ape."|";
		$info_factura.=$esp_init.$direccion."|";
		$info_factura.=$esp_init.$dui."|";
		$info_factura.=$esp_init.$nit."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,factura_detalle_dia.*
		FROM factura_detalle_dia JOIN producto ON factura_detalle_dia.id_producto=producto.id_producto
		WHERE  factura_detalle_dia.id_factura_dia='$id_factura'
		";

		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		$info_factura.="\n";
		//$info_factura.="\n";
		$info_factura.= chr(27).chr(51)."2"; //espacio entre lineas 6 x pulgada
		//$info_factura.="\n";
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =$row_fact_det['descripcion'];
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factdet_dia'];
			$id_prod_serv =$row_fact_det['id_producto'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];

			//linea por linea de productos
			$descrip=texto_espacios($descripcion,42);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}

			$col2=2;
			$sp1=len_espacios($cantidad,7);
			$esp_col1=espacios_izq(" ",$sp1);
			$sp2=len_espacios($precio_sin_iva_print,8);
			$esp_col2=espacios_izq(" ",$sp2+4);
			$sp3=len_espacios($subt_sin_iva_print,10);
			$esp_col3=espacios_izq(" ",$sp3+1);
			$esp_desc=espacios_izq(" ",6);
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
			}
			if ($exento==0){
				$sp3=$sp3+11;
				$esp_col3=espacios_izq(" ",$sp3);
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3.$subtotal."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);

	//totales
	$lineas_faltantes=12-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada

	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 40,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",56);
	$splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,12);
	$esp_lentot2=espacios_izq(" ",$splentot2);

	//imprimir totales

	$linea0=strlen(trim($tmplinea[0]));
	$len_desc=72-$linea0;
	$esp_totales=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",10);
	$info_factura.="\n";
	$info_factura.="\n";
	$info_factura.=$esp_init.$tmplinea[0].$esp_totales."  ".$esp_lentot2.$total_value_gravado."\n";
	if($ln>1){
		$esp_init=espacios_izq(" ",6);
		$len_desc=76-strlen(trim($tmplinea[1]));
		$esp_totales=espacios_izq(" ",$len_desc);
		$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2." "."\n";
		for($x=0;$x<2;$x++){
			$info_factura.="\n";
		}
	}
	else{
		for($x=0;$x<3;$x++){
			$info_factura.="\n";
		}
	}
	$esp_totales_g=espacios_izq(" ",83);

	$info_factura.=$esp_totales_g."  ".$esp_lentot2.$total_value_gravado."\n";

	$esp_totales=espacios_izq(" ",83);
	for($x=0;$x<2;$x++){
		$info_factura.="\n";
	}
	$info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);

}
function print_ccf3($id_fact,$tipo_id,$nitcte,$nrccte,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	$id_factura=$id_fact;
	$tipo_id=$tipo_id;
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro_empresa=$row_empresa['giro'];
	$iva=$row_empresa['iva']/100;
	//Sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	$nombre_sucursal1=texto_espacios($nombre_sucursal,30);
	$empresa1=texto_espacios($empresa,30);
	$razonsocial1=texto_espacios($razonsocial,30);
	$giro1=texto_espacios($giro_empresa,30);
	//inicio datos
	$info_factura="";
	//$info_factura.=$empresa1."|".$nombre_sucursal1."|".$razonsocial1."|".$giro1."|";
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];
		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result=_query($sql);
		$count=_num_rows($result);
		if ($count > 0) {
			for ($i = 0; $i < $count; $i ++) {
				$row1 = _fetch_array($result);
				//$id_cliente=$row1["id_cliente"];
				$nombre=$row1["nombre"];
				$nit=$row1["nit"];
				$dui=$row1["dui"];
				$telefono1=$row1["telefono1"];
				$giro_cte=$row1["giro"];
				$nombres=$row1['nombre'];
			}
		}
		//Columnas y posiciones base
		$base1=7;
		$col0=1;
		$col1=4;
		$col2=3;
		$col3=13;
		$col4=5;
		$sp1=2;
		$sp_prec=15;
		$sp=espacios_izq(" ",$sp1);
		$sp2=espacios_izq(" ",12);


		$esp_enc2=espacios_izq(" ",3);
		$esp_init2=espacios_izq(" ",76);
		$nombre_ape=texto_espacios($nombres,45);
		$dir_txt=texto_espacios($direccion,43);
		$giro_cte1=texto_espacios($giro_cte,25);
		$total_final=0;
		$imprimir="";

		//Datos encabezado factura
		$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
		//$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada

		$imprimir="";
		for($s=0;$s<8;$s++){
			$imprimir.="\n";
		}
		$nombreapecte=trim($nombreapecte);
		$info_factura.=$imprimir;
		$esp_init=espacios_izq(" ",12);
		$info_factura.=$esp_init.$nombreapecte."|";
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$esp_init2=espacios_izq(" ",60);
		$info_factura.=$esp_init2.$diaa." - ".$mess." - ".$anio."|";
		//NRC
		$esp_init2=espacios_izq(" ",65);
		$info_factura.=$esp_init2.$nrccte."|";
		$esp_init=espacios_izq(" ",5);
		//$info_factura.="\n";
		$info_factura.=$esp_init.$dir_txt."|";
		//NIT
		$esp_init2=espacios_izq(" ",53);
		$info_factura.=$esp_init2.$nitcte."|";
		//GIRO
		//$esp_init2=espacios_izq(" ",0);
		//$info_factura.="\n";
		$info_factura.=$giro_cte1."|"; //$esp_init2." ".$giro_cte1."|";


		for($p=0;$p<2;$p++){
			$info_factura.="\n";
		}


		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		//Obtener informacion de tabla Factura_detalle y producto
		/*
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		*/
		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion_pr,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;

		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv =$row_fact_det['tipo_prod_serv'];
			//linea a linea
			$descripcion1=substr($descpresenta,0,7).", ".substr($descripcion,0,22)." ".substr($descpre,0,10);
			$descrip=texto_espacios($descripcion1,37);

			$subt=$precio_venta*$cantidad;


			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$precio_sin_iva0 =$row_fact_det['precio_venta']/(1+($iva));
				$precio_sin_iva =round($row_fact_det['precio_venta']/(1+($iva)),2);
				$subt_sin_iva=$precio_sin_iva0*$cantidad;
				$subt_gravado=sprintf("%.2f",$subt_sin_iva);
				$total_gravado=$subt_sin_iva+$total_gravado;
			}
			else{
				$e_g="E";
				$precio_sin_iva =round($row_fact_det['precio_venta'],2);
				$precio_sin_iva0 =$row_fact_det['precio_venta'];
				$subt_sin_iva=$precio_sin_iva0*$cantidad;
				$subt_exento=sprintf("%.2f",$subt_sin_iva);
				$total_exento=$subt_sin_iva+$total_exento;

			}
			$precio_sin_iva_print=sprintf("%.2f",$precio_sin_iva);

			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$col2=2;

			$espacios1=espacios_izq(" ",1);
			$espacios2=espacios_izq(" ",2);
			$espacios3=espacios_izq(" ",3);
			$espacios4=espacios_izq(" ",4);
			$espacios5=espacios_izq(" ",5);
			$sp1=len_espacios($cantidad,7);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_sin_iva_print,7);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subt_sin_iva_print,10);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",3);
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$espacios4.$precio_sin_iva_print.$esp_col3."  ".$subt_sin_iva_print."\n";
			}
			if ($exento==0){
				$info_factura.=$espacios1.$esp_col1.$cantidad.$espacios4.$descrip.$espacios1.$esp_col2.$precio_sin_iva_print.$espacios5.$esp_col4.$subt_sin_iva_print."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$calc_iva=round($iva*$total_gravado,2);
	$total_iva_format=sprintf("%.2f",$calc_iva);
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if($entero=='100' && $decimal=='00'){
		$enteros_txt="CIEN";
	}
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);
	//totales y n lineas
	$lineas_faltantes=10-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$imprimir.="\n";
	}
	$info_factura.=$imprimir;

	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt,50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$info_factura.="\n";
	$esp_init=espacios_izq(" ",5);
	$subtotal_gravado=round($total_gravado+$calc_iva,2);
	$subtotal_exento=$total_exento;
	$total_final_todos=round($subtotal_exento+$subtotal_gravado,2);
	//$info_factura.=chr(27).chr(50);
	$esp_totales=espacios_izq(" ",72);
	$splentot1=len_espacios($total_value_exento,10);
	$esp_lentot1=espacios_izq(" ",$splentot1+5);
	$splentot2=len_espacios($total_value_gravado,10);
	$esp_lentot2=espacios_izq(" ",$splentot2+3);

	$espacio=espacios_izq(" ",62);
	$splentot1=len_espacios($total_value_gravado,10);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	//imprime  total gravado
	$info_factura.=$espacio.$esp_lentot1.$total_value_gravado."\n";
	$espacio_txtnum=espacios_izq(" ",4);
	$splentot_iva=len_espacios($total_iva_format,10);
	$esp_tot_iva=espacios_izq(" ",$splentot_iva);
	$len_desc=50-strlen(trim($tmplinea[0]));
	$esp_desc=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",5);
	$espacios=espacios_izq(" ",7);
	// imprime total en texto e IVA
	$info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_tot_iva.$total_iva_format."\n";

	$subtotal_gravado_print=sprintf("%.2f",$subtotal_gravado);
	if($ln>1){
		$len_desc=65-strlen(trim($tmplinea[1]));
		$esp_totales=espacios_izq(" ",$len_desc);
		$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2."   ".$subtotal_gravado_print."\n";
	}
	else{
		$espacio=espacios_izq(" ",62);
		$splentot1=len_espacios($subtotal_gravado_print,10);
		$esp_lentot1=espacios_izq(" ",$splentot1);
		// imprime Subtotal
		$info_factura.=$espacio.$esp_lentot1.$subtotal_gravado_print."\n";
	}

	for($k=0;$k<1;$k++){
		$info_factura.="\n";
	}

	$total_final_todoss=sprintf("%.2f",$total_final_todos);

	$info_factura.="\n";
	$espacio=espacios_izq(" ",62);
	$splentot1=len_espacios($total_final_todoss,10);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	// imprime total Final
	$info_factura.=$espacio.$esp_lentot1.$total_final_todoss."\n";
	$info_factura.="|".$total_final_todos;
	// retornar valor generado en funcion
	return ($info_factura);
}
function print_ncr($id_factura,$tipo_id,$nombreapecte,$direccion){
	$id_sucursal=$_SESSION['id_sucursal'];
	//Valido el sistema operativo y lo devuelvo para saber a que puerto redireccionar
	$info = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($info, 'Windows') == TRUE)
	$so_cliente='win';
	else
	$so_cliente='lin';
	$info_factura="";
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM factura WHERE id_factura='$id_factura'";
	$result_fact=_query($sql_fact);
	$row_fact=_fetch_array($result_fact);
	$nrows_fact=_num_rows($result_fact);
	if($nrows_fact>0){
		$id_cliente=$row_fact['id_cliente'];
		$id_factura = $row_fact['id_factura'];
		$id_usuario=$row_fact['id_usuario'];
		$fecha=$row_fact['fecha'];
		$fecha_fact=ed($fecha);
		$numero_doc=trim($row_fact['numero_doc']);
		$total=$row_fact['total'];

		$len_numero_doc=strlen($numero_doc)-4;
		$num_fact=substr($numero_doc,0,$len_numero_doc);
		$tipo_fact=substr($numero_doc,$len_numero_doc,4);
		$numfact=espacios_izq($num_fact,10);
		//Datos de empleado
		$sql_user="select * from usuario where id_usuario='$id_usuario'";
		$result_user= _query($sql_user);
		$row_user=_fetch_array($result_user);
		$nrow_user=_num_rows($result_user);
		$usuario=$row_user['usuario'];
		$nombreusuario=$row_user['nombre'];

		//Datos del Cliente
		$sql="select * from cliente where id_cliente='$id_cliente'";
		$result= _query($sql);
		$row1=_fetch_array($result);
		$nrow1=_num_rows($result);
		$nombres=$row1['nombre'];
		$dui=$row1['dui'];
		$nit=$row1['nit'];
		$nrc=$row1['nrc'];
		//$direccion=$row1['direccion'];

		//Columnas y posiciones base
		$esp_init=espacios_izq(" ",13);
		$esp_init2=espacios_izq(" ",65);
		$nombre_ape=texto_espacios($nombreapecte,32);
		$dir_txt=texto_espacios($direccion,30);
		$total_final=0;
		$imprimir="";
		for($h=0;$h<8;$h++){
			$info_factura.="\n";
		}
		//Datos encabezado factura
		list($diaa,$mess,$anio)=explode("-",$fecha_fact);
		$info_factura.=$esp_init2.$diaa."   ".$mess."   ".$anio."|";

		//Datos del cliente
		$info_factura.=$esp_init."".$nombre_ape."|";
		$info_factura.="\n";
		$info_factura.=$esp_init.$direccion."|";
		for($k=0;$k<2;$k++){
			$info_factura.="\n";
		}
		$info_factura.=$esp_init2.$nrc."|";
		//Obtener informacion de tabla Factura_detalle y producto o servicio

		$sql_fact_det="SELECT  producto.id_producto, producto.descripcion, producto.exento,
		presentacion.descripcion_pr,
		presentacion_producto.descripcion AS descpre,
		factura_detalle.*
		FROM factura_detalle
		JOIN producto ON factura_detalle.id_prod_serv=producto.id_producto
		JOIN presentacion_producto ON factura_detalle.id_presentacion=presentacion_producto.id_presentacion
		JOIN presentacion ON presentacion.id_presentacion=presentacion_producto.presentacion
		WHERE  factura_detalle.id_factura='$id_factura'
		";
		$result_fact_det=_query($sql_fact_det);
		$nrows_fact_det=_num_rows($result_fact_det);
		$total_final=0;
		$lineas=6;
		$cuantos=0;
		$subt_exento=0;
		$subt_gravado=0;
		$total_exento=0;
		$total_gravado=0;
		for($k=0;$k<3;$k++){
			$info_factura.=chr(10);
		}

		//$info_factura.="\n";
		$info_factura.= chr(27).chr(51)."1"; //espacio entre lineas 6 x pulgada
		for($i=0;$i<$nrows_fact_det;$i++){
			$row_fact_det=_fetch_array($result_fact_det);
			$id_producto =$row_fact_det['id_producto'];
			$descripcion =trim($row_fact_det['descripcion']);
			//descripcion presentacion
			$descpre =trim($row_fact_det['descpre']);
			$descpresenta =trim($row_fact_det['descripcion_pr']);
			$exento=$row_fact_det['exento'];
			$id_factura_detalle =$row_fact_det['id_factura_detalle'];
			$id_prod_serv =$row_fact_det['id_prod_serv'];
			$cantidad =$row_fact_det['cantidad'];
			$precio_venta =$row_fact_det['precio_venta'];
			$subt =$row_fact_det['subtotal'];
			$id_empleado =$row_fact_det['id_empleado'];
			$tipo_prod_serv ='PRODUCTO';

			//linea por linea de productos
			$descripcion1=substr($descpresenta,0,7).", ".substr($descripcion,0,22)." ".substr($descpre,0,10);
			$descrip=texto_espacios($descripcion1,42);
			$subt=$precio_venta*$cantidad;
			$subt_sin_iva=$precio_venta*$cantidad;
			$subt_sin_iva_print=sprintf("%.2f",$subt_sin_iva);
			$precio_unit=sprintf("%.2f",$precio_venta);
			$subtotal=sprintf("%.2f",$subt);
			$total_final=$total_final+$subtotal;
			if ($exento==0){
				$e_g="G";
				$subt_gravado=sprintf("%.2f",$subtotal);
				$total_gravado=$subtotal+$total_gravado;
			}
			else{
				$e_g="E";
				$subt_exento=sprintf("%.2f",$subtotal);
				$total_exento=$subtotal+$total_exento;
			}
			//$precio_sin_iva_print=sprintf("%.2f",$precio_sin_iva);
			$col2=2;
			$espacios1=espacios_izq(" ",1);
			$espacios2=espacios_izq(" ",2);
			$espacios3=espacios_izq(" ",3);
			$espacios4=espacios_izq(" ",4);
			$espacios5=espacios_izq(" ",5);
			$sp1=len_espacios($cantidad,5);
			$esp_col1=espacios_izq(" ",$sp1);
			$esp_col2=espacios_izq(" ",2);
			$sp3=len_espacios($precio_unit,7);
			$esp_col3=espacios_izq(" ",$sp3);
			$sp4=len_espacios($subtotal,10);
			$esp_col4=espacios_izq(" ",$sp4);
			$esp_desc=espacios_izq(" ",6);
			//imprimir productos
			if ($exento==1){
				$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2."".$precio_unit.$esp_col3.$subtotal."\n";
			}
			if ($exento==0){
				//	$sp3=$sp3+11;
				//	$esp_col3=espacios_izq(" ",$sp3);
				$info_factura.=$espacios2.$esp_col1.$cantidad.$espacios3.$descrip.$espacios1.$esp_col3.$precio_unit.$espacios5.$esp_col4.$subtotal."\n";
				//	$info_factura.=$esp_col1.$cantidad.$esp_desc.$descrip.$esp_col2.$precio_unit.$esp_col3.$subtotal."\n";
			}
			$cuantos=$cuantos+1;
		}
	}
	$total_final_format=sprintf("%.2f",$total_final);
	list($entero,$decimal)=explode('.',$total_final_format);
	$enteros_txt=num2letras($entero);
	if(strlen($decimal)==1){
		$decimales_txt=$decimal."0";
	}
	else{
		$decimales_txt=$decimal;
	}

	$cadena_salida_txt= " ".$enteros_txt." dolares con ".$decimales_txt."/100 ctvs";
	$esp=espacios_izq(" ",7);
	$total_value=sprintf("%.2f",$total);
	$sp3=10;
	$total_fin=$total_exento+$total_gravado;
	$total_value_exento=sprintf("%.2f",$total_exento);
	$total_value_gravado=sprintf("%.2f",$total_gravado);
	$total_value_fin=sprintf("%.2f",$total_fin);

	//totales
	$lineas_faltantes=15-$cuantos;
	$imprimir="";
	for($j=0;$j<$lineas_faltantes;$j++){
		$info_factura.="\n";
	}
	$info_factura.= chr(27).chr(50); //espacio entre lineas 6 x pulgada
	//$info_factura.="\n";
	$esp_init2=espacios_izq(" ",25);
	$esp_totales=espacios_izq(" ",40);
	//generar 2 lineas del texto del total de la factura
	$total_txt0 =cadenaenlineas($cadena_salida_txt, 50,2);
	$concepto_print="";
	$tmplinea = array();
	$ln=0;
	$esp_init=espacios_izq(" ",2);

	foreach($total_txt0 as $total_txt1){
		$tmplinea[]=$total_txt1;
		$ln=$ln+1;
	}
	$esp_totales=espacios_izq(" ",56);
	$splentot1=len_espacios($total_value_exento,8);
	$esp_lentot1=espacios_izq(" ",$splentot1);
	$splentot2=len_espacios($total_value_gravado,8);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$len_desc=50-strlen(trim($tmplinea[0]));
	$esp_desc=espacios_izq(" ",$len_desc);
	$esp_init=espacios_izq(" ",5);
	$espacios=espacios_izq(" ",16);

	//imprimir totales
	$esp_totales=espacios_izq(" ",71);
	$info_factura.=$esp_init.$tmplinea[0].$esp_desc.$espacios.$esp_lentot2.$total_value_gravado."\n";
	$esp_init=espacios_izq(" ",7);

	if($ln>1){
		$esp_init=espacios_izq(" ",6);
		$len_desc=68-strlen(trim($tmplinea[1]));
		$esp_totales=espacios_izq(" ",$len_desc);
		$info_factura.=$esp_init.$tmplinea[1].$esp_totales.$esp_lentot2." "."\n";
		for($x=0;$x<2;$x++){
			$info_factura.="\n";
		}
	}
	$info_factura.="\n";
	$esp_totales_g=espacios_izq(" ",71);
	$info_factura.=$esp_totales_g.$esp_lentot2.$total_value_gravado."\n";
	$esp_totales=espacios_izq(" ",71);
	for($x=0;$x<2;$x++){
		$info_factura.="\n";
	}
	$splentot2=len_espacios($total_final_format,8);
	$esp_lentot2=espacios_izq(" ",$splentot2);
	$info_factura.=$esp_totales.$esp_lentot2.$total_final_format."\n";
	$info_factura.="|".$esp_totales.$esp_lentot2.$total_final_format."\n";
	// retornar valor generado en funcion
	return ($info_factura);
}
function print_vale($id_movimiento){
	$id_sucursal=$_SESSION['id_sucursal'];
	//sucursal
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre_lab'];
	$razonsocial=$row_empresa['razon_social'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	$id_sucursal=$_SESSION['id_sucursal'];
	//consulta
	$sql="SELECT  e.id_empleado, e.nombre,
	mc.concepto, mc.valor,mc.fecha,mc.hora,mc.entrada,mc.salida,mc.id_sucursal,
	mc.tipo_delige, mc.viatico,mc.detalle,
	mc.nombre_recibe, mc.nombre_autoriza, mc.nombre_proveedor, mc.iva, mc.tipo_doc, mc.numero_doc, mc.caja
	FROM mov_caja AS mc
	JOIN empleado AS e ON(e.id_empleado=mc.id_empleado)
	WHERE  mc.id_movimiento='$id_movimiento'";
	$result=_query($sql);
	$nrow = _num_rows($result);
	$row = _fetch_array($result);
	$id_empleado = $row["id_empleado"];
	$concepto = $row["concepto"];
	$nombre = $row["nombre"];
	//$nombre ="";
	$hora= $row["hora"];
	$fecha= $row["fecha"];
	$valor= $row["valor"];
	$entrada= $row["entrada"];
	$nombre_recibe= $row["nombre_recibe"];
	$nombre_autoriza= $row["nombre_autoriza"];
	$nombre_proveedor = $row["nombre_proveedor"];
	$tipo_doc = $row["tipo_doc"];
	$detalle = $row["detalle"];
	$numero_doc = $row["numero_doc"];
	$tipo_dilige= $row["tipo_delige"];
	$viatico= $row["viatico"];
	$caja = $row["caja"];

	$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
	$dats_caja = _fetch_array($sql_caja);
	$fehca = ED($dats_caja["fecha"]);
	$resolucion = $dats_caja["resolucion"];
	$serie = $dats_caja["serie"];
	$desde = $dats_caja["desde"];
	$hasta = $dats_caja["hasta"];

	//$id_sucursal=$row["id_sucursal"];
	if($entrada==1){
		$tipo="INGRESO";
	}
	else{
		$tipo="EGRESO";
	}
	$line1=str_repeat("_",30)."\n";
	$line2=str_repeat("-",40)."\n";
	$val = sprintf('%.2f', $valor);
	if($tipo_doc == "CCF")
	{
		$valor= sprintf('%.2f', ($valor/1.13));
	}
	else
	{
		$valor= sprintf('%.2f', $valor);
	}

	//Datos
	$col0=1;		$col1=3; 		$col2=3;
	$col3=6;		$col4=5;		$sp1=2;
	$sp_prec=10;
	$sp=espacios_izq(" ",$sp1);
	$sp2=espacios_izq(" ",12);
	$esp_init=espacios_izq(" ",$col0);
	$esp_init0=espacios_izq(" ",1);
	$esp_precios=espacios_izq(" ",$sp_prec);
	$esp_enc2=espacios_izq(" ",3);
	$esp_init2=espacios_izq(" ",23);
	$info_factura="";
	$info_factura.=$esp_init.$empresa."\n";
	$giros = explode(";", $giro);
	for ($ni = 0; $ni < (count($giros)); $ni++)
	{
		$info_factura.=$esp_init.trim($giros[$ni])."\n";
	}
	$info_factura.=$esp_init0."RESOLUCION:  ".$resolucion."\n";
	$info_factura.=$esp_init0."DEL ".$desde." AL ".$hasta."\n";
	$info_factura.=$esp_init0."SERIE ".$serie."\n";
	$info_factura.=$esp_init0."FECHA RESOLUCION ".$fehca."\n";
	$info_factura.=$esp_init."COMPROBANTE DE EGRESO # ".$id_movimiento."\n";
	$caracteres = strlen($concepto);
	$lineas = round(($caracteres /40),2,PHP_ROUND_HALF_UP);
	$con = divtextlin($concepto, 40);

	$info_factura.=$line2."";
	if($tipo == 0)
	{
		if($tipo_doc == "CCF")
		{
			$text = "CREDITO FISCAL";
		}
		else if($tipo_doc == "COF")
		{
			$text = "FACTURA";
		}
		else if($tipo_doc == "RE")
		{
			$text = "RECIBO";
		}
		else if($tipo_doc == "VAL")
		{
			$text = "VALE";
		}
		$a = str_pad("TIPO DOCUMENTO: ".$text,40," ",STR_PAD_RIGHT);
		$b = str_pad("DOCUMENTO # ".$numero_doc,40," ",STR_PAD_RIGHT);
		$f = str_pad(("FECHA: ".ED($fecha)."    HORA:".hora($hora)),40," ",STR_PAD_RIGHT);
		$info_factura.=$esp_init.$f."\n";
		$info_factura.=$esp_init.$a."\n";
		$info_factura.=$esp_init.$b."\n\n";

		$c = str_pad("CONCEPTO: ",40," ",STR_PAD_RIGHT);

		$info_factura.=$esp_init.$c."\n";
		foreach ($con as $key => $value) {
			$d=str_pad($value,40," ",STR_PAD_RIGHT);
			$info_factura.=$esp_init.$d."\n";
		}
	}

	$info_factura.=$line2."";

	if($tipo_doc == "CCF")
	{
		$sp1=len_num($valor,4);
		$e=texto_espacios("VALOR SIN IVA: ",30);
		$info_factura.=$e.$sp1."$".$valor."\n";
		$iva = $row["iva"];
		$sp1=len_num($iva,4);
		$e=texto_espacios("IVA: ",30);
		$info_factura.=$e.$sp1."$".$iva."\n";
		$info_factura.=$line2."";
		$sp1=len_num($val,4);
		$e=texto_espacios("TOTAL VALE: ",30);
		$info_factura.=$e.$sp1."$".$val."\n";
	}
	else
	{
		$sp1=len_num($valor,4);
		$e=texto_espacios("VALOR: ",30);
		$info_factura.=$e.$sp1."$".$valor."\n";
	}

	$info_factura.="\n\n\n";
	$info_factura.=$esp_init."ENTREGA: _______________________________\n".$nombre."\n\n\n";
	$info_factura.=$esp_init."RECIBE: _______________________________\n".$nombre_recibe."\n\n\n";
	$info_factura.=$esp_init."AUTORIZA: _____________________________\n".$nombre_autoriza."\n";

	return ($info_factura);

}
function print_corte($id_corte){
	include_once "_core.php";
	//EMPRESA
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre_lab'];
	$razonsocial=$row_empresa['razon_social'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	$id_sucursal=$_SESSION['id_sucursal'];
	//sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['nombre_lab'];
	//consulta
	$sql="SELECT c.caja, c.turno, c.tinicio, c.tfinal, c.totalnot, c.texento, c.tgravado, c.totalt, c.finicio, c.ffinal, c.totalnof, c.fexento, c.fgravado, c.totalf, c.cfinicio, c.cffinal, c.totalnocf, c.cfexento, c.cfgravado,
	c.totalcf, c.cashinicial, c.vtacontado, c.vtaefectivo, c.vtatcredito, c.totalgral, c.cashfinal, c.totalnodev, c.totalnoanu, c.vales, c.ingresos, c.id_empleado, c.id_sucursal, c.id_apertura, c.fecha_corte, c.hora_corte, c.tipo_corte,e.nombre, c.monto_ch, c.tiket,
	c.turno, c.viaticos, c.recuperacion, c.abono_credito, c.venta_pendiente, c.remesa, c.total_facturado, c.saldo_caja, c.faltante, c.sobrante, c.n_remesa, c.caja_chica, c.vcheque, c.vtransferencia, c.abono_creditoE, c.abono_creditoC, c.abono_creditoT, c.total_RE, c.total_RC, c.total_RT,
	c.remesa, c.n_remesa FROM controlcaja AS c JOIN usuario AS e ON(e.id_usuario=c.id_empleado) WHERE c.id_corte='$id_corte'";
	$result=_query($sql);
	$nrow = _num_rows($result);
	$row = _fetch_array($result);
	$id_empleado = $row["id_empleado"];
	$nombre_emp = $row["nombre"];
	$hora= $row["hora_corte"];
	$fecha= ED($row["fecha_corte"]);
	$tipo= $row["tipo_corte"];
	$tinicio= $row["tinicio"];
	$tfinal= $row["tfinal"];
	$finicio= $row["finicio"];
	$ffinal= $row["ffinal"];
	$cfinicio= $row["cfinicio"];
	$cffinal= $row["cffinal"];
	$cashini= $row["cashinicial"];
	$vtaefectivo= $row["vtaefectivo"];
	$ingresos= $row["ingresos"];
	$vales= $row["vales"];
	$totalgral= $row["totalgral"];
	$cashfinal= $row["cashfinal"];
	$totalnot= $row["totalnot"];
	$totalnof= $row["totalnof"];
	$totalnocf= $row["totalnocf"];
	$monto_ch = $row["monto_ch"];
	$caja = $row["caja"];
	$tike = $row['tiket'];
	$turno = $row["turno"];
	$recuperacion = $row["recuperacion"];
	$abono_credito = $row["abono_credito"];
	$viaticos = $row["viaticos"];
	$venta_pendiente = $row["venta_pendiente"];
	$vtatcredito = $row["vtatcredito"];
	$vcheque = $row["vcheque"];
	$vtransferencia = $row["vtransferencia"];
	$total_RE = $row["total_RE"];
	$total_RC = $row["total_RC"];
	$total_RT = $row["total_RT"];
	$abono_creditoE = $row["abono_creditoE"];
	$abono_creditoC = $row["abono_creditoC"];
	$abono_creditoT = $row["abono_creditoT"];
	$n_remesa = $row["n_remesa"];
	$remesa = $row["remesa"];

	$explora = explode(" ", $nombre_emp);
	$cue_em = count($explora);
	if($cue_em <= 2)
	{
		$nombre_emp = $explora[0]." ".$explora[1];
	}
	else
	{
		$nombre_emp = $explora[0]." ".$explora[2];
	}
	$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
	$dats_caja = _fetch_array($sql_caja);
	$fehca = ED($dats_caja["fecha"]);
	$resolucion = $dats_caja["resolucion"];
	$serie = $dats_caja["serie"];
	$desde = $dats_caja["desde"];
	$hasta = $dats_caja["hasta"];

	$texento= sprintf('%.2f', $row["texento"]);
	$tgravado= sprintf('%.2f', $row["tgravado"]);
	$totalt=  sprintf('%.2f', $row["totalt"]);
	$fexento= sprintf('%.2f', $row["fexento"]);
	$fgravado=sprintf('%.2f',  $row["fgravado"]);
	$totalf= sprintf('%.2f', $row["totalf"]);
	$cfexento= sprintf('%.2f', $row["cfexento"]);
	$cfgravado=sprintf('%.2f',  $row["cfgravado"]);
	$totalcf=sprintf('%.2f',  $row["totalcf"]);
	$monto_apertura = sprintf('%.2f', $cashini);
	$vtatotales=$totalt+$totalf+$totalcf;
	$vtatotales_print=sprintf('%.2f', $vtatotales);
	$vtaefectivo= sprintf('%.2f', $vtaefectivo);
	$cashini= sprintf('%.2f', $cashini);
	$ingresos= sprintf('%.2f', $ingresos);
	$monto_ch = sprintf('%.2f', $monto_ch);
	$recuperacion = sprintf('%.2f', $recuperacion);
	$abono_credito = sprintf('%.2f', $abono_credito);
	$viaticos = sprintf('%.2f', $viaticos);
	$vtatcredito = sprintf('%.2f', $vtatcredito);
	$venta_pendiente = sprintf('%.2f', $venta_pendiente);
	$vcheque = sprintf('%.2f', $vcheque);
	$vtransferencia = sprintf('%.2f', $vtransferencia);
	$remesa = sprintf('%.2f', $remesa);

	$total_RE = sprintf('%.2f', $total_RE);
	$total_RC = sprintf('%.2f', $total_RC);
	$total_RT = sprintf('%.2f', $total_RT);

	$abono_creditoE = sprintf('%.2f',$abono_creditoE);
	$abono_creditoC = sprintf('%.2f',$abono_creditoC);
	$abono_creditoT = sprintf('%.2f',$abono_creditoT);

	$TOTAL_CONTADO = $vtaefectivo + $vcheque + $vtransferencia;
	$TOTAL_PENDIENTE = $venta_pendiente + $vtatcredito;
	$TOTAL_CONTADO = sprintf('%.2f', $TOTAL_CONTADO);
	$TOTAL_PENDIENTE = sprintf('%.2f', $TOTAL_PENDIENTE);

	$TOTAL_RCREDITO = $abono_creditoE + $abono_creditoC + $abono_creditoT;
	$TOTAL_RR = $total_RE + $total_RC + $total_RT;

	$TOTAL_RCREDITO = sprintf('%.2f', $TOTAL_RCREDITO);
	$TOTAL_RR = sprintf('%.2f', $TOTAL_RR);

	$vales=sprintf('%.2f', $vales);
	$cashfinal= sprintf('%.2f', $cashfinal);

	$esp_init=espacios_izq(" ",0);
	$esp_init0=espacios_izq(" ",1);
	$esp_init1=espacios_izq(" ",12);
	$esp_init2=espacios_izq(" ",20);
	$line1=str_repeat("-",41);
	$info_factura="";
	$tinicio= zfill($tinicio, 7);
	$tfinal= zfill($tfinal, 7);
	$empresa=$row_empresa['nombre_lab'];

	if($tipo=="C"){
		$desc_tipo='CORTE DE CAJA';
	}
	else{
		$desc_tipo=$tipo;
	}
	$info_factura.=$esp_init0.$empresa."\n";
	$giros = explode(";", $giro);
	for ($ni = 0; $ni < (count($giros)); $ni++)
	{
		$info_factura.=$esp_init.trim($giros[$ni])."\n";
	}
	//$info_factura.=$esp_init0."SUCURSAL ".$nombre_sucursal."\n";

	$info_factura.=$esp_init0."RESOLUCION:  ".$resolucion."\n";
	$info_factura.=$esp_init0."DEL ".$desde." AL ".$hasta."\n";
	$info_factura.=$esp_init0."SERIE ".$serie."\n";
	$info_factura.=$esp_init0."FECHA RESOLUCION ".$fehca."\n";
	if($tipo=="X" || $tipo=="Z")
	{
		$info_factura.=$esp_init."TIQUETE # ".$tike."\n";
		$info_factura.=$line1."\n";
	}
	else {
		$info_factura.=$line1."\n";
	}
	$a=str_pad($esp_init."CORTE ".$desc_tipo,38," ",STR_PAD_RIGHT);
	$b=str_pad($esp_init." FECHA: ".$fecha."    HORA:".hora($hora),40," ",STR_PAD_RIGHT);
	$c=str_pad($esp_init."EMPLEADO: ".$nombre_emp,38," ",STR_PAD_RIGHT);
	$w=str_pad($esp_init."CAJA : ".$caja. "  TURNO: ".$turno,38," ",STR_PAD_RIGHT);
	$info_factura.=$a."\n";
	$info_factura.=$b."\n";
	$info_factura.=$c."\n";
	$info_factura.=$w."\n";

	/////TOTALES
	$total_caja_h = $monto_ch + $ingresos - $viaticos - $vales;
	$total_caja_h = sprintf('%.2f', $total_caja_h);
	$total_cajac = $cashini + $recuperacion + $abono_credito + $vtatotales_print;
	$total_cajac = sprintf('%.2f', $total_cajac);
	//$info_factura.="\n";
	if($tipo=="C"){
		$subtotal=$cashini+$vtaefectivo+$ingresos+$monto_ch;
		$totalcaja=$subtotal-$vales;
		$subtotal=sprintf('%.2f', $subtotal);
		$totalcaja=sprintf('%.2f', $totalcaja);
		//$info_factura.=$esp_init1."DESDE:      HASTA:"."\n";

		$n=18;
		$info_factura.="VENTA CONTADO\n";
		$info_factura.=$line1."\n";
		$sp1=len_num($vtaefectivo,24);
		$info_factura.=$esp_init0."EFECTIVO  ".$sp1."$".$vtaefectivo."\n";
		$info_factura.=$line1."\n";
		$sp1=len_num($TOTAL_CONTADO,14);
		$info_factura.=$esp_init." TOTAL VENTA CONTADO ".$sp1."$".$TOTAL_CONTADO."\n\n";




		$info_factura.="CAJA CHICA\n";
		$info_factura.=$line1."\n";
		$sp1=len_num($monto_ch,16);
		$info_factura.=$esp_init0."SALDO CAJA CHICA  ".$sp1."$".$monto_ch."\n";
		$sp1=len_num($ingresos,$n);
		$info_factura.=$esp_init0."(+)INGRESOS     ".$sp1."$".$ingresos."\n";
		$sp1=len_num($viaticos,$n);
		$info_factura.=$esp_init0."(-) VIATICOS    ".$sp1."$".$viaticos."\n";
		$sp1=len_num($vales,$n);
		$info_factura.=$esp_init0."(-) VALES       ".$sp1."$".$vales."\n";
		$info_factura.=$line1;
		$sp1=len_num($total_caja_h,16);
		$info_factura.=$esp_init0." TOTAL CAJA CHICA  ".$sp1."$".$total_caja_h."\n";
		$info_factura.="\n";


		$sp1=len_num($monto_apertura,16);
		$info_factura.=$esp_init0."SALDO DE APERTURA  ".$sp1."$".$monto_apertura."\n";
		$r=str_pad("# ".$n_remesa,23," ",STR_PAD_LEFT);
		$info_factura.=$esp_init0."NUMERO DE REMESA ".$r."\n";
		$sp1=len_num($remesa,21);
		$info_factura.=$esp_init0."TOTAL REMESA  ".$sp1."$".$remesa."\n";



	}

return ($info_factura);


}
function print_viatico($id_movimiento)
{
	$id_sucursal=$_SESSION['id_sucursal'];
	//sucursal
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['descripcion'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	$id_sucursal=$_SESSION['id_sucursal'];
	$empresa=empresa();
	//consulta
	$sql="SELECT  e.id_empleado, e.nombre,
	mc.concepto, mc.valor,mc.fecha,mc.hora,mc.entrada,mc.salida,mc.id_sucursal,
	mc.tipo_delige, mc.viatico,mc.detalle,
	mc.nombre_recibe, mc.nombre_autoriza, mc.nombre_proveedor, mc.iva, mc.tipo_doc, mc.numero_doc, mc.caja, e.nombre as name_em
	FROM mov_caja AS mc
	JOIN empleados AS e ON(e.id_empleado=mc.id_empleado)
	WHERE  mc.id_movimiento='$id_movimiento'";
	$result=_query($sql);
	$nrow = _num_rows($result);
	$row = _fetch_array($result);
	$id_empleado = $row["id_empleado"];
	$concepto = $row["concepto"];
	$nombre = $row["nombre"];
	$nombre_em = $row["name_em"];
	$explora = explode(" ", $nombre_em);
	$cue_em = count($explora);
	if($cue_em <= 2)
	{
		$nombre_em = $explora[0]." ".$explora[1];
	}
	else
	{
		$nombre_em = $explora[0]." ".$explora[2];
	}
	//$nombre ="";
	$hora= $row["hora"];
	$fecha= $row["fecha"];
	$valor= $row["valor"];
	$entrada= $row["entrada"];
	$nombre_recibe= $row["nombre_recibe"];
	$nombre_autoriza= $row["nombre_autoriza"];
	$nombre_proveedor = $row["nombre_proveedor"];
	$tipo_doc = $row["tipo_doc"];
	$detalle = $row["detalle"];
	$numero_doc = $row["numero_doc"];
	$tipo_dilige= $row["tipo_delige"];
	$viatico= $row["viatico"];
	$caja = $row["caja"];

	$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
	$dats_caja = _fetch_array($sql_caja);
	$fehca = ED($dats_caja["fecha"]);
	$resolucion = $dats_caja["resolucion"];
	$serie = $dats_caja["serie"];
	$desde = $dats_caja["desde"];
	$hasta = $dats_caja["hasta"];

	//$id_sucursal=$row["id_sucursal"];
	if($viatico==1){
		$tipo="VIATICO";
	}
	else{
		$tipo="";
	}
	$line1=str_repeat("_",30)."\n";
	$line2=str_repeat("-",40)."\n";
	$line3=str_repeat("-",40);
	$valor= sprintf('%.2f', $valor);
	//Datos
	$col0=1;		$col1=3; 		$col2=3;
	$col3=6;		$col4=5;		$sp1=2;
	$sp_prec=10;
	$sp=espacios_izq(" ",$sp1);
	$sp2=espacios_izq(" ",12);
	$esp_init=espacios_izq(" ",0);
	$esp_init0=espacios_izq(" ",1);
	$esp_precios=espacios_izq(" ",$sp_prec);
	$esp_enc2=espacios_izq(" ",3);
	$esp_init2=espacios_izq(" ",23);
	$info_factura="";
	$info_factura.=$esp_init0.$empresa."\n";
	$giros = explode(";", $giro);
	for ($ni = 0; $ni < (count($giros)); $ni++)
	{
		$info_factura.=$esp_init.trim($giros[$ni])."\n";
	}
	//$info_factura.=$esp_init0."SUCURSAL ".$nombre_sucursal."\n";

	$info_factura.=$esp_init0."RESOLUCION:  ".$resolucion."\n";
	$info_factura.=$esp_init0."DEL ".$desde." AL ".$hasta."\n";
	$info_factura.=$esp_init0."SERIE ".$serie."\n";
	$info_factura.=$esp_init0."FECHA RESOLUCION ".$fehca."\n";
	$info_factura.="COMPROBANTE DE VIATICO # ".$id_movimiento."\n";

	$caracteres = strlen($concepto);
	$lineas = round(($caracteres /40),2,PHP_ROUND_HALF_UP);
	$con = divtextlin($concepto, 40);



	$a=str_pad(("FECHA: ".ED($fecha)."    HORA:".hora($hora)),40," ",STR_PAD_RIGHT);
	$b=str_pad(("EMPLEADO: ".$nombre_em),40," ",STR_PAD_RIGHT);
	$c=str_pad(("DILIGENCIA: ".ucwords($tipo_dilige)),40," ",STR_PAD_RIGHT);
	$info_factura.=$line2."";
	$info_factura.=$a."\n";
	$info_factura.=$b."\n";
	$info_factura.=$c."\n";
	$info_factura.=$line2;
	$e=str_pad("CONCEPTO:",40," ",STR_PAD_RIGHT);
	$info_factura.=$esp_init.$e."\n";

	foreach ($con as $key => $value) {
		$d=str_pad($value,40," ",STR_PAD_RIGHT);
		$info_factura.=$esp_init.$d."\n";
	}
	$info_factura.="\n";
	$valor= sprintf('%.2f', $valor);
	$info_factura.=$esp_init."DETALLES                           VALOR\n";
	$info_factura.=$line3."";

	//$n_lineas=count($lineas_det);
	$sql_detalles = _query("SELECT * FROM mov_caja_detalle WHERE id_mov_caja = '$id_movimiento'");
	$cuenta_detalle = _num_rows($sql_detalles);

	if($cuenta_detalle > 0)
	{
		while ($row_detalle = _fetch_array($sql_detalles))
		{
			$id_md = $row_detalle["id_mcd"];
			$natu_gasto = $row_detalle["natu_gasto"];
			$detalle = $row_detalle["detalle"];
			$valor_de = $row_detalle["valor"];
			$valor_de= sprintf('%.2f', $valor_de);
			$sp1=len_num($valor_de,4);
			$detalle=texto_espacios($detalle,31);
			$a=str_pad($detalle,34," ",STR_PAD_RIGHT);
			$info_factura.=$esp_init."\n";
			$info_factura.=$detalle.$sp1."$".$valor_de;
		}
	}
	$info_factura.="\n".$line2."";
	$info_factura.=$esp_init."TOTAL                             $".$valor."\n";
	$info_factura.="\n\n\n";
	$info_factura.=$esp_init."ENTREGA: ______________________________\n".$nombre."\n\n\n";
	$info_factura.=$esp_init."RECIBE: _______________________________\n".$nombre_recibe."\n\n\n";
	$info_factura.=$esp_init."AUTORIZA: _____________________________\n".$nombre_autoriza."\n";

	return ($info_factura);
}
function print_granZ($mes, $anhio, $caja){
	include_once "_core.php";
	//EMPRESA
	if($mes < 10)
	{
		$mes = "0".$mes;
	}
	else {
		$mes = $mes;
	}
	$month = $anhio.'-'.$mes;
	$aux = date('Y-m-d', strtotime("{$month} + 1 month"));
	$dia = date('Y-m-d', strtotime("{$aux} - 1 day"));
	$fini = $anhio."-".$mes."-01";
	$ffin = $dia;
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['descripcion'];
	$razonsocial=$row_empresa['razonsocial'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	$id_sucursal=$_SESSION['id_sucursal'];



	//sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['descripcion'];
	//consulta
	$sql="SELECT FORMAT(SUM(totalt),4) AS ttiket, FORMAT(SUM(totalf),4) AS tfact, FORMAT(SUM(totalcf), 4) as tcfact FROM controlcaja WHERE fecha_corte BETWEEN '$fini' AND '$ffin' AND caja = '$caja' AND tipo_corte = 'Z'";
	$result=_query($sql);
	$nrow = _num_rows($result);
	$row = _fetch_array($result);

	$sql_caja = _query("SELECT * FROM caja WHERE id_caja='$caja'");
	$dats_caja = _fetch_array($sql_caja);
	$fehca = ED($dats_caja["fecha"]);
	$resolucion = $dats_caja["resolucion"];
	$serie = $dats_caja["serie"];
	$desde = $dats_caja["desde"];
	$hasta = $dats_caja["hasta"];

	$sql_existe = _query("SELECT * FROM corte_z WHERE mes = '$mes' AND anhio = '$anhio' AND caja = '$caja'");
	$existe = _num_rows($sql_existe);
	if($existe == 1)
	{
		$row_existe = _fetch_array($sql_existe);
		$correlativo_dispo = $row_existe["n_tike"];
		$id_cortez = $row_existe["id_cortez"];
	}
	else
	{
		$correlativo_dispo = $dats_caja["correlativo_dispo"]+ 1;
	}


	$ttiket= sprintf('%.2f', $row["ttiket"]);
	$tfact= sprintf('%.2f', $row["tfact"]);
	$tcfact=  sprintf('%.2f', $row["tcfact"]);
	$tike_exe = sprintf('%.2f', "0.00");
	$fact_exe = sprintf('%.2f', "0.00");
	$cf_exe = sprintf('%.2f', "0.00");

	$vtatotales=$ttiket+$tfact+$tcfact;
	$vtatotales_print=sprintf('%.2f', $vtatotales);
	$col0=0;
	$esp_init=espacios_izq(" ",$col0);
	$esp_init0=espacios_izq(" ",1);
	$esp_init1=espacios_izq(" ",12);
	$esp_init2=espacios_izq(" ",20);
	$line1=str_repeat("-",40)."\n";
	$info_factura="";
	//$tinicio= zfill($tinicio, 7);
	//$tfinal= zfill($tfinal, 7);
	$empresa=empresa();


	$info_factura.=$esp_init0.$empresa."\n";
	$giros = explode(";", $giro);
	for ($ni = 0; $ni < (count($giros)); $ni++)
	{
		$info_factura.=$esp_init0.trim($giros[$ni])."\n";
	}
	//$info_factura.=$esp_init0."SUCURSAL ".$nombre_sucursal."\n";

	$info_factura.=$esp_init0."RESOLUCION:  ".$resolucion."\n";
	$info_factura.=$esp_init0."DEL ".$desde." AL ".$hasta."\n";
	$info_factura.=$esp_init0."SERIE ".$serie."\n";
	$info_factura.=$esp_init0."FECHA RESOLUCION ".$fehca."\n";
	$info_factura.=$esp_init0."TOTAL Z MENSUAL\n";
	$info_factura.=$esp_init0."TICKET # ".$correlativo_dispo."\n\n";
	//$info_factura.=$esp_init0."CORTE DE CAJA  : ".$id_corte."|";
	$n=20;
	$sp1=len_num($tike_exe,$n);
	$sp2=len_num($fact_exe,$n);
	$sp3=len_num($cf_exe,$n);
	$sp4=len_num($ttiket,$n);
	$sp5=len_num($tfact,19);
	$sp8=len_num($tfact,20);
	$sp6=len_num($tcfact,10);
	$sp7=len_num($tcfact,20);
	$info_factura.=$esp_init0."CREDITO FISCAL\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL EXENTO: ".$sp3.$cf_exe."\n";
	$info_factura.=$esp_init0."TOTAL GRAVADO:".$sp7.$tcfact."\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL CREDITOS FISCALES:".$sp6.$tcfact."\n\n";

	$info_factura.=$esp_init0."FACTURAS\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL EXENTO: ".$sp2.$fact_exe."\n";
	$info_factura.=$esp_init0."TOTAL GRAVADO:".$sp8.$tfact."\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL FACTURAS:".$sp5.$tfact."\n\n";

	$info_factura.=$esp_init0."TICKETS\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL EXENTO: ".$sp1.$tike_exe."\n";
	$info_factura.=$esp_init0."TOTAL GRAVADO:".$sp4.$ttiket."\n";
	$info_factura.=$line1;
	$info_factura.=$esp_init0."TOTAL TICKETS:".$sp4.$ttiket."\n\n";

	//$info_factura.=$esp_init."FECHA: ".$fecha."    HORA:".hora($hora)."\n";
	//$info_factura.=$esp_init."EMPLEADO: ".$nombre_emp."\n";
	//$info_factura.=$esp_init."CAJA : ".$caja;

	if($existe == 0)
	{
		$tabla = "corte_z";
		$form_data = array(
			'totalte' => $tike_exe,
			'totaltg' => $ttiket,
			'totalt' => $ttiket,
			'totalfe' => $fact_exe,
			'totalfg' => $tfact,
			'totalf' => $tfact,
			'totalcfe' => $cf_exe,
			'totalcfg' => $tcfact,
			'totalcf' => $tcfact,
			'n_tike' => $correlativo_dispo,
			'mes' => $mes,
			'anhio' => $anhio,
			'caja' => $caja,
		);
		$insertar = _insert($tabla, $form_data);
		if($insertar)
		{
			$tab = "caja";
			$f = array('correlativo_dispo' => $correlativo_dispo, );
			$w = "id_caja='".$caja."'";
			$update = _update($tab, $f, $w);
		}
	}
	else
	{
		$tablap = "corte_z";
		$form_datap = array(
			'totalte' => $tike_exe,
			'totaltg' => $ttiket,
			'totalt' => $ttiket,
			'totalfe' => $fact_exe,
			'totalfg' => $tfact,
			'totalf' => $tfact,
			'totalcfe' => $cf_exe,
			'totalcfg' => $tcfact,
			'totalcf' => $tcfact,
		);
		$wp = "id_cortez='".$id_cortez."'";
		$up = _insert($tablap, $form_datap, $wp);
	}
	$info_factura.="\n";

return ($info_factura);


}

function len_num($val,$qty){
	if(strlen($val)<=4)
	$numsp=$qty;
	if(strlen($val)==5)
	$numsp=$qty-1;
	if(strlen($val)==6)
	$numsp=$qty-2;
	if(strlen($val)==7)
	$numsp=$qty-3;
	if(strlen($val)==8)
	$numsp=$qty-4;

	$val_sp=espacios_izq(" ",$numsp);
	return $val_sp;
}


function texto_espacios($texto,$long){
	$countchars=0;
	$countch=0;
	$texto=trim($texto);
	$len_txt=strlen($texto);
	$latinchars = array( 'ñ','á','é', 'í', 'ó','ú','Ñ','Á','É','Í','Ó','Ú','°');
	foreach($latinchars as $value){
		$countchars=substr_count($texto,$value);
		$countch= $countchars+$countch;
	}

	if($len_txt<=$long){
		if($countch>0)
		$n=($long+$countch)-$len_txt;
		else
		$n=$long-$len_txt;

		$texto_repeat=str_repeat(" ",$n);
		$texto_salida=$texto.$texto_repeat;
	}
	else{
		$long=$long-1;
		$texto_salida=substr($texto,0,$long).".";
	}
	return $texto_salida;
}
function espacios_izq($texto,$long){
	$len_txt=strlen($texto);

	if($len_txt<=$long){

		$alinear='STR_PAD_LEFT';
		$texto_salida=str_pad($texto, $long, " ",STR_PAD_LEFT );
	}
	else{
		$texto_salida=substr($texto,0,$long);
	}
	return $texto_salida;
}
function cadenaenlineas( $text, $width = '80', $lines = '10', $break = '\n', $cut = 0 ) {
	$wrappedarr = array();
	$wrappedtext = wordwrap( $text, $width, $break , true );
	$wrappedtext = trim( $wrappedtext );
	$arr = explode( $break, $wrappedtext );
	return $arr;
}
function len_espacios($valor,$col){
	$valor=strlen($valor);
	if($valor==1){
		$sp=$col;
	}
	else{
		$sp=$col-($valor-1);
	}
	return $sp;
}
function datos_empresa(){
	//EMPRESA
	$sql_empresa = "SELECT * FROM sucursal WHERE id_sucursal='".$_SESSION["id_sucursal"]."'";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre_lab'];
	$razonsocial=$row_empresa['razon_social'];
	$giro=$row_empresa['giro'];
	$nit=$row_empresa['nit'];
	$nrc=$row_empresa['nrc'];
	$empresa1=$empresa;
	$razonsocial1=$razonsocial;
	$giro1=$giro;
	$arr_emp= array($empresa1,$razonsocial1,$giro1,$nit,$nrc);
	//json_encode(array(2=>"dos", 10=>"diez"));
	$data = array('empresa' => $empresa1, 'razonsocial' => $razonsocial1, 'giro' => $giro1,'nit' => $nit,'nrc' => $nrc);
	$datos= json_encode($data);
	return $datos;
}
function datos_sucursal($id_sucursal){
	//Sucursal
	$sql_sucursal=_query("SELECT * FROM sucursal WHERE id_sucursal='$id_sucursal'");
	$array_sucursal=_fetch_array($sql_sucursal);
	$nombre_sucursal=$array_sucursal['nombre_lab'];
	$nombre_sucursal1=texto_espacios($nombre_sucursal,30);
	return $nombre_sucursal1;
}
function datos_factura($id_factura){
	//Obtener informacion de tabla Factura
	$sql_fact="SELECT * FROM cobro WHERE id_cobro='$id_factura'";
	$result_fact=_query($sql_fact);
	return $result_fact;
}
function datos_impuesto(){
	//impuestos
	$sql_iva="select iva,monto_retencion1,monto_retencion10,monto_percepcion from monto_impuesto";
	$result_IVA=_query($sql_iva);
	return $result_IVA;

}
function datos_fact_det($id_factura){
	$sql_fact_det="	SELECT  examen.id_examen, examen.nombre_examen,
		detalle_cobro.*
		FROM detalle_cobro
		JOIN examen ON detalle_cobro.id_examen=examen.id_examen
		WHERE  detalle_cobro.id_cobro='$id_factura'";

	$result_fact_det=_query($sql_fact_det);
	return $result_fact_det;
}
function datos_cliente($id_cliente){
	//Obtener informacion de tabla Cliente
	$sql="select * from cliente where id_cliente='$id_cliente'";
	$result= _query($sql);
	return $result;
}
function datos_empleado($id_empleado,$id_vendedor){
	//Obtener informacion de tabla Cliente
	$sql="select * from empleado where id_empleado='$id_empleado'";
	$result= _query($sql);
	$row=_fetch_array($result);
	$empleado=$row['nombre'];

	$sql2="select * from empleado where id_empleado='$id_vendedor'";
	$result2= _query($sql2);
	$row2=_fetch_array($result2);
	$vendedor=$row2['nombre'];
	$empleado_vendedor=	$empleado."|".$vendedor;
	return $empleado_vendedor;
}
function empresa(){
	//Empresa
	$sql_empresa = "SELECT * FROM empresa";
	$result_empresa=_query($sql_empresa);
	$row_empresa=_fetch_array($result_empresa);
	$empresa=$row_empresa['nombre'];

	return $empresa;
}
?>
