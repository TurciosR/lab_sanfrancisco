<?php
include ("_core.php");
function initial(){
	$id_paciente = $_REQUEST['id_paciente'];
	$id_sucursal=$_SESSION["id_sucursal"];
	$sql="SELECT foto FROM paciente WHERE id_paciente='$id_paciente' and id_sucursal='$id_sucursal'";
	$result = _query($sql);
	$data = _fetch_array($result);
	$img_ruta="img/pacientes/no_disponible.png";
	$imagen = $data["foto"];
	if($imagen != "")
	{
		$img_ruta = $imagen;
	}
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
		<h4 class="modal-title">Foto del paciente</h4>
	</div>
	<div class="modal-body">
		<div class="wrapper wrapper-content  animated fadeInRight">
			<div class="row" id="row1">
				<div class="col-lg-12">
					<form id="form" enctype="multipart/form-data" role="form">
						<div class="form-group">
							<div class="col-md-12">
								<!--Utilizando jasny solo para el control del input file-->
								<input type="file" name="foto" id="foto" class="file" accept=".png,.jpg" data-preview-file-type="image">
								<!--Fin Utilizando jasny solo para el control del input file-->
								<!-- HIDDEN INPUT-->
								<input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente;?>">
								<input type="hidden" name="process" id="process" value="upload_s">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<br>
								<a class="btn btn-primary pull-right" id="btn_agregar_ft"><i class="fa fa-upload" aria-hidden="true"></i> Subir</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!--/modal-footer -->
	<script type="text/javascript">
	$("#foto").fileinput({'showUpload':true, 'previewFileType':'image'});
</script>
<?php

}
function upload_s(){
	require_once 'class.upload.php';
	$id_paciente=$_POST['id_paciente'];
	$foo = new Upload($_FILES['foto'],'es_ES');
	if ($foo->uploaded)
	{
		$pref = uniqid()."_";
		$foo->file_force_extension = false;
		$foo->no_script = false;
		$foo->file_name_body_pre = $pref;
		// save uploaded image with no changes
		$foo->Process('img/pacientes/');
		if ($foo->processed) {
			$archivo = $_FILES["foto"]["name"];
			$url = 'img/pacientes/'.$pref.$foo->file_src_name_body.".".$foo->file_src_name_ext;
			$table = 'paciente';
			$form_data = array (
				'foto' => $url,
			);
			$where_clause = "id_paciente='" . $id_paciente . "'";
			$updates = _update( $table, $form_data, $where_clause );

			if($updates)
			{
				$xdatos ['typeinfo'] = 'Success';
				$xdatos ['msg'] = "El archivo se subio con exito";
				$xdatos ['url'] = $url;

			}
			else
			{
				$xdatos ['typeinfo'] = 'Error';
				$xdatos ['msg'] = "El archivo no se guardo en la base de datos";
			}
		}
		else
		{
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = "El archivo no pudo ser subido, error: ".$foo->error;
			$xdatos ['error'] = $foo->error;

		}
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = "El archivo no pudo ser subido, error: ".$foo->error;
		$xdatos ['error'] = $foo->error;
	}
	echo json_encode($xdatos);
}


/*function deleted()
{
	$id_img = $_POST ['id_img'];
	$sqlfile = _query("SELECT url FROM img_paciente WHERE id_img = '$id_img'");
	$resultfile=_fetch_array($sqlfile);
	$file_to_delete = $resultfile["url"];
	if($file_to_delete != "")
	{
		if(unlink($file_to_delete))
		{
			$table = 'img_paciente';
			$where_clause = "id_img='" . $id_img . "'";
			$delete = _delete ( $table, $where_clause );
			if ($delete) {
				$xdatos ['typeinfo'] = 'Success';
			} else {
				$xdatos ['typeinfo'] = 'Error';
			}
		}
		else
		{
			$xdatos ['typeinfo'] = 'Error';
		}
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
	}
	echo json_encode ( $xdatos );
}*/

if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'upload_s' :
			upload_s();
			break;
			case 'deleted' :
			deleted();
			break;
		}
	}
}

?>
