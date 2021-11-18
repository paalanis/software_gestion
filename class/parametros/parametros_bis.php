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
	$texto=utf8_decode($_REQUEST['texto']);


	switch ($opcion) {
		case 'conduccion':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_conduccion (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;	
		
		case 'umedida':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_unidad (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;

		case 'tipo_insumo':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_tipo_insumo (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;	
		
		case 'riego':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_riego (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;

		case 'tipo_labor':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_tipo_labor (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;

		case 'puesto':
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_puesto (nombre) VALUES (lower('$texto'))";
			mysqli_query($conexion,$sql);
			break;	
	}

	$array=array('success'=>'true');

	echo json_encode($array);

}


?>