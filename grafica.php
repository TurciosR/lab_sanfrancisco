<?php
	include '_core.php';
		$actual = date("Y-m-d");
		$nuevafecha = strtotime ( '-1 year' , strtotime ( $actual ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		$id_sucursal=$_SESSION["id_sucursal"];
		$sql = _query("SELECT COUNT(ep.id_examen) as numero, e.nombre_examen FROM examen_paciente as ep INNER JOIN examen as e ON(ep.id_examen=e.id_examen)
		WHERE ep.fecha_realizado BETWEEN '$nuevafecha' AND '$actual' AND
		ep.id_sucursal='$id_sucursal' and e.id_sucursal='$id_sucursal' and ep.estado_realizado='Hecho' GROUP BY ep.id_examen ORDER BY numero DESC LIMIT 5");

		while($row = _fetch_array($sql)){
			$num=$row["numero"];
			$nom=$row["nombre_examen"];
			if($num!="" &&$nom!="")
			{
				$num1=$num;
				$nom1=$nom;
			}else {
				$num1=0;
				$nom1="No hay";
			}
		$data[] = array(
			"total" => $num1,
			"nombre" => $nom1,
	//		"mes" => meses($m),
			);
		}
		if (_num_rows($sql)==0) {
			// code...
			$data[] = array(
				"total" => 0,
				"nombre" => "Vacio",
		//		"mes" => meses($m),
				);
		}

		//$inicio = sumar_meses($ini,1);
	//}
	echo json_encode($data);
?>
