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

?>
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); traspasos()">
 <h4><span class="label label-default">Detalle de insumos recibidos de otros depósitos</span></h4>
 <div class="well bs-component">
 <div class="row">

 <div class="col-lg-6">
 
   <fieldset id="div_remitos">
    
      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:230px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Fecha</th>
            <th>Insumo</th>
            <th>U. Medida</th>
            <th>Cantidad</th>
            <th>Origen</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              include '../../conexion/conexion.php';
               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlingreso = "SELECT
              DATE_FORMAT(tb_consumo_insumos_".$deposito.".fecha, '%d/%m/%y') AS fecha,
              tb_insumo.nombre_comercial AS insumo,
              tb_unidad.nombre AS unidad,
              tb_deposito.nombre AS origen,
              tb_consumo_insumos_".$deposito.".ingreso as ingreso
              FROM
              tb_consumo_insumos_".$deposito."
              INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
              INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
              INNER JOIN tb_deposito ON tb_deposito.id_deposito = tb_consumo_insumos_".$deposito.".id_deposito_origen
              ORDER BY
              tb_consumo_insumos_".$deposito.".id_consumo_insumos DESC
              ";
              $rsingreso = mysqli_query($conexion, $sqlingreso);
              
              $cantidad =  mysqli_num_rows($rsingreso);

              if ($cantidad > 0) { // si existen ingreso con de esa ingreso se muestran, de lo contrario queda en blanco  
                
              while ($datos = mysqli_fetch_array($rsingreso)){
              $fecha=utf8_encode($datos['fecha']);
              $insumo=utf8_encode($datos['insumo']);
              $unidad=utf8_encode($datos['unidad']);
              $ingreso=utf8_encode($datos['ingreso']);
              $origen=utf8_encode($datos['origen']);
                
              echo '

              <tr>
                <td>'.$fecha.'</td>
                <td>'.$insumo.'</td>
                <td>'.$unidad.'</td>
                <td>'.$ingreso.'</td>
                <td>'.$origen.'</td>
                </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay traspasos.";
              }
      ?>
      </div>
      </div>  
         
   </fieldset>
  </div> 
</div>  
</div>
</form>
