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

	$finca=utf8_decode($_REQUEST['alta_finca']);
	$localidad=utf8_decode($_REQUEST['alta_localidad']);
	$provincia=utf8_decode($_REQUEST['alta_provincia']);
	$has=$_REQUEST['alta_has'];
	

		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_finca (nombre, localidad, provincia, has)
	VALUES (lower('$finca'), lower('$localidad'), lower('$provincia'), '$has')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');

	echo json_encode($array);



} //fin else



?>