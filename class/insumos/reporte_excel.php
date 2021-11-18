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
if (PHP_SAPI == 'cli')
	die('Este ejemplo sólo se puede ejecutar desde un navegador Web');

/** Incluye PHPExcel */
require_once "../../classes/PHPExcel.php";
// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Propiedades del documento
$objPHPExcel->getProperties()->setCreator("Obed Alvarado")
							 ->setLastModifiedBy("Obed Alvarado")
							 ->setTitle("Office 2010 XLSX Documento de prueba")
							 ->setSubject("Office 2010 XLSX Documento de prueba")
							 ->setDescription("Documento de prueba para Office 2010 XLSX, generado usando clases de PHP.")
							 ->setKeywords("office 2010 openxml php")
							 ->setCategory("Archivo con resultado de prueba");



// Combino las celdas desde A1 hasta E1
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Consumo de insumos')
            ->setCellValue('A2', 'Insumo')
            ->setCellValue('B2', 'Tipo')
            ->setCellValue('C2', 'Total')
			->setCellValue('D2', 'Unidad')
			->setCellValue('E2', '% Concentración')
			->setCellValue('F2', 'Resultado');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);		

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    $insumo=$_POST['post_insumo'];
	$desde=$_POST['post_desde'];
	$hasta=$_POST['post_hasta'];
	$consulta_insumos = "";
	if ($insumo != "") {
	$consulta_insumos = "AND tb_consumo_insumos_".$deposito.".id_insumo = '$insumo'";
	}
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlconsumo = "SELECT
	tb_insumo.nombre_comercial AS insumo,
	tb_tipo_insumo.nombre AS tipo,
	tb_unidad.nombre AS unidad,
	Sum(tb_consumo_insumos_".$deposito.".egreso) AS egreso,
	tb_insumo.concentracion as porcentaje,
	Sum(tb_consumo_insumos_".$deposito.".egreso) * tb_insumo.concentracion /100 as resultado
	FROM
	tb_consumo_insumos_".$deposito."
	LEFT JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
	LEFT JOIN tb_tipo_insumo ON tb_insumo.id_tipo_insumo = tb_tipo_insumo.id_tipo_insumo
	LEFT JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
	WHERE
	tb_consumo_insumos_".$deposito.".egreso NOT LIKE 0 AND
	tb_consumo_insumos_".$deposito.".fecha BETWEEN '$desde' AND '$hasta' $consulta_insumos
	GROUP BY
	tb_consumo_insumos_".$deposito.".id_insumo
	ORDER BY
	insumo ASC";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsconsumo = mysqli_query($conexion, $sqlconsumo);
	$cantidad =  mysqli_num_rows($rsconsumo);
	while ($datos = mysqli_fetch_array($rsconsumo)){
	$insumo=utf8_encode($datos['insumo']);
	$tipo=utf8_encode($datos['tipo']);
	$egreso=$datos['egreso'];
	$unidad=utf8_encode($datos['unidad']);
	$porcentaje=utf8_encode($datos['porcentaje']);
	$resultado=utf8_encode($datos['resultado']);
		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			$f="F".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $insumo)
            ->setCellValue($b, $tipo)
            ->setCellValue($c, $egreso)
            ->setCellValue($d, $unidad)
			->setCellValue($e, $porcentaje)
			->setCellValue($f, $resultado);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$f";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte consumos');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_consumo.xls"');
header('Cache-Control: max-age=0');
// Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
header('Cache-Control: max-age=1');

// Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;