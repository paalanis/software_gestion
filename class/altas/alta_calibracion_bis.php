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
   
	$array=array('success'=>'false');

	echo json_encode($array);
	 
	exit();

}else{

	$fecha=utf8_decode($_REQUEST['calibracion_fecha']);
	$nombre=utf8_decode($_REQUEST['calibracion_nombre']);
	$id_aforador=utf8_decode($_REQUEST['calibracion_aforador']);
	$a=utf8_decode($_REQUEST['calibracion_a']);
	$b=utf8_decode($_REQUEST['calibracion_b']);
	

		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_calibra_formula (id_aforador, fecha, nombre, valor_a, valor_b)
	VALUES ('$id_aforador', '$fecha', lower('$nombre'), '$a', '$b')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');

	echo json_encode($array);



} //fin else



?>