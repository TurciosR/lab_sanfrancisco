<?php
	include '_core.php';
	$inicio = restar_meses(date("Y-m-d"),4);
	for($i=0; $i<=4; $i++)
	{
		$a = explode("-",$inicio)[0];
		$m = explode("-",$inicio)[1];
		$ult = cal_days_in_month(CAL_GREGORIAN, $m, $a);
		$ini = "$a-$m-01";
		$fin = "$a-$m-$ult";

		$result1 = _query("SELECT sum(total) as total, MONTH(fecha) as mes FROM egreso WHERE fecha BETWEEN '$ini' AND '$fin'");
		$row1 = _fetch_array($result1);
		
		$result = _query("SELECT sum(total) as total, MONTH(fecha) as mes FROM factura WHERE fecha BETWEEN '$ini' AND '$fin'");
		$row = _fetch_array($result);

		$total = $row["total"];
		$totale = $row1["total"];
		if($total =="")
		{
			$total = 0;
		}
		if($totale =="")
		{
			$totale = 0;
		}
		$data[] = array(
			"total" => $total, 
			"totale" => $totale, 
			"mes" => meses($m), 
			);
		$inicio = sumar_meses($ini,1);
	}
	echo json_encode($data);
?>