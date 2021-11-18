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
 $sqltransporte = "SELECT
              tb_transporte.id_transporte as id_transporte,
              tb_transporte.razon_social as transporte
              FROM
              tb_transporte
              ORDER BY
              transporte ASC";
 $rstransporte = mysqli_query($conexion, $sqltransporte); 

 $sqlvariedad = "SELECT
                tb_variedad.id_variedad as id_variedad,
                tb_variedad.nombre as variedad
                FROM
                tb_variedad
                ORDER BY
                variedad ASC
                ";
 $rsvariedad = mysqli_query($conexion, $sqlvariedad);

 $sqlpendientes = "SELECT
                tb_finca.nombre as finca
                FROM
                tb_cosecha
                INNER JOIN tb_finca ON tb_cosecha.id_finca = tb_finca.id_finca
                WHERE
                tb_cosecha.pendiente = '0'
                GROUP BY
                tb_finca.nombre";
 $rspendientes = mysqli_query($conexion, $sqlpendientes); 

?>

<form class="form-horizontal" method="post" action="class/cosecha/reporte_excel_jor.php">
 
<h4><span class="label label-default">Reporte jornales diarios</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-5">
          <input type="date" class="form-control" id="desde" name='post_desde' aria-describedby="basic-addon1" required autofocus="">
        </div>
        <div class="col-lg-5">
          <input type="date" class="form-control" id="hasta" name='post_hasta' aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      

   </fieldset>
   <div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Importante!</strong> La descarga del reporte incluye todas las fincas.
   </div>
   <div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Importante!</strong> Hay remitos pendientes en... No se incluirán en el reporte.
    <?php
      while ($sql_pendientes = mysqli_fetch_array($rspendientes)){
        $finca = $sql_pendientes['finca'];
        echo utf8_encode('<option>'.$finca.'</option>');
       }
      ?>
   </div> 
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
          <button type="button" class="btn btn-primary" onclick="reporte_de_jornales()">Buscar</button>  
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