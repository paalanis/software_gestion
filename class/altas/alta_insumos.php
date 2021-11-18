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


 $sqlumedida = "SELECT
              tb_unidad.id_unidad as id_umedida,
              tb_unidad.nombre as nombre
              FROM tb_unidad";
 $rsumedida = mysqli_query($conexion, $sqlumedida);  

 $sqltipo_insumo = "SELECT
              tb_tipo_insumo.id_tipo_insumo as id_tipo_insumo,
              tb_tipo_insumo.nombre as nombre
              FROM tb_tipo_insumo";
 $rstipo_insumo = mysqli_query($conexion, $sqltipo_insumo);

 ?>
<input type="hidden" class="form-control" value="" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_insumos()">
 
 <h4><span class="label label-default">Alta Insumos</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-4">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Nombre comercial</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" id="alta_n_comercial" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Principio activo</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" id="alta_principio" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Concentración</label>
        <div class="col-lg-8">
          <div class="input-group input-group-sm">
          <input type="text" class="form-control" autocomplete="off" id="alta_concentracion" placeholder="Porcentaje de concentración" aria-describedby="basic-addon1" required autofocus>
          <span class="input-group-addon">%</span>
          </div>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-4 control-label">Unidad de medida</label>
        <div class="col-lg-8">
          <select class="form-control" id="alta_umedida" required>   
              <option value=""></option>
              <?php
              while ($sql_umedida = mysqli_fetch_array($rsumedida)){
                $idumedida= $sql_umedida['id_umedida'];
                $umedida = $sql_umedida['nombre'];

                echo utf8_encode('<option value='.$idumedida.'>'.$umedida.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-4 control-label">Tipo de insumo</label>
        <div class="col-lg-8">
          <select class="form-control" id="alta_tipo_insumo" required>   
              <option value=""></option>
              <?php
              while ($sql_tipo_insumo = mysqli_fetch_array($rstipo_insumo)){
                $idtipo_insumo= $sql_tipo_insumo['id_tipo_insumo'];
                $tipo_insumo = $sql_tipo_insumo['nombre'];

                echo utf8_encode('<option value='.$idtipo_insumo.'>'.$tipo_insumo.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Asociado a labor</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_asociado">
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
 <div class="col-lg-8">
   <fieldset>

      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:340px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Insumo</th>
            <th>N.Comercial</th>
            <th>% Conc.</th>
            <th>Tipo</th>
            <th>Unidad</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlinsumo = "SELECT
                            tb_insumo.id_insumo as id_insumo,
                            tb_insumo.nombre_comercial as insumo,
                            tb_insumo.principio_activo as principio,
                            tb_insumo.concentracion as concentracion,
                            tb_unidad.nombre as unidad,
                            tb_tipo_insumo.nombre as tipo_insumo
                            FROM
                            tb_insumo
                            LEFT JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
                            LEFT JOIN tb_tipo_insumo ON tb_tipo_insumo.id_tipo_insumo = tb_insumo.id_tipo_insumo
                            ORDER BY
                            insumo ASC";
              $rsinsumo = mysqli_query($conexion, $sqlinsumo);
              
              $cantidad =  mysqli_num_rows($rsinsumo);

              if ($cantidad > 0) { // si existen insumo con de esa insumo se muestran, de lo contrario queda en blanco  
             
              $contador = 0; 

              while ($datos = mysqli_fetch_array($rsinsumo)){
              $insumo=utf8_encode($datos['insumo']);
              $id_insumo=utf8_encode($datos['id_insumo']);
              $principio=utf8_encode($datos['principio']);
              $concentracion=utf8_encode($datos['concentracion']);
              $unidad=utf8_encode($datos['unidad']);
              $tipo_insumo=utf8_encode($datos['tipo_insumo']);

              $contador = $contador + 1;
              
              echo '

              <tr>
                <td>'.$insumo.'</td>
                <td>'.$principio.'</td>
                <td>'.$concentracion.'</td>
                <td>'.$tipo_insumo.'</td>
                <td>'.$unidad.'</td>
                <td><button class="ver_riego ver_riego-default ver_riego-xs" id="mod_insumo_'.$contador.'" value="'.$id_insumo.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay insumos cargados.";
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
    
  $('#alta_concentracion').mask("##.00", {reverse: true});
    
  });

  $(function() {
        $('.ver_riego-default').click(function() {

         var id = $(this).val()
         $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $('#panel_inicio').load("class/altas/modifica_insumos.php", {id: id});            
         

              
        })
      })

  </script>