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
date_default_timezone_set("America/Argentina/Mendoza");
$id_global = date("Ymdhis");
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
exit();
}else{
$fecha=$_REQUEST['ingreso_fecha'];
$insumo=$_REQUEST['ingreso_insumo'];
$cantidad=$_REQUEST['ingreso_cantidad'];
$obs=utf8_decode($_REQUEST['ingreso_obs']);

$sqlsaldo = "SELECT
tb_consumo_insumos_".$deposito.".id_consumo_insumos AS id,
tb_consumo_insumos_".$deposito.".saldo AS saldo
FROM
tb_consumo_insumos_".$deposito."
WHERE
tb_consumo_insumos_".$deposito.".id_insumo = '$insumo'
ORDER BY
tb_consumo_insumos_".$deposito.".id_consumo_insumos DESC
LIMIT 1";
$rssaldo = mysqli_query($conexion, $sqlsaldo);
$datos = mysqli_fetch_array($rssaldo);
$saldo=utf8_encode($datos['saldo']);

$saldo = $saldo + $cantidad;

mysqli_select_db($conexion,'pernot_ricard');
$sql = "INSERT INTO tb_consumo_insumos_".$deposito." (fecha, id_insumo, ingreso, id_deposito_origen, id_deposito_destino,
		id_parte_diario_global, obs, estado, saldo)
VALUES ('$fecha', '$insumo', '$cantidad', '0', '0', '$id_global', '$obs', '1', '$saldo')";
mysqli_query($conexion,$sql);    


$array=array('success'=>'true');

echo json_encode($array);
} //fin else
?>