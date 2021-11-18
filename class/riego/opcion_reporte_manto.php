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

 $sqlaforador = "SELECT
tb_aforador.id_aforador as id,
tb_aforador.nombre as aforador
FROM
tb_aforador
WHERE
tb_aforador.id_finca = '$id_finca_usuario'
ORDER BY
tb_aforador.nombre ASC";
 $rsaforador = mysqli_query($conexion, $sqlaforador); 
?>

<form class="form-horizontal" method="post" action="class/riego/reporte_excel_manto.php">

<h4><span class="label label-default">Reporte Riego manto</span></h4>
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
        <label  class="col-lg-2 control-label">Aforador</label>
        <div class="col-lg-10">
          <select class="form-control" id="aforador" name="post_afora">   
              <option value=""></option>
              <?php
              while ($sql_aforador = mysqli_fetch_array($rsaforador)){
                $idaforador= $sql_aforador['id'];
                $aforador = $sql_aforador['aforador'];

                echo utf8_encode('<option value='.$idaforador.'>'.$aforador.'</option>');
                
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
          <button type="button" class="btn btn-primary" onclick="reporte_riego_manto()">Buscar</button>  
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
      
   document.getElementById("botonExcel1").style.visibility = "hidden";

   })

</script>
