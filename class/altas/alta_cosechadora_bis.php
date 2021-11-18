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

	$nombre=utf8_decode($_REQUEST['alta_nombre']);
	$caracteristicas=utf8_decode($_REQUEST['alta_caracteristicas']);
	$eventual=utf8_decode($_REQUEST['alta_eventual']);
	$id_modifica=$_REQUEST['id_modifica'];


	if ($id_modifica == "") {

	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_cosechadora (nombre, caracteristicas, propia)
	VALUES ('$nombre', '$caracteristicas', '$eventual')";
	mysqli_query($conexion,$sql);    

	$array=array('success'=>'true');
	echo json_encode($array);

	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_cosechadora 
		SET nombre = '$nombre',
			caracteristicas = '$caracteristicas',
			propia = '$eventual'
		WHERE tb_cosechadora.id_cosechadora = '$id_modifica'";
		mysqli_query($conexion,$sql);    

		$array=array('success'=>'true');
		echo json_encode($array);

	}


} //fin else



?>