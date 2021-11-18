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

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_transportes()">

 <h4><span class="label label-default">Alta Transportes</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Razón Social</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_rs" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Observación</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_obs" aria-describedby="basic-addon1" autofocus="">
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

      <div class="panel-body" id="Panel1" style="height:145px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Razón Social</th>
            <th>Observación</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqltransporte = "SELECT
                          tb_transporte.razon_social as razon_s,
                          tb_transporte.obs as obs
                          FROM
                          tb_transporte
                          ORDER BY
                          tb_transporte.razon_social ASC
                          ";
              $rstransporte = mysqli_query($conexion, $sqltransporte);
              
              $cantidad =  mysqli_num_rows($rstransporte);

              if ($cantidad > 0) { // si existen transporte con de esa transporte se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rstransporte)){
              $transporte=utf8_encode($datos['razon_s']);
              $obs=utf8_encode($datos['obs']);
              
              echo '

              <tr>
                <td>'.$transporte.'</td>
                <td>'.$obs.'</td>
              </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay transportes cargados.";
              }
      ?>
      </div>
      </div>  
         
   </fieldset>
  </div> 

 </div>  
  



 </div>

  
</form>

