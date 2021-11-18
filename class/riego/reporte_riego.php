<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
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
      <th>Parte N°</th>
      <th>Fecha</th>
      <th>Caudalímetro</th>
      <th>Válvulas</th>
      <th>Lectura I</th>
      <th>Lectura F</th>
      <th>m3</th>
      <th>Ver</th>
      </tr>
  </thead>
  <tbody>
   
        <?php
        
        $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $finca=$_POST['finca'];
        $caudalimetro=$_POST['caudalimetro'];
        $valvula=$_POST['valvula'];

        
        $consulta_caudalimetro = "";
        $consulta_valvula = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_finca.id_finca = '$finca' ";
        }
        if ($caudalimetro != "") {
        $consulta_caudalimetro = "AND tb_caudalimetro.id_caudalimetro = '$caudalimetro' ";
        }
        if ($valvula != "") {
        $consulta_valvula = "AND tb_valvula.nombre = '$valvula' ";
        }


        $sqlcuarteles = "SELECT
                        tb_milimetros_riego.id_global AS id_global,
                        DATE_FORMAT(tb_milimetros_riego.fecha, '%d/%m/%y') AS fecha,
                        tb_caudalimetro.nombre as caudalimetro,
                        IFNULL(GROUP_CONCAT(tb_valvula.nombre ORDER BY CAST(tb_valvula.nombre AS SIGNED) ASC ), 'Dilución') AS grupov,
                        IFNULL(tb_milimetros_riego.lectura_inicial, 0) as lectura_i,
                        IFNULL(tb_milimetros_riego.lectura_final, 0) as lectura_f,
                        ROUND(SUM(tb_milimetros_riego.mm_regados), 2) as m3,
                        tb_finca.nombre AS finca
                        FROM
                        tb_milimetros_riego
                        LEFT JOIN tb_valvula ON tb_valvula.id_valvula = tb_milimetros_riego.id_valvula
                        LEFT JOIN tb_caudalimetro ON tb_milimetros_riego.id_caudalimetro = tb_caudalimetro.id_caudalimetro
                        LEFT JOIN tb_finca ON tb_finca.id_finca = tb_caudalimetro.id_finca
                        WHERE
                        tb_milimetros_riego.fecha BETWEEN '$desde' AND '$hasta' $consulta_finca$consulta_caudalimetro$consulta_valvula
                        GROUP BY
                        tb_milimetros_riego.id_global
                        ORDER BY
                        caudalimetro DESC,
                        tb_milimetros_riego.fecha DESC";

        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
        $totalmm = 0;
        $contador = 0;

          while ($datos = mysqli_fetch_array($rscuarteles)){
          $id_global=$datos['id_global'];
          $fecha=utf8_encode($datos['fecha']);
          $lectura_i=utf8_encode($datos['lectura_i']);
          $lectura_f=utf8_encode($datos['lectura_f']);
          $caudalimetro2=utf8_encode($datos['caudalimetro']);
          $grupov=$datos['grupov'];
          $m3=utf8_encode($datos['m3']);
          
          $totalmm = round($m3,0,PHP_ROUND_HALF_UP) + $totalmm;
          $contador = $contador + 1;
   
	  echo '

          <tr>
            <td>'.$id_global.'</td>
            <td>'.$fecha.'</td>
            <td>'.$caudalimetro2.'</td>
            <td>'.$grupov.'</td>
            <td>'.$lectura_i.'</td>
            <td>'.$lectura_f.'</td>
            <td>'.$m3.'</td>
            <td><button type="button" name="'.$id_global.'" id="ver_riego" class="ver_riego ver_riego-info ver_riego-xs">
                  <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                </button></td>
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
}else{
        echo'<div align="right"><label><h4>Total general '.$totalmm.' m3</h4></label></div>';
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
<script type="text/javascript">
$(function() {
        $('.ver_riego-info').click(function() {

        var parte = $(this).attr('name')
        
         $("#div_reporte").load("class/riego/modal.php", {parte:parte});
          
        })
      })
</script>
<script type="text/javascript">
$('#myModal').on('hidden.bs.modal', function (e) {
})
</script>

