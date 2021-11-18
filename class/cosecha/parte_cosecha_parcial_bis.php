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
	date_default_timezone_set("America/Argentina/Mendoza");
	$id_global = date("Ymdhis");	

	$fecha=$_REQUEST['diario_fecha'];
	$remito=$_REQUEST['diario_remito'];
	$ciu=$_REQUEST['diario_ciu'];
	$transporte=$_REQUEST['diario_transporte'];
	$chofer=$_REQUEST['diario_chofer'];
	$patente=$_REQUEST['diario_patente'];
	$finca=$_REQUEST['diario_finca'];
	$destino=$_REQUEST['diario_destino'];
	$mecanica=$_REQUEST['diario_mecanica'];
	$propia=$_REQUEST['diario_propia'];
	$eventual=$_REQUEST['diario_eventual'];
	$fichas=$_REQUEST['diario_fichas'];
	$precio=$_REQUEST['diario_precio'];
	$horas=$_REQUEST['diario_horas'];
	$kilos=$_REQUEST['diario_kilos'];
	$obs=$_REQUEST['diario_obs'];
	
	$totalhas=$_REQUEST['totalhas'];

	$idinicial=$_REQUEST['idinicial']; // id inicial de cuarteles
	$idfinal=$_REQUEST['idfinal'];

	if ($ciu == '' || $kilos == '') {
		
		$pendiente = '0';
	}else{

		if ($ciu != '' && $kilos != '') {

		$pendiente = '1';			
		}
	}

		$coef_horas = $horas/$totalhas;
		
		for ($i=$idinicial; $i <=$idfinal ; $i++) { 

			$cuartel=$_GET['cuartel'.$i.''];
			$has=$_GET['has_seleccionadas'.$i.''];
			$kilos=$_GET['kilos'.$i.''];

			$hectareas[''.$i.''] = ''.$has.'';
			$cuarteles[''.$i.''] = ''.$cuartel.'';
			$kilogramos[''.$i.''] = ''.$kilos.'';

			if ($has == 0)
			        continue;

			    $horas_proporcional = $has * $coef_horas;
			    			
			mysqli_select_db($conexion,'pernot_ricard');
			$sql = "INSERT INTO tb_cosecha (fecha, remito, ciu, id_transporte, chofer, patente, id_finca, destino, id_cosechadora, manual_p, manual_t, fichas, precio, id_cuartel, has, has_total, horas, kilos, obs, pendiente, id_global)
			VALUES ('$fecha', '$remito', '$ciu', '$transporte', '$chofer', '$patente', '$finca', '$destino', '$mecanica', '$propia', '$eventual', '$fichas', '$precio', '$cuartel', '$has', '$totalhas', round('$horas_proporcional',2), '$kilos', '$obs', '$pendiente', '$id_global')";
			mysqli_query($conexion,$sql);    

		}

			mysqli_select_db($conexion,'pernot_ricard');
			$sqlagregaparte = "UPDATE tb_consumo_insumos_".$deposito."
					   SET tb_consumo_insumos_".$deposito.".id_parte_diario_global = '$id_global'
					   WHERE tb_consumo_insumos_".$deposito.".estado = '0'"; 
			mysqli_query($conexion, $sqlagregaparte);

	    	mysqli_select_db($conexion,'pernot_ricard');
			$sqlinsumo = "UPDATE tb_consumo_insumos_".$deposito." SET tb_consumo_insumos_".$deposito.".estado = '1' WHERE tb_consumo_insumos_".$deposito.".estado = '0'"; 
			mysqli_query($conexion, $sqlinsumo);

			$array=array('success'=>'true');
			echo json_encode($array);

		

		
} //fin else conexion



?>