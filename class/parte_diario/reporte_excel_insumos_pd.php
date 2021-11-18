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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Insumos en parte diario')
            ->setCellValue('A2', 'Parte')
            ->setCellValue('B2', 'Fecha')
            ->setCellValue('C2', 'Personal')
      			->setCellValue('D2', 'Labor')
      			->setCellValue('E2', 'Horas')
      			->setCellValue('F2', 'Cuarteles')
      			->setCellValue('G2', 'Insumo')
      			->setCellValue('H2', 'Cantidad')
      			->setCellValue('I2', 'Unidad');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray($boldArray);		

	
			
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

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
        $finca=$_POST['post_finca'];
        $desde=$_POST['post_desde'];
        $hasta=$_POST['post_hasta'];
        $labor=$_POST['post_labor'];
        $insumo=$_POST['post_insumo'];
        $reporte=$_POST['post_tiporeporte'];

  include '../../conexion/conexion.php';
  if (mysqli_connect_errno()) {
  printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
  exit();
  }

if ($reporte=="1"){

        $consulta_finca = "";
        $consulta_insumo = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_consumo_alternativo.id_finca = '$finca' ";
        }
        if ($insumo != "") {
        $consulta_insumo = "AND tb_consumo_alternativo.id_insumo = '$insumo' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_consumo_alternativo.id_labor = '$labor' ";
        }
        $sqlcuarteles = "SELECT
                        tb_consumo_alternativo.id_parte_diario_global AS parte, 
                        DATE_FORMAT(tb_consumo_alternativo.fecha, '%d/%m/%Y') AS fecha, 
                        tb_consumo_alternativo.personal AS personal, 
                        tb_labor.nombre AS labor, 
                        tb_consumo_alternativo.horas AS horas, 
                        tb_consumo_alternativo.cuarteles AS cuarteles, 
                        tb_insumo.nombre_comercial AS insumo, 
                        tb_consumo_alternativo.cantidad AS cantidad, 
                        tb_consumo_alternativo.unidad AS unidad
                      FROM
                        tb_consumo_alternativo
                        INNER JOIN
                        tb_labor
                        ON 
                          tb_consumo_alternativo.id_labor = tb_labor.id_labor
                        INNER JOIN
                        tb_insumo
                        ON 
                          tb_consumo_alternativo.id_insumo = tb_insumo.id_insumo
                      WHERE
                        tb_consumo_alternativo.fecha BETWEEN '$desde' AND '$hasta' $consulta_finca$consulta_insumo$consulta_labor
                      ORDER BY
                        tb_consumo_alternativo.fecha ASC";

  }

  if ($reporte=="0"){

        $consulta_finca = "";
        $consulta_insumo = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_parte_diario.id_finca = '$finca' ";
        }
        if ($insumo != "") {
        $consulta_insumo = "AND tb_consumo_insumos_".$deposito.".id_insumo = '$insumo' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_parte_diario.id_labor = '$labor' ";
        }

        $sqlcuarteles = "SELECT
                      CONCAT(LEFT(tb_parte_diario.id_parte_diario_global, 8), '-', RIGHT(tb_parte_diario.id_parte_diario_global, 6)) AS parte,
                      DATE_FORMAT(tb_parte_diario.fecha, '%d/%m/%y') AS fecha,
                      tb_parte_diario.id_finca,
                      CONCAT(tb_personal.apellido, ', ',tb_personal.nombre) AS personal,
                      tb_labor.nombre AS labor,
                      Round(sum(tb_parte_diario.horas_trabajadas),2) AS horas,
                      GROUP_CONCAT(tb_cuartel.nombre ORDER BY tb_cuartel.nombre ASC ) AS cuarteles,
                      tb_insumo.nombre_comercial AS insumo,
                      tb_consumo_insumos_".$deposito.".egreso AS cantidad,
                      tb_unidad.nombre as unidad
                      FROM
                      tb_parte_diario
                      LEFT JOIN tb_consumo_insumos_".$deposito." ON tb_consumo_insumos_".$deposito.".id_parte_diario_global = tb_parte_diario.id_parte_diario_global
                      LEFT JOIN tb_personal ON tb_parte_diario.id_personal = tb_personal.id_personal
                      LEFT JOIN tb_labor ON tb_parte_diario.id_labor = tb_labor.id_labor
                      INNER JOIN tb_insumo ON tb_insumo.id_insumo = tb_consumo_insumos_".$deposito.".id_insumo
                      LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_parte_diario.id_cuartel
                      LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
                      WHERE
                      tb_parte_diario.fecha BETWEEN '$desde' AND '$hasta' $consulta_finca$consulta_insumo$consulta_labor
                      GROUP BY
                      tb_parte_diario.id_parte_diario_global,
                      tb_consumo_insumos_".$deposito.".id_insumo
                      ORDER BY
                      tb_parte_diario.fecha ASC,
                      parte ASC";
  }


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rscuarteles = mysqli_query($conexion, $sqlcuarteles);
  $cantidad =  mysqli_num_rows($rscuarteles);
	
  while ($datos = mysqli_fetch_array($rscuarteles)){
	    $parte=utf8_encode($datos['parte']);
      $fecha=utf8_encode($datos['fecha']);
      $personal=utf8_encode($datos['personal']);
      $labor=utf8_encode($datos['labor']);
      $horas=$datos['horas'];
      $cuarteles=utf8_encode($datos['cuarteles']);
      $insumo=utf8_encode($datos['insumo']);
      $cantidad=$datos['cantidad'];
      $unidad=utf8_encode($datos['unidad']);
		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			$f="F".$cel;
			$g="G".$cel;
			$h="H".$cel;
			$i="I".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $parte)
            ->setCellValue($b, $fecha)
            ->setCellValue($c, $personal)
            ->setCellValue($d, $labor)
			->setCellValue($e, $horas)
			->setCellValue($f, $cuarteles)
			->setCellValue($g, $insumo)
			->setCellValue($h, $cantidad)
			->setCellValue($i, $unidad);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$i";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('borderStyle'=> Border::BORDER_THIN,'color'=>array('argb' => '00000000')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte insumos_p_diario');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_insumo_pd-'.date("d.m.y").'.xls"');
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