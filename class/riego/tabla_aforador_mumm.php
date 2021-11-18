<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../../index.php");
}
$deposito=$_SESSION['deposito'];
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$altura=$_REQUEST['altura'];

$sqlaltura = "SELECT
tb_aforo_mumm.caudal as caudal
FROM
tb_aforo_mumm
WHERE
tb_aforo_mumm.altura = '$altura'";
$rsaltura = mysqli_query($conexion, $sqlaltura); 

$filas = mysqli_num_rows($rsaltura);

while ($datos = mysqli_fetch_array($rsaltura)){
$caudal=$datos['caudal'];

};


 if ($filas > 0) {

 	$array=array('success'=>'false', 'caudal'=>$caudal); 
 	echo json_encode($array);
 
 }else{

	$array=array('success'=>'true');
	echo json_encode($array);
 }


?>