<?php
include_once "_core.php";
function initial()
{
    $title='Configuraciones';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
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

    //Get data from db
    $sql_municipalidad = _query("SELECT mn.*, d.nombre_departamento, m.nombre_municipio FROM laboratorio as mn, departamento as d, municipio as m WHERE d.id_departamento=mn.id_departamento AND m.id_municipio=mn.id_municipio AND mn.id_municipalidad='1'");
    $row = _fetch_array($sql_municipalidad);

    $telefono1 = $row["telefono1"];
    $telefono2 = $row["telefono2"];
    $email=$row["email"];
    $web=$row["website"];
    $direccion = $row["direccion"];
    $nombre_lab = $row["nombre_lab"];
    $departamento = $row["id_departamento"];
    $municipio = $row["id_municipio"];
    $logo = $row["logo"];
    //$mgmax = $row["minutos_gracia"];
    //$hpmax = $row["horas_permiso"];

?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <?php
                //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                 <h3><i class="fa fa-gear"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_municipalidad" id="formulario_municipalidad" autocomplete='off'>
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-info">
                            <label>Nombre del Laboratorio</label>
                            <input type="text" class="form-control" id="nombre_lab" name="nombre_lab" value="<?php echo $nombre_lab; ?>">
                        </div>
                    </div>
                      <div class="col-md-6">
                          <div class="form-group has-info">
                              <label>Dirección <span style="color:red;">*</span></label>
                              <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
                          </div>
                      </div>
                  </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Departamento <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="departamento" name="departamento">
                                    <option value="">Seleccione</option>
                                    <?php
                                        $sqld = "SELECT * FROM departamento";
                                        $resultd=_query($sqld);
                                        while($depto = _fetch_array($resultd))
                                        {
                                            echo "<option value='".$depto["id_departamento"]."'";
                                            if($depto["id_departamento"] == $departamento)
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$depto["nombre_departamento"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Municipio <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="municipio" name="municipio">
                                    <?php
                                        $sqld = "SELECT * FROM municipio WHERE id_departamento_municipio ='$departamento'";
                                        $resultd=_query($sqld);
                                        while($depto = _fetch_array($resultd))
                                        {
                                            echo "<option value='".$depto["id_municipio"]."'";
                                            if($depto["id_municipio"] == $municipio)
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$depto["nombre_municipio"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono 1<span style="color:red;">*</span></label>
                                <input type="text" class="form-control tel" id="telefono1" name="telefono1" value="<?php echo $telefono1; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono 2</label>
                                <input type="text" class="form-control tel" id="telefono2" name="telefono2" value="<?php echo $telefono2; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Correo Electronico</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Sitio Web</label>
                                <input type="text" class="form-control" id="web" name="web" value="<?php echo $web; ?>">
                            </div>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Minutos de Gracia al Mes</label>
                                <input type="text" class="form-control numeric" id="mgmax" name="mgmax" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Horas de Permiso anual (Con goce de sueldo)</label>
                                <input type="text" class="form-control numeric" id="hpmax" name="hpmax" value="">
                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Logo</label>
                                <input type="file" name="logo" id="logo" class="file" data-preview-file-type="image">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <img id="logo_view" src="<?php echo $logo;?>" style='width: 100px; height: 100px;'>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_adm_laboratorio.js'></script>";
echo " <script src='js/plugins/fileinput/fileinput.js'></script>";
} //permiso del script
else
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
}
function editar()
{
    require_once 'class.upload.php';
    if ($_FILES["logo"]["name"]!="")
    {
    $foo = new Upload($_FILES['logo'],'es_ES');
    if ($foo->uploaded) {
        $pref = uniqid()."_";
        $foo->file_force_extension = false;
        $foo->no_script = false;
        $foo->file_name_body_pre = $pref;
       // save uploaded image with no changes
       $foo->Process('img/');
       if ($foo->processed)
       {
        $query = _query("SELECT logo FROM laboratorio WHERE id_municipalidad='1'");
        $result = _fetch_array($query);
        $urlb=$result["logo"];
        if($urlb!="")
        {
        //    unlink($urlb);
        }
        $telefono1=$_POST["telefono1"];
        $telefono2=$_POST["telefono2"];
        $email=$_POST["email"];
        $web=$_POST["web"];
        $departamento=$_POST["departamento"];
        $municipio=$_POST["municipio"];
        $direccion=$_POST["direccion"];
        $nombre_lab=$_POST["nombre_lab"];
        //$mgmax = $_POST["mgmax"];
        //$hpmax = $_POST["hpmax"];
        $cuerpo=quitar_tildes($foo->file_src_name_body);
        $cuerpo=trim($cuerpo);
        $url = 'img/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
        $tabla = 'laboratorio';
        $form_data = array(
            'id_departamento' => $departamento,
            'id_municipio' => $municipio,
            'direccion' => $direccion,
            'telefono1' => $telefono1,
            'telefono2' => $telefono2,
            'email' => $email,
            'nombre_lab' => $nombre_lab,
            'website' => $web,
            //'minutos_gracia' => $mgmax,
            //'horas_permiso' => $hpmax,
            'logo' => $url
            );
        $where_clause = "id_municipalidad='1'";
        $editar =_update($tabla, $form_data, $where_clause);
        if($editar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Datos de alcaldia editados correctamente !';
           $xdatos['process']='edit';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Datos de alcaldia no pudieron ser editados!'._error();
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
        $telefono1=$_POST["telefono1"];
        $telefono2=$_POST["telefono2"];
        $email=$_POST["email"];
        $web=$_POST["web"];
        $departamento=$_POST["departamento"];
        $municipio=$_POST["municipio"];
        $direccion=$_POST["direccion"];
        $nombre_lab=$_POST["nombre_lab"];

        $tabla = 'laboratorio';

        $form_data = array(
            'id_departamento' => $departamento,
            'id_municipio' => $municipio,
            'direccion' => $direccion,
            'telefono1' => $telefono1,
            'telefono2' => $telefono2,
            'email' => $email,
            'nombre_lab' => $nombre_lab,
            'website' => $web,
            //'minutos_gracia' => $mgmax,
            //'horas_permiso' => $hpmax,
            );
        $where_clause = "id_municipalidad='1'";
        $editar =_update($tabla, $form_data, $where_clause);
        if($editar)
        {
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Datos de alcaldia editados correctamente !';
           $xdatos['process']='edit';
        }
        else
        {
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Datos de alcaldia no pudieron ser editados!'._error();
        }
    }
    echo json_encode($xdatos);
}
function municipio($id_departamento)
{
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
            case 'edit':
                editar();
                break;
            case 'municipio':
                municipio($_POST["id_departamento"]);
                break;
        }
    }
}
?>
