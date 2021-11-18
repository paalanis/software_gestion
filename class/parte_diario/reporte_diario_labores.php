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
include '../../conexion/conexion.php';  
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
?>
<div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Cuartel</th>
      <th>Variedad</th>
      <th>Has</th>
      <th>Labor</th>
      <th>Tipo</th>
      <th>Horas_t</th>
      <th>Has_t</th>
      <th>Obs_labor</th>
      <th>Obs_gral</th>
    </tr>
  </thead>
  <tbody>
   
        <?php
        $finca=$_POST['finca'];
        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $labor=$_POST['labor'];
        
        $consulta_finca = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_parte_diario.id_finca = '$finca' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_parte_diario.id_labor = '$labor' ";
        }

        $sqlcuarteles = "SELECT
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

        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rscuarteles)){
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
          
          echo '
          <tr>
            <td>'.$cuartel.'</td>
            <td>'.$variedad.'</td>
            <td>'.$has_cuartel.'</td>
            <td>'.$labor.'</td>
            <td>'.$tipo.'</td>
            <td>'.$horas_t.'</td>
            <td>'.$has_t.'</td>
            <td>'.$obs_labor.'</td>
            <td>'.$obs_gral.'</td>
            </tr>';
      
          }   
        
          ?>
<script type="text/javascript">
  document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){
echo "No hay registros";
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "hidden";
</script>
<?php
}

?>
</div>
</div>
<script type="text/javascript">
$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })
</script>