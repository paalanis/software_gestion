<?php
header("Pragma: public");
header("Expires: 0");
$filename = "reporte_insumo_pd.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$deposito=$_SESSION['deposito'];
include '../../conexion/conexion.php';  
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

?>

<table>
  <thead>

  
    <h4>Reporte - Insumos en parte diario</h4>
    
    
    <tr>
      <th>Parte</th>
      <th>Fecha</th>
      <th>Personal</th>
      <th>Labor</th>
      <th>Horas</th>
      <th>Cuarteles</th>
      <th>Insumo</th>
      <th>Cantidad</th>
      <th>Unidad</th>
    </tr>
  </thead>
  <tbody>
   
        <?php
        $finca=$_POST['post_finca'];
        $desde=$_POST['post_desde'];
        $hasta=$_POST['post_hasta'];
        $labor=$_POST['post_labor'];
        $insumo=$_POST['post_insumo'];
        $reporte=$_POST['post_tiporeporte'];


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

        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rscuarteles)){
          $parte=utf8_encode($datos['parte']);
          $fecha=utf8_encode($datos['fecha']);
          $personal=utf8_encode($datos['personal']);
          $labor=utf8_decode($datos['labor']);
          $horas=$datos['horas'];
          $cuarteles=utf8_encode($datos['cuarteles']);
          $insumo=utf8_encode($datos['insumo']);
          $cantidad=$datos['cantidad'];
          $unidad=utf8_encode($datos['unidad']);
          

          
          // if ($cuarteles == "") {
          //       $cuarteles = 'Sin asignar'; 
                
          //               }              
          
          echo '
          <tr>
            <td>'.$parte.'</td>
            <td>'.$fecha.'</td>
            <td>'.$personal.'</td>
            <td>'.$labor.'</td>
            <td>'.$horas.'</td>
            <td>'.$cuarteles.'</td>
            <td>'.$insumo.'</td>
            <td>'.$cantidad.'</td>
            <td>'.$unidad.'</td>
            </tr>';
      
          }   
}
?>

</tbody>
</table> 
<?php
if ($cantidad == 0){
echo "No hay registros";
}
?>