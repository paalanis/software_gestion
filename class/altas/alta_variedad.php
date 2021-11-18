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

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_variedades()">

 <h4><span class="label label-default">Alta Variedades</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_variedad" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Tipo de pie</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_tipo" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Origen</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_origen" aria-describedby="basic-addon1" required autofocus="">
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

      <div class="panel-body" id="Panel1" style="height:180px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Variedad</th>
            <th>Tipo de pie</th>
            <th>Origen</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlvariedad = "SELECT
                          tb_variedad.nombre as variedad,
                          tb_variedad.tipo as tipo,
                          tb_variedad.origen as origen
                          FROM
                          tb_variedad
                          ORDER BY
                          tb_variedad.nombre ASC
                          ";
              $rsvariedad = mysqli_query($conexion, $sqlvariedad);
              
              $cantidad =  mysqli_num_rows($rsvariedad);

              if ($cantidad > 0) { // si existen variedad con de esa variedad se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsvariedad)){
              $variedad=utf8_encode($datos['variedad']);
              $tipo=utf8_encode($datos['tipo']);
              $origen=utf8_encode($datos['origen']);
              
              echo '

              <tr>
                <td>'.$variedad.'</td>
                <td>'.$tipo.'</td>
                <td>'.$origen.'</td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay variedades cargadas.";
              }
      ?>
      </div>
      </div>  
         
   </fieldset>
  </div> 

 </div>  
  



 </div>

  
</form>

