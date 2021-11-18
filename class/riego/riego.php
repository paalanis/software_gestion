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
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
$sqlcaudalimetro = "SELECT
              CONCAT(tb_caudalimetro.dilucion,'-',tb_caudalimetro.id_caudalimetro) as dilucion_id_caudalimetro,
              if(tb_caudalimetro.dilucion = '0', tb_caudalimetro.nombre, CONCAT(tb_caudalimetro.nombre,' - Dilución')) as nombre,
              tb_caudalimetro.coef as coef
              FROM tb_caudalimetro
              WHERE
              tb_caudalimetro.id_finca = '$id_finca_usuario'
              ";
 $rscaudalimetro = mysqli_query($conexion, $sqlcaudalimetro);


 ?>
<input type="hidden" class="form-control" value="" id="tipo_dilucion" aria-describedby="basic-addon1">
<input type="hidden" class="form-control" value="<?php echo $id_finca_usuario; ?>" id="id_finca_usuario" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); carga_riego()">
 
 <h4><span class="label label-default">Parte Riego-goteo</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="riego_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Caudalímetro</label>
        <div class="col-lg-10">
          <select class="form-control" id="riego_caudalimetro" required onchange="llama_riego_valvula_operacion()">   
              <option value=""></option>
              <?php
              while ($sql_caudalimetro = mysqli_fetch_array($rscaudalimetro)){
                $dilucion_idcaudalimetro= $sql_caudalimetro['dilucion_id_caudalimetro'];
                $caudalimetro = utf8_decode($sql_caudalimetro['nombre']);
                              
                echo utf8_encode('<option value='.$dilucion_idcaudalimetro.' name="hola">'.$caudalimetro.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
     
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Metros cúbicos</label>
        <div class="col-lg-2" id='div_ultima_lectura'>
          <!-- <input type="text" class="form-control" style="width: 85px;" autocomplete="off" id="riego_inicial" placeholder="Lec. inicial" aria-describedby="basic-addon1" onblur="consumo_milimetros()" required autofocus=""> -->
        </div>
        <div class="col-lg-2">
          <input type="text" class="form-control" style="width: 85px;" autocomplete="off" id="riego_final" placeholder="Lec. final" aria-describedby="basic-addon1" onblur="consumo_milimetros()" required autofocus="">
        </div>
        <label for="inputPassword" class="col-lg-1 control-label">xCoef</label>
        <div class="col-lg-1" id="div_coef">
        </div>
        <div class="col-lg-4">
          <input type="text" class="form-control"  autocomplete="off" id="riego_resultado" placeholder="Consumo ajustado" readonly aria-describedby="basic-addon1" required>
        </div>  
      </div>
           
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
         
          </div>
          
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
   </fieldset>
 </div>
 <div class="col-lg-6">
   <fieldset>

  <div class="form-group form-group-sm">
    <!-- <label  class="col-lg-2 control-label">Válvulas</label> -->
    <div class="col-lg-4">
    <div id="div_operacion"></div>
    </div>
    <div class="col-lg-8">
    <div id="div_valvula"></div>
    </div>
  </div>



   </fieldset>
  </div> 
 </div>  
 </div>
</form>


<script type="text/javascript">

  $(document).ready(function () {
    
  $('#riego_inicial').mask("##.00", {reverse: true});
  $('#riego_final').mask("##.00", {reverse: true});
    
  });

  </script>