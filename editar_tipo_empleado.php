<?php
include_once "_core.php";

function initial() {
    //permiso del script
	   $id_user=$_SESSION["id_usuario"];
	   $admin=$_SESSION["admin"];
		 	$uri = $_SERVER['SCRIPT_NAME'];
			$id_sucursal=$_SESSION["id_sucursal"];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    $id_tipo_empleado = $_REQUEST["id_tipo_empleado"];
    $query_user = _query("SELECT * FROM tipo_empleado WHERE id_tipo_empleado='$id_tipo_empleado'and id_sucursal='$id_sucursal'");
    $datos_user = _fetch_array($query_user);
    $nombre = $datos_user["descripcion"];
    //permiso del script023+-
?>
<div class="modal-header">

	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title text-center text-navy">Editar Tipo Empleado</h3>
</div>
<form name="formulario" id="formulario">
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInUp">
    <div class="row">
      <?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
					?>
        <div class="col-lg-10">
								  <div class="container">

                      	<div class="row">
                        	<div class="form-group col-md-5">
                          	<label>Nombre</label>
                            	<input type="text" placeholder="Ingresa Nombres" class="form-control may" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                          </div>
                        </div>
                        <div class="row">
										    	<div class="form-actions col-md-5">

                      		</div>
                        </div>

                  </div>
                </div>
            </div>
		      </div>
        </div>
<div class="modal-footer">
 <input type="hidden" name="process" id="process" value="edited">
 <input type="hidden" name="id_tipo_empleado" id="id_tipo_empleado" value="<?php echo $id_tipo_empleado; ?>">
 <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary"/>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
</div>
  </form>
<script>
	$(document).ready(function(){
		$('#formulario').validate({
			rules: {
				nombre: {
					required: true,
				},
			},
			messages: {
				nombre: "Por favor ingrese un nombre",
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {
				senddata();
			}
		});

		$(".may").keyup(function() {
	    $(this).val($(this).val().toUpperCase());
	  });
	});
	</script>


<?php
//include_once("footer.php");
echo "<script src='js/funciones/funciones_tipo_empleado.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function editar_tipo_empleado()
{
    $id_tipo_empleado = $_POST["id_tipo_empleado"];
    $nombre = $_POST["nombre"];
		$id_sucursal=$_SESSION["id_sucursal"];

    $sql_result=_query("SELECT id_tipo_empleado FROM tipo_empleado WHERE  id_tipo_empleado='$id_tipo_empleado'and id_sucursal='$id_sucursal'");
    $numrows=_num_rows($sql_result);

    $table = 'tipo_empleado';
    $form_data = array (
    'descripcion' => $nombre
    );
    $where_clause = "id_tipo_empleado ='".$id_tipo_empleado."'and id_sucursal='".$id_sucursal."'";
    if($numrows != 0)
    {
        $insertar = _update($table,$form_data, $where_clause);
        if($insertar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Tipo Empleado editado correctamente!';
           $xdatos['process']='insert';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Tipo Empleado no pudo ser editado!';
    	}
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Tipo Empleado no esta disponible, intente con uno diferente!';
    }
	echo json_encode($xdatos);
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
        	case 'edited':
        		editar_tipo_empleado();
        		break;
        }
    }
}
?>
