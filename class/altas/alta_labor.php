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


 ?>
<input type="hidden" class="form-control" value="" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_labores()">

 <h4><span class="label label-default">Alta Labores</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_labor" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Tipo de labor</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_tipo" required>   
              <option value=""></option>
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
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
      
      
   </fieldset>
 
 </div>
 <div class="col-lg-7">
 
   <fieldset>

     <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:230px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px; position: relative">
            <th style="position: relative">Labor</th>
            <th style="position: relative">Tipo</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqllabor = "SELECT
                            tb_labor.id_labor as id_labor,
                            tb_labor.nombre as labor,
                            tb_tipo_labor.nombre as tipo
                            FROM
                            tb_labor
                            LEFT JOIN tb_tipo_labor ON tb_labor.id_tipo_labor = tb_tipo_labor.id_tipo_labor
                            ORDER BY
                            labor ASC
                            ";
              $rslabor = mysqli_query($conexion, $sqllabor);
              
              $cantidad =  mysqli_num_rows($rslabor);

              if ($cantidad > 0) { // si existen labor con de esa labor se muestran, de lo contrario queda en blanco  
             
              $contador = 0;   

              while ($datos = mysqli_fetch_array($rslabor)){
              $labor=utf8_encode($datos['labor']);
              $id_labor=utf8_encode($datos['id_labor']);
              $tipo=utf8_encode($datos['tipo']);

              $contador = $contador + 1;
                            
              echo '

              <tr>
                <td>'.$labor.'</td>
                <td>'.$tipo.'</td>
                <td><button class="ver_riego ver_riego-default ver_riego-xs" id="mod_labor_'.$contador.'" value="'.$id_labor.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
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
<script type="text/javascript">
  $(function() {
        $('.ver_riego-default').click(function() {

         var id = $(this).val()
         $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $('#panel_inicio').load("class/altas/modifica_labor.php", {id: id});            
         

              
        })
      })

  </script>