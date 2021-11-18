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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');

$objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Reporte - Insumos por Cuartel')
      ->setCellValue('A2', 'Finca')
      ->setCellValue('B2', 'Cuartel')
      ->setCellValue('C2', 'Variedad')
			->setCellValue('D2', 'Has')
			->setCellValue('E2', 'Insumo')
			->setCellValue('F2', 'Tipo')
			->setCellValue('G2', 'Ppio Activo')
			->setCellValue('H2', 'Concentracion')
			->setCellValue('I2', 'Cantidad')
      ->setCellValue('J2', 'Unidad');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER));

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
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);			
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);      

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
        $desde=$_POST['post_desde'];
        $hasta=$_POST['post_hasta'];
        $insumo=$_POST['post_insumo'];

        $consulta_insumo = "";
   
    if ($insumo != "") {
        $consulta_insumo = "AND tb_insumo_proporcional_".$deposito.".id_insumo = '$insumo' ";
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
                tb_cuartel.has AS has,
                tb_insumo.nombre_comercial AS insumo,
                tb_tipo_insumo.nombre AS tipo,
                tb_insumo.principio_activo AS ppio_activo,
                tb_insumo.concentracion AS concentracion,
                ROUND(Sum(tb_insumo_proporcional_".$deposito.".proporcion),3) AS cantidad,
                tb_unidad.nombre AS unidad
                FROM
                tb_insumo_proporcional_".$deposito."
                LEFT JOIN tb_insumo ON tb_insumo.id_insumo = tb_insumo_proporcional_".$deposito.".id_insumo
                LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
                LEFT JOIN tb_tipo_insumo ON tb_tipo_insumo.id_tipo_insumo = tb_insumo.id_tipo_insumo
                LEFT JOIN tb_cuartel ON tb_insumo_proporcional_".$deposito.".id_cuartel = tb_cuartel.id_cuartel
                LEFT JOIN tb_finca ON tb_cuartel.id_finca = tb_finca.id_finca
                LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
                WHERE
                tb_insumo_proporcional_".$deposito.".fecha BETWEEN '$desde' AND '$hasta' $consulta_insumo
                GROUP BY
                tb_insumo_proporcional_".$deposito.".id_cuartel,
                tb_insumo_proporcional_".$deposito.".id_insumo";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsconsumo = mysqli_query($conexion, $sqlconsumo);
	$cantidad =  mysqli_num_rows($rsconsumo);
	while ($datos = mysqli_fetch_array($rsconsumo)){
	  $finca=utf8_encode($datos['finca']);
    $cuartel=utf8_encode($datos['cuartel']);
    $variedad=utf8_encode($datos['variedad']);
    $has=utf8_encode($datos['has']);
    $insumo=$datos['insumo'];
    $tipo=$datos['tipo'];
    $ppio_activo=utf8_encode($datos['ppio_activo']);
    $concentracion=$datos['concentracion'];
    $cantidad=utf8_encode($datos['cantidad']);
    $unidad=$datos['unidad'];
		
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
      ->setCellValue($d, $has)
			->setCellValue($e, $insumo)
			->setCellValue($f, $tipo)
			->setCellValue($g, $ppio_activo)
			->setCellValue($h, $concentracion)
      ->setCellValue($i, $cantidad)
			->setCellValue($j, $unidad);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$j";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allBorders'=>array('borderStyle'=> Border::BORDER_THIN,'color'=>array('argb' => '00000000')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte insumos_cuartel');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_insumo_cuartel.xls"');
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