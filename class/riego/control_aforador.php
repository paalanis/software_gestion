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

$aforador=$_REQUEST['aforador'];

$sqlaforador = "SELECT
tb_calibra_formula.valor_a as valor_a,
tb_calibra_formula.valor_b as valor_b
FROM
tb_calibra_formula
WHERE
tb_calibra_formula.id_aforador = '$aforador'";
$rsaforador = mysqli_query($conexion, $sqlaforador); 

$filas = mysqli_num_rows($rsaforador);

while ($datos = mysqli_fetch_array($rsaforador)){
$valor_a=$datos['valor_a'];
$valor_b=$datos['valor_b'];
};


 if ($filas > 0) {

 	$array=array('success'=>'false', 'valor_a'=>$valor_a, 'valor_b'=>$valor_b); 
 	echo json_encode($array);
 
 }else{

	$array=array('success'=>'true');
	echo json_encode($array);
 }


?>