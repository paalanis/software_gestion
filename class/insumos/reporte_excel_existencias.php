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
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

require '../../vendor/autoload.php';

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
$objPHPExcel = new Spreadsheet();

// Propiedades del documento
$objPHPExcel->getProperties()->setCreator("Obed Alvarado")
							 ->setLastModifiedBy("Obed Alvarado")
							 ->setTitle("Office 2010 XLSX Documento de prueba")
							 ->setSubject("Office 2010 XLSX Documento de prueba")
							 ->setDescription("Documento de prueba para Office 2010 XLSX, generado usando clases de PHP.")
							 ->setKeywords("office 2010 openxml php")
							 ->setCategory("Archivo con resultado de prueba");



// Combino las celdas desde A1 hasta E1
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Existencias')
            ->setCellValue('A2', 'Insumo')
            ->setCellValue('B2', 'Saldo')
            ->setCellValue('C2', 'Unidad')
			->setCellValue('D2', 'Principio activo')
			->setCellValue('E2', '% Concentración');
	
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		

/*Extraer datos de MYSQL*/
	# conectare la base de datos
$insumo=$_POST['post_insumo'];
$consulta_insumos = "";
if ($insumo != "") {
$consulta_insumos = "AND tb_consumo_insumos_".$deposito.".id_insumo = '$insumo'";
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexi贸n con el servidor de base de datos fall贸 comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
		$sqlcuarteles = "SELECT
		tb_consumo_insumos_".$deposito.".id_consumo_insumos as id,
		tb_insumo.nombre_comercial as insumo,
		FORMAT(tb_consumo_insumos_".$deposito.".saldo, 2) as saldo,
		tb_unidad.nombre as unidad,
		tb_insumo.principio_activo as principio,
		tb_insumo.concentracion as concentracion
		FROM
		tb_consumo_insumos_".$deposito."
		INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
		INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
		WHERE
		tb_consumo_insumos_".$deposito.".id_consumo_insumos IN ((SELECT MAX(tb_consumo_insumos_".$deposito.".id_consumo_insumos ) FROM tb_consumo_insumos_".$deposito." GROUP BY tb_consumo_insumos_".$deposito.".id_insumo)) $consulta_insumos
		ORDER BY
		insumo asc
		";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rscuarteles = mysqli_query($conexion, $sqlcuarteles);
	$cantidad =  mysqli_num_rows($rscuarteles);
	while ($datos = mysqli_fetch_array($rscuarteles)){
	$insumo=utf8_encode($datos['insumo']);
	$saldo=utf8_encode($datos['saldo']);
	$unidad=utf8_decode($datos['unidad']);
	$principio=utf8_decode($datos['principio']);
	$concentracion=utf8_encode($datos['concentracion']);
		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $insumo)
            ->setCellValue($b, $saldo)
            ->setCellValue($c, $unidad)
            ->setCellValue($d, $principio)
			->setCellValue($e, $concentracion);
			

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$e";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('borderStyle'=> Border::BORDER_THIN,'color'=>array('argb' => '00000000')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte Existencias');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_existencias-'.date("d.m.y").'.xls"');
header('Cache-Control: max-age=0');
// Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
header('Cache-Control: max-age=1');

// Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
$objWriter->save('php://output');
exit;