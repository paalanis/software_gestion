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

	$fecha=$_REQUEST['riego_fecha'];
	$caudalimetro=$_REQUEST['riego_caudalimetro'];
	$caudalimetro=substr($caudalimetro, 2);
	$riego_inicial=$_REQUEST['riego_inicial'];
	$riego_final=$_REQUEST['riego_final'];

	$milimetros=$_REQUEST['riego_resultado'];

	$tipo_dilucion=$_REQUEST['tipo_dilucion'];	

	if ($tipo_dilucion == '0') {
		
		$totalhas=$_REQUEST['totalhas'];
		$idinicial=$_REQUEST['idinicial']; // id inicial de valvulas
		$idfinal=$_REQUEST['idfinal'];


		$coef_mm = $milimetros/$totalhas;

		for ($i=$idinicial; $i <= $idfinal ; $i++) { 

			$valvula =$_GET['valvula_'.$i.''];
			$has =$_GET['has_seleccionadas'.$i.''];

			if ($has == 0)
					        continue;
					
			$sqlvalvulas = "SELECT
							tb_valvula.id_valvula AS id_valvula,
							tb_valvula.has_asignadas AS has_asignadas
							FROM
							tb_valvula
							WHERE
							tb_valvula.id_caudalimetro = '$caudalimetro' AND
							tb_valvula.id_valvula = '$valvula'
							";
			$rsvalvulas = mysqli_query($conexion, $sqlvalvulas);	

			 while ($datos = mysqli_fetch_array($rsvalvulas)){ //
			       
				        $has_asignadas=utf8_encode($datos['has_asignadas']);
				        $id_valvula=utf8_encode($datos['id_valvula']);

				        $proporcion_mm = $has_asignadas * $coef_mm;
	    			
						mysqli_select_db($conexion,'pernot_ricard');
						$sql = "INSERT INTO tb_milimetros_riego (fecha, id_valvula, id_caudalimetro, mm_regados, id_global, lectura_inicial, lectura_final)
						VALUES ('$fecha', '$id_valvula', '$caudalimetro', '$proporcion_mm', '$id_global', '$riego_inicial', '$riego_final')";
						mysqli_query($conexion,$sql); 	
			    					   
			} // fin while
		
		}
	
	$array=array('success'=>'true');
	echo json_encode($array);

	}else{

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "INSERT INTO tb_milimetros_riego (fecha, id_caudalimetro, mm_regados, id_global, lectura_inicial, lectura_final)
		VALUES ('$fecha', '$caudalimetro', '$milimetros', '$id_global', '$riego_inicial', '$riego_final')";
		mysqli_query($conexion,$sql);

		$array=array('success'=>'true');
		echo json_encode($array);
	}

}



?>