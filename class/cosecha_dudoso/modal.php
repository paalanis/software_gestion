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

$sqlterminado = "SELECT
DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
tb_cosecha.ciu AS ciu,
tb_cosecha.remito AS remito,
tb_transporte.razon_social AS transporte,
tb_cosecha.chofer AS chofer,
tb_cosecha.patente AS patente,
tb_cosecha.destino AS destino,
tb_cosechadora.nombre AS cosechadora,
tb_cosecha.manual_p AS propios,
tb_cosecha.manual_t AS terceros,
tb_cosecha.fichas AS fichas,
tb_cosecha.precio AS precio,
round(tb_cosecha.has_total,2) AS totalhas,
round(sum(tb_cosecha.horas),2) AS horas,
tb_cosecha.kilos AS kilos,
tb_cosecha.obs AS obs,
tb_cosecha.id_global AS id_global,
GROUP_CONCAT(tb_cuartel.nombre ORDER BY CAST(tb_cuartel.nombre AS SIGNED) ASC ) AS cuarteles,
tb_insumo.nombre_comercial as insumo,
CONCAT(tb_consumo_insumos_".$deposito.".egreso, ' ', tb_unidad.nombre) AS cantidad
FROM
tb_cosecha
left JOIN tb_transporte ON tb_transporte.id_transporte = tb_cosecha.id_transporte
left JOIN tb_cosechadora ON tb_cosechadora.id_cosechadora = tb_cosecha.id_cosechadora
left JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_cosecha.id_cuartel
left JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
LEFT JOIN tb_consumo_insumos_".$deposito." ON tb_cosecha.id_global = tb_consumo_insumos_".$deposito.".id_parte_diario_global
LEFT JOIN tb_insumo ON tb_insumo.id_insumo = tb_consumo_insumos_".$deposito.".id_insumo
LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
WHERE
tb_cosecha.id_global = '$parte'
GROUP BY
tb_cosecha.id_global
ORDER BY
fecha desc";

$rsterminado = mysqli_query($conexion, $sqlterminado);
$cantidad =  mysqli_num_rows($rsterminado);
if ($cantidad > 0) { // si existen terminado con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsterminado)){
$fecha=utf8_encode($datos['fecha']);
$remito=utf8_encode($datos['remito']);
$ciu=$datos['ciu'];
$transporte=utf8_encode($datos['transporte']);
$chofer=utf8_encode($datos['chofer']);
$patente=utf8_encode($datos['patente']);
$destino=utf8_encode($datos['destino']);
$cosechadora=utf8_encode($datos['cosechadora']);
$propios=utf8_encode($datos['propios']);
$terceros=utf8_encode($datos['terceros']);
$fichas=utf8_encode($datos['fichas']);
$precio=utf8_encode($datos['precio']);
$totalhas=utf8_encode($datos['totalhas']);
$horas=utf8_encode($datos['horas']);
$kilos=utf8_encode($datos['kilos']);
$cuarteles=utf8_encode($datos['cuarteles']);
$obs=utf8_encode($datos['obs']);
$id_global=utf8_encode($datos['id_global']);
$insumo=utf8_encode($datos['insumo']);
$cantidad=utf8_encode($datos['cantidad']);
} 
}

?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reporte parte cosecha n° <?php echo $parte; ?></h4>
      </div>
      <div class="modal-body">

       <div class="panel-body" id="Panel1" style="height:300px">

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Fecha</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" value="<?php echo $fecha;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Remito N°</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $remito;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">CIU</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $ciu;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Transporte</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $transporte;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Chofer</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $chofer;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Patente</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $patente;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Destino</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $destino;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Cosechadora</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $cosechadora;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Manual Propios</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $propios;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Manual Terceros</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $terceros;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Fichas</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $fichas;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Precio</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $precio;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Total has</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $totalhas;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Horas</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $horas;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Kilos</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $kilos;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Cuarteles</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $cuarteles;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Insumo</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $insumo;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Cantidad</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $cantidad;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Obsservaciones</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $obs;?>" aria-describedby="basic-addon1" disabled>
          </div>
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

})
</script>
