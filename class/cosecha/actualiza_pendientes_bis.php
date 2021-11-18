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
	
	$kilos=$_REQUEST['kilos'];
	$ciu=$_REQUEST['ciu'];
	$id_global=$_REQUEST['id_global'];


    $sqlcosecha = "SELECT
			tb_cosecha.id_cosecha as id_cosecha,
			tb_cosecha.has as has,
			tb_cosecha.has_total as has_t
			FROM
			tb_cosecha
			WHERE
			tb_cosecha.id_global = '$id_global'";
	$rscosecha = mysqli_query($conexion, $sqlcosecha);



	while ($sql_cosecha = mysqli_fetch_array($rscosecha)){

		$idcosecha= $sql_cosecha['id_cosecha'];
		$has= $sql_cosecha['has'];
		$has_t= $sql_cosecha['has_t'];

		$proporcion_kilos= $has*$kilos/$has_t;

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "UPDATE tb_cosecha SET kilos = round('$proporcion_kilos',2), ciu = '$ciu', pendiente = '1' WHERE tb_cosecha.id_cosecha = '$idcosecha'";
		mysqli_query($conexion,$sql);  

	};


	$array=array('success'=>'true');
	echo json_encode($array);


} //fin else



?>