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
$deposito=$_SESSION['deposito'];
$fecha = date("Y-m-d");
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
exit();
}else{
	$id_global=$_REQUEST['id_global']; // para eliminar
	$deposito_i=$_REQUEST['deposito']; // para eliminar
	$id_insumo=$_REQUEST['id_insumo']; // para eliminar/reingresar
	$cantidad=$_REQUEST['cantidad']; // para reingresar

//ELIMINAMOS EN EL DEPOSITO DE DESTINO

		// Primero eliminamos el remito seleccionado

				mysqli_select_db($conexion,'pernot_ricard');
				$sql = "DELETE FROM tb_consumo_insumos_".$deposito_i." WHERE id_parte_diario_global = '$id_global'";
				mysqli_query($conexion,$sql);

				
		// Luego buscamos el saldo anterior al remito eliminado

				$sqlsaldo = "SELECT
							tb_consumo_insumos_".$deposito_i.".saldo as saldo_anterior
							FROM
							tb_consumo_insumos_".$deposito_i."
							INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito_i.".id_insumo = tb_insumo.id_insumo
							INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
							WHERE
							tb_consumo_insumos_".$deposito_i.".id_consumo_insumos IN ((SELECT 
							MAX(tb_consumo_insumos_".$deposito_i.".id_consumo_insumos ) 
							FROM 
							tb_consumo_insumos_".$deposito_i." 
							WHERE
							tb_consumo_insumos_".$deposito_i.".id_parte_diario_global < $id_global
							GROUP BY 
							tb_consumo_insumos_".$deposito_i.".id_insumo)) AND
							tb_consumo_insumos_".$deposito_i.".id_insumo = $id_insumo
							";
				$rssaldo = mysqli_query($conexion, $sqlsaldo);
				$cantidad =  mysqli_num_rows($rssaldo);

				if ($cantidad > 0) { // si existen saldo con de esa finca se muestran, de lo contrario queda en blanco  

				$datos = mysqli_fetch_array($rssaldo);
				$saldo_anterior=utf8_encode($datos['saldo_anterior']);

				}else{

					$saldo_anterior=0;
				}

		// Buscamos los ingresos y egresos posteriores al remito eliminado y actualizamos saldos

				$sql_ing_eg = "SELECT
								tb_consumo_insumos_".$deposito_i.".id_consumo_insumos as id_consumo,	
								tb_consumo_insumos_".$deposito_i.".ingreso as ingreso,
								tb_consumo_insumos_".$deposito_i.".egreso as egreso
								FROM
								tb_consumo_insumos_".$deposito_i."
								WHERE
								tb_consumo_insumos_".$deposito_i.".id_insumo = $id_insumo AND
								tb_consumo_insumos_".$deposito_i.".id_parte_diario_global > $id_global
								";
				$rs_ing_eg = mysqli_query($conexion, $sql_ing_eg);
				$cantidad =  mysqli_num_rows($rs_ing_eg);

				if ($cantidad > 0) { // si existen _ing_eg con de esa finca se muestran, de lo contrario queda en blanco  

				 while ($datos = mysqli_fetch_array($rs_ing_eg)){
					$ingreso=utf8_encode($datos['ingreso']);
					$egreso=utf8_encode($datos['egreso']);
					$id_consumo=utf8_encode($datos['id_consumo']);  


					$saldo_actual = $saldo_anterior + $ingreso - $egreso;

					$saldo_anterior = $saldo_actual;

					mysqli_select_db($conexion,'pernot_ricard');
					$sql = "UPDATE tb_consumo_insumos_".$deposito_i."
							   SET tb_consumo_insumos_".$deposito_i.".saldo = $saldo_actual
							   WHERE tb_consumo_insumos_".$deposito_i.".id_consumo_insumos = $id_consumo"; 
					mysqli_query($conexion, $sql);	

				 }
				}


// ELIMINAMOS EL TRASPASO EN EL DEPOSITO LOCAL

	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "DELETE FROM tb_consumo_insumos_".$deposito." WHERE id_parte_diario_global = '$id_global'";
	mysqli_query($conexion,$sql);
				

//INGRESAMOS EL INSUMO EN DEPOSITO LOCAL

	$sqlsaldo_ing = "SELECT
	tb_consumo_insumos_".$deposito.".id_consumo_insumos AS id,
	tb_consumo_insumos_".$deposito.".saldo AS saldo
	FROM
	tb_consumo_insumos_".$deposito."
	WHERE
	tb_consumo_insumos_".$deposito.".id_insumo = '$id_insumo'
	ORDER BY
	tb_consumo_insumos_".$deposito.".id_consumo_insumos DESC
	LIMIT 1";
	$rssaldo_ing = mysqli_query($conexion, $sqlsaldo_ing);
	$datos_ing = mysqli_fetch_array($rssaldo_ing);
	$saldo_ingresar=$datos_ing['saldo'];

	$saldo_ingresar = $saldo_ingresar + $cantidad;

	mysqli_select_db($conexion,'pernot_ricard');
	$sql = "INSERT INTO tb_consumo_insumos_laflorida (fecha, id_insumo, ingreso, id_deposito_origen, id_deposito_destino,
			id_parte_diario_global, obs, estado, reingreso, saldo)
	VALUES ('$fecha', '$id_insumo', '$cantidad', '0', '0', '$id_global', 'reingreso', '1', '1', '$saldo_ingresar')";
	mysqli_query($conexion,$sql);    



$array=array('success'=>'true');
echo json_encode($array);
}
?>