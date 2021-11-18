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
            ->setCellValue('A1', 'Reporte - Cosecha')
            ->setCellValue('A2', 'Fecha')
            ->setCellValue('B2', 'Finca')
            ->setCellValue('C2', 'CIU')
			->setCellValue('D2', 'Remito')
			->setCellValue('E2', 'Transporte')
			->setCellValue('F2', 'Chofer')
			->setCellValue('G2', 'Patente')
			->setCellValue('H2', 'Destino')
			->setCellValue('I2', 'Cosechadora')
			->setCellValue('J2', 'Manual_propia')
			->setCellValue('K2', 'Manual_terceros')
			->setCellValue('L2', 'Fichas')
			->setCellValue('M2', 'Precio')
			->setCellValue('N2', 'Cuartel')
			->setCellValue('O2', 'Variedad')
			->setCellValue('P2', 'Has')
			->setCellValue('Q2', 'Has_cosechadas')
			->setCellValue('R2', 'Horas')
			->setCellValue('S2', 'Kilos')
			->setCellValue('T2', 'Observación')
			->setCellValue('U2', 'numero_parte');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:U2')->applyFromArray($boldArray);		

	
			
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
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);		

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
				DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
				tb_finca.nombre AS finca,
				tb_cosecha.ciu AS ciu,
				tb_cosecha.remito AS remito,
				tb_transporte.razon_social AS transporte,
				tb_cosecha.chofer AS chofer,
				tb_cosecha.patente AS patente,
				tb_cosecha.destino AS destino,
				tb_cosechadora.nombre AS cosechadora,
				tb_cosecha.manual_p AS manual_propia,
				tb_cosecha.manual_t AS manual_terceros,
				tb_cosecha.fichas AS fichas,
				tb_cosecha.precio AS precio,
				tb_cuartel.nombre AS cuartel,
				tb_variedad.nombre AS variedad,
				tb_cuartel.has AS has,
				round(tb_cosecha.has,2) AS has_cosechadas,
				round(tb_cosecha.horas,2) AS horas,
				tb_cosecha.kilos AS kilos,
				tb_cosecha.obs AS obs,
				tb_cosecha.id_global AS id_global
				FROM
				tb_cosecha
				LEFT JOIN tb_transporte ON tb_cosecha.id_transporte = tb_transporte.id_transporte
				LEFT JOIN tb_finca ON tb_cosecha.id_finca = tb_finca.id_finca
				LEFT JOIN tb_cosechadora ON tb_cosechadora.id_cosechadora = tb_cosecha.id_cosechadora
				LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_cosecha.id_cuartel
				LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
				WHERE
				tb_cosecha.fecha BETWEEN '$desde' AND '$hasta' and tb_cosecha.pendiente = '1'
				ORDER BY
				ciu ASC";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsriego = mysqli_query($conexion, $sqlriego);
	$cantidad =  mysqli_num_rows($rsriego);
	while ($datos = mysqli_fetch_array($rsriego)){
	$fecha=utf8_encode($datos['fecha']);
	$finca=utf8_encode($datos['finca']);
	$ciu=$datos['ciu'];
	$remito=utf8_encode($datos['remito']);
	$transporte=utf8_encode($datos['transporte']);
    $chofer=utf8_encode($datos['chofer']);
	$patente=utf8_encode($datos['patente']);
	$destino=utf8_encode($datos['destino']);
	$cosechadora=utf8_encode($datos['cosechadora']);
	$manual_propia=utf8_encode($datos['manual_propia']);
	$manual_terceros=utf8_encode($datos['manual_terceros']);
	$fichas=utf8_encode($datos['fichas']);
	$precio=utf8_encode($datos['precio']);
	$cuartel=utf8_encode($datos['cuartel']);
	$variedad=utf8_encode($datos['variedad']);
	$has=utf8_encode($datos['has']);
	$has_cosechadas=utf8_encode($datos['has_cosechadas']);
	$horas=utf8_encode($datos['horas']);
	$kilos=utf8_encode($datos['kilos']);
	$obs=utf8_encode($datos['obs']);
	$id_global=utf8_encode($datos['id_global']);
		
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
			$r="R".$cel;
			$s="S".$cel;
			$t="T".$cel;
			$u="U".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $fecha)
            ->setCellValue($b, $finca)
            ->setCellValue($c, $ciu)
            ->setCellValue($d, $remito)
			->setCellValue($e, $transporte)
			->setCellValue($f, $chofer)
			->setCellValue($g, $patente)
			->setCellValue($h, $destino)
			->setCellValue($i, $cosechadora)
			->setCellValue($j, $manual_propia)
			->setCellValue($k, $manual_terceros)
			->setCellValue($l, $fichas)
			->setCellValue($m, $precio)
			->setCellValue($n, $cuartel)
			->setCellValue($o, $variedad)
			->setCellValue($p, $has)
			->setCellValue($q, $has_cosechadas)
			->setCellValue($r, $horas)
			->setCellValue($s, $kilos)
			->setCellValue($t, $obs)
			->setCellValue($u, $id_global);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$u";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte cosecha');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_cosecha.xls"');
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