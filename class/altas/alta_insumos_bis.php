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

	$unidad=$_REQUEST['alta_umedida'];
	$tipo_insumo=$_REQUEST['alta_tipo_insumo'];
	$asociado=$_REQUEST['alta_asociado'];
	$nombre_comercial=utf8_decode($_REQUEST['alta_n_comercial']);
	$principio_activo=utf8_decode($_REQUEST['alta_principio']);
	$concentracion=$_REQUEST['alta_concentracion'];
	$id_modifica=$_REQUEST['id_modifica'];

	if ($id_modifica == "") {

	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_insumo (id_unidad, id_tipo_insumo, nombre_comercial, principio_activo, concentracion, asociado)
	VALUES ('$unidad', '$tipo_insumo', lower('$nombre_comercial'), lower('$principio_activo'), '$concentracion', '$asociado')";
	mysqli_query($conexion,$sql);    

	$array=array('success'=>'true');
	echo json_encode($array);

	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_insumo 
		SET id_unidad = '$unidad',
			id_tipo_insumo = '$tipo_insumo',
			nombre_comercial = lower('$nombre_comercial'),
			principio_activo = lower('$principio_activo'),
			concentracion = '$concentracion',
			asociado = '$asociado'
		WHERE tb_insumo.id_insumo = '$id_modifica'";
		mysqli_query($conexion,$sql);    

		$array=array('success'=>'true');
		echo json_encode($array);
	}


} //fin else



?>