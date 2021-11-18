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

<div class="panel-body" id="paneloperacion" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Operacion</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>
   
<?php

$caudalimetro=$_POST['caudalimetro'];

$sqloperacion = "SELECT
                tb_operacion.nombre as operacion,
                tb_operacion.id_operacion as id_operacion,
                tb_operacion.id_finca as id_finca
                FROM
                tb_operacion
                WHERE
                tb_operacion.id_caudalimetro = '$caudalimetro'
                ORDER BY
                operacion ASC
                ";
$rsoperacion = mysqli_query($conexion, $sqloperacion);

$cantidad =  mysqli_num_rows($rsoperacion);

if ($cantidad > 0) { // si existen operacion con de esa finca se muestran, de lo contrario queda en blanco  

$contador = 0;

while ($datos = mysqli_fetch_array($rsoperacion)){
$operacion=utf8_encode($datos['operacion']);
$id_operacion=utf8_encode($datos['id_operacion']);
$id_finca=utf8_encode($datos['id_finca']);

$contador = $contador + 1;

echo '

<tr>
  <th>'.$operacion.'</th>
  <th><input type="checkbox" value="'.$id_operacion.'" class="cbxoperacion" id="operacion_'.$contador.'" ></th>
  </tr>
';

}

$idinicial=1;
$idfinal=$contador;

  echo '<input type="hidden" class="form-control" id="idinicial_op" value="'.$idinicial.'">
  <input type="hidden" class="form-control" id="idfinal_op" value="'.$idfinal.'">';    
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){

  echo "No hay operaciones cargadas.";
  }
?>
</div>
</div>



<script type="text/javascript">

  $(function() {
        $('.cbxoperacion').click(function() {

           var numero = $(this).val()
           var estado = $(this).prop('checked')
           var inicio = $('#idinicial').val()
           var fin = $('#idfinal').val()
                     
           if (estado == true) {

            for (var i = inicio; i <= fin; i++) {
             
                var valvula = $('#'+i).attr('name')
                if (valvula == numero) {

                    $('#'+i).prop('checked',true)

                    var has = $('#has_'+i).val()
                    $('#has_seleccionadas'+i).val(has)
                  
                    var idinicial = $('#idinicial').val();
                    var idfinal = $('#idfinal').val();
                    var suma = 0;

                    for (var v = idinicial; v <= idfinal; v++) {
                      
                      var ha = $('#has_seleccionadas'+v).val();
                      var suma = parseFloat(ha) + suma;

                      $('#totalhas').val(suma.toFixed(2));

                    } } }


           }else{

            for (var i = inicio; i <= fin; i++) {
             
                var valvula = $('#'+i).attr('name')
                if (valvula == numero) {

                  $('#'+i).prop('checked',false)

                   var has = $('#has_'+i).val()
                   $('#has_seleccionadas'+i).val('0')
                   
                   var idinicial = $('#idinicial').val();
                   var idfinal = $('#idfinal').val();
                   var suma = 0;

                   for (var v = idinicial; v <= idfinal; v++) {
                    
                    var ha = $('#has_seleccionadas'+v).val();
                    var suma = parseFloat(ha) + suma;

                    $('#totalhas').val(suma.toFixed(2));

                    }

                }

            }
           }

   
        })
      })



 </script>      