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
$parte=$_POST['parte'];


?>
<!-- Modal -->
<div class="modal fade" id="modal_reporte" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reporte parte cosecha n° <?php echo $parte; ?></h4>
      </div>
      <div class="modal-body">

       <div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover" id="Exportar_a_Excel1">
<thead>
<tr style="height:5px">
<th>Cuartel</th>
<th>Variedad</th>
<th>Has</th>
<th>Has cosechadas</th>
<th>Has pendientes</th>
<th>Kilos</th>
<th>Kilos/ha</th>
</tr>
</thead>
<tbody>
<?php    

$sqlmodal_cosecha = "SELECT
                  tb_cuartel.nombre AS cuartel,
                  tb_variedad.nombre AS variedad,
                  tb_cuartel.has AS has,
                  round(tb_cosecha.has,2) AS has_cosechadas,
                  round(tb_cuartel.has-tb_cosecha.has,2) AS has_pendientes,
                  tb_cosecha.kilos AS kilos,
                  round(tb_cosecha.kilos/tb_cosecha.has,2) AS kilos_ha
                  FROM
                  tb_cosecha
                  LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_cosecha.id_cuartel
                  LEFT JOIN tb_variedad ON tb_cuartel.id_variedad = tb_variedad.id_variedad
                  WHERE
                  tb_cosecha.id_global = '$parte'
                  ORDER BY
                  cuartel ASC
                  ";

$rsmodal_cosecha = mysqli_query($conexion, $sqlmodal_cosecha);
$cantidad =  mysqli_num_rows($rsmodal_cosecha);
if ($cantidad > 0) { // si existen modal_cosecha con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsmodal_cosecha)){
$cuartel=utf8_encode($datos['cuartel']);
$variedad=utf8_encode($datos['variedad']);
$has=$datos['has'];
$has_cosechadas=utf8_encode($datos['has_cosechadas']);
$has_pendientes=utf8_encode($datos['has_pendientes']);
$kilos=utf8_encode($datos['kilos']);
$kilos_ha=utf8_encode($datos['kilos_ha']);


echo '
<tr>
<td>'.$cuartel.'</td>
<td>'.$variedad.'</td>
<td>'.$has.'</td>
<td>'.$has_cosechadas.'</td>
<td>'.$has_pendientes.'</td>
<td>'.$kilos.'</td>
<td>'.$kilos_ha.'</td>
</tr>
';
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


      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$('#modal_reporte').modal('show')
</script>

<script type="text/javascript">
$('#modal_reporte').on('hidden.bs.modal', function (e) {
reporte = reporte_de_cosecha()
})
</script>
