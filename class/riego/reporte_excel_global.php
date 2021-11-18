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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Riego global')
            ->setCellValue('A2', 'Fecha')
            ->setCellValue('B2', 'N° Parte')
            ->setCellValue('C2', 'Caudalímetro')
			->setCellValue('D2', 'Válvula')
			->setCellValue('E2', 'Has_asignadas')
			->setCellValue('F2', 'Lectura_i')
			->setCellValue('G2', 'Lectura_f')
			->setCellValue('H2', 'M3_regados')
			->setCellValue('I2', 'Cuartel')
			->setCellValue('J2', 'Has')
			->setCellValue('K2', 'Variedad');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:K2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);	
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

/*Extraer datos de MYSQL*/
	# conectare la base de datos
 
	$desde=$_POST['post_desde'];
	$hasta=$_POST['post_hasta'];
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlriego = "SELECT
		tb_valvula.id_valvula,
		DATE_FORMAT(tb_milimetros_riego.fecha, '%d/%m/%y') AS fecha,
		tb_milimetros_riego.id_global as parte,
		tb_caudalimetro.nombre AS caudalimetro,
		IFNULL(tb_milimetros_riego.lectura_inicial, 0) as lectura_i,
        IFNULL(tb_milimetros_riego.lectura_final, 0) as lectura_f,
		IFNULL(tb_valvula.nombre, 'dilucion') AS valvula,
		tb_valvula.has_asignadas AS has_asignadas,
		round(tb_milimetros_riego.mm_regados, 2) AS mm_regados,
		tb_cuartel.nombre AS cuartel,
		tb_cuartel.has AS has,
		tb_variedad.nombre AS variedad
		FROM
		tb_milimetros_riego
		LEFT JOIN tb_valvula ON tb_milimetros_riego.id_valvula = tb_valvula.id_valvula
		LEFT JOIN tb_cuartel ON tb_valvula.id_cuartel = tb_cuartel.id_cuartel
		LEFT JOIN tb_caudalimetro ON tb_caudalimetro.id_caudalimetro = tb_milimetros_riego.id_caudalimetro
		LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
		WHERE
		tb_milimetros_riego.fecha BETWEEN '$desde' AND '$hasta'
		ORDER BY
		fecha asc";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsriego = mysqli_query($conexion, $sqlriego);
	$cantidad =  mysqli_num_rows($rsriego);
	while ($datos = mysqli_fetch_array($rsriego)){
	$fecha=utf8_encode($datos['fecha']);
	$parte=utf8_encode($datos['parte']);
	$caudalimetro=$datos['caudalimetro'];
	$valvula=utf8_encode($datos['valvula']);
	$lectura_i=utf8_encode($datos['lectura_i']);
    $lectura_f=utf8_encode($datos['lectura_f']);
	$has_asignadas=utf8_encode($datos['has_asignadas']);
	$mm_regados=utf8_encode($datos['mm_regados']);
	$cuartel=utf8_encode($datos['cuartel']);
	$has=utf8_encode($datos['has']);
	$variedad=utf8_encode($datos['variedad']);
		
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
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $fecha)
            ->setCellValue($b, $parte)
            ->setCellValue($c, $caudalimetro)
            ->setCellValue($d, $valvula)
			->setCellValue($e, $has_asignadas)
			->setCellValue($f, $lectura_i)
			->setCellValue($g, $lectura_f)
			->setCellValue($h, $mm_regados)
			->setCellValue($i, $cuartel)
			->setCellValue($j, $has)
			->setCellValue($k, $variedad);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$k";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte riego global');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_riego_global.xls"');
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