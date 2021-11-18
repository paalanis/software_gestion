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
?>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-12">
   <fieldset>

<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover" id="Exportar_a_Excel1">
<thead>
<tr style="height:5px">
<th>Fecha</th>
<th>Finca</th>
<th>Jornales</th>
</tr>
</thead>
<tbody>
<?php

include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];


$sqljornales = "SELECT
                DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
                tb_finca.nombre as finca,
                Max(tb_cosecha.manual_t) as jornales
                FROM
                tb_cosecha
                LEFT JOIN tb_finca ON tb_finca.id_finca = tb_cosecha.id_finca
                WHERE
                tb_cosecha.pendiente = '1' AND
                tb_cosecha.id_cosechadora = '0' AND
                tb_cosecha.fecha BETWEEN '$desde' AND '$hasta'
                GROUP BY
                tb_cosecha.id_finca,
                tb_cosecha.fecha
                ORDER BY
                tb_cosecha.fecha ASC,
                tb_cosecha.id_finca ASC";
$rsjornales = mysqli_query($conexion, $sqljornales);


$cantidad =  mysqli_num_rows($rsjornales);
if ($cantidad > 0) { // si existen jornales con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsjornales)){
$fecha=utf8_encode($datos['fecha']);
$finca=utf8_encode($datos['finca']);
$jornales=$datos['jornales'];


echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$finca.'</td>
<td>'.$jornales.'</td>
</tr>
';
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
} 
}

?>
</tbody>
</table> 
<?php
if ($cantidad == 0){
echo "No hay registros";
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "hidden";
</script>
<?php
}
?>

</div>
</div>
      
   </fieldset>
 
 </div>
 <!-- <div class="col-lg-6">
 
   <fieldset>
      <div id="div_reporte"></div>
   
   </fieldset>
  </div>  -->
</div>  
</div>


<script type="text/javascript">


$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })

</script>
