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
$caudalimetro=$_POST['caudalimetro'];

$sqllectura_final = "SELECT
					IFNULL(Max(tb_milimetros_riego.lectura_final),0) as lectura_final
					FROM
					tb_milimetros_riego
					WHERE
					tb_milimetros_riego.id_caudalimetro = '$caudalimetro'";
$rslectura_final = mysqli_query($conexion, $sqllectura_final);
$datos = mysqli_fetch_array($rslectura_final);
$lectura_final=$datos['lectura_final'];

echo '<input type="text" class="form-control" style="width: 85px;" value="'.$lectura_final.'" autocomplete="off" id="riego_inicial" placeholder="Lec. inicial" aria-describedby="basic-addon1" onblur="consumo_milimetros()" required autofocus="">';

?>




