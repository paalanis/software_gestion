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
$parte=$_POST['parte'];
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reporte n° <?php echo $parte; ?></h4>
      </div>
      <div class="modal-body">


<div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Fecha</th>
      <th>Válvulas</th>
      <th>Cuartel</th>
      <th>Variedad</th>
      <th>Has</th>
      <th>m3</th>
      </tr>
  </thead>
  <tbody>
   
        <?php
         $sqlmodal_riego = "SELECT
              DATE_FORMAT(tb_milimetros_riego.fecha, '%d/%m/%y') AS fecha,
              tb_valvula.nombre AS valvula,
              tb_cuartel.nombre AS cuartel,
              tb_variedad.nombre AS variedad,
              tb_valvula.has_asignadas AS has,
              FORMAT(tb_milimetros_riego.mm_regados, 2) AS m3,
              tb_milimetros_riego.id_global as id_global
              FROM
              tb_milimetros_riego
              INNER JOIN tb_valvula ON tb_milimetros_riego.id_valvula = tb_valvula.id_valvula
              INNER JOIN tb_cuartel ON tb_valvula.id_cuartel = tb_cuartel.id_cuartel
              INNER JOIN tb_caudalimetro ON tb_valvula.id_caudalimetro = tb_caudalimetro.id_caudalimetro
              INNER JOIN tb_finca ON tb_finca.id_finca = tb_cuartel.id_finca
              INNER JOIN tb_variedad ON tb_cuartel.id_variedad = tb_variedad.id_variedad
              WHERE
              tb_milimetros_riego.id_global = '$parte'
              ORDER BY
              id_global asc,
              fecha ASC,
              valvula ASC,
              cuartel ASC";

     
        $rsmodal_riego = mysqli_query($conexion, $sqlmodal_riego);
        
        $cantidad =  mysqli_num_rows($rsmodal_riego);

        if ($cantidad > 0) { // si existen modal_riego con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rsmodal_riego)){
          $fecha=utf8_encode($datos['fecha']);
          $valvula=$datos['valvula'];
          $cuartel=utf8_encode($datos['cuartel']);
          $variedad=$datos['variedad'];
          $has=$datos['has'];
          $m3=utf8_encode($datos['m3']);

   
    echo '

          <tr>
            <td>'.$fecha.'</td>
            <td>'.$valvula.'</td>
            <td>'.$cuartel.'</td>
            <td>'.$variedad.'</td>
            <td>'.$has.'</td>
            <td>'.$m3.'</td>
            </tr>
          ';
         
          }   
        
        }
        ?>

</tbody>
</table> 
</div>
</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$('#myModal').modal('show')
</script>

<script type="text/javascript">
$('#myModal').on('hidden.bs.modal', function (e) {
reporte= reporte_riego()
})
</script>
