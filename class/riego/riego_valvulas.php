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
?>
<div class="panel panel-default">

<div class="panel-body" id="panelvalvulas" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Operación</th>
      <th>Válvula</th>
      <th>Has</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>
   
<?php

$caudalimetro=$_POST['caudalimetro'];

$sqlvalvulas = "SELECT
                tb_valvula.nombre as valvula,
                tb_valvula.id_valvula as id_valvula,
                tb_operacion.nombre as operacion,
                tb_operacion_asignada.id_operacion as id_operacion,
                tb_valvula.has_asignadas as has
                FROM
                tb_operacion_asignada
                INNER JOIN tb_valvula ON tb_valvula.id_valvula = tb_operacion_asignada.id_valvula
                INNER JOIN tb_operacion ON tb_operacion.id_operacion = tb_operacion_asignada.id_operacion
                WHERE
                tb_valvula.id_caudalimetro = '$caudalimetro'
                ORDER BY
                tb_operacion.nombre ASC";
$rsvalvulas = mysqli_query($conexion, $sqlvalvulas);

$cantidad =  mysqli_num_rows($rsvalvulas);

if ($cantidad > 0) { // si existen valvulas con de esa finca se muestran, de lo contrario queda en blanco  

$contador = 0;

while ($datos = mysqli_fetch_array($rsvalvulas)){
$valvula=utf8_encode($datos['valvula']);
$id_valvula=utf8_encode($datos['id_valvula']);
$operacion=utf8_encode($datos['operacion']);
$id_operacion=utf8_encode($datos['id_operacion']);
$has=$datos['has'];

$contador = $contador + 1;

echo '

<tr>
  <th><input type="text" class="form-control" id="operacion_'.$contador.'" style="width: 95px; height:25px" value="'.$operacion.'" readonly></th>
  <th><input type="text" class="form-control" id="valvula_nom_'.$contador.'" style="width: 75px; height:25px" value="'.$valvula.'"></th>
  <th><input type="text" class="form-control" id="has_'.$contador.'" style="width: 50px; height:25px" value="'.$has.'" readonly></th>
  <th><input type="checkbox" name="'.$id_operacion.'" value="'.$contador.'" class="cbxvalvula" id="'.$contador.'" ></th>
  <th><input type="hidden" class="form-control" id="has_seleccionadas'.$contador.'"  value="0" readonly></th>
  <th><input type="hidden" class="form-control" id="valvula_'.$contador.'"  value="'.$id_valvula.'" readonly></th>
</tr>
';

}

$idinicial=1;
$idfinal=$contador;

  echo '<input type="hidden" class="form-control" id="idinicial" value="'.$idinicial.'">
  <input type="hidden" class="form-control" id="totalhas" value="0">
  <input type="hidden" class="form-control" id="idfinal" value="'.$idfinal.'">';    
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){

  echo "La finca no tiene Válvulas cargadas.<input type='hidden' class='form-control' id='totalhas' value='0'>";
  }
?>
</div>
</div>



<script type="text/javascript">

  $(function() {
        $('.cbxvalvula').change(function() {

           var numero = $(this).val()
           var estado = $(this).prop('checked')
                     
           if (estado == true) {

                var has = $('#has_'+numero).val()
                $('#has_seleccionadas'+numero).val(has)
              
                var idinicial = $('#idinicial').val();
                var idfinal = $('#idfinal').val();
                var suma = 0;

                for (var i = idinicial; i <= idfinal; i++) {
                  
                  var ha = $('#has_seleccionadas'+i).val();
                  var suma = parseFloat(ha) + suma;

                  $('#totalhas').val(suma.toFixed(2));

                };

                // alert(suma)

           }else{

               var has = $('#has_'+numero).val()
               $('#has_seleccionadas'+numero).val('0')
               
               var idinicial = $('#idinicial').val();
               var idfinal = $('#idfinal').val();
               var suma = 0;

               for (var i = idinicial; i <= idfinal; i++) {
                
                var ha = $('#has_seleccionadas'+i).val();
                var suma = parseFloat(ha) + suma;

                $('#totalhas').val(suma.toFixed(2));

                }

                // alert(suma)

           }
          
   
        })
      })



 </script>      