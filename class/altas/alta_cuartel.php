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
$sqlconduccion = "SELECT
              tb_conduccion.id_conduccion as id_conduccion,
              tb_conduccion.nombre as nombre
              FROM tb_conduccion";
$rsconduccion = mysqli_query($conexion, $sqlconduccion); 

 $sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as nombre
              FROM tb_finca
              WHERE
              tb_finca.id_finca = '$id_finca_usuario'";
 $rsfinca = mysqli_query($conexion, $sqlfinca); 

 $sqlriego = "SELECT
              tb_riego.id_riego as id_riego,
              tb_riego.nombre as nombre
              FROM tb_riego";
 $rsriego = mysqli_query($conexion, $sqlriego);

 $sqlvariedad = "SELECT
              tb_variedad.id_variedad as id_variedad,
              tb_variedad.nombre as nombre
              FROM tb_variedad
              ORDER BY
              nombre ASC";
 $rsvariedad = mysqli_query($conexion, $sqlvariedad);     


 ?>
<input type="hidden" class="form-control" value="" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_cuarteles()">
 
 <h4><span class="label label-default">Alta Cuarteles</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">N°-Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_cuartel" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Finca</label>
        <div class="col-lg-10">
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
        <label  class="col-lg-2 control-label">Variedad</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_variedad" required>   
              <option value=""></option>
              <?php
              while ($sql_variedad = mysqli_fetch_array($rsvariedad)){
                $idvariedad= $sql_variedad['id_variedad'];
                $variedad = $sql_variedad['nombre'];

                echo utf8_encode('<option value='.$idvariedad.'>'.$variedad.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Tipo de riego</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_riego" required>   
              <option value=""></option>
              <?php
              while ($sql_riego = mysqli_fetch_array($rsriego)){
                $idriego= $sql_riego['id_riego'];
                $riego = $sql_riego['nombre'];

                echo utf8_encode('<option value='.$idriego.'>'.$riego.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Tipo de conducción</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_conduccion" required>   
              <option value=""></option>
              <?php
              while ($sql_conduccion = mysqli_fetch_array($rsconduccion)){
                $idconduccion= $sql_conduccion['id_conduccion'];
                $conduccion = $sql_conduccion['nombre'];

                echo utf8_encode('<option value='.$idconduccion.'>'.$conduccion.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="col-lg-2"></div>
      <div class="col-lg-5">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Año</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_año" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-5">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Dist.</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_distancia" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-2"></div>
      <div class="col-lg-4">      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Has</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" autocomplete="off" id="alta_has" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-6">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-6 control-label">ID Mapeo satelital</label>
        <div class="col-lg-6">
          <input type="text" class="form-control" autocomplete="off" id="alta_mapeo" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>

      <div class="col-lg-2"></div>
      <div class="col-lg-6">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Malla antigranizo</label>
        <div class="col-lg-8">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_malla">
          </div>
        </div>
      </div>
      </div>
      <div class="col-lg-4">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Hileras</label>
        <div class="col-lg-8">
          <div align="left">
          <input type="text" class="form-control" autocomplete="off" id="alta_hileras" aria-describedby="basic-addon1" required autofocus="">
          </div>
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

      <div class="panel-body" id="Panel1" style="height:450px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Cuartel</th>
            <th>Var</th>
            <th>Has</th>
            <th>Año</th>
            <th>Hil</th>
            <th>Riego</th>
            <th>ID_SUPER</th>
            <th>Dist.</th>
            <th>Malla</th>
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
                                tb_cuartel.id_cuartel as id_cuartel,
                                CAST(tb_cuartel.nombre AS SIGNED) as orden_cuartel,
                                tb_finca.nombre as finca,
                                tb_cuartel.nombre as cuartel,
                                LEFT(tb_variedad.nombre, 4) as variedad,
                                tb_cuartel.has as has,
                                tb_cuartel.ano as ano,
                                tb_cuartel.hileras as hileras,
                                tb_riego.nombre as riego,
                                tb_cuartel.id_super AS id_super,
                                tb_cuartel.distancia as distancia,
                                IF(tb_cuartel.malla = '1', 'Si', 'No') as malla
                                FROM
                                tb_cuartel
                                LEFT JOIN tb_finca ON tb_cuartel.id_finca = tb_finca.id_finca
                                LEFT JOIN tb_variedad ON tb_cuartel.id_variedad = tb_variedad.id_variedad
                                LEFT JOIN tb_conduccion ON tb_conduccion.id_conduccion = tb_cuartel.id_conduccion
                                LEFT JOIN tb_riego ON tb_riego.id_riego = tb_cuartel.id_riego
                                WHERE
                                tb_cuartel.id_finca = '$id_finca_usuario'
                                ORDER BY
                                orden_cuartel ASC";
              $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
              
              $cantidad =  mysqli_num_rows($rscuarteles);

              if ($cantidad > 0) { // si existen cuarteles con de esa cuarteles se muestran, de lo contrario queda en blanco  
             
              $contador = 0; 

              while ($datos = mysqli_fetch_array($rscuarteles)){
              $finca=utf8_encode($datos['finca']);
              $cuartel=utf8_encode($datos['cuartel']);
              $id_cuartel=utf8_encode($datos['id_cuartel']);
              $variedad=utf8_encode($datos['variedad']);
              $has=utf8_encode($datos['has']);
              $ano=utf8_encode($datos['ano']);
              $hileras=utf8_encode($datos['hileras']);
              $riego=utf8_encode($datos['riego']);
              $id_super=utf8_encode($datos['id_super']);
              $distancia=utf8_encode($datos['distancia']);
              $malla=utf8_encode($datos['malla']);

              $contador = $contador + 1;
              
              echo '

              <tr>
                <td>'.$cuartel.'</td>
                <td>'.$variedad.'</td>
                <td>'.$has.'</td>
                <td>'.$ano.'</td>
                <td>'.$hileras.'</td>
                <td>'.$riego.'</td>
                <td>'.$id_super.'</td>
                <td>'.$distancia.'</td>
                <td>'.$malla.'</td>
                <td><button class="ver_riego ver_riego-default ver_riego-xs" id="mod_cuartel_'.$contador.'" value="'.$id_cuartel.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay cuarteles cargados.";
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
  $('#alta_año').mask("0000", {reverse: true});
 
    
  });

  $(function() {
        $('.ver_riego-default').click(function() {

         var id = $(this).val()
         $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $('#panel_inicio').load("class/altas/modifica_cuartel.php", {id: id});            
        
           
        })
      })

  </script>