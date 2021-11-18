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
	$finca=$_REQUEST['diario_finca'];
	$personal=$_REQUEST['diario_personal'];
	$horas=$_REQUEST['diario_horas'];
	
	$obs_general=utf8_decode($_REQUEST['diario_obs_general']);
	$labor=$_REQUEST['diario_labor'];
	$obs_labor=utf8_decode($_REQUEST['diario_obs_labor']);

	$totalhas=$_REQUEST['totalhas'];

	$idinicial=$_REQUEST['idinicial']; // id inicial de cuarteles
	$idfinal=$_REQUEST['idfinal'];

	if ($totalhas == 0) {

		mysqli_select_db($conexion,'pernot_ricard');
		$sql = "INSERT INTO tb_parte_diario (id_finca, id_personal, fecha, horas_trabajadas, obs_general, id_cuartel, has, obs_labor, id_labor, id_parte_diario_global)
		VALUES ('$finca', '$personal', '$fecha', '$horas', '$obs_general', '0', '0', '$obs_labor', '$labor', '$id_global')";
		mysqli_query($conexion,$sql);

			$sqlinsumos = "SELECT
							tb_consumo_insumos_".$deposito.".id_insumo AS id,
							tb_consumo_insumos_".$deposito.".egreso AS cantidad
							FROM
							tb_consumo_insumos_".$deposito."
							WHERE
							tb_consumo_insumos_".$deposito.".estado = '0'
							ORDER BY
							tb_consumo_insumos_".$deposito.".id_consumo_insumos ASC";
		    $rsinsumos = mysqli_query($conexion, $sqlinsumos);
		    
		    $filas =  mysqli_num_rows($rsinsumos);

		    if ($filas > 0) { // si existen insumos con de esa finca se muestran, de lo contrario queda en blanco  
		   
		    	
		        while ($datos = mysqli_fetch_array($rsinsumos)){ //
		       
			        $cantidad=utf8_encode($datos['cantidad']);
			        $id=utf8_encode($datos['id']);
    			
					mysqli_select_db($conexion,'pernot_ricard');
					$sql = "INSERT INTO tb_insumo_proporcional_".$deposito." (id_insumo, id_cuartel, id_labor, proporcion, fecha, id_parte_diario_global)
					VALUES ('$id', '0', '$labor', '$cantidad', '$fecha', '$id_global')";
					mysqli_query($conexion,$sql); 	
		    					   
		    	} // fin while

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
			
		    }else{

		    	$array=array('success'=>'true');
				echo json_encode($array);

		    }		

	}else{

			$coef_horas = $horas/$totalhas;

			for ($i=$idinicial; $i <=$idfinal ; $i++) { 

				$cuartel=$_GET['cuartel'.$i.''];
				$has=$_GET['has_seleccionadas'.$i.''];

				$hectareas[''.$i.''] = ''.$has.'';
				$cuarteles[''.$i.''] = ''.$cuartel.'';

				if ($has == 0)
				        continue;

				    $horas_proporcional = $has * $coef_horas;
				
				mysqli_select_db($conexion,'pernot_ricard');
				$sql = "INSERT INTO tb_parte_diario (id_finca, id_personal, fecha, horas_trabajadas, obs_general, id_cuartel, has, obs_labor, id_labor, id_parte_diario_global)
				VALUES ('$finca', '$personal', '$fecha', '$horas_proporcional', '$obs_general', '$cuartel', '$has', '$obs_labor', '$labor', '$id_global')";
				mysqli_query($conexion,$sql);    

			}

			$sqlinsumos = "SELECT
							tb_consumo_insumos_".$deposito.".id_insumo AS id,
							tb_consumo_insumos_".$deposito.".egreso AS cantidad
							FROM
							tb_consumo_insumos_".$deposito."
							WHERE
							tb_consumo_insumos_".$deposito.".estado = '0'
							ORDER BY
							tb_consumo_insumos_".$deposito.".id_consumo_insumos ASC";
		    $rsinsumos = mysqli_query($conexion, $sqlinsumos);
		    
		    $filas =  mysqli_num_rows($rsinsumos);

		    if ($filas > 0) { // si existen insumos con de esa finca se muestran, de lo contrario queda en blanco  
		   
		    	$contador = 0;
		        while ($datos = mysqli_fetch_array($rsinsumos)){ //
		       
			        $cantidad=utf8_encode($datos['cantidad']);
			        $id=utf8_encode($datos['id']);
			        $contador++;

			            	for ($i=$idinicial; $i <= $idfinal; $i++) { 
							
								if ($hectareas[$i] == 0)
					        	continue;

								$proporcion = $cantidad/ $totalhas * $hectareas[$i];	
								$idcuartel = $cuarteles[$i];

								mysqli_select_db($conexion,'pernot_ricard');
								$sql = "INSERT INTO tb_insumo_proporcional_".$deposito." (id_insumo, id_cuartel, id_labor, proporcion, fecha, id_parte_diario_global)
								VALUES ('$id', '$idcuartel', '$labor', '$proporcion', '$fecha', '$id_global')";
								mysqli_query($conexion,$sql); 	
					    		
					        }

				   
		    	} // fin while

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
			
		    }else{

		    	$array=array('success'=>'true');
				echo json_encode($array);

		    } // fin else filas 

		 } // fin else has 0   

} //fin else conexion



?>