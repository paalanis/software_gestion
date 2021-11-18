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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Riego manto')
            ->setCellValue('A2', 'N° Parte')
            ->setCellValue('B2', 'Aforador')
            ->setCellValue('C2', 'Cuarteles')
			->setCellValue('D2', 'Has')
			->setCellValue('E2', 'Fecha-I')
			->setCellValue('F2', 'Lectura-I')
			->setCellValue('G2', 'Calculo-I')
			->setCellValue('H2', 'Fecha-M')
			->setCellValue('I2', 'Lectura-M')
			->setCellValue('J2', 'Calculo-M')
			->setCellValue('K2', 'Fecha-F')
			->setCellValue('L2', 'Lectura-F')
			->setCellValue('M2', 'Calculo-F')
			->setCellValue('N2', 'Total ingresado');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:N2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);		

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    $afora=$_POST['post_afora'];
	$desde=$_POST['post_desde'];
	$hasta=$_POST['post_hasta'];
	$finca=$_POST['post_finca'];
	$consulta_aforador = "";
	if ($afora != "") {
    $consulta_aforador = "AND tb_aforador.id_aforador = '$afora' ";
    }
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlriego = "SELECT
    tb_riego_manto.id_global AS id_global,
    tb_aforador.nombre AS aforador,
    GROUP_CONCAT(tb_cuartel.nombre ORDER BY tb_cuartel.nombre ASC ) AS cuartel,
    Sum(tb_riego_manto.has) AS has,
    DATE_FORMAT(tb_riego_manto.fe_ho_1, '%d/%m/%Y - %H:%i') AS fecha_i,
    tb_riego_manto.altura_1 AS lectura_i,
    ROUND(tb_riego_manto.calculo_1,2) AS calculo_i,
    DATE_FORMAT(tb_riego_manto.fe_ho_2, '%d/%m/%Y - %H:%i') AS fecha_m,
    tb_riego_manto.altura_2 AS lectura_m,
    ROUND(tb_riego_manto.calculo_2,2) AS calculo_m,
    DATE_FORMAT(tb_riego_manto.fe_ho_3, '%d/%m/%Y - %H:%i') AS fecha_f,
    tb_riego_manto.altura_3 AS lectura_f,
    ROUND(tb_riego_manto.calculo_3,2) AS calculo_f,
    ROUND((TIMESTAMPDIFF(MINUTE,tb_riego_manto.fe_ho_1,tb_riego_manto.fe_ho_3)/60)*((tb_riego_manto.calculo_1+tb_riego_manto.calculo_2+tb_riego_manto.calculo_3)/3),0) as agua_total
    FROM
    tb_riego_manto
    LEFT JOIN tb_aforador ON tb_riego_manto.id_aforador = tb_aforador.id_aforador
    LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_riego_manto.id_cuartel
    WHERE
    tb_riego_manto.fe_ho_1 BETWEEN '$desde' AND '$hasta' AND tb_aforador.id_finca = '$finca' $consulta_aforador
    GROUP BY
    tb_riego_manto.id_global
    ORDER BY
    tb_riego_manto.fe_ho_1 DESC";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsriego = mysqli_query($conexion, $sqlriego);
	$cantidad =  mysqli_num_rows($rsriego);
	while ($datos = mysqli_fetch_array($rsriego)){
	  $id_global=$datos['id_global'];
      $aforador=utf8_encode($datos['aforador']);
      $cuartel=utf8_encode($datos['cuartel']);
      $has=utf8_encode($datos['has']);
      $fecha_i=utf8_encode($datos['fecha_i']);
      $lectura_i=utf8_encode($datos['lectura_i']);
      $calculo_i=utf8_encode($datos['calculo_i']);
      $fecha_m=utf8_encode($datos['fecha_m']);
      $lectura_m=utf8_encode($datos['lectura_m']);
      $calculo_m=utf8_encode($datos['calculo_m']);
      $fecha_f=utf8_encode($datos['fecha_f']);
      $lectura_f=utf8_encode($datos['lectura_f']);
      $calculo_f=utf8_encode($datos['calculo_f']);
      $agua_total=utf8_encode($datos['agua_total']);
		
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
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $id_global)
            ->setCellValue($b, $aforador)
            ->setCellValue($c, $cuartel)
            ->setCellValue($d, $has)
			->setCellValue($e, $fecha_i)
			->setCellValue($f, $lectura_i)
			->setCellValue($g, $calculo_i)
			->setCellValue($h, $fecha_m)
			->setCellValue($i, $lectura_m)
			->setCellValue($j, $calculo_m)
			->setCellValue($k, $fecha_f)
			->setCellValue($l, $lectura_f)
			->setCellValue($m, $calculo_f)
			->setCellValue($n, $agua_total);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$n";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte riego');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_riego_manto.xls"');
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