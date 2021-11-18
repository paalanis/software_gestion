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

		date_default_timezone_set("America/Argentina/Mendoza");
		$id_global = date("Ymdhis");

		$aforador=$_REQUEST['riego_aforador'];
		$fecha_1=$_REQUEST['fecha_1'];
		$fecha_2=$_REQUEST['fecha_2'];
		$fecha_3=$_REQUEST['fecha_3'];
		$altura_1=$_REQUEST['altura_1'];
		$altura_2=$_REQUEST['altura_2'];
		$altura_3=$_REQUEST['altura_3'];
		$calculo_1=$_REQUEST['calculo_1'];
		$calculo_2=$_REQUEST['calculo_2'];
		$calculo_3=$_REQUEST['calculo_3'];
		
		// $totalhas=$_REQUEST['totalhas'];
		$idinicial=$_REQUEST['idinicial']; // id inicial de valvulas
		$idfinal=$_REQUEST['idfinal'];
	

		for ($i=$idinicial; $i <= $idfinal ; $i++) { 

			$cuartel=$_GET['cuartel'.$i.''];
			$has=$_GET['has_seleccionadas'.$i.''];

			// $hectareas[''.$i.''] = ''.$has.'';
			// $cuarteles[''.$i.''] = ''.$cuartel.'';

			if ($has == 0)
			        continue;
					
mysqli_select_db($conexion,'pernot_ricard');
$sql = "INSERT INTO tb_riego_manto (id_aforador, id_cuartel, has, fe_ho_1, altura_1, calculo_1, fe_ho_2, altura_2, calculo_2, fe_ho_3, altura_3, calculo_3, id_global)
		VALUES ('$aforador', '$cuartel', '$has', '$fecha_1', '$altura_1', '$calculo_1', '$fecha_2', '$altura_2', '$calculo_2', '$fecha_3', '$altura_3', '$calculo_3', '$id_global')";
mysqli_query($conexion,$sql);  
		
		}
	
	$array=array('success'=>'true');
	echo json_encode($array);


}



?>