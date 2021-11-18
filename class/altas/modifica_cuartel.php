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

 $id=$_REQUEST['id'];

 $sqlcuarteles = "SELECT
                tb_finca.nombre as finca,
                tb_finca.id_finca as id_finca,
                tb_cuartel.id_cuartel as id_cuartel,
                tb_cuartel.nombre as cuartel,
                tb_variedad.nombre as variedad,
                tb_variedad.id_variedad as id_variedad,
                tb_cuartel.has as has,
                tb_cuartel.ano as ano,
                tb_conduccion.nombre as conduccion,
                tb_conduccion.id_conduccion as id_conduccion,
                tb_riego.nombre as riego,
                tb_riego.id_riego as id_riego,
                tb_cuartel.id_super AS id_super,
                tb_cuartel.distancia as distancia,
                tb_cuartel.hileras as hileras,
                IF(tb_cuartel.malla = '0', '','checked') as malla
                FROM
                tb_cuartel
                LEFT JOIN tb_finca ON tb_cuartel.id_finca = tb_finca.id_finca
                LEFT JOIN tb_variedad ON tb_cuartel.id_variedad = tb_variedad.id_variedad
                LEFT JOIN tb_conduccion ON tb_conduccion.id_conduccion = tb_cuartel.id_conduccion
                LEFT JOIN tb_riego ON tb_riego.id_riego = tb_cuartel.id_riego
                WHERE
                tb_cuartel.id_cuartel = '$id'";
$rscuarteles = mysqli_query($conexion, $sqlcuarteles);

$cantidad =  mysqli_num_rows($rscuarteles);

if ($cantidad > 0) { // si existen cuarteles con de esa cuarteles se muestran, de lo contrario queda en blanco  

while ($datos = mysqli_fetch_array($rscuarteles)){
$finca=utf8_encode($datos['finca']);
$id_finca=utf8_encode($datos['id_finca']);
$cuartel=utf8_encode($datos['cuartel']);
$id_cuartel=utf8_encode($datos['id_cuartel']);
$variedad=utf8_encode($datos['variedad']);
$id_variedad=utf8_encode($datos['id_variedad']);
$has=utf8_encode($datos['has']);
$ano=utf8_encode($datos['ano']);
$conduccion=utf8_encode($datos['conduccion']);
$id_conduccion=utf8_encode($datos['id_conduccion']);
$riego=utf8_encode($datos['riego']);
$id_riego=utf8_encode($datos['id_riego']);
$id_super=utf8_encode($datos['id_super']);
$distancia=utf8_encode($datos['distancia']);
$malla=utf8_encode($datos['malla']);
$hileras=$datos['hileras'];

}   
}


 ?>
<input type="hidden" class="form-control" value="<?php echo $id; ?>" id="id_modifica" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_cuarteles()">
 
 <h4><span class="label label-default">Modifica Cuartel</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">N°-Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $cuartel; ?>" autocomplete="off" id="alta_cuartel" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Finca</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_finca" required>   
              <option value="<?php echo $id_finca; ?>"><?php echo $finca; ?></option>
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
              <option value="<?php echo $id_variedad; ?>"><?php echo $variedad; ?></option>
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
              <option value="<?php echo $id_riego; ?>"><?php echo $riego; ?></option>
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
              <option value="<?php echo $id_conduccion; ?>"><?php echo $conduccion; ?></option>
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
          <input type="text" class="form-control" value="<?php echo $ano; ?>" autocomplete="off" id="alta_año" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-5">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Dist.</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $distancia; ?>" autocomplete="off" id="alta_distancia" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-2"></div>
      <div class="col-lg-4">      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Has</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" value="<?php echo $has; ?>" autocomplete="off" id="alta_has" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      <div class="col-lg-6">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-6 control-label">ID Mapeo satelital</label>
        <div class="col-lg-6">
          <input type="text" class="form-control" value="<?php echo $id_super; ?>" autocomplete="off" id="alta_mapeo" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      </div>
      
      <div class="col-lg-2"></div>
      <div class="col-lg-6">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Malla antigranizo</label>
        <div class="col-lg-8">
          <div align="left">
          <input class="form-control" type="checkbox" id="alta_malla" <?php echo $malla; ?>>
          </div>
        </div>
      </div>
      </div>
      <div class="col-lg-4">
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-4 control-label">Hileras</label>
        <div class="col-lg-8">
          <div align="left">
          <input type="text" class="form-control" value="<?php echo $hileras; ?>" autocomplete="off" id="alta_hileras" aria-describedby="basic-addon1" required autofocus="">
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
          <button type="button"   class="btn btn-default" onclick='llama_alta_cuartel()' aria-label="Left Align">Salir 
            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
            </button>
          <button type="submit" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
   </fieldset>
 </div>
 <div class="col-lg-7">
   <fieldset>

   </fieldset>
  </div> 
 </div>  
 </div>
</form>

<script type="text/javascript">

  $(document).ready(function () {
  
    
  // $('#alta_has').mask("##.00", {reverse: true});
  $('#alta_año').mask("0000", {reverse: true});
 
    
  });

  </script>