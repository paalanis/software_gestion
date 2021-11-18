<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../../index.php");
}
$deposito=$_SESSION['deposito'];
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
$sqllabores = "SELECT
              tb_labor.id_labor as id,
              tb_labor.nombre as nombre 
              FROM
              tb_labor
              ORDER BY
              tb_labor.nombre ASC";
$rslabores = mysqli_query($conexion, $sqllabores); 
$sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as finca
              FROM
              tb_finca
              WHERE
              tb_finca.id_finca = '$id_finca_usuario'";
$rsfinca = mysqli_query($conexion, $sqlfinca); 
$sqlinsumos = "SELECT
                tb_insumo.id_insumo as id,
                CONCAT(tb_insumo.nombre_comercial, ' - ',tb_unidad.nombre) as nombre
                FROM
                tb_insumo
                INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
                ORDER BY
                tb_insumo.nombre_comercial ASC
                ";
$rsinsumos = mysqli_query($conexion, $sqlinsumos); 

mysqli_select_db($conexion,'pernot_ricard');
$sql = "DELETE FROM tb_consumo_insumos_".$deposito." WHERE estado = '0'";
mysqli_query($conexion,$sql);
echo '<input class="form-control" id="deposito" value="'.$deposito.'"  type="hidden">';
?>
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); carga_diario()">
 <h4><span class="label label-default">Parte Diario</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-4">
          <input type="date" class="form-control" id="diario_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1" required>
        </div>
        <label  class="col-lg-1 control-label">Finca</label>
        <div class="col-lg-5">
            <select class="form-control" id="diario_finca" required>   
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
        <label  class="col-lg-2 control-label">Personal</label>
        <div class="col-lg-10" id="div_personal">
          <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Propio</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="propio">
          </div>
        </div>
        <label for="inputPassword" class="col-lg-2 control-label">Eventual</label>
        <div class="col-lg-2">
          <div align="left">
          <input class="form-control" type="checkbox" id="eventual">
          </div>
        </div>
        <div class="col-lg-2">
          <div align="right">
          <button class="btn btn-default btn-sm" type="button" onclick="busca_personal()">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</button>
          </div>
        </div>
        </div>       
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Labor</label>
        <div class="col-lg-5">
          <select class="form-control" id="diario_labor" required>   
              <option value=""></option>
              <?php
              while ($sql_labores = mysqli_fetch_array($rslabores)){
                $idlabores= $sql_labores['id'];
                $labores = $sql_labores['nombre'];
                echo utf8_encode('<option value='.$idlabores.'>'.$labores.'</option>');
              }
              ?>
            </select>
        </div>
        <label for="textArea" class="col-lg-1 control-label">Obs.</label>
        <div class="col-lg-4">
          <textarea class="form-control" placeholder="Detalle de tarea" autocomplete="off" rows="1" id="diario_obs_labor"></textarea>
         </div>
      </div>
      <label>Cuarteles</label>
      <div class="form-group form-group-sm">
        <div class="col-lg-12" id="div_cuarteles">
        
        </div>
      </div>
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>
      
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumos</label>
        <div class="col-lg-6">
          <select class="form-control" id="diario_insumo" onchange="saldo()">   
              <option value=""></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['nombre'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
              }
              ?>
            </select>
            <div id="div_saldo"></div>
        </div>
        <div class="col-lg-4">
          <div class="input-group input-group-sm">
            <input class="form-control" id="diario_insumo_cantidad" autocomplete="off" type="text">
            <input class="form-control" id="tamano_cuadro" value="150" type="hidden">
            <span class="input-group-btn">
              <button class="btn btn-default" id='boton_insumo' type="button" onclick="carga_insumo()">Ok</button>
            </span>
          </div>
        </div>
      </div>
      <!-- <div class="form-group form-group-sm">
        <label for="text" class="col-lg-2 control-label">Insumos cargados</label> -->
        <div id="div_insumos_cargados">
        </div>
     <!--  </div> -->
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Horas trabajadas</label>
        <div class="col-lg-3">
        <input class="form-control" type="text" autocomplete="off" id="diario_horas" required>  
        </div>
        <label for="textArea" class="col-lg-2 control-label">Observación</label>
        <div class="col-lg-5">
          <textarea class="form-control" placeholder="Detalle parte" autocomplete="off" rows="1" id="diario_obs_general"></textarea>
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
 </div>  
 </div>
</form>
<script type="text/javascript">
 $(document).ready(function () {
  var variedad = ""
  var panel = "200"
  var finca = $('#diario_finca').val();
  $("#div_cuarteles").html('<div class="text-center"><div class="loadingsm"></div></div>');
  $("#div_cuarteles").load("class/parte_diario/cuarteles.php", {finca: finca, variedad: variedad, panel: panel});
  $('#diario_rendimiento').mask("##.00", {reverse: true});
  $('#diario_horas').mask("##.00", {reverse: true});
  $('#diario_insumo_cantidad').mask("##.00", {reverse: true});
  });
  </script>