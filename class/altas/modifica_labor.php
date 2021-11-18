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

$sqltipo_labor = "SELECT
            tb_tipo_labor.id_tipo_labor as id_tipo_labor,
            tb_tipo_labor.nombre as nombre
            FROM tb_tipo_labor";
$rstipo_labor = mysqli_query($conexion, $sqltipo_labor);  

$id=$_REQUEST['id'];

$sqllabor = "SELECT
              tb_labor.id_labor as id_labor,
              tb_labor.nombre as labor,
              tb_tipo_labor.nombre as tipo,
              tb_tipo_labor.id_tipo_labor as id_tipo
              FROM
              tb_labor
              LEFT JOIN tb_tipo_labor ON tb_labor.id_tipo_labor = tb_tipo_labor.id_tipo_labor
              WHERE
              tb_labor.id_labor = '$id'";
$rslabor = mysqli_query($conexion, $sqllabor);

$cantidad =  mysqli_num_rows($rslabor);

if ($cantidad > 0) { // si existen labor con de esa labor se muestran, de lo contrario queda en blanco  

while ($datos = mysqli_fetch_array($rslabor)){
$labor=utf8_encode($datos['labor']);
$id_labor=utf8_encode($datos['id_labor']);
$tipo=utf8_encode($datos['tipo']);
$id_tipo=utf8_encode($datos['id_tipo']);

}   
}

 ?>
<input type="hidden" class="form-control" value="<?php echo $id; ?>" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_labores()">

 <h4><span class="label label-default">Modifica Labor</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $labor; ?>" autocomplete="off" id="alta_labor" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Tipo de labor</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_tipo" required>   
              <option value="<?php echo $id_tipo; ?>"><?php echo $tipo; ?></option>
              <?php
              while ($sql_tipo_labor = mysqli_fetch_array($rstipo_labor)){
                $idtipo_labor= $sql_tipo_labor['id_tipo_labor'];
                $tipo_labor = $sql_tipo_labor['nombre'];

                echo utf8_encode('<option value='.$idtipo_labor.'>'.$tipo_labor.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
         
          </div>
          
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="button"   class="btn btn-default" onclick='llama_alta_labor()' aria-label="Left Align">Salir 
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

