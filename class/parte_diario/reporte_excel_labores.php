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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Reporte - Labores por Cuartel')
      ->setCellValue('A2', 'Finca')
      ->setCellValue('B2', 'Cuartel')
      ->setCellValue('C2', 'Variedad') 
			->setCellValue('D2', 'Has')  
			->setCellValue('E2', 'Labor')
			->setCellValue('F2', 'Tipo')
			->setCellValue('G2', 'Horas_t')
			->setCellValue('H2', 'Has_t')
			->setCellValue('I2', 'Obs_labor')
      ->setCellValue('J2', 'Obs_gral');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:J2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);			
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);      

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
        $finca=$_POST['id_finca'];
        $desde=$_POST['post_desde'];
        $hasta=$_POST['post_hasta'];
        $labor=$_POST['id_labor'];
        
        $consulta_finca = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_parte_diario.id_finca = '$finca' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_parte_diario.id_labor = '$labor' ";
        }

	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlconsumo = "SELECT
                  tb_finca.nombre AS finca,
                  tb_cuartel.nombre AS cuartel,
                  tb_variedad.nombre AS variedad,
                  tb_cuartel.has AS has_cuartel,
                  tb_labor.nombre AS labor,
                  tb_tipo_labor.nombre AS tipo,
                  ROUND(Sum(tb_parte_diario.horas_trabajadas),2) AS horas_t,
                  ROUND(Sum(tb_parte_diario.has),3) AS has_t,
                  ANY_VALUE(tb_parte_diario.obs_general) AS obs_gral,
                  ANY_VALUE(tb_parte_diario.obs_labor) AS obs_labor
                  FROM
                  tb_parte_diario
                  LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_parte_diario.id_cuartel
                  LEFT JOIN tb_finca ON tb_parte_diario.id_finca = tb_finca.id_finca
                  LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
                  LEFT JOIN tb_labor ON tb_parte_diario.id_labor = tb_labor.id_labor
                  LEFT JOIN tb_tipo_labor ON tb_labor.id_tipo_labor = tb_tipo_labor.id_tipo_labor
                  WHERE
                  tb_parte_diario.fecha BETWEEN '$desde' AND '$hasta' $consulta_finca$consulta_labor
                  GROUP BY
                  tb_parte_diario.id_cuartel,
                  tb_parte_diario.id_labor";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsconsumo = mysqli_query($conexion, $sqlconsumo);
	$cantidad =  mysqli_num_rows($rsconsumo);
	while ($datos = mysqli_fetch_array($rsconsumo)){
	  $finca=utf8_encode($datos['finca']);
    $cuartel=utf8_encode($datos['cuartel']);
    $variedad=utf8_encode($datos['variedad']);
    $has_cuartel=utf8_encode($datos['has_cuartel']);
    $labor=utf8_encode($datos['labor']);
    $tipo=utf8_encode($datos['tipo']);
    $horas_t=utf8_encode($datos['horas_t']);
    $has_t=$datos['has_t'];
    $obs_labor=utf8_encode($datos['obs_labor']);
    $obs_gral=utf8_encode($datos['obs_gral']);
		
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
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue($a, $finca)
      ->setCellValue($b, $cuartel)
      ->setCellValue($c, $variedad)
      ->setCellValue($d, $has_cuartel)
			->setCellValue($e, $labor)
			->setCellValue($f, $tipo)
			->setCellValue($g, $horas_t)
			->setCellValue($h, $has_t)
      ->setCellValue($i, $obs_labor)
			->setCellValue($j, $obs_gral);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$j";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte labores_cuartel');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_labores_cuartel.xls"');
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