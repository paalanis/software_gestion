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
?>

<form class="form-horizontal" method="post" action="class/insumos/reporte_excel_existencias.php">

<h4><span class="label label-default">Reporte de Existencias</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
        <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumos</label>
        <div class="col-lg-10">
          <select class="form-control" name="post_insumo" id="insumo">   
              <option value=""></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['nombre'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
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
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general"></div>
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="submit" class="btn btn-info" id="botonExcel1" aria-label="Left Align">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</button>
          <button type="button" class="btn btn-primary" onclick="reporte_existencias()">Buscar</button>  
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
