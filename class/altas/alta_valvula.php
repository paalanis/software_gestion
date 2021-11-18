<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';

 if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
   exit();
}
$sqlcaudalimetro = "SELECT
            tb_caudalimetro.id_caudalimetro as id_caudalimetro,
            tb_caudalimetro.nombre as nombre
            FROM tb_caudalimetro
            WHERE
            tb_caudalimetro.id_finca = '$id_finca_usuario'
            and tb_caudalimetro.dilucion = '0'";
$rscaudalimetro = mysqli_query($conexion, $sqlcaudalimetro);

$sqlcuartel = "SELECT
                tb_cuartel.id_cuartel AS id_cuartel,
                CONCAT(tb_cuartel.nombre, ' - Has ', tb_cuartel.has) AS nombre
                FROM
                tb_cuartel
                WHERE
                tb_cuartel.id_finca = '$id_finca_usuario'
                ";
$rscuartel = mysqli_query($conexion, $sqlcuartel);  

$cantidad =  mysqli_num_rows($rscuartel);

 ?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_valvulas()">
 
 <h4><span class="label label-default">Alta Válvulas</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Caudalímetro</label>
        <div class="col-lg-9">
          <select class="form-control" id="alta_caudalimetro" required>   
              <?php
              while ($sql_caudalimetro = mysqli_fetch_array($rscaudalimetro)){
                $idcaudalimetro= $sql_caudalimetro['id_caudalimetro'];
                $caudalimetro = $sql_caudalimetro['nombre'];

                echo utf8_encode('<option value='.$idcaudalimetro.'>'.$caudalimetro.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Nombre de válvula</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_valvula" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Asignar cuarteles</label>
        <div class="col-lg-5">
         <select class="form-control" id="alta_asignar" required>   
          <option value=""></option>
          <?php
          if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
          while ($sql_cuartel = mysqli_fetch_array($rscuartel)){
            $idcuartel= $sql_cuartel['id_cuartel'];
            $cuartel = $sql_cuartel['nombre'];
            echo utf8_encode('<option value='.$idcuartel.'>'.$cuartel.'</option>');
          }
          }else{
            echo utf8_encode('<option value="0">Sin cuarteles</option>');
          }  
          ?>
        </select>
</div>
<div class="col-lg-4">
  <div class="input-group input-group-sm">
    <input class="form-control" autocomplete="off" placeholder='Asignar has' id="alta_asignar_has" type="text" required>
    <span class="input-group-btn">
      <button class="btn btn-default" type="submit">Ok</button>
    </span>
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
          <!-- <button type="reset" class="btn btn-default">Borrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>   -->
          </div>
          
        </div>
      </div>
   </fieldset>
 </div>
 <div class="col-lg-7">
<fieldset>
<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover ">
<thead>
<tr style="height:5px">
<th>Finca</th>
<th>Caudalímetro</th>
<th>Válvula</th>
<th>Cuartel</th>
<th>Has asignadas</th>
</tr>
</thead>
<tbody>
<?php
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
$sqlcuarteles = "SELECT
        tb_finca.nombre as finca,
        tb_caudalimetro.nombre as caudalimetro,
        tb_valvula.nombre as valvula,
        CAST(tb_valvula.nombre AS SIGNED) as orden_valvula,
        tb_cuartel.nombre as cuartel,
        tb_valvula.has_asignadas as has
        FROM
        tb_valvula
        INNER JOIN tb_caudalimetro ON tb_valvula.id_caudalimetro = tb_caudalimetro.id_caudalimetro
        INNER JOIN tb_finca ON tb_caudalimetro.id_finca = tb_finca.id_finca
        INNER JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_valvula.id_cuartel
        WHERE
        tb_finca.id_finca = '$id_finca_usuario'
        ORDER BY
        caudalimetro ASC,
        orden_valvula ASC";
$rscuarteles = mysqli_query($conexion, $sqlcuarteles);

$cantidad =  mysqli_num_rows($rscuarteles);

if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  

while ($datos = mysqli_fetch_array($rscuarteles)){
$finca=utf8_encode($datos['finca']);
$caudalimetro=utf8_encode($datos['caudalimetro']);
$valvula=utf8_encode($datos['valvula']);
$cuartel=utf8_encode($datos['cuartel']);
$has=$datos['has'];

echo '

<tr>
<td>'.$finca.'</td>
<td>'.$caudalimetro.'</td>
<td>'.$valvula.'</td>
<td>'.$cuartel.'</td>
<td>'.$has.'</td>
</tr>
';

}   
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){

echo "El caudalimetro no tiene válvulas cargadas.";
}
?>
</div>
</div>
</fieldset>
</div> 
</div>  
</div>
</form>

<script type="text/javascript">
 $(document).ready(function () {
  
  $('#alta_asignar_has').mask("##.00", {reverse: true});
 
  });
  </script>