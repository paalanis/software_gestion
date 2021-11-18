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

	$variedad=utf8_decode($_REQUEST['alta_variedad']);
	$tipo=utf8_decode($_REQUEST['alta_tipo']);
	$origen=utf8_decode($_REQUEST['alta_origen']);
	

		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_variedad (nombre, tipo, origen)
	VALUES (lower('$variedad'), lower('$tipo'), lower('$origen'))";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');

	echo json_encode($array);



} //fin else



?>