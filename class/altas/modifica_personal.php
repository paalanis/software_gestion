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

$sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as finca
              FROM
              tb_finca";
$rsfinca = mysqli_query($conexion, $sqlfinca); 
$sqlpuesto = "SELECT
              tb_puesto.id_puesto as id_puesto,
              tb_puesto.nombre as puesto
              FROM
              tb_puesto";
$rspuesto = mysqli_query($conexion, $sqlpuesto);               

$id=$_REQUEST['id'];

$sqlpersonal = "SELECT
          tb_finca.nombre as finca,
          tb_finca.id_finca as idfinca,
          tb_personal.nombre as nombre,
          tb_personal.apellido as apellido, 
          DATE_FORMAT(tb_personal.nacimiento, '%Y-%m-%d') as nac, 
          DATE_FORMAT(tb_personal.ingreso, '%Y-%m-%d') as ing,
          tb_puesto.nombre as puesto,
          tb_puesto.id_puesto as idpuesto,
          IF(tb_personal.eventual = '0', '','checked') as eventual
          FROM
          tb_personal
          INNER JOIN tb_finca ON tb_personal.id_finca = tb_finca.id_finca
          INNER JOIN tb_puesto ON tb_puesto.id_puesto = tb_personal.id_puesto
          WHERE
          tb_personal.id_personal = '$id'";
$rspersonal = mysqli_query($conexion, $sqlpersonal);

$cantidad =  mysqli_num_rows($rspersonal);

if ($cantidad > 0) { // si existen personal con de esa personal se muestran, de lo contrario queda en blanco  

while ($datos = mysqli_fetch_array($rspersonal)){
$finca=utf8_encode($datos['finca']);
$idfinca=utf8_encode($datos['idfinca']);
$nombre=utf8_encode($datos['nombre']);
$apellido=utf8_encode($datos['apellido']);
$nac=utf8_encode($datos['nac']);
$ing=utf8_encode($datos['ing']);
$puesto=utf8_encode($datos['puesto']);
$idpuesto=utf8_encode($datos['idpuesto']);
$eventual=utf8_encode($datos['eventual']);

}   
}

 ?>
<input type="hidden" class="form-control" value="<?php echo $id; ?>" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_personal()">
 
 <h4><span class="label label-default">Modifica Personal</span></h4>
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
        <label for="inputPassword" class="col-lg-2 control-label">Apellido</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $apellido; ?>" autocomplete="off" id="alta_apellido" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha nacimiento</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" value="<?php echo $nac; ?>" id="alta_nacimiento" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha ingreso</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" value="<?php echo $ing; ?>" id="alta_ingreso" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Puesto</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_puesto" required>   
              <option value="<?php echo $idpuesto; ?>"><?php echo $puesto; ?></option>
              <?php
              while ($sql_puesto = mysqli_fetch_array($rspuesto)){
                $idpuesto= $sql_puesto['id_puesto'];
                $puesto = $sql_puesto['puesto'];

                echo utf8_encode('<option value='.$idpuesto.'>'.$puesto.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Finca</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_finca" required>   
              <option value="<?php echo $idfinca; ?>"><?php echo $finca; ?></option>
              <?php
              while ($sql_finca = mysqli_fetch_array($rsfinca)){
                $idfinca= $sql_finca['id_finca'];
                $finca = $sql_finca['finca'];

                echo utf8_encode('<option value='.$idfinca.'>'.$finca.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Personal eventual</label>
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
          <button type="button"   class="btn btn-default" onclick='llama_alta_personal()' aria-label="Left Align">Salir 
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

