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
	case 'conduccion':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_conduccion SET nombre = lower('$texto') WHERE tb_conduccion.id_conduccion = '$id'";
		mysqli_query($conexion,$sql);
		break;
	
	case 'umedida':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_unidad SET nombre = lower('$texto') WHERE tb_unidad.id_unidad = '$id'";
		mysqli_query($conexion,$sql);
		break;

	case 'tipo_insumo':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_tipo_insumo SET nombre = lower('$texto') WHERE tb_tipo_insumo.id_tipo_insumo = '$id'";
		mysqli_query($conexion,$sql);
		break;

	case 'riego':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_riego SET nombre = lower('$texto') WHERE tb_riego.id_riego = '$id'";
		mysqli_query($conexion,$sql);
		break;

	case 'tipo_labor':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_tipo_labor SET nombre = lower('$texto') WHERE tb_tipo_labor.id_tipo_labor = '$id'";
		mysqli_query($conexion,$sql);
		break;

	case 'puesto':
		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_puesto SET nombre = lower('$texto') WHERE tb_puesto.id_puesto = '$id'";
		mysqli_query($conexion,$sql);
		break;		
}

	$array=array('success'=>'true');

	echo json_encode($array);

}


?>