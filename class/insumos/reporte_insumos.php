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
<table class="table table-hover ">
<thead>
<tr style="height:5px">
<th>Insumo</th>
<th>Saldo</th>
<th>Unidad</th>
<th>Principio activo</th>
<th>% Concentración</th>
</tr>
</thead>
<tbody>
<?php
$insumo=$_POST['insumo'];
$consulta_insumos = "";
if ($insumo != "") {
$consulta_insumos = "AND tb_consumo_insumos_".$deposito.".id_insumo = '$insumo'";
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
$sqlcuarteles = "SELECT
tb_consumo_insumos_".$deposito.".id_consumo_insumos as id,
tb_insumo.nombre_comercial as insumo,
FORMAT(tb_consumo_insumos_".$deposito.".saldo, 2) as saldo,
tb_unidad.nombre as unidad,
tb_insumo.principio_activo as principio,
tb_insumo.concentracion as concentracion
FROM
tb_consumo_insumos_".$deposito."
INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
WHERE
tb_consumo_insumos_".$deposito.".id_consumo_insumos IN ((SELECT MAX(tb_consumo_insumos_".$deposito.".id_consumo_insumos ) FROM tb_consumo_insumos_".$deposito." GROUP BY tb_consumo_insumos_".$deposito.".id_insumo)) $consulta_insumos
ORDER BY
insumo asc
";
$rscuarteles = mysqli_query($conexion, $sqlcuarteles);
$cantidad =  mysqli_num_rows($rscuarteles);
if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rscuarteles)){
$insumo=utf8_encode($datos['insumo']);
$saldo=utf8_encode($datos['saldo']);
$unidad=utf8_decode($datos['unidad']);
$principio=utf8_decode($datos['principio']);
$concentracion=utf8_encode($datos['concentracion']);
echo '
<tr>
<td>'.$insumo.'</td>
<td>'.$saldo.'</td>
<td>'.$unidad.'</td>
<td>'.$principio.'</td>
<td>'.$concentracion.'</td>
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