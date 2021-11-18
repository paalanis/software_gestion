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
exit(); } 
$tamano_cuadro=$_REQUEST['tamano_cuadro'];
?>
<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:<?php echo $tamano_cuadro;?>px;">
<table class="table table-hover">
<thead>
<tr>
<th>Nombre</th>
<th>Cantidad</th>
<th></th>
</tr>
</thead>
<tbody>
<?php
$sqlinsumos = "SELECT
tb_consumo_insumos_".$deposito.".id_consumo_insumos as id,
tb_insumo.nombre_comercial as nombre,
tb_consumo_insumos_".$deposito.".egreso as cantidad
FROM
tb_consumo_insumos_".$deposito."
INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
WHERE
tb_consumo_insumos_".$deposito.".estado = '0'
ORDER BY
tb_consumo_insumos_".$deposito.".id_consumo_insumos ASC
";
$rsinsumos = mysqli_query($conexion, $sqlinsumos);
$cantidad =  mysqli_num_rows($rsinsumos);
if ($cantidad > 0) { // si existen insumos con de esa finca se muestran, de lo contrario queda en blanco  
$contador = 0;
while ($datos = mysqli_fetch_array($rsinsumos)){
$nombreinsumo=utf8_encode($datos['nombre']);
$cantidad=utf8_encode($datos['cantidad']);
$id=$datos['id'];
$contador = $contador + 1;
echo '
<tr>
<td><input type="text" class="form-control" id="nombre'.$id.'" style="width: 205px; height:25px" value="'.$nombreinsumo.'" disabled></td>
<td><input type="text" class="form-control" id="cantidad'.$id.'" style="width: 60px; height:25px" value="'.$cantidad.'" disabled></td>
<td><button type="button" class="ver_riego ver_riego-danger ver_riego-xs" value="'.$id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
<td><input type="hidden" class="form-control" id="id'.$id.'" style="width: 55px; height:25px" value="'.$id.'" disabled></td>
</td>
</tr>
';
}   
// $idinicial=$id-$contador+1;
// $idfinal=$id;
// echo '<input type="text" class="form-control" id="idinicial" value="'.$idinicial.'">
// <input type="text" class="form-control" id="idfinal" value="'.$idfinal.'">'; 
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){
// echo "La finca no tiene insumos cargados.";
?>
<script type="text/javascript">
$('#div_insumos_cargados').html('')
</script>
<?php
}
?>
</div>
</div>
<script type="text/javascript">
$(function() {
$('.ver_riego-danger').click(function() {
var numero = $(this).val()
var pars = "id=" + numero + "&";
$("#div_insumos_cargados").html('<div class="text-center"><div class="loadingsm"></div></div>');
$.ajax({
url : "class/parte_diario/elimina_insumo.php",
data : pars,
dataType : "json",
type : "get",
success: function(data){
if (data.success == 'true') {
$("#div_insumos_cargados").load("class/parte_diario/insumos_cargados.php");
} else {
$('#div_insumos_cargados').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');        
setTimeout("$('#mensaje_general').alert('close')", 2000);
}
}
});
})
})
</script>