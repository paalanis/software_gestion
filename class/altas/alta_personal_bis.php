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

	$finca=$_REQUEST['alta_finca'];
	$puesto=$_REQUEST['alta_puesto'];
	$ingreso=$_REQUEST['alta_ingreso'];
	$nacimiento=$_REQUEST['alta_nacimiento'];
	$nombre=utf8_decode($_REQUEST['alta_nombre']);
	$apellido=utf8_decode($_REQUEST['alta_apellido']);
	$eventual=utf8_decode($_REQUEST['alta_eventual']);
	$id_modifica=$_REQUEST['id_modifica'];


	if ($id_modifica == "") {

	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_personal (id_finca, id_puesto, ingreso, nacimiento, nombre, apellido, eventual)
	VALUES ('$finca', '$puesto', '$ingreso', '$nacimiento', '$nombre', '$apellido', '$eventual')";
	mysqli_query($conexion,$sql);    

	$array=array('success'=>'true');
	echo json_encode($array);

	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_personal 
		SET id_finca = '$finca',
			id_puesto = '$puesto',
			ingreso = '$ingreso',
			nacimiento = '$nacimiento',
			nombre = '$nombre',
			apellido = '$apellido',
			eventual = '$eventual'
		WHERE tb_personal.id_personal = '$id_modifica'";
		mysqli_query($conexion,$sql);    

		$array=array('success'=>'true');
		echo json_encode($array);

	}


} //fin else



?>