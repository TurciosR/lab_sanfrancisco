<?php
include_once "_core.php";
function initial()
{
	$_PAGE = array ();
	$title='Editar Producto';
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_producto = $_REQUEST['id_producto'];
	$id_sucursal = $_SESSION["id_sucursal"];

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user, $filename);

	// Producto, si existe
	$sql="SELECT * FROM producto WHERE id_producto='$id_producto' AND id_sucursal='$id_sucursal'";
	$result=_query($sql);
	$row=_fetch_array($result);
	$descripcion=$row['descripcion'];
	$barcode=$row['barcode'];
	$marca=$row['marca'];
	$estado=$row['estado'];
	$exento=$row['exento'];
	$id_proveedor=$row['id_proveedor'];
	$minimo=$row['minimo'];
	$perecedero=$row['perecedero'];
	$id_categoria=$row['id_categoria'];
	$img = $row["imagen"];

	// categoria
	$arrayCat = array();
	$qcategoria=_query("SELECT * FROM categoria_p ORDER BY nombre_cat ASC");
	while($row_cat=_fetch_array($qcategoria))
	{
		$idCat=$row_cat['id_categoria'];
		$description=$row_cat['nombre_cat'];
		$arrayCat[$idCat] = $description;
	}
	//presentacion
	$qpresentacion=_query("SELECT * FROM presentacion");
	while($row_pr=_fetch_array($qpresentacion))
	{
		$idPr=$row_pr['id_presentacion'];
		$description=$row_pr['nombre'];
		$arrayPr[$idPr] = $description;
	}

	$provssa=_query("SELECT * FROM proveedor  WHERE id_sucursal='$id_sucursal' ORDER BY nombre ASC");
	while($row_pr=_fetch_array($provssa))
	{
		$idPr=$row_pr['id_proveedor'];
		$description=$row_pr['nombre'];
		$arrayPro[$idPr] = $description;
	}
	$mes = date("m");
	$anio = date("Y");
	$primer = $anio."-".$mes."-01";
	$actu = date("Y-m-d");
	?>
	<style>
	/* Center the loader */
	.sect
	{
		height: 400px;
	}
	#loader {
		position: absolute;
		left: 50%;
		top: 50%;
		z-index: 1;
		width: 150px;
		height: 150px;
		margin: -75px 0 0 -75px;
		border: 16px solid #f3f3f3;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 120px;
		height: 120px;
		-webkit-animation: spin 2s linear infinite;
		animation: spin 2s linear infinite;
	}

	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}

	/* Add animation to "page content" */
	.animate-bottom {
		position: relative;
		-webkit-animation-name: animatebottom;
		-webkit-animation-duration: 1s;
		animation-name: animatebottom;
		animation-duration: 1s
	}

	@-webkit-keyframes animatebottom {
		from { bottom:-100px; opacity:0 }
		to { bottom:0px; opacity:1 }
	}

	@keyframes animatebottom {
		from{ bottom:-100px; opacity:0 }
		to{ bottom:0; opacity:1 }
	}
</style>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<?php	if ($links!='NOT' || $admin=='1' ){ ?>
						<div class="ibox-title">
							<div class="row">
								<div class="col-lg-12">
									<h5 class="text-navy"><?php echo $title; ?></h5>
									<a class='btn btn-primary pull-right img_bbt'><i class="fa fa-plus"></i> Editar Imagen</a>
								</div>
							</div>
						</div>
						<div class="ibox-content">
							<ul class="nav nav-tabs">
								<li class="active" id="hom"><a data-toggle="tab" href="#home">Editar</a></li>
								<li id="hkardex"><a data-toggle="tab" href="#kardex">Kardex</a></li>
								<li id="hrotacion"><a data-toggle="tab" href="#rotacion">Rotacion</a></li>
							</ul>
							<div class="row">
								<div class="tab-content">
									<div id="home" class="tab-pane fade in active"><br>
										<div class="col-lg-12">
							<form name="formulario" id="formulario" autocomplete="off">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Código de Barra </label>
											<input type="text" placeholder="Digite Código de Barra" class="form-control" id="barcode" name="barcode" value="<?php echo $barcode ?>">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Descripción</label>
											<input type="text" placeholder="Descripcion" class="form-control may" id="descripcion" name="descripcion" value="<?php echo $descripcion?>">
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group has-info single-line">
											<label>Marca</label>
											<input type="text" placeholder="Marca" class="form-control may" id="marca" name="marca" value="<?php echo $marca?>">
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group has-info single-line">
											<label>Stock Minimo</label>
											<input type="text" placeholder="Minimo" class="form-control" id="minimo" name="minimo" value="<?php echo $minimo?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-3">
										<div class="form-group has-info single-line">
											<label>Proveedor &nbsp;</label>
											<?php
											$select=crear_select2("proveedor",$arrayPro,$id_proveedor,"width:100%;");
											echo $select;
											?>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<div class="form-group has-info single-line">
											<label>Categoria &nbsp;</label>
											<?php
											$select=crear_select2("id_categoria",$arrayCat,$id_categoria,"width:100%;");
											echo $select;
											?>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group has-info single-line">
											<label class="control-label">Exento de IVA </label>
											<?php
											if($exento==1)
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='exento' name='exento' value='1' checked> <i></i>  </label></div>";
											}
											else
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='exento' name='exento' value='1'> <i></i>  </label></div>";
											}
											?>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group has-info single-line">
											<label class="control-label">Producto perecedero </label>
											<?php
											if($perecedero==1)
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='perecedero' name='perecedero' value='1' checked> <i></i>  </label></div>";
											}
											else
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='perecedero' name='perecedero' value='1'> <i></i>  </label></div>";
											}
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group has-info single-line">
											<label class="control-label">Activo </label>
											<?php
											if($estado==0)
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='activo' name='activo' value='1' checked> <i></i>  </label></div>";
											}
											else
											{
												echo "<div class='checkbox i-checks'><label> <input type='checkbox'  id='activo' name='activo' value='1'> <i></i>  </label></div>";
											}
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12"><br>
										<div class="alert alert-warning text-center"><h3>Presentaciones</h3></div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<label>Presentación</label>
											<?php
											$select=crear_select2("id_presentacion",$arrayPr,"","width:100%;");
											echo $select;
											?>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<label>Descripción</label>
											<input type="text" name="desc_pre" id="desc_pre" class="form-control clear may">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<label>Unidades</label>
											<input type="text" name="unidad_pre" id="unidad_pre" class="form-control clear">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<label>Costo</label>
											<input type="text" name="costo_pre" id="costo_pre" class="form-control clear">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<label>Precio</label>
											<input type="text" name="precio_pre" id="precio_pre" class="form-control clear">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group has-info">
											<br>
											<a  class="btn btn-primary pull-right" id="add_pre"><i class="fa fa-plus"></i> Agregar</a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-hover table-bordered">
											<thead>
												<tr>
													<th class="col-md-2">Presentación</th>
													<th class="col-sm-3">Descripción</th>
													<th class="col-md-2">Unidad</th>
													<th class="col-md-2">Costo</th>
													<th class="col-md-2">Precio</th>
													<th class="col-md-1">Acción</th>
												</tr>
											</thead>
											<tbody id="presentacion_table">
												<?php
												$sql_p = _query("SELECT * FROM presentacion_producto WHERE id_producto = '$id_producto' AND id_sucursal=$_SESSION[id_sucursal]");
												$n = 0;
												while ($row_p = _fetch_array($sql_p))
												{
													$id_presentacion_pro = $row_p["id_presentacion"];
													$pres = $row_p["presentacion"];
													$sql_present1 = _query("SELECT * FROM presentacion WHERE id_presentacion = '$pres' ");
													$pr = _fetch_array($sql_present1);
													$descrip_pr = $pr["nombre"];
													$des = $row_p["descripcion"];
													$uni = $row_p["unidad"];
													$pre = $row_p["precio"];
													$costo = $row_p["costo"];
													$activo = $row_p["activo"];
													if($activo)
													{
														echo "<tr class='exis'>";
													}
													else
													{
														echo "<tr class='exis' style='background: #fcf8e3;'>";
													}
													echo "<td><input type='hidden' class='id_pres_prod' value='".$id_presentacion_pro."'><input type='hidden' class='presentacion' value='".$pres."'>".$descrip_pr."</td>";
													echo "<td class='descripcion_p'>".$des."</td>";
													echo "<td class='unidad_p'>".$uni."</td>";
													echo "<td class='costo ed'>".$costo."</td>";
													echo "<td class='precio_p ed2'>".$pre."</td>";
													if($activo)
													{
														echo "<td class='text-center'><a class='deactive' id='".$id_presentacion_pro."'><i class='fa fa-times iconsa'></i></a></td>";
													}
													else
													{
														echo "<td class='text-center'><a class='activate' id='".$id_presentacion_pro."'><i class='fa fa-check iconsa'></i></a></td>";
													}
													$n++;
												}

												?>
											</tbody>
										</table>
									</div>
								</div>
								<input type="hidden" name="process" id="process" value="edited">
								<input type='hidden' name='urlprocess' id='urlprocess'value="<?php echo $filename;?>">
								<input type="hidden" name="id_producto" id="id_producto" value="<?php echo $id_producto; ?> ">
								<div class="row">
									<div class="col-lg-12">
										<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary pull-right m-t-n-xs"/>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!--
					<div id="kardex" class="tab-pane fade"><br><br>
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-3">
									<div class="form-group has-info">
										<label>Fecha Inicio</label>
										<input type="text" name="fini" id="fini" class="form-control datepick" value="<?php echo $primer; ?>">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group has-info">
										<label>Fecha Fin</label>
										<input type="text" name="fin" id="fin" class="form-control datepick" value="<?php echo $actu; ?>">
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group has-info"><br>
										<button type="button" class="btn btn-primary" id="skardex"><i class="fa fa-search"></i> Buscar</button>
										<button type="button" class="btn btn-primary pull-right" id="pkardex"><i class="fa fa-print"></i> Imprimir</button>
									</div>
								</div>
							</div>
							<div class="row" id="res" hidden><br>
								<div class="col-lg-12 pre-scrollable">
									<table class="table table-bordered">
										<thead>
											<tr>
												<td rowspan="2" style="vertical-align: middle;" class="text-center">Fecha</td>
												<td rowspan="2" style="vertical-align: middle;" class="text-center">Tipo Doc.</td>
												<td rowspan="2" style="vertical-align: middle;" class="text-center">Numero Doc.</td>
												<td colspan="3" class="text-center">Entrada</td>
												<td colspan="3" class="text-center">Salida</td>
												<td colspan="3" class="text-center">Saldo</td>
												<td rowspan="2" style="vertical-align: middle;" class="text-center">Proveedor</td>
											</tr>
											<tr>
												<td>Cantidad</td>
												<td>Costo</td>
												<td>Subtotal</td>
												<td>Cantidad</td>
												<td>Costo</td>
												<td>Subtotal</td>
												<td>Cantidad</td>
												<td>Costo</td>
												<td>Subtotal</td>
											</tr>
										</thead>
										<tbody id="resultado">

										</tbody>
									</table>
								</div>
							</div>
							<div class="row" id="no-data" hidden><br>
								<div class="col-lg-12">
									<div class="alert alert-warning">
										No se encontraron resultados que coincidan con los criterios de busqueda
									</div>
								</div>
							</div>
							<div class="row" style="display: none;" id="divh">
								<div class="col-lg-12">
									<div class="ibox float-e-margins">
										<div class="ibox-content">
											<section class="sect">
												<div id="loader">
												</div>
											</section>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="rotacion" class="tab-pane fade"><br>
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-3">
									<div class="form-group has-info">
										<label>Fecha Inicio</label>
										<input type="text" name="fini1" id="fini1" class="form-control datepick" value="<?php echo $primer; ?>">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group has-info">
										<label>Fecha Fin</label>
										<input type="text" name="fin1" id="fin1" class="form-control datepick" value="<?php echo $actu; ?>">
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group has-info"><br>
										<button type="button" class="btn btn-primary pull-right" id="srotacion"><i class="fa fa-search"></i> Buscar</button>
									</div>
								</div>
							</div>
							<div class="row" id="res1">
								<div class="col-lg-6">
									<h3 class="text-success text-center">Compras</h3>
									<div class=" pre-scrollable">
										<table class="table table-bordered">
											<thead>
												<tr>
													<td class="text-center">Fecha</td>
													<td class="text-center">Proveedor</td>
													<td class="text-center">Tipo Doc.</td>
													<td class="text-center">Num. Doc.</td>
													<td class="text-center">Cant.</td>
													<td class="text-center">Costo</td>
													<td class="text-center">Subtotal</td>
												</tr>
											</thead>
											<tbody id="resultado1">

											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-6">
									<h3 class="text-success text-center">Ventas</h3>
									<div class=" pre-scrollable">
										<table class="table table-bordered">
											<thead>
												<tr>
													<td class="text-center">Fecha</td>
													<td class="text-center">Tipo Doc.</td>
													<td class="text-center">Num. Doc.</td>
													<td class="text-center">Cant.</td>
													<td class="text-center">Precio</td>
													<td class="text-center">Desc.</td>
													<td class="text-center">Subtotal</td>
												</tr>
											</thead>
											<tbody id="resultado2">

											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row" id="no-data1" hidden><br>
								<div class="col-lg-12">
									<div class="alert alert-warning">
										No se encontraron resultados que coincidan con los criterios de busqueda
									</div>
								</div>
							</div>
							<div class="row" style="display: none;" id="divh1">
								<div class="col-lg-12">
									<div class="ibox float-e-margins">
										<div class="ibox-content">
											<section class="sect">
												<div id="loader">
												</div>
											</section>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					</div>
					</div>
							<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class="modal-header">
											<button type="button" class="close" id='cerrar_ven' data-dismiss="modal"
											aria-hidden="true">&times;</button>
											<h4 class="modal-title">Agregar Imagen de Producto</h4>
										</div>
										<div class="modal-body">
											<div class="wrapper wrapper-content  animated fadeInRight">
															<form name="formulario_pro" id="formulario_pro" enctype='multipart/form-data' method="POST">
																<div class="row">
																	<div class="col-md-12">
																		<div class="form-group has-info single-line">
																				<label>Producto</label>
																				<input type="file" name="logo" id="logo" class="file" data-preview-file-type="image">
																				<input type="hidden" name="id_id_p" id="id_id_p">
																				<input type="hidden" name="process" id="process" value="insert_img">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-1">

																	</div>
																	<div class="col-md-10">
	                                  <div class="form-group has-info">
																			<?php
																			 	if ($img != "")
																				{
																			?>
																				<img id="logo_view" src="<?php echo $img;?>" style='width: 400px; height: 300px;'>
																			<?php
																			 	}
																				else
																				{
																						echo "<label style='text-align: center'>Este producto no contiene imagen</label>";
																				}
																			?>

	                                  </div>
	                                </div>
																	<div class="col-md-1">

																	</div>
																</div>
															</form>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-primary" id="btnGimg">Guardar</button>
												<button type="button" class="btn btn-default bb" data-dismiss="modal">Cerrar</button>
											</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							<!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
						</div><!--div class='ibox-content'-->
					</div><!--<div class='ibox float-e-margins' -->
					</div> <!--div class='col-lg-12'-->
				</div> <!--div class='row'-->
			</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
			<?php
			include 'footer.php';
			echo "<script src='js/funciones/funciones_producto.js'></script>";
		} //permiso del script
		else {
			echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
			include_once ("footer.php");
		}
	}
	function kardex()
	{
		$fini = $_POST["fini"];
		$fin = $_POST["fin"];
		$id_sucursal = $_SESSION["id_sucursal"];
		$id_producto = $_POST["id_producto"];
		$table = "";
		$sql = _query("SELECT * FROM movimiento_producto_detalle as md, movimiento_producto as m
            WHERE md.id_movimiento=m.id_movimiento
            AND m.id_sucursal='$id_sucursal'
            AND md.id_producto='$id_producto'
            AND m.tipo!='ASIGNACION'
            AND m.tipo!='TRANSFERENCIA'
            AND m.anulado=0
            AND CAST(m.fecha AS DATE) BETWEEN '$fini' AND '$fin' ORDER BY md.id_detalle ASC");
		if(_num_rows($sql)>0)
		{
			$entrada = 0;
			$salida = 0;
			$i = 1;
			$init = 1;
			while ($row = _fetch_array($sql))
			{
				$fechadoc = ED($row["fecha"]);
				if($row["tipo"] == "ENTRADA" || $row["proceso"] =="TRR")
				{
					$csal = -1;
					$centr = $row["cantidad"];
					$entrada += $centr;
				}
				else if($row["tipo"] == "SALIDA" || $row["proceso"] =="TRE")
				{
					$centr = -1;
					$csal = $row["cantidad"];
					$salida += $csal;
				}
				$id_compra = $row["id_compra"];
				$id_factura = $row["id_factura"];
				if($id_factura > 0)
				{
					$sql_comp = _query("SELECT tipo_documento, num_fact_impresa FROM factura WHERE id_factura='$id_factura'");
					$dats_comp = _fetch_array($sql_comp);
					$alias_tipodoc = $dats_comp["tipo_documento"];
					$numero_doc = $dats_comp["num_fact_impresa"];
				}
				if($id_compra > 0)
				{
					$sql_comp = _query("SELECT alias_tipodoc, numero_doc FROM compra WHERE id_compra='$id_compra'");
					$dats_comp = _fetch_array($sql_comp);
					$alias_tipodoc = $dats_comp["alias_tipodoc"];
					$numero_doc = $dats_comp["numero_doc"];
				}
				if($id_compra == 0 && $id_factura == 0)
				{
					$alias_tipodoc = "";
					$numero_doc = "";
				}
				$ultcosto = $row["costo"];
				$stock_actual = $row["stock_actual"];
				$stock_anterior = $row["stock_anterior"];
				$id_proveedor = $row["id_proveedor"];
				if($init)
				{
					if($stock_anterior > 0)
					{
						$table.="<tr><td colspan='8' class='text-center'>INVENTARIO INICIAL<td>";
						$table.="<td>".$stock_anterior."</td>";
						$table.="<td>".$ultcosto."</td>";
						$table.="<td>".number_format(($stock_anterior * $ultcosto), 2)."</td>";
					}
					$init=0;
				}
				$table.="<tr><td>".$fechadoc."</td>";
				$table.="<td>".$alias_tipodoc."</td>";
				$table.="<td>".$numero_doc."</td>";
				if($centr >= 0)
				{
						$table.="<td>".$centr."</td>";
						$table.="<td>".$ultcosto."</td>";
						$table.="<td>".number_format(($centr * $ultcosto), 2)."</td>";
				}
				else
				{
						$table.="<td>".""."</td>";
						$table.="<td>".""."</td>";
						$table.="<td>".""."</td>";
				}
				if($csal >= 0)
				{
						$table.="<td>".$csal."</td>";
						$table.="<td>".$ultcosto."</td>";
						$table.="<td>".number_format(($csal * $ultcosto), 2)."</td>";
				}
				else
				{
						$table.="<td>".""."</td>";
						$table.="<td>".""."</td>";
						$table.="<td>".""."</td>";
				}
				$table.="<td>".$stock_actual."</td>";
				$table.="<td>".$ultcosto."</td>";
				$table.="<td>".number_format(($stock_actual * $ultcosto), 2)."</td>";
				if($id_proveedor>0)
				{
						$sql2 = _query("SELECT nombre FROM proveedor WHERE id_proveedor='".$id_proveedor."'");
						$datos2 = _fetch_array($sql2);
						$nombr = utf8_decode($datos2["nombre"]);
								$table.="<td>".$nombr."</td></tr>";
				}
				else
				{
						$table.="<td>".""."</td></tr>";
				}
			}
			$table.="<tr>
			<td></td>
			<td colspan='2'><b>Total Entrada</b></td>
			<td>".$entrada."</td>
			<td colspan='2'><b>Total Salida</b></td>
			<td>".$salida."</td>
			<td colspan='6'></td>
			</tr>";
			$xdatos["typeinfo"] = "Success";
			$xdatos["table"] = $table;
		}
		else
		{
			$xdatos["typeinfo"] = "Error";
		}
		echo json_encode($xdatos);
	}
	function rotacion()
	{
		$fini = $_POST["fini"];
		$fin = $_POST["fin"];
		$id_sucursal = $_SESSION["id_sucursal"];
		$id_producto = $_POST["id_producto"];
		$table1 = "";
		$table2 = "";
		$sql1 = _query("SELECT dc.cantidad, dc.ultcosto, dc.subtotal, c.fechadoc, c.numero_doc, c.alias_tipodoc, c.id_proveedor FROM compras as c, detalle_compras as dc WHERE dc.id_compras=c.id_compras AND dc.id_producto='$id_producto' AND c.id_sucursal='$id_sucursal' AND CAST(c.fechadoc AS DATE) BETWEEN '$fini' AND '$fin' ORDER BY CAST(c.fechadoc as DATE) ASC");
		if(_num_rows($sql1)>0)
		{
			while ($row1 = _fetch_array($sql1))
			{
				$sqlp = _query("SELECT nombre FROM proveedores WHERE id_proveedor='".$row1["id_proveedor"]."'");
				$datosp = _fetch_array($sqlp);
				$nombr = $datosp["nombre"];
				$table1.="<tr>
				<td>".ED($row1["fechadoc"])."</td>
				<td>".$nombr."</td>
				<td>".$row1["alias_tipodoc"]."</td>
				<td>".$row1["numero_doc"]."</td>
				<td>".$row1["cantidad"]."</td>
				<td>".$row1["ultcosto"]."</td>
				<td>".round($row1["cantidad"] * $row1["ultcosto"], 2)."</td>
				</tr>";
			}
		}
		$sql2 = _query("SELECT dc.cantidad, dc.precio, dc.descuento, dc.gravado as subtotal, c.fecha_doc as fechadoc, c.numero_doc, c.alias_tipodoc FROM factura as c, detalle_factura as dc WHERE dc.idtransace=c.idtransace AND dc.id_producto='$id_producto' AND c.alias_tipodoc!='DEV' AND c.id_sucursal='$id_sucursal' AND CAST(c.fecha_doc AS DATE) BETWEEN '$fini' AND '$fin' ORDER BY CAST(c.fecha_doc as DATE) ASC");
		if(_num_rows($sql2)>0)
		{
			while ($row2 = _fetch_array($sql2))
			{
				$table2.="<tr>
				<td>".ED($row2["fechadoc"])."</td>
				<td>".$row2["alias_tipodoc"]."</td>
				<td>".$row2["numero_doc"]."</td>
				<td>".$row2["cantidad"]."</td>
				<td>".$row2["precio"]."</td>
				<td>".$row2["descuento"]."</td>
				<td>".round(($row2["cantidad"] * $row2["precio"]) - $row2["descuento"], 2)."</td>
				</tr>";
			}
		}
		if($table1 !="" || $table2 !="")
		{
			$xdatos["typeinfo"] = "Success";
			$xdatos["table1"] = $table1;
			$xdatos["table2"] = $table2;
		}
		else
		{
			$xdatos["typeinfo"] = "Error";
		}
		echo json_encode($xdatos);
	}
	function editar1()
	{
		$id_sucursal=$_SESSION["id_sucursal"];
		$id_producto=$_POST['id_producto'];
		$descripcion=$_POST['descripcion'];
		$barcode=$_POST['barcode'];
		$marca=$_POST['marca'];
		$minimo=$_POST['minimo'];
		$exento=$_POST['exento'];
		$estado=$_POST['estado'];
		$perecedero=$_POST['perecedero'];
		$id_categoria=$_POST['id_categoria'];
		$proveedor=$_POST['proveedor'];
		$lista = $_POST["lista"];
		$cuantos = $_POST["cuantos"];
		if ($estado == 1)
		{
			$estado_entra = 0;
		}
		else
		{
			$estado_entra = 1;
		}

		$descripcion=trim($descripcion);
		$descripcion=strtoupper($descripcion);
		$barcode=trim($barcode);
		$name_producto="";

		$sql_result=_query("SELECT id_producto,descripcion,barcode FROM producto WHERE descripcion='$descripcion' AND id_producto!='$id_producto' AND id_sucursal='$id_sucursal'");
		$numrows=_num_rows($sql_result);
		$row_update=_fetch_array($sql_result);
		$id_update=$row_update["id_producto"];
		$name_producto=trim($row_update["descripcion"]);

		$descrip_producto_existe=false;

		if($name_producto!="" && $descripcion!="" )
		{
			$descrip_producto_existe=true;
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Registro no insertado, Descripción de Producto ya existe! ';
			$xdatos['process']='noinsert';
		}
		if ($barcode=="")
		$barcodeexiste=false;
		if ($barcode!="")
		{
			$sql_barcode="SELECT id_producto,descripcion,barcode FROM producto WHERE barcode='$barcode'  AND id_producto!='$id_producto' AND id_sucursal='$id_sucursal'";
			$sql_result_barcode=_query($sql_barcode);
			$numrows_barcode=_num_rows($sql_result_barcode);
			if($numrows_barcode>0)
			{
				$xdatos['typeinfo']='Error';
				$xdatos['msg']='El Barcode ya está asignado a otro producto!';
				$xdatos['process']='existbarcode';
				$barcodeexiste=true;
			}
			else
			{
				$barcodeexiste=false;
			}
		}
		_begin();
		$table = 'producto';
		$form_data = array (
			'descripcion' => $descripcion,
			'barcode' => $barcode,
			'marca' => $marca,
			'minimo' => $minimo,
			'id_proveedor' => $proveedor,
			'estado' => $estado_entra,
			'exento' => $exento,
			'id_categoria' => $id_categoria,
			'perecedero' => $perecedero,
			'id_sucursal' => $id_sucursal,
		);
		$where_clause = "id_producto='" . $id_producto . "'";
		if(!$descrip_producto_existe)
		{
			if(!$barcodeexiste)
			{
				$updates = _update ( $table, $form_data, $where_clause );
				if($updates)
				{
					$explora = explode("|", $lista);
					$c = count($explora);
					$n=0;
					for ($i=0; $i < $c-1 ; $i++)
					{
						$ex = explode(",", $explora[$i]);
						$id_presen = $ex[0];
						$des = $ex[1];
						$uni = $ex[2];
						$pre = $ex[3];
						$id_x = $ex[4];
						$costo = $ex[5];
						$tabla_p = "presentacion_producto";
						if($id_x == 0)
						{
							/*insertando presentacion en todas las sucursales*/
							$sql_suc=_query("SELECT id_sucursal FROM sucursal");
							$a=_num_rows($sql_suc);
							while($row_su=_fetch_array($sql_suc))
							{
								$tabla_p = "presentacion_producto";
								$form_pre = array(
									'id_producto' => $id_producto,
									'presentacion' => $id_presen,
									'descripcion' => $des,
									'unidad' => $uni,
									'precio' => $pre,
									'costo' => $costo,
									'activo' => 1,
									'id_sucursal'=>$row_su['id_sucursal']
								);
								$presentax = _insert($tabla_p, $form_pre);
							}
						}
						else
						{
							$form_pre = array(
								'id_producto' => $id_producto,
								'presentacion' => $id_presen,
								'descripcion' => $des,
								'unidad' => $uni,
								'precio' => $pre,
								'costo' => $costo,
								'id_sucursal' => $id_sucursal,
							);
							$where_p = "id_presentacion='".$id_x."'";
							$presentax = _update($tabla_p, $form_pre, $where_p);

							/*ver si hay equivalentesno creados*/
							$sql_suc=_query("SELECT id_sucursal FROM sucursal WHERE id_sucursal!=$id_sucursal");
							$a=_num_rows($sql_suc);
							while($row_su=_fetch_array($sql_suc))
							{
								$sql_pre=_query("SELECT * FROM presentacion_producto WHERE id_sucursal=$row_su[id_sucursal] AND id_producto=$id_producto AND presentacion=$id_presen");
								$b=_num_rows($sql_pre);
								if ($b==0) {
									# code...
									$tabla_p = "presentacion_producto";
									$form_pre = array(
										'id_producto' => $id_producto,
										'presentacion' => $id_presen,
										'descripcion' => $des,
										'unidad' => $uni,
										'precio' => $pre,
										'costo' => $costo,
										'activo' => 1,
										'id_sucursal'=>$row_su['id_sucursal']
									);
									$presentax = _insert($tabla_p, $form_pre);
								}
							}
						}
						if($presentax)
						{
							$n++;
						}
					}
					if($n == $c-1)
					{
						_commit();
						$xdatos["typeinfo"] = "Success";
						$xdatos["msg"] = "Producto editado correctamente";
						$xdatos["id_producto"] = $id_producto;
					}
				}
				else
				{
					_rollback();
					$xdatos["typeinfo"] = "Error";
					$xdatos["msg"] = "El Barcode ya fue registrado en otro producto";
				}
			}
			else
			{
				_rollback();
				$xdatos["typeinfo"] = "Error";
				$xdatos["msg"] = "Producto no pudo ser actualizado";
			}
		}
		else
		{
			_rollback();
			$xdatos["typeinfo"] = "Error";
			$xdatos["msg"] = "La descripcion del producto ya fue registrada en otro producto";
		}
		echo json_encode($xdatos);
	}
	function deactive()
	{

		$id_sucursal = $_SESSION["id_sucursal"];
		$id_pres = $_POST["id_pres"];
		$table ="presentacion_producto";
		$form_data = array(
			'activo' => 0,
		);
		$where ="id_presentacion='".$id_pres."' AND id_sucursal='".$id_sucursal."'";
		$del = _update($table, $form_data, $where);
		if($del)
		{
			$xdatos["typeinfo"] = "Success";
		}
		else
		{
			$xdatos["typeinfo"] = "Error";
		}
		echo json_encode($xdatos);
	}
	function active()
	{
		$id_sucursal = $_SESSION["id_sucursal"];
		$id_pres = $_POST["id_pres"];
		$table ="presentacion_producto";
		$form_data = array(
			'activo' => 1,
		);
		$where ="id_presentacion='".$id_pres."' AND id_sucursal='".$id_sucursal."'";
		$del = _update($table, $form_data, $where);
		if($del)
		{
			$xdatos["typeinfo"] = "Success";
		}
		else
		{
			$xdatos["typeinfo"] = "Error";
		}
		echo json_encode($xdatos);
	}

	function insert_img()
	{
			require_once 'class.upload.php';
			$id_producto = $_POST["id_id_p"];
			if ($_FILES["logo"]["name"]!="")
			{
			$foo = new Upload($_FILES['logo'],'es_ES');
			if ($foo->uploaded) {
					$pref = uniqid()."_";
					$foo->file_force_extension = false;
					$foo->no_script = false;
					$foo->file_name_body_pre = $pref;
				 // save uploaded image with no changes
				 $foo->Process('img/productos/');
				 if ($foo->processed)
				 {
					 $query = _query("SELECT imagen FROM producto WHERE id_producto='$id_producto'");
					 $result = _fetch_array($query);
					 $urlb=$result["imagen"];
					 if($urlb!="")
					 {
							 unlink($urlb);
					 }
					$cuerpo=quitar_tildes($foo->file_src_name_body);
					$cuerpo=trim($cuerpo);
					$url = 'img/productos/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
					$table = 'producto';
					$form_data = array (
					'imagen' => $url,
					);
					$where_clause = "id_producto='".$id_producto."'";
					$editar =_update($table, $form_data, $where_clause);
					if($editar)
					{
						 $xdatos['typeinfo']='Success';
						 $xdatos['msg']='Datos guardados correctamente !';
						 $xdatos['process']='edit';
					}
					else
					{
						 $xdatos['typeinfo']='Error';
						 $xdatos['msg']='Error al guardar los dartos!'._error();
					}
				 }
				 else
				 {
						$xdatos['typeinfo']='Error';
						$xdatos['msg']='Error al guardar la imagen!';
				 }
			}
			else
			{
					$xdatos['typeinfo']='Error';
					$xdatos['msg']='Error al subir la imagen!';
			}
			}
			else
			{
				 $xdatos['typeinfo']='Success';
				 $xdatos['msg']='Datos guardados correctamente !';
				 $xdatos['process']='edit';
			}
			echo json_encode($xdatos);
	}
	if (!isset($_POST['process'])) {
		initial();
	} else {
		if (isset($_POST['process'])) {
			switch ($_POST['process'])
			{
				case 'edited':
				editar1();
				break;
				case 'deactive':
				deactive();
				break;
				case 'active':
				active();
				break;
				case 'kardex':
				kardex();
				break;
				case 'rotacion':
				rotacion();
				break;
			}
		}
	}
	?>
