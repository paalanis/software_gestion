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

	$nombre=utf8_decode($_REQUEST['alta_cuartel']);
	$finca=$_REQUEST['alta_finca'];
	$variedad=$_REQUEST['alta_variedad'];
	$hileras=$_REQUEST['alta_hileras'];
	$riego=$_REQUEST['alta_riego'];
	$conduccion=$_REQUEST['alta_conduccion'];
	$año=$_REQUEST['alta_año'];
	$distancia=$_REQUEST['alta_distancia'];
	$has=$_REQUEST['alta_has'];
	$mapeo=$_REQUEST['alta_mapeo'];
	$malla=$_REQUEST['alta_malla'];
	$id_modifica=$_REQUEST['id_modifica'];

	if ($id_modifica == "") {
		
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_cuartel (id_finca, nombre, id_variedad, id_riego, id_conduccion, ano, distancia, has, id_super, malla, hileras)
	VALUES ('$finca', lower('$nombre'), '$variedad', '$riego', '$conduccion', '$año', '$distancia', '$has', '$mapeo', '$malla', '$hileras')";
	mysqli_query($conexion,$sql);    

	$array=array('success'=>'true');
	echo json_encode($array);
	
	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_cuartel 
		SET id_finca = '$finca',
			nombre = lower('$nombre'),
			id_variedad = '$variedad',
			id_riego = '$riego',
			id_conduccion = '$conduccion',
			ano = '$año',
			distancia = '$distancia',
			has = '$has',
			id_super = '$mapeo',
			malla = '$malla',
			hileras = '$hileras'
		WHERE tb_cuartel.id_cuartel = '$id_modifica'";
		mysqli_query($conexion,$sql);    

		$array=array('success'=>'true');
		echo json_encode($array);



	}


} //fin else



?>