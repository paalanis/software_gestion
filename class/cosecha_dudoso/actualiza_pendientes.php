<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$id_global_ciu=$_POST['id_global_ciu'];

$sqlpendiente = "SELECT
DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
tb_cosecha.ciu as ciu,
tb_cosecha.remito as remito,
tb_cosecha.destino as destino,
tb_cosecha.kilos as kilos,
tb_cosecha.id_global as id_global
FROM
tb_cosecha
WHERE
tb_cosecha.id_global = '$id_global_ciu'
";

$rspendiente = mysqli_query($conexion, $sqlpendiente);
$cantidad =  mysqli_num_rows($rspendiente);
if ($cantidad > 0) { // si existen pendiente con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rspendiente)){
$fecha=utf8_encode($datos['fecha']);
$remito=utf8_encode($datos['remito']);
$ciu=$datos['ciu'];
$destino=utf8_encode($datos['destino']);
$kilos=utf8_encode($datos['kilos']);
$id_global=utf8_encode($datos['id_global']);

}
 }
?>


<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); actualiza_remitos()">

       <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <h4><span class="label label-default">Actualizar remito</span></h4>
          </div>
        </div>

        <input type="hidden" class="form-control" id="pendiente_id_global" value="<?php echo $id_global_ciu;?>" aria-describedby="basic-addon1"></div>
       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="pendiente_fecha" value="<?php echo $fecha;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Remito N°</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" autocomplete="off" id="pendiente_remito" value="<?php echo $remito;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>
       
       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Destino</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" autocomplete="off" id="pendiente_destino" value="<?php echo $destino;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>
        
       <div class="form-group form-group-sm"> 
        <label for="textArea" class="col-lg-2 control-label">CIU</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" autocomplete="off" id="pendiente_ciu" value="<?php echo $ciu;?>" placeholder="Completar" autofocus="" style="background-color: #c9302c; color: white" aria-describedby="basic-addon1" required>
          </div>
        </div>

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Kilos</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" autocomplete="off" id="pendiente_kilos" placeholder="Completar" style="background-color: #c9302c; color: white" value="" autofocus="" required>
          </div>

        <div class="col-lg-6">
          <div class="btn-group btn-group-sm" role="group" aria-label="...">
            <button type="submit" id="boton_actualizar" class="btn btn-primary btn-sm" aria-label="Left Align">Actualizar 
            <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            </button>
            <button type="button" id="boton_actualizar" class="btn btn-default btn-sm" onclick='llama_pendientes_cosecha()' aria-label="Left Align">Salir 
            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
            </button>
          </div>  
        </div>
      </div>

</form>

<script type="text/javascript">
$('#pendiente_kilos').mask("##.00", {reverse: true});
</script>