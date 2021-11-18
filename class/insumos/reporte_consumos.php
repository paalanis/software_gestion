<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$deposito=$_SESSION['deposito'];
?>
<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover" id="Exportar_a_Excel1">
<thead>
<tr style="height:5px">
<th>Insumo</th>
<th>Tipo</th>
<th>Total</th>
<th>Unidad</th>
<th>% Conc</th>
<th>Resultado</th>
</tr>
</thead>
<tbody>
<?php
$insumo=$_POST['insumo'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];
$consulta_insumos = "";
if ($insumo != "") {
$consulta_insumos = "AND tb_consumo_insumos_".$deposito.".id_insumo = '$insumo'";
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
$sqlconsumo = "SELECT
tb_insumo.nombre_comercial AS insumo,
tb_tipo_insumo.nombre AS tipo,
tb_unidad.nombre AS unidad,
Sum(tb_consumo_insumos_".$deposito.".egreso) AS egreso,
tb_insumo.concentracion as porcentaje,
FORMAT(Sum(tb_consumo_insumos_".$deposito.".egreso) * tb_insumo.concentracion /100,2) as resultado
FROM
tb_consumo_insumos_".$deposito."
LEFT JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
LEFT JOIN tb_tipo_insumo ON tb_insumo.id_tipo_insumo = tb_tipo_insumo.id_tipo_insumo
LEFT JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
WHERE
tb_consumo_insumos_".$deposito.".egreso NOT LIKE 0 AND
tb_consumo_insumos_".$deposito.".fecha BETWEEN '$desde' AND '$hasta' $consulta_insumos
GROUP BY
tb_consumo_insumos_".$deposito.".id_insumo
ORDER BY
insumo ASC";


$rsconsumo = mysqli_query($conexion, $sqlconsumo);
$cantidad =  mysqli_num_rows($rsconsumo);
if ($cantidad > 0) { // si existen consumo con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsconsumo)){
$insumo=utf8_encode($datos['insumo']);
$tipo=utf8_encode($datos['tipo']);
$egreso=$datos['egreso'];
$unidad=utf8_encode($datos['unidad']);
$porcentaje=utf8_encode($datos['porcentaje']);
$resultado=utf8_encode($datos['resultado']);
echo '
<tr>
<td>'.$insumo.'</td>
<td>'.$tipo.'</td>
<td>'.$egreso.'</td>
<td>'.$unidad.'</td>
<td>'.$porcentaje.'</td>
<td>'.$resultado.'</td>
</tr>
';
} 
?>

<script type="text/javascript">
  document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
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
<script type="text/javascript">
$(function() {
        $('.form-control').change(function() {

        	document.getElementById("botonExcel1").style.visibility = "hidden";
        	
        })
      })
</script>