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

	$nombre=utf8_decode($_REQUEST['alta_caudalimetro']);
	$finca=$_REQUEST['alta_finca'];
	$caracteristicas=utf8_decode($_REQUEST['alta_caracteristicas']);
	$dilucion=utf8_decode($_REQUEST['alta_dilucion']);
	$coef=utf8_decode($_REQUEST['alta_coef']);
		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_caudalimetro (id_finca, nombre, caracteristicas, dilucion, coef)
	VALUES ('$finca', lower('$nombre'), lower('$caracteristicas'), '$dilucion', '$coef')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);



} //fin else



?>