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

 $sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as finca
              FROM
              tb_finca
              WHERE
              tb_finca.id_finca = '$id_finca_usuario'";
 $rsfinca = mysqli_query($conexion, $sqlfinca);

 $sqlpuesto = "SELECT
              tb_puesto.id_puesto as id_puesto,
              tb_puesto.nombre as puesto
              FROM
              tb_puesto";
 $rspuesto = mysqli_query($conexion, $sqlpuesto);  


 ?>
<input type="hidden" class="form-control" value="" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_personal()">
 
 <h4><span class="label label-default">Alta Personal</span></h4> 
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
        <label for="inputPassword" class="col-lg-3 control-label">Apellido</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_apellido" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Fecha nacimiento</label>
        <div class="col-lg-9">
          <input type="date" class="form-control" id="alta_nacimiento" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label">Fecha ingreso</label>
        <div class="col-lg-9">
          <input type="date" class="form-control" id="alta_ingreso" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Puesto</label>
        <div class="col-lg-9">
          <select class="form-control" id="alta_puesto" required>   
              <option value=""></option>
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
        <label  class="col-lg-3 control-label">Finca</label>
        <div class="col-lg-9">
          <select class="form-control" id="alta_finca" required>   
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
        <label for="inputPassword" class="col-lg-3 control-label">Personal eventual</label>
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

      <div class="panel-body" id="Panel1" style="height:380px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Nombre</th>
            <th>Fecha Nac.</th>
            <th>Fecha Ing.</th>
            <th>Puesto</th>
            <th>Eventual</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
              }

              $sqlpersonal = "SELECT
                              tb_finca.nombre as finca,
                              CONCAT(tb_personal.apellido, ', ', tb_personal.nombre) as nombre,
                              tb_personal.id_personal as idpersonal,
                              DATE_FORMAT(tb_personal.nacimiento, '%d/%m/%y') as nac, 
                              DATE_FORMAT(tb_personal.ingreso, '%d/%m/%y') as ing,
                              tb_puesto.nombre as puesto,
                              IF(tb_personal.eventual = '0', 'No','Si') as eventual
                              FROM
                              tb_personal
                              INNER JOIN tb_finca ON tb_personal.id_finca = tb_finca.id_finca
                              INNER JOIN tb_puesto ON tb_puesto.id_puesto = tb_personal.id_puesto
                              WHERE
                              tb_personal.id_finca = '$id_finca_usuario'
                              ORDER BY
                              nombre ASC";
              $rspersonal = mysqli_query($conexion, $sqlpersonal);
              
              $cantidad =  mysqli_num_rows($rspersonal);

              if ($cantidad > 0) { // si existen personal con de esa personal se muestran, de lo contrario queda en blanco  

              $contador = 0;    
             
              while ($datos = mysqli_fetch_array($rspersonal)){
              $finca=utf8_encode($datos['finca']);
              $nombre=utf8_encode($datos['nombre']);
              $nac=utf8_encode($datos['nac']);
              $ing=utf8_encode($datos['ing']);
              $puesto=utf8_encode($datos['puesto']);
              $eventual=utf8_encode($datos['eventual']);
              $idpersonal=utf8_encode($datos['idpersonal']);

              $contador = $contador + 1;
              
              echo '

              <tr>
                <td>'.$nombre.'</td>
                <td>'.$nac.'</td>
                <td>'.$ing.'</td>
                <td>'.$puesto.'</td>
                <td>'.$eventual.'</td>
                <td><button class="ver_riego ver_riego-default ver_riego-xs" id="mod_personal_'.$contador.'" value="'.$idpersonal.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay personal cargado.";
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
         $('#panel_inicio').load("class/altas/modifica_personal.php", {id: id});            
         

              
        })
      })

 </script>