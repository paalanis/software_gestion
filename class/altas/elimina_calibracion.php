<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$deposito=$_SESSION['deposito'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
exit();
}else{
$id_calibra_formula=$_REQUEST['id_calibra_formula'];
mysqli_select_db($conexion,'pernot_ricard');
$sql = "DELETE FROM tb_calibra_formula WHERE id_calibra_formula = '$id_calibra_formula'";
mysqli_query($conexion,$sql);
$array=array('success'=>'true');
echo json_encode($array);
}
?>