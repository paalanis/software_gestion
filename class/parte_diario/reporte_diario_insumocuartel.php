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
      <th>Finca</th>
      <th>Cuartel</th>
      <th>Variedad</th>
      <th>Has</th>
      <th>Insumo</th>
      <th>Tipo</th>
      <th>Ppio Activo</th>
      <th>Concentración</th>
      <th>Cantidad</th>
      <th>Unidad</th>
    </tr>
  </thead>
  <tbody>
   
        <?php

        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $insumo=$_POST['insumo'];

        $consulta_insumo = "";

        if ($insumo != "") {
        $consulta_insumo = "AND tb_insumo_proporcional_".$deposito.".id_insumo = '$insumo' ";
        }

        $sqlcuarteles = "SELECT
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

        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rscuarteles)){
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
          
          echo '
          <tr>
            <td>'.$finca.'</td>
            <td>'.$cuartel.'</td>
            <td>'.$variedad.'</td>
            <td>'.$has.'</td>
            <td>'.$insumo.'</td>
            <td>'.$tipo.'</td>
            <td>'.$ppio_activo.'</td>
            <td>'.$concentracion.'</td>
            <td>'.$cantidad.'</td>
            <td>'.$unidad.'</td>
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