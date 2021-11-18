<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../../index.php");
}
$deposito=$_SESSION['deposito'];
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$tipo=$_REQUEST['tipo'];


$sqlcosechadora = "SELECT
                tb_cosechadora.nombre as nombre,
                tb_cosechadora.id_cosechadora as id_cosechadora
                FROM
                tb_cosechadora
                WHERE
                tb_cosechadora.propia = '$tipo'
                ORDER BY
                nombre ASC";
$rscosechadora = mysqli_query($conexion, $sqlcosechadora);

?>
<form class="form-horizontal" role="form">
<select class="form-control" id="diario_mecanica" required>
	<option value="0" >Seleccione</option>
	<?php while ($datos = mysqli_fetch_array($rscosechadora)){
		$id_cosechadora= $datos['id_cosechadora'];
		$nombre = $datos['nombre'];
		echo utf8_encode('<option value='.$id_cosechadora.'>'.$nombre.'</option>');
	}?>
</select>

</form>