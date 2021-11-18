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

 $sqlinsumo = "SELECT
                tb_insumo.id_insumo as id,
                tb_insumo.nombre_comercial AS insumo
                FROM
                tb_insumo
                ORDER BY
                insumo ASC";
 $rsinsumo = mysqli_query($conexion, $sqlinsumo); 

  

 ?>

<form class="form-horizontal" method="post" action="class/parte_diario/reporte_excel_insumos_pd.php">
 
<h4><span class="label label-default">Reporte Insumos por PD</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-5">
          <input type="date" class="form-control" name="post_desde" id="desde" aria-describedby="basic-addon1" required autofocus="">
        </div>
        <div class="col-lg-5">
          <input type="date" class="form-control" name="post_hasta" id="hasta" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Finca</label>
        <div class="col-lg-10">
          <select class="form-control" name="post_finca" id="finca">   
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
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>
      
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Labor</label>
        <div class="col-lg-10">
          <select class="form-control" name="post_labor" id="labor">   
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
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumo</label>
        <div class="col-lg-10">
          <select class="form-control" name="post_insumo" id="insumo">   
              <option value=""></option>
              <?php
              while ($sql_insumo = mysqli_fetch_array($rsinsumo)){
                $idinsumo= $sql_insumo['id'];
                $insumo = $sql_insumo['insumo'];

                echo utf8_encode('<option value='.$idinsumo.'>'.$insumo.'</option>');
                
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
          <button type="submit" class="btn btn-info" id="botonExcel1" aria-label="Left Align">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</button> 
          <button type="button" class="btn btn-primary" onclick="reporte_diario_insumo()">Buscar</button>  
          </div>
          
        </div>
      </div>  
   </fieldset>
  </div> 

 </div>  
  



 </div>

<div id="div_reporte"></div>
  
</form>


<script type="text/javascript">

  $(document).ready(function () {
      
   document.getElementById("botonExcel1").style.visibility = "hidden";

   })

</script>