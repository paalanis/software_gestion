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

$ciu=$_REQUEST['ciu'];

$sqlciu = "SELECT
			tb_cosecha.ciu
			FROM
			tb_cosecha
			WHERE
			tb_cosecha.ciu = $ciu";
$rsciu = mysqli_query($conexion, $sqlciu); 

$filas = mysqli_num_rows($rsciu);

 if ($filas > 0) {

 	$array=array('success'=>'false'); 
 	echo json_encode($array);
 
 }else{

	$array=array('success'=>'true');
	echo json_encode($array);
 }


?>