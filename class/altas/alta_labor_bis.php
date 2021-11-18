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

	$tipo_labor=$_REQUEST['alta_tipo'];
	$nombre=utf8_decode($_REQUEST['alta_labor']);
	$id_modifica=$_REQUEST['id_modifica'];

	if ($id_modifica == "") {
		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_labor (id_tipo_labor, nombre)
	VALUES ('$tipo_labor', lower('$nombre'))";
	mysqli_query($conexion,$sql);    

	$array=array('success'=>'true');
	echo json_encode($array);

	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_labor 
		SET id_tipo_labor = '$tipo_labor',
			nombre = lower('$nombre')
		WHERE tb_labor.id_labor = '$id_modifica'";
		mysqli_query($conexion,$sql);    

		$array=array('success'=>'true');
		echo json_encode($array);
	}


} //fin else



?>