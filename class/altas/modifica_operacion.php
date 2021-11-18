<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$opcion=$_REQUEST['opcion'];
$id=$_REQUEST['id'];
$texto=$_REQUEST['texto'];
?>

<div class="input-group input-group-sm">
<input type="text" id="texto_modificado" class="form-control" autocomplete="off" value="<?php echo $texto; ?>" required>
<input type="hidden" id="id_modificado" class="form-control"  value="<?php echo $id; ?>">
<input type="hidden" id="opcion_modificada" class="form-control"  value="<?php echo $opcion; ?>">
<span class="input-group-btn">
<button class="btn btn-primary" type="button" onclick="modifica_operaciones()"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
</span>
</div>
