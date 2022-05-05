<?php
	include '_core.php';
	$inicio = restar_meses(date("Y-m-d"),4);
	$id_sucursal=$_SESSION["id_sucursal"];
	for($i=0; $i<=4; $i++)
	{
		$a = explode("-",$inicio)[0];
		$m = explode("-",$inicio)[1];
		$ult = cal_days_in_month(CAL_GREGORIAN, $m, $a);
		$ini = "$a-$m-01";
		$fin = "$a-$m-$ult";
		$query = "SELECT COUNT(ep.id_examen) as total FROM examen_paciente as ep
							JOIN cobro as c ON c.id_cobro=ep.id_cobro
							WHERE ep.id_sucursal='$id_sucursal' AND c.fecha BETWEEN '$ini' AND '$fin'";
		$result = _query($query);
		$row = _fetch_array($result);
		$total = $row["total"];
		$data[] = array(
			"total" => $total,
			"mes" => meses($m),
			);
		$inicio = sumar_meses($ini,1);
	}
	echo json_encode($data);
?>
