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

 ?>
<input type="hidden" class="form-control" value="" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_cosechadora()">
 
 <h4><span class="label label-default">Alta Cosechadora</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Nombre</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_nombre" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Características</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_caracteristicas" aria-describedby="basic-addon1" autofocus="">
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Propia</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_eventual">
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

      <div class="panel-body" id="Panel1" style="height:185px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Nombre</th>
            <th>Características</th>
            <th>Propia/Alquiler</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
              }

              $sqlcosechadora = "SELECT
                              tb_cosechadora.nombre as nombre,
                              tb_cosechadora.caracteristicas as caracteristicas,
                              tb_cosechadora.id_cosechadora as id_cosechadora,
                              IF(tb_cosechadora.propia = '0', 'Alquiler','Propia') as eventual
                              FROM
                              tb_cosechadora
                              ORDER BY
                              nombre ASC";
              $rscosechadora = mysqli_query($conexion, $sqlcosechadora);
              
              $cantidad =  mysqli_num_rows($rscosechadora);

              if ($cantidad > 0) { // si existen cosechadora con de esa cosechadora se muestran, de lo contrario queda en blanco  

              $contador = 0;    
             
              while ($datos = mysqli_fetch_array($rscosechadora)){
              $nombre=utf8_encode($datos['nombre']);
              $caracteristicas=utf8_encode($datos['caracteristicas']);
              $id_cosechadora=utf8_encode($datos['id_cosechadora']);
              $eventual=utf8_encode($datos['eventual']);
              
              $contador = $contador + 1;
              
              echo '

              <tr>
                <td>'.$nombre.'</td>
                <td>'.$caracteristicas.'</td>
                <td>'.$eventual.'</td>
                <td><button class="ver_riego ver_riego-default ver_riego-xs" id="mod_personal_'.$contador.'" value="'.$id_cosechadora.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay cosechadoras cargadas.";
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
  $(function() {
        $('.ver_riego-default').click(function() {

         var id = $(this).val()
         $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $('#panel_inicio').load("class/altas/modifica_cosechadora.php", {id: id});            
         

              
        })
      })

 </script>