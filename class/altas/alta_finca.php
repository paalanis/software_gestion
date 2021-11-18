<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_fincas()">
 <h4><span class="label label-default">Alta Fincas</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_finca" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Localidad</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_localidad" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Provincia</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_provincia" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Has</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_has" aria-describedby="basic-addon1" required autofocus="">
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

      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:225px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Finca</th>
            <th>Localidad</th>
            <th>Provincia</th>
            <th>Has</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlfinca = "SELECT
                            tb_finca.nombre as finca,
                            tb_finca.localidad as localidad,
                            tb_finca.provincia as provincia,
                            tb_finca.has as has
                            FROM
                            tb_finca
                            ORDER BY
                            tb_finca.nombre ASC
                            ";
              $rsfinca = mysqli_query($conexion, $sqlfinca);
              
              $cantidad =  mysqli_num_rows($rsfinca);

              if ($cantidad > 0) { // si existen finca con de esa finca se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsfinca)){
              $finca=utf8_encode($datos['finca']);
              $localidad=utf8_encode($datos['localidad']);
              $provincia=utf8_encode($datos['provincia']);
              $has=$datos['has'];
              
              echo '

              <tr>
                <td>'.$finca.'</td>
                <td>'.$localidad.'</td>
                <td>'.$provincia.'</td>
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

                echo "No hay fincas cargadas.";
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
    
  $('#alta_has').mask("##.00", {reverse: true});
    
  });

  </script>
