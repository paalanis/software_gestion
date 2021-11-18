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
$fecha=$_REQUEST['traspaso_fecha'];

$deposito_e=$_REQUEST['n_deposito_e'];
$id_deposito_e=$_REQUEST['deposito_e'];
$deposito_i=$_REQUEST['n_deposito_i'];
$id_deposito_i=$_REQUEST['deposito_i'];

$insumo=$_REQUEST['insumo_e'];
$cantidad=$_REQUEST['cantidad_e'];

$obs=utf8_decode($_REQUEST['traspaso_obs']);

//EGRESO DEL INSUMO LOCAL
//

$sqlsaldo_e = "SELECT
				tb_consumo_insumos_".$deposito_e.".id_consumo_insumos AS id,
				tb_consumo_insumos_".$deposito_e.".saldo AS saldo_e
				FROM
				tb_consumo_insumos_".$deposito_e."
				WHERE
				tb_consumo_insumos_".$deposito_e.".id_insumo = '$insumo'
				ORDER BY
				tb_consumo_insumos_".$deposito_e.".id_consumo_insumos DESC
				LIMIT 1";
$rssaldo_e= mysqli_query($conexion, $sqlsaldo_e);
$datos_e = mysqli_fetch_array($rssaldo_e);
$saldo_e=$datos_e['saldo_e'];
$saldo_e = $saldo_e - $cantidad;
mysqli_select_db($conexion,'pernot_ricard');
$sql = "INSERT INTO tb_consumo_insumos_".$deposito_e." (id_insumo, id_parte_diario_global, id_deposito_origen, id_deposito_destino, fecha, egreso, estado, obs, saldo)
VALUES ('$insumo', '$id_global', '0', '$id_deposito_i', '$fecha', '$cantidad', '1', '$obs', '$saldo_e')";
mysqli_query($conexion,$sql);    


//INGRESO DEL INSUMO EN EL DEPOSITO DE DESTINO


$sqlsaldo_i = "SELECT
			tb_consumo_insumos_".$deposito_i.".id_consumo_insumos AS id,
			tb_consumo_insumos_".$deposito_i.".saldo AS saldo_i
			FROM
			tb_consumo_insumos_".$deposito_i."
			WHERE
			tb_consumo_insumos_".$deposito_i.".id_insumo = '$insumo'
			ORDER BY
			tb_consumo_insumos_".$deposito_i.".id_consumo_insumos DESC
			LIMIT 1";
$rssaldo_i = mysqli_query($conexion, $sqlsaldo_i);
$cantidad_datos =  mysqli_num_rows($rssaldo_i);

if ($cantidad_datos > 0) {

$datos_i = mysqli_fetch_array($rssaldo_i);
$saldo_i=$datos_i['saldo_i'];

}else{
	$saldo_i=0;
}

$saldo_i = $saldo_i + $cantidad;

mysqli_select_db($conexion,'pernot_ricard');
$sql = "INSERT INTO tb_consumo_insumos_".$deposito_i." (fecha, id_insumo, id_parte_diario_global, id_deposito_origen, id_deposito_destino, ingreso, obs, estado, saldo)
VALUES ('$fecha', '$insumo', '$id_global', '$id_deposito_e', '0', '$cantidad', '$obs', '1', '$saldo_i')";
mysqli_query($conexion,$sql);    


$array=array('success'=>'true');
echo json_encode($array);
} //fin else
?>