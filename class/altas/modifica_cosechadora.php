<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$id=$_REQUEST['id'];

$sqlcosechadora = "SELECT
                tb_cosechadora.nombre as nombre,
                tb_cosechadora.caracteristicas as caracteristicas,
                tb_cosechadora.id_cosechadora as id_cosechadora,
                IF(tb_cosechadora.propia = '0', '','checked') as eventual
                FROM
                tb_cosechadora
                WHERE
                tb_cosechadora.id_cosechadora = '$id'
                ORDER BY
                nombre ASC";
$rscosechadora = mysqli_query($conexion, $sqlcosechadora);

$cantidad =  mysqli_num_rows($rscosechadora);

if ($cantidad > 0) { // si existen cosechadora con de esa cosechadora se muestran, de lo contrario queda en blanco  

while ($datos = mysqli_fetch_array($rscosechadora)){
$nombre=utf8_encode($datos['nombre']);
$caracteristicas=utf8_encode($datos['caracteristicas']);
$eventual=utf8_encode($datos['eventual']);

}   
}

 ?>
<input type="hidden" class="form-control" value="<?php echo $id; ?>" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_cosechadora()">
 
 <h4><span class="label label-default">Modifica Cosechadora</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $nombre; ?>" autocomplete="off" id="alta_nombre" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Características</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $caracteristicas; ?>" autocomplete="off" id="alta_caracteristicas" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Propia</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_eventual" <?php echo $eventual; ?>>
          </div>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
         
          </div>
          
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="button"   class="btn btn-default" onclick='llama_alta_cosechadora()' aria-label="Left Align">Salir 
            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
            </button>
          <button type="submit" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
      
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>

         
   </fieldset>
  </div> 
 </div>  
 </div>
</form>

