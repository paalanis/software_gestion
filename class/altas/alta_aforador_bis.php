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

	$aforador=utf8_decode($_REQUEST['alta_aforador']);
	$finca=utf8_decode($_REQUEST['alta_aforador_finca']);
	$detalle=utf8_decode($_REQUEST['alta_aforador_detalle']);
	

		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_aforador (nombre, id_finca, detalle)
	VALUES (lower('$aforador'), '$finca', lower('$detalle'))";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');

	echo json_encode($array);



} //fin else



?>