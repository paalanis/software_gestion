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

$opcion=$_REQUEST['opcion'];
$id=$_REQUEST['id'];
$texto=utf8_decode($_REQUEST['texto']);

switch ($opcion) {
	case 'operacion':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_operacion SET nombre = lower('$texto') WHERE tb_operacion.id_operacion = '$id'";
		mysqli_query($conexion,$sql);
		break;
	}

	$array=array('success'=>'true');

	echo json_encode($array);

}


?>