<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
?>
<div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:200px">
<table class="table table-hover">
  <thead>
    <tr style="height:5px">
      <th>#</th>
      <th>Nombre</th>
      <th>Hileras</th>
      <th>Hil_Reales</th>
      <th>Has</th>
      <th>Has-Reales</th>
      <th>Kilos</th>
    </tr>
  </thead>
  <tbody>
   
        <?php
        
        $finca=$_POST['finca'];
        $variedad=$_POST['variedad'];

        $consulta_variedad = "";

        if ($variedad != "") {
        $consulta_variedad = "AND tb_cuartel.id_variedad = '$variedad' ";
        }
      

        include '../../conexion/conexion.php';

         if (mysqli_connect_errno()) {
         printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
         exit();
}

        $sqlcuarteles = "SELECT
tb_cuartel.nombre as cuartel,                        
CAST(tb_cuartel.nombre AS SIGNED) as orden_cuartel,
                        tb_variedad.nombre as variedad,
                        tb_cuartel.has as has,
                        tb_cuartel.id_cuartel as id_cuartel,
                        tb_cuartel.hileras as hileras
                        FROM
                        tb_cuartel
                        INNER JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
                        WHERE
                        tb_cuartel.id_finca = '$finca' $consulta_variedad
                        ORDER BY
                        orden_cuartel ASC";
        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
        $contador = 0;

        while ($datos = mysqli_fetch_array($rscuarteles)){
        $nombrecuartel=utf8_encode($datos['cuartel']);
        $variedad=utf8_encode($datos['variedad']);
        $has=$datos['has'];
        $id=$datos['id_cuartel'];
        $hileras=$datos['hileras'];

        $contador = $contador + 1;

        echo '


        <tr>
          <th><input type="checkbox" value="'.$id.'" name="'.$contador.'"  class="cbxcuartel" id="cuartel'.$contador.'" ></th>
          <td><input type="text" class="form-control" id="nombre'.$contador.'" style="width: 70px; height:25px" value="'.$nombrecuartel.'" disabled></td>
          <td><input type="text" class="form-control" id="hileras_ant_'.$contador.'" style="width: 45px; height:25px" value="'.$hileras.'" disabled></td>
          <td><input type="number" class="form-control-hil" id="hileras_'.$contador.'" name="'.$contador.'" style="width: 65px; height:25px" value="'.$hileras.'" disabled></td>
          <td><input type="text" class="form-control" id="has_'.$contador.'" style="width: 45px; height:25px" value="'.$has.'" disabled></td>
          <td><input type="text" class="form-control-has" autocomplete="off" id="has_seleccionadas'.$contador.'" style="width: 75px; height:25px" value="0" disabled></td>
          <td><input type="text" class="form-control-kilos" autocomplete="off" id="kilos'.$contador.'" style="width: 70px; height:25px" value="0" disabled required></td>
          </td>
        </tr>
        ';
    
        }   
                
        $idinicial=1;
        $idfinal=$contador;

        echo '<input type="hidden" class="form-control" id="idinicial" value="'.$idinicial.'">
        <input type="hidden" class="form-control" id="totalhas" value="0">
        <input type="hidden" class="form-control" id="totalkilos" value="0">
        <input type="hidden" class="form-control" id="idfinal" value="'.$idfinal.'">
        <input type="hidden" class="form-control" id="control_cuartel" value="1">'; 
        
        
        }
        ?>
  </tbody>
</table> 
<?php
 if ($cantidad == 0){

          echo "La finca no tiene cuarteles cargados.<input type='hidden' class='form-control' id='control_cuartel' value='0'>";
        }
?>
</div>
</div>

<script type="text/javascript">

  $(document).ready(function () {

    var idinicial = $('#idinicial').val();
    var idfinal = $('#idfinal').val();    
    
    for (var i = idinicial; i <= idfinal; i++) {
        
      $('#has_seleccionadas'+i).mask("##.00", {reverse: true});

    };  
    
  });

  $(function() {
        $('.cbxcuartel').click(function() {

           var numero = $(this).attr('name')
           var estado = $(this).prop('checked')
                     
           if (estado == true) {

                var has = $('#has_'+numero).val()
                var hil = $('#hileras_ant_'+numero).val()
                var hil_r = $('#hileras_'+numero).val()
                var has = has/hil*hil_r;
                $('#has_seleccionadas'+numero).val(has.toFixed(2))
                document.getElementById('has_seleccionadas'+numero).disabled = false
                document.getElementById('hileras_'+numero).disabled = false
                $('#kilos'+numero).val('0')
                document.getElementById('kilos'+numero).disabled = false

                var idinicial = $('#idinicial').val();
                var idfinal = $('#idfinal').val();
                var suma = 0;
                var sumakg = 0;

                for (var i = idinicial; i <= idfinal; i++) {
                  
                  var ha = $('#has_seleccionadas'+i).val();
                  var suma = parseFloat(ha) + suma;
                  $('#totalhas').val(suma);
                  
                  var kg = $('#kilos'+i).val();
                  var sumakg = parseFloat(kg) + sumakg;
                  $('#totalkilos').val(sumakg);

                };

                // alert(sumakg)

           }else{

               var has = $('#has_'+numero).val()
               $('#has_seleccionadas'+numero).val('0')
               document.getElementById('has_seleccionadas'+numero).disabled = true
               document.getElementById('hileras_'+numero).disabled = true
               $('#kilos'+numero).val('0')
               document.getElementById('kilos'+numero).disabled = true

               var idinicial = $('#idinicial').val();
               var idfinal = $('#idfinal').val();
               var suma = 0;
               var sumakg = 0;

               for (var i = idinicial; i <= idfinal; i++) {
                
                var ha = $('#has_seleccionadas'+i).val();
                var suma = parseFloat(ha) + suma;
                $('#totalhas').val(suma);

                var kg = $('#kilos'+i).val();
                var sumakg = parseFloat(kg) + sumakg;
                $('#totalkilos').val(sumakg);

                }

                // alert(sumakg)

           }
          
          if (sumakg != 0) {
            document.getElementById('diario_kilos').disabled = true
          }else{
            document.getElementById('diario_kilos').disabled = false
          }
   
        })
      })

$(function() {
        $('.form-control-has').change(function() {

           var numero = $(this).attr('name')
           var estado = $(this).prop('checked')
                     
           if (estado == true) {

                var idinicial = $('#idinicial').val();
                var idfinal = $('#idfinal').val();
                var suma = 0;

                for (var i = idinicial; i <= idfinal; i++) {
                  
                  var ha = $('#has_seleccionadas'+i).val();
                  var suma = parseFloat(ha) + suma;

                  $('#totalhas').val(suma);

                };

                // alert(suma)

           }else{


               var idinicial = $('#idinicial').val();
               var idfinal = $('#idfinal').val();
               var suma = 0;

               for (var i = idinicial; i <= idfinal; i++) {
                
                var ha = $('#has_seleccionadas'+i).val();
                var suma = parseFloat(ha) + suma;

                $('#totalhas').val(suma);

                }

                // alert(suma)

           }
          
   
        })
      })

$(function() {
        $('.form-control-kilos').change(function() {

           var numero = $(this).attr('id')
           var numero = numero.substring(5)
           var estado = $('#cuartel'+numero).prop('checked')

       
           if (estado == true) {

                var idinicial = $('#idinicial').val();
                var idfinal = $('#idfinal').val();
                var suma = 0;

                for (var i = idinicial; i <= idfinal; i++) {
                  
                  var kg = $('#kilos'+i).val();
                  if (kg== '') {
                    $('#kilos'+i).val(0);
                    kg=0;
                  };
                  var suma = parseFloat(kg) + suma;
                  $('#totalkilos').val(suma);


                };

                // alert(suma)

           }else{


               var idinicial = $('#idinicial').val();
               var idfinal = $('#idfinal').val();
               var suma = 0;

               for (var i = idinicial; i <= idfinal; i++) {
                
                var kg = $('#kilos'+i).val();
                var suma = parseFloat(kg) + suma;
                $('#totalkilos').val(suma);

                }

                // alert(suma)

           }
          
          if (suma != 0) {
            document.getElementById('diario_kilos').disabled = true
            $('#diario_kilos').val('');
          }else{
            document.getElementById('diario_kilos').disabled = false
          }

          
        })
      })


$(function() {
        $('.form-control-hil').change(function() {

           var numero = $(this).attr('name')
           var estado = $('#cuartel'+numero).prop('checked')
                  
           if (estado == true) {

                var valor_hil = $('#hileras_'+numero).val()
               
                if ( valor_hil == '0') {

                 $('#hileras_'+numero).val($('#hileras_ant_'+numero).val())

                  var has = $('#has_'+numero).val()
                  var hil = $('#hileras_ant_'+numero).val()
                  var hil_r = $('#hileras_'+numero).val()
                  var has = has/hil*hil_r;
                  var has = parseFloat(has)
                  $('#has_seleccionadas'+numero).val(has.toFixed(2)) 

                 for (var i = idinicial; i <= idfinal; i++) {
                  
                  var ha = $('#has_seleccionadas'+i).val();
                  var suma = parseFloat(ha) + suma;

                  $('#totalhas').val(suma);

                  };
                
                }else{

                var has = $('#has_'+numero).val()
                var hil = $('#hileras_ant_'+numero).val()
                var hil_r = $('#hileras_'+numero).val()
                var has = has/hil*hil_r;
                var has = parseFloat(has)
                $('#has_seleccionadas'+numero).val(has.toFixed(2)) 
               
                var idinicial = $('#idinicial').val();
                var idfinal = $('#idfinal').val();
                var suma = 0;

                for (var i = idinicial; i <= idfinal; i++) {
                  
                  var ha = $('#has_seleccionadas'+i).val();
                  var suma = parseFloat(ha) + suma;

                  $('#totalhas').val(suma);

                  };
                }

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

                $('#totalhas').val(suma);

                }

                // alert(suma)

           }
          
   
        })
      })

 </script>