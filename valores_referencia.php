<?php
include ("_core.php");
function initial(){

    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "valores_referencia.php";
	$valores = $_REQUEST["valores"];
	$row = $_REQUEST["row"];
	$col = $_REQUEST["col"];
	$links=permission_usr($id_user,$filename);

?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Valores de referencia</h4>
</div>
<div class="modal-body">


	<div class="wrapper wrapper-content  animated fadeInUp">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if (true){
			?>
			<div class="col-lg-12">
				<table class="table table-bordered table-hover" id="tabla1">
			 <thead>
				 <tr>
				 <th class="col-lg-3">SEXO</th>
				 <th class="col-lg-2">EDAD INICIO</th>
				 <th class="col-lg-2">EDAD FINAL</th>
				 <th class="col-lg-3">VALORES DE REFERENCIA</th>
				 <th class="col-lg-2">ACCION</th>
				 </tr>
			 </thead>
			 <tbody id="ref">
				 <?php
           if($valores=="")
					 {
				  ?>
					 <tr  style='height:35px;'>
						 <td class='sel sexo'></td>
						 <td class='nm edad_inicio'></td>
						 <td class='nm edad_fin'></td>
						 <td class='tex valores fin'></td>
						<td class='text-center'><a class=' lndelete' type='button' name='button'> <span class='fa fa-trash'></span> </a></td>
					</tr>
					<?php
					}
					else
					{
						$campos = explode(";", $valores);
						$ncampos = count($campos);
						for($i=0; $i<($ncampos-1); $i++)
						{
							list($campo1, $campo2, $campo3,$campo4) = explode(":",$campos[$i], 4);
							?>
							<tr  style='height:35px;'>
	 						 <td class='sel sexo'><?php echo $campo1; ?></td>
	 						 <td class='nm edad_inicio'><?php echo $campo2; ?></td>
	 						 <td class='nm edad_fin'><?php echo $campo3; ?></td>
	 						 <td class='tex valores fin'><?php echo utf8_encode($campo4); ?></td>
	 						<td class='text-center'><a class=' lndelete' type='button' name='button'> <span class='fa fa-trash'></span> </a></td>
	 					</tr>

							<?php


						}

					}
				 ?>

			 </tbody>
				</table>
			</div>
		</div>
			<?php
			echo "<input type='hidden' nombre='row' id='row' value='$row'>";
			echo "<input type='hidden' nombre='col' id='col' value='$col'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<input type="submit" id="valores" name="valores" value="Guardar" class="btn btn-primary" />
	<button type="button"id="cerrar" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function ver()
{
	$id_producto = $_POST ['id_producto'];
	if (isset($id_producto)) {
		$xdatos ['typeinfo'] = 'Success';
		} else {
		$xdatos ['typeinfo'] = 'Error';
		}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formVer' :
				initial();
				break;
			case 'ver' :
				ver();
				break;
		}
	}
}

?>
