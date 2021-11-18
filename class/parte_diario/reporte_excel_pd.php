<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Q1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Parte diario')
            ->setCellValue('A2', 'Numero_parte')
            ->setCellValue('B2', 'Fecha')
            ->setCellValue('C2', 'Finca')
			->setCellValue('D2', 'Personal')
			->setCellValue('E2', 'Cuartel')
			->setCellValue('F2', 'Variedad')
			->setCellValue('G2', 'Has_cuartel')
			->setCellValue('H2', 'Labor')
			->setCellValue('I2', 'Obs_labor')
			->setCellValue('J2', 'Has_trabajadas')
			->setCellValue('K2', 'Horas')
			->setCellValue('L2', 'Obs_gral')
			->setCellValue('M2', 'Insumo')
			->setCellValue('N2', 'Cantidad')
			->setCellValue('O2', 'Unidad')
			->setCellValue('P2', 'Principio')
			->setCellValue('Q2', 'Concentracion');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:Q2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);	

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
	$desde=$_POST['post_desde'];
	$hasta=$_POST['post_hasta'];
	$id_finca=$_POST['id_finca'];
	$deposito=$_POST['deposito'];
	
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlriego = "SELECT
				tb_parte_diario.id_parte_diario_global AS id_global,
				DATE_FORMAT(tb_parte_diario.fecha, '%d/%m/%Y') AS fecha,
				tb_finca.nombre AS finca,
				CONCAT(tb_personal.apellido,', ',tb_personal.nombre) AS personal,
				tb_cuartel.nombre AS cuartel,
				tb_variedad.nombre AS variedad,
				round(tb_cuartel.has,3) AS has,
				tb_labor.nombre AS labor,
				tb_parte_diario.obs_labor AS obs_labor,
				round(tb_parte_diario.has,2) AS has_trabajadas,
				round(tb_parte_diario.horas_trabajadas,2) AS horas,
				tb_parte_diario.obs_general AS obs_gral,
				tb_insumo.nombre_comercial as insumo,
				round(tb_insumo_proporcional_".$deposito.".proporcion,2) as cantidad,
				tb_unidad.nombre as unidad,
				tb_insumo.principio_activo as principio,
				tb_insumo.concentracion as concentra
				FROM
				tb_parte_diario
				LEFT JOIN tb_finca ON tb_parte_diario.id_finca = tb_finca.id_finca
				LEFT JOIN tb_personal ON tb_parte_diario.id_personal = tb_personal.id_personal
				LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_parte_diario.id_cuartel
				LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
				LEFT JOIN tb_labor ON tb_labor.id_labor = tb_parte_diario.id_labor
				LEFT JOIN tb_insumo_proporcional_".$deposito." ON tb_parte_diario.id_parte_diario_global = tb_insumo_proporcional_".$deposito.".id_parte_diario_global AND tb_parte_diario.id_cuartel = tb_insumo_proporcional_".$deposito.".id_cuartel
				LEFT JOIN tb_insumo ON tb_insumo_proporcional_".$deposito.".id_insumo = tb_insumo.id_insumo
				LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
				WHERE
				tb_parte_diario.fecha BETWEEN '$desde' AND '$hasta' AND
				tb_parte_diario.id_finca = '$id_finca'
				GROUP BY
				tb_parte_diario.id_parte_diario_global,
				tb_parte_diario.id_cuartel
				ORDER BY
				tb_parte_diario.fecha ASC,
				tb_parte_diario.id_parte_diario ASC";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsriego = mysqli_query($conexion, $sqlriego);
	$cantidad =  mysqli_num_rows($rsriego);
	while ($datos = mysqli_fetch_array($rsriego)){
	$id_global=utf8_encode($datos['id_global']);
	$fecha=utf8_encode($datos['fecha']);
	$finca=utf8_encode($datos['finca']);
	$personal=$datos['personal'];
	$cuartel=utf8_encode($datos['cuartel']);
	$variedad=utf8_encode($datos['variedad']);
	$has=utf8_encode($datos['has']);
	$labor=utf8_encode($datos['labor']);
	$obs_labor=utf8_encode($datos['obs_labor']);
    $has_trabajadas=utf8_encode($datos['has_trabajadas']);
	$horas=utf8_encode($datos['horas']);
	$obs_gral=utf8_encode($datos['obs_gral']);
	$insumo=utf8_encode($datos['insumo']);
	$cantidad=utf8_encode($datos['cantidad']);
	$unidad=utf8_encode($datos['unidad']);
	$principio=utf8_encode($datos['principio']);
	$concentra=utf8_encode($datos['concentra']);

		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			$f="F".$cel;
			$g="G".$cel;
			$h="H".$cel;
			$i="I".$cel;
			$j="J".$cel;
			$k="K".$cel;
			$l="L".$cel;
			$m="M".$cel;
			$n="N".$cel;
			$o="O".$cel;
			$p="P".$cel;
			$q="Q".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($a, $id_global)
            ->setCellValue($b, $fecha)
            ->setCellValue($c, $finca)
            ->setCellValue($d, $personal)
			->setCellValue($e, $cuartel)
			->setCellValue($f, $variedad)
			->setCellValue($g, $has)
			->setCellValue($h, $labor)
			->setCellValue($i, $obs_labor)
			->setCellValue($j, $has_trabajadas)
			->setCellValue($k, $horas)
			->setCellValue($l, $obs_gral)
			->setCellValue($m, $insumo)
			->setCellValue($n, $cantidad)
			->setCellValue($o, $unidad)
			->setCellValue($p, $principio)
			->setCellValue($q, $concentra);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$q";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte parte diario');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_parte_diario.xls"');
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