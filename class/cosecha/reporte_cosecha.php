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
<th>Remito</th>
<th>CIU</th>
<th>Variedad</th>
<th>Destino</th>
<th>Kilos</th>
<th>Ver</th>
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
        $tipo_cosecha=$_POST['tipo_cosecha'];
        $transporte=$_POST['transporte'];
        $variedad=$_POST['variedad'];

        $consulta_tipo_cosecha = "";
        $consulta_variedad = "";
        $consulta_transporte = "";


        if ($tipo_cosecha != '0') {

          if ($tipo_cosecha == '1') { //MECANICA
            $consulta_tipo_cosecha = "AND tb_cosecha.id_cosechadora NOT LIKE '0' ";
          }

          if ($tipo_cosecha == '2') { // MANUAL
            $consulta_tipo_cosecha = "AND tb_cosecha.id_cosechadora = '0' ";
          }
        }
        if ($variedad != "") {
        $consulta_variedad = "AND tb_cuartel.id_variedad = '$variedad' ";
        }
        if ($transporte != "") {
        $consulta_transporte = "AND tb_cosecha.id_transporte = '$transporte' ";
        }


$sqlpendiente = "SELECT
              DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
              tb_cosecha.ciu AS ciu,
              tb_cosecha.remito AS remito,
              tb_cosecha.destino AS destino,
              tb_cosecha.kilos AS kilos,
              tb_cosecha.id_global AS id_global,
              tb_variedad.nombre as variedad
              FROM
              tb_cosecha
              LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_cosecha.id_cuartel
              LEFT JOIN tb_variedad ON tb_cuartel.id_variedad = tb_variedad.id_variedad
              WHERE
              tb_cosecha.pendiente = '1' AND
              tb_cosecha.id_finca = '$id_finca_usuario' AND tb_cosecha.fecha BETWEEN '$desde' AND '$hasta' $consulta_tipo_cosecha$consulta_variedad$consulta_transporte
              GROUP BY
              tb_cosecha.id_global
              ORDER BY
              ciu ASC
              ";

$rspendiente = mysqli_query($conexion, $sqlpendiente);
$cantidad =  mysqli_num_rows($rspendiente);
if ($cantidad > 0) { // si existen pendiente con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rspendiente)){
$fecha=utf8_encode($datos['fecha']);
$remito=utf8_encode($datos['remito']);
$ciu=$datos['ciu'];
$destino=utf8_encode($datos['destino']);
$variedad=utf8_encode($datos['variedad']);
$kilos=utf8_encode($datos['kilos']);
$id_global=utf8_encode($datos['id_global']);

echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$remito.'</td>
<td>'.$ciu.'</td>
<td>'.$variedad.'</td>
<td>'.$destino.'</td>
<td>'.$kilos.'</td>
<td><button type="button" name="'.$id_global.'_ver" id="ver_pendiente" class="ver_riego ver_riego-default ver_riego-xs">
    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
    </button></td>
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
        $('.ver_riego-default').click(function() {

        var parte = $(this).attr('name')
        var parte = parte.substring(0,14)
        
         $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');        
         $("#div_reporte").load("class/cosecha/modal_reporte.php", {parte:parte});
          
        })
      })

$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })

</script>
