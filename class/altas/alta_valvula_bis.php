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

	$nombre=utf8_decode($_REQUEST['alta_valvula']);
	$caudalimetro=$_REQUEST['alta_caudalimetro'];
	$cuartel=$_REQUEST['alta_asignar'];
	$has=$_REQUEST['alta_asignar_has'];
	
		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_valvula (id_caudalimetro, nombre, id_cuartel, has_asignadas)
	VALUES ('$caudalimetro', lower('$nombre'), '$cuartel', '$has')";
	mysqli_query($conexion,$sql);    


	$array=array('success'=>'true');
	echo json_encode($array);



} //fin else



?>