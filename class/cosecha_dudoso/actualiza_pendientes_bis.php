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
	
	$kilos=$_REQUEST['kilos'];
	$ciu=$_REQUEST['ciu'];
	$id_global=$_REQUEST['id_global'];
		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "UPDATE tb_cosecha SET kilos = '$kilos', ciu = '$ciu', pendiente = '1' WHERE tb_cosecha.id_global = '$id_global'";
	mysqli_query($conexion,$sql);   

	$array=array('success'=>'true');

	echo json_encode($array);


} //fin else



?>