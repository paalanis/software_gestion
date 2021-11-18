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

	$id_valvula=utf8_decode($_REQUEST['id_valvula']);
	$id_finca=$_REQUEST['id_finca'];
	$id_operacion=utf8_decode($_REQUEST['id_operacion']);
			
	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_operacion_asignada (id_finca, id_valvula, id_operacion)
	VALUES ('$id_finca', '$id_valvula', '$id_operacion')";
	mysqli_query($conexion,$sql);    

	mysqli_select_db($conexion,'pernot_ricard');
	$sqlestado = "UPDATE tb_valvula SET tb_valvula.estado_op = '1' WHERE tb_valvula.id_valvula = '$id_valvula'"; 
	mysqli_query($conexion, $sqlestado);


	$array=array('success'=>'true');
	echo json_encode($array);



} //fin else



?>