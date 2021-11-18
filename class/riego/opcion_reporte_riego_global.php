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

 $sqlcaudalimetro = "SELECT
                    tb_caudalimetro.id_caudalimetro AS id,
                    tb_caudalimetro.nombre AS caudalimetro
                    FROM
                    tb_caudalimetro
                    WHERE
                    tb_caudalimetro.id_finca = '$id_finca_usuario'
                    ORDER BY
                    caudalimetro ASC";
 $rscaudalimetro = mysqli_query($conexion, $sqlcaudalimetro); 
?>

<form class="form-horizontal" method="post" action="class/riego/reporte_excel_global.php">

<h4><span class="label label-default">Reporte Riego Global</span></h4>
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

      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>

      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
         
          </div>
          
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="submit" class="btn btn-info" id="botonExcel1" aria-label="Left Align">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</button>
         <!--  <button type="button" class="btn btn-primary" onclick="reporte_riego()">Buscar</button>   -->
          </div>
          
        </div>
      </div>  
   </fieldset>
  </div> 

 </div>  
  



 </div>
  
</form>

<div id="div_reporte"></div>

<script type="text/javascript">

  $(document).ready(function () {
      
   // document.getElementById("botonExcel1").style.visibility = "hidden";

   })

</script>
