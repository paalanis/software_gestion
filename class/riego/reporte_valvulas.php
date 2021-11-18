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
$finca=$_POST['finca'];
 $sqlvalvulas = "SELECT
              tb_valvula.id_valvula AS id,
              tb_valvula.nombre AS valvula
              FROM
              tb_valvula
              WHERE
              tb_valvula.id_caudalimetro = '$finca'
              GROUP BY
              tb_valvula.nombre
              ORDER BY
              valvula ASC
              ";
 $rsvalvulas = mysqli_query($conexion, $sqlvalvulas); 

 $cantidad =  mysqli_num_rows($rsvalvulas);

?>


<select class="form-control" id="valvula">   
  <option value=""></option>
  <?php
  
  if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  

  while ($sql_valvulas = mysqli_fetch_array($rsvalvulas)){
    $idvalvulas= $sql_valvulas['id'];
    $valvulas = $sql_valvulas['valvula'];

    echo utf8_encode('<option value='.$valvulas.'>'.$valvulas.'</option>');
    
  }
  }else{
    echo utf8_encode('<option value="">Sin valvulas</option>');
  }


  ?>
</select>