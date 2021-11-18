<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location:../../index.php");
}
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
 $sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as nombre
              FROM tb_finca
              WHERE
              tb_finca.id_finca = '$id_finca_usuario'";
 $rsfinca = mysqli_query($conexion, $sqlfinca); 
 ?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_caudalimetros()">
 
 <h4><span class="label label-default">Alta Caudalímetros</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Nombre</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_caudalimetro" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Finca</label>
        <div class="col-lg-9">
          <select class="form-control" id="alta_finca" required>   
              <?php
              while ($sql_finca = mysqli_fetch_array($rsfinca)){
                $idfinca= $sql_finca['id_finca'];
                $finca = $sql_finca['nombre'];

                echo utf8_encode('<option value='.$idfinca.'>'.$finca.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Características</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_caracteristicas" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Caudalímetro de dilución</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_dilucion">
          </div>
        </div>
        <label for="inputPassword" class="col-lg-4 control-label">Coef. corrección</label>
        <div class="col-lg-3">
          <div align="left">
          <input type="text" class="form-control" autocomplete="off" id="alta_coef" aria-describedby="basic-addon1" required>
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
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
   </fieldset>
 </div>
 <div class="col-lg-7">
   <fieldset>

      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:235px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Finca</th>
            <th>Caudalímetro</th>
            <th>Características</th>
            <th>Coef</th>
            <th>Dilución</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              include '../../conexion/conexion.php';
               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlcaudalimetro = "SELECT
                            tb_finca.nombre as finca,
                            tb_caudalimetro.nombre as caudalimetro,
                            tb_caudalimetro.caracteristicas as caracteristica,
                            tb_caudalimetro.coef as coef,
                            if(tb_caudalimetro.dilucion = '0', 'No', 'Si') as dilucion
                            FROM
                            tb_caudalimetro
                            INNER JOIN tb_finca ON tb_caudalimetro.id_finca = tb_finca.id_finca
                            WHERE
                            tb_caudalimetro.id_finca = '$id_finca_usuario'
                            ORDER BY
                            finca ASC
                            ";
              $rscaudalimetro = mysqli_query($conexion, $sqlcaudalimetro);
              
              $cantidad =  mysqli_num_rows($rscaudalimetro);

              if ($cantidad > 0) { // si existen caudalimetro con de esa caudalimetro se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rscaudalimetro)){
              $finca=utf8_encode($datos['finca']);
              $caudalimetro=utf8_encode($datos['caudalimetro']);
              $caracteristica=utf8_encode($datos['caracteristica']);
              $dilucion=utf8_encode($datos['dilucion']);
              $coef=utf8_encode($datos['coef']);
              
              echo '

              <tr>
                <td>'.$finca.'</td>
                <td>'.$caudalimetro.'</td>
                <td>'.$caracteristica.'</td>
                <td>'.$coef.'</td>
                <td>'.$dilucion.'</td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay caudalimetros cargados.";
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
    
  $('#alta_coef').mask("##.00", {reverse: true});
  });

  </script>