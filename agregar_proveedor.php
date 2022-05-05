<?php
include_once "_core.php";
function initial()
{
	$title = 'Agregar Proveedor';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	?>
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-lg-2">
		</div>
	</div>
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<?php
					//permiso del script
					if ($links!='NOT' || $admin=='1' ){
						?>
						<div class="ibox-title">
							<h3 class="text-navy"><b><i class="fa fa-plus-circle fa-1x"></i> <?php echo $title;?></b></h3>
						</div>
						<div class="ibox-content">
							<form name="formulario" id="formulario">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Nombre Comercial <span style="color:red;">*</span></label>
											<input type="text" placeholder="Nombre Legal" class="form-control may" id="nombre_proveedor" name="nombre_proveedor">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group has-info single-line">
											<label>Nombre Legal <span style="color:red;">*</span></label>
											<input type="text" placeholder="Nombre comercial" class="form-control may" id="nombre_legal" name="nombre_legal">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Dirección</label>
											<input type="text" placeholder="Dirección" class="form-control may" id="direccion" name="direccion">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Departamento <span style="color:red;">*</span></label>
											<select class="col-md-12 select may" id="departamento" name="departamento">
												<option value="">Seleccione un departamento</option>
												<?php
												$sqld = "SELECT * FROM departamento";
												$resultd=_query($sqld);
												while($depto = _fetch_array($resultd))
												{
													echo "<option value='".$depto["id_departamento"]."'";

													echo">".$depto["nombre_departamento"]."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Municipio <span style="color:red;">*</span></label>
											<select class="col-md-12 select may" id="municipio" name="municipio">
												<option value="">Primero seleccione un departamento</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>DUI</label>
											<input type="text" placeholder="00000000-0" class="form-control" id="dui" name="dui">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Categoria del Proveedor <span style="color:red;">*</span></label>
											<select class="col-md-12 select " id="categoria_proveedor" name="categoria_proveedor">
												<?php
												$sqld = "SELECT * FROM categoria_proveedor";
												$resultd=_query($sqld);
												while($depto = _fetch_array($resultd))
												{
													echo "<option value='".$depto["id_categoria"]."'";

													echo">".$depto["nombre"]."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>NIT  <span style="color:red;">*</span></label>
											<input type="text" placeholder="0000-000000-000-0" class="form-control" id="nit" name="nit">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>NRC  <span style="color:red;">*</span></label>
											<input type="text" placeholder="Registro" class="form-control" id="nrc" name="nrc">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Giro  <span style="color:red;">*</span></label>
											<input type="text" placeholder="Giro del negocio" class="form-control may" id="giro" name="giro">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Nombre del Contacto <span style="color:red;">*</span></label>
											<input type="text" placeholder="Nombre del Contacto" class="form-control may" id="nombre_contacto" name="nombre_contacto">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Nombre para Cheques <span style="color:red;">*</span></label>
											<input type="text" placeholder="Nombre para cheques" class="form-control may" id="nombre_cheque" name="nombre_cheque">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Teléfono 1 <span style="color:red;">*</span></label>
											<input type="text" placeholder="0000-0000" class="form-control tel" id="telefono1" name="telefono1">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Teléfono 2</label>
											<input type="text" placeholder="0000-0000" class="form-control tel" id="telefono2" name="telefono2">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Tipo de Proveedor <span style="color:red;">*</span></label>
											<select class="col-md-12 select may" id="tipo" name="tipo">
												<option value="1">Costo</option>
												<option value="2">Gasto</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Fax</label>
											<input type="text" placeholder="0000-0000" class="form-control tel" id="fax" name="fax">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>Correo</label>
											<input type="text" placeholder="mail@server.com" class="form-control may" id="correo" name="correo">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group has-info single-line">
											<label>País de Origen</label>
											<select  name='pais' id='pais'  style="width:100%;" class="select">
												<option value=''>Seleccione</option>
												<?php
												$qcategoria=_query("SELECT * FROM paises WHERE iso!='SV' ORDER BY nombre ");
												echo " <option value='68' selected>El Salvador</option>";
												while($row_categoria=_fetch_array($qcategoria))
												{
													$id_categoria=$row_categoria["id"];
													$nombrecat=utf8_encode($row_categoria["nombre"]);
													echo "<option value='$id_categoria'>$nombrecat</option>";
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">

									<div class="col-md-2">
										<div class="form-group has-info single-line">
											<div class='radio i-checks'><label><input id='percibe' name='percibe' type='checkbox'> <span class="label-text"><b>Percibe 1%</b></span></label></div>
											<input type="hidden" name="hi_percibe" id="hi_percibe" value="0">
										</div>
									</div>
									<div class="col-md-2" hidden>
										<div class="form-group has-info single-line">
											<div class='radio i-checks'><label><input id='retiene' name='retiene' type='radio'> <span class="label-text"><b>Retiene</b></span></label></div>
											<input type="hidden" name="hi_retiene" id="hi_retiene" value="0">
										</div>
									</div>
									<div class="col-md-2" hidden>
										<div class="form-group has-info single-line">
											<div class='radio i-checks'><label><input id='no_retiene' name='no_retiene' type='radio'> <span class="label-text"><b>No retiene</b></span></label></div>
											<input type="hidden" name="hi_no_retiene" id="hi_no_retiene" value="0">
										</div>
									</div>
									<div class="col-md-3" hidden="true" id="retiene_select">
										<div class="form-group has-info single-line">
											<label>Porcentaje de Retención <span style="color:red;">*</span></label>
											<select class="col-md-12 select" id="porcentaje" name="porcentaje">
												<option value="0">Sin Retención</option>
												<option value="1">1%</option>
												<option value="10">10%</option>
											</select>
										</div>
									</div>
								</div>
								<input type="hidden" name="process" id="process" value="insert"><br>
								<div>
									<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		include_once ("footer.php");
		echo "<script src='js/funciones/funciones_proveedor.js'></script>";
	} //permiso del script
	else
	{
		$mensaje = mensaje_permiso();
		echo "<br><br>$mensaje<div><div></div></div</div></div>";
		include "footer.php";
	}
}

function insertar()
{
	$nombre_proveedor=strtoupper($_POST["nombre_proveedor"]);
	$nombre_comercial=strtoupper($_POST["nombre_legal"]);
	$direccion=$_POST["direccion"];
	$departamento=$_POST["departamento"];
	$municipio=$_POST["municipio"];
	$nacionalidad=$_POST["pais"];
	$dui=$_POST["dui"];
	$nit=$_POST["nit"];
	$nrc=$_POST["nrc"];
	$giro=$_POST["giro"];
	$categoria_proveedor=$_POST["categoria_proveedor"];
	$porcentaje=$_POST["porcentaje"];
	if($porcentaje == 1)
	{
		$retiene = 1;
		$retiene10 = 0;
	}
	else if($porcentaje == 0)
	{
		$retiene = 0;
		$retiene10 = 0;
	}
	else
	{
		$retiene = 0;
		$retiene10 = 1;
	}
	$tipo=$_POST["tipo"];
	if(isset($_POST['percibe']))
	{
		$percibe = 1;
	}
	else
	{
		$percibe = 0;
	}

	if(isset($_POST['no_retiene']))
	{
		$percibe = 0;
		$retiene10 = 0;
		$retiene = 0;
	}
	$nombre_contacto=$_POST["nombre_contacto"];
	$nombre_cheque=$_POST["nombre_cheque"];
	$telefono1=$_POST["telefono1"];
	$telefono2=$_POST["telefono2"];
	$fax=$_POST["fax"];
	$correo=$_POST["correo"];

	$id_sucursal = $_SESSION["id_sucursal"];
	$sql_exis=_query("SELECT id_proveedor FROM proveedor WHERE nit ='$nit' AND id_sucursal='$id_sucursal'");
	$num_exis = _num_rows($sql_exis);
	if($num_exis > 0)
	{
		$xdatos['typeinfo']='Error';
		$xdatos['msg']='Ya se registro un proveedor con estos datos!';
	}
	else
	{
		$table = 'proveedor';
		$form_data = array(
			'categoria' => $categoria_proveedor,
			'tipo' => $tipo,
			'nombre' => $nombre_proveedor,
			'nombre_legal' => $nombre_comercial,
			'direccion' => $direccion,
			'municipio' => $municipio,
			'depto' => $departamento,
			'contacto' => $nombre_contacto,
			'nrc' => $nrc,
			'nit' => $nit,
			'dui' => $dui,
			'giro' => $giro,
			'telefono1' => $telefono1,
			'telefono2' => $telefono2,
			'fax' => $fax,
			'email' => $correo,
			'percibe' => $percibe,
			'retiene' => $retiene,
			'retiene10' => $retiene10,
			'nombreche' => $nombre_cheque,
			'nacionalidad' => $nacionalidad,
			'id_sucursal' => $id_sucursal,
		);
		$insertar = _insert($table,$form_data );
		if($insertar)
		{
			$xdatos['typeinfo']='Success';
			$xdatos['msg']='Registro guardado con exito!';
			$xdatos['process']='insert';
		}
		else
		{
			$xdatos['typeinfo']='Error';
			$xdatos['msg']='Registro no pudo ser guardado !'._error();
		}
	}
	echo json_encode($xdatos);
}
function municipio()
{
	$id_departamento = $_POST["id_departamento"];
	$option = "";
	$sql_mun = _query("SELECT * FROM municipio WHERE id_departamento_municipio='$id_departamento'");
	while($mun_dt=_fetch_array($sql_mun))
	{
		$option .= "<option value='".$mun_dt["id_municipio"]."'>".$mun_dt["nombre_municipio"]."</option>";
	}
	echo $option;
}
if(!isset($_POST['process']))
{
	initial();
}
else
{
	if(isset($_POST['process']))
	{
		switch ($_POST['process'])
		{
			case 'insert':
			insertar();
			break;
			case 'municipio':
			municipio();
			break;

		}
	}
}
?>
