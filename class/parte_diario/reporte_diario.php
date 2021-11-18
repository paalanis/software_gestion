<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../../index.php");
}
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
      <th>Parte</th>
      <th>Fecha</th>
      <th>Personal</th>
      <th>Cuartel</th>
      <th>Has</th>
      <th>Labor</th>
      <th>Obs labor</th>
      <th>Horas</th>
      <th>Obs general</th>
    </tr>
  </thead>
  <tbody>

        <?php
        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $finca=$_POST['finca'];
        $labor=$_POST['labor'];
        $personal=$_POST['personal'];

        $consulta_finca = "";
        $consulta_personal = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_parte_diario.id_finca = '$finca' ";
        }
        if ($personal != "") {
        $consulta_personal = "AND tb_parte_diario.id_personal = '$personal' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_parte_diario.id_labor = '$labor' ";
        }

        $sqlcuarteles = "SELECT
                          tb_parte_diario.id_parte_diario_global as id_global,
                          DATE_FORMAT(tb_parte_diario.fecha, '%d/%m/%Y') AS fecha,
                          CONCAT(tb_personal.apellido,', ',tb_personal.nombre) AS personal,
                          GROUP_CONCAT(tb_cuartel.nombre ORDER BY tb_cuartel.nombre ASC ) AS cuarteles,
                          round(sum(tb_parte_diario.has),2) AS has,
                          tb_labor.nombre AS labor,
                          tb_parte_diario.obs_labor AS obs_labor,
                          round(sum(tb_parte_diario.horas_trabajadas), 2) AS horas,
                          tb_parte_diario.obs_general AS obs_gral
                          FROM
                          tb_parte_diario
                          LEFT JOIN tb_personal ON tb_parte_diario.id_personal = tb_personal.id_personal
                          LEFT JOIN tb_cuartel ON tb_parte_diario.id_cuartel = tb_cuartel.id_cuartel
                          LEFT JOIN tb_labor ON tb_labor.id_labor = tb_parte_diario.id_labor
                          WHERE
                          tb_parte_diario.fecha BETWEEN '$desde' AND '$hasta' $consulta_finca$consulta_personal$consulta_labor
                          GROUP BY
                          tb_parte_diario.id_parte_diario_global
                          ORDER BY
                          tb_parte_diario.fecha DESC,
                          tb_parte_diario.id_parte_diario_global desc
                          ";
        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rscuarteles)){
          $id_global=utf8_encode($datos['id_global']);
          $fecha=utf8_encode($datos['fecha']);
          $personal=utf8_encode($datos['personal']);
          $cuartel=utf8_encode($datos['cuarteles']);
          $has=$datos['has'];
          $labor=utf8_encode($datos['labor']);
          $obs_labor=utf8_encode($datos['obs_labor']);
          $horas=utf8_encode($datos['horas']);
          $obs_gral=utf8_encode($datos['obs_gral']);

          if ($cuartel == "") {
                $cuartel = 'Sin asignar'; 
                $has = 'Sin asignar';
                
                        }      
          
          
          echo '

          <tr>
            <td>'.$id_global.'</td>
            <td>'.$fecha.'</td>
            <td>'.$personal.'</td>
            <td>'.$cuartel.'</td>
            <td>'.$has.'</td>
            <td>'.$labor.'</td>
            <td>'.$obs_labor.'</td>
            <td>'.$horas.'</td>
            <td>'.$obs_gral.'</td>
          </tr>
          ';
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
}   
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