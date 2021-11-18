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
date_default_timezone_set("America/Argentina/Mendoza");
$sqlaforador = "SELECT
tb_aforador.id_aforador as id_aforador,
tb_aforador.nombre as aforador
FROM
tb_aforador
WHERE
tb_aforador.id_finca = '$id_finca_usuario'
order by
tb_aforador.nombre ASC";
$rsaforador = mysqli_query($conexion, $sqlaforador);
?>
<input type="hidden" class="form-control" value="<?php echo $id_finca_usuario; ?>" id="id_finca_usuario" aria-describedby="basic-addon1">
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); carga_riego_manto()">
 
 <h4><span class="label label-default">Parte Riego-manto</span></h4>
 <div class="well bs-component">
 <div class="row">
   <div class="col-lg-6">
     <fieldset>
       
       <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Aforador</label>
        <div class="col-lg-10">
          <select class="form-control" id="riego_aforador" required autofocus="">
          <option value=''></option>   
              <?php
              while ($sql_aforador = mysqli_fetch_array($rsaforador)){
                $idaforador= $sql_aforador['id_aforador'];
                $aforador = $sql_aforador['aforador'];
                echo utf8_encode('<option value='.$idaforador.'>'.$aforador.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
     </fieldset>
    
     <div class="row">
    <div class="col-lg-12">
      <div class="col-lg-4">
        <div align="center">
            <label class="control-label">Medición inicial</label><br><br>
          </div>
      </div>
      <div class="col-lg-4">
        <div align="center">
            <label class="control-label">Medición media</label><br><br>
          </div>
      </div>
      <div class="col-lg-4">
        <div align="center">
            <label class="control-label">Medición final</label><br><br>
          </div>
      </div>  
    </div>
    
  </div> 


 <div class="row">
   <div class="col-lg-12">
   <fieldset>
      
      <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <input type="datetime-local" class="form-control" style="width: 180px;" id="fecha_1"  aria-describedby="basic-addon1" required >
        </div>
        <div class="col-lg-4">
          <input type="datetime-local" class="form-control" style="width: 180px;" id="fecha_2"  aria-describedby="basic-addon1" required>
        </div>
        <div class="col-lg-4">
          <input type="datetime-local" class="form-control" style="width: 180px;" id="fecha_3"  aria-describedby="basic-addon1" required>
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <input type="text" class="form-control-kilos" style="width: 180px;" name='1' autocomplete="off" id="altura_1" placeholder="Altura Inicial (H)" aria-describedby="basic-addon1" required >
        </div>
        <div class="col-lg-4">
          <input type="text" class="form-control-kilos" style="width: 180px;" name='2' autocomplete="off" id="altura_2" placeholder="Altura Media (H)" aria-describedby="basic-addon1" required >
        </div>
        <div class="col-lg-4">
          <input type="text" class="form-control-kilos" style="width: 180px;" name='3' autocomplete="off" id="altura_3" placeholder="Altura Final (H)" aria-describedby="basic-addon1" required >
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <input type="text" class="form-control" style="width: 180px;" autocomplete="off" id="calculo_1" placeholder="Cálculo Inicial (Q)" aria-describedby="basic-addon1" disabled>
        </div>
        <div class="col-lg-4">
          <input type="text" class="form-control" style="width: 180px;" autocomplete="off" id="calculo_2" placeholder="Cálculo Medio (Q)" aria-describedby="basic-addon1" disabled>
        </div>
        <div class="col-lg-4">
          <input type="text" class="form-control" style="width: 180px;" autocomplete="off" id="calculo_3" placeholder="Cálculo Final (Q)" aria-describedby="basic-addon1" disabled>
        </div>
      </div>
    
        <div class="form-group form-group-sm">
        <div class="col-lg-9">
          <div align="center" id="div_mensaje_general">
          
          </div>
          
        </div>
        <div class="col-lg-3">
          <div align="right">
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>

   </fieldset>
  </div>


   </div>
   
 
 </div> 
  
  <div class="col-lg-6">
     <div id='div_cuarteles'></div> 
   </div> 

 </div>
</form>


 <script type="text/javascript">
 $(document).ready(function () {
  var variedad = ""
  var panel = "280"
  var finca = $('#id_finca_usuario').val();
  $("#div_cuarteles").html('<div class="text-center"><div class="loadingsm"></div></div>');
  $("#div_cuarteles").load("class/parte_diario/cuarteles.php", {finca: finca, variedad: variedad, panel: panel});
  $('#boton_guardar').attr('disabled', true);
  // $('.form-control').mask("##.00", {reverse: true});
  });

 $(function() { 
        $('#riego_aforador').change(function() {

          var aforador = $(this).val()
          $(".form-control-kilos").val('')

          if (aforador != ''){

           if ($('#id_finca_usuario').val() == '4'){

            // se usa la tabla ya cargada de mumm, ver formula siguiente
            $("#div_mensaje_general").html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>El valor se trae de una tabla cargada</div>');

           }else{ // se usan las calibraciones cargadas
              var pars = "aforador=" + aforador + "&";

              $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');

               $.ajax({
                url : "class/riego/control_aforador.php",
                data : pars,
                dataType : "json",
                type : "get",

                success: function(data){
                    
                  if (data.success == 'true') {

                    $(".form-control-kilos").val('')
                    $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Este aforador no tiene calibraciones cargadas</div>');       
                    $('#boton_guardar').attr('disabled', true);
                  }else {

                    // $(".form-control-kilos").val('')
                    $('#boton_guardar').attr('disabled', false);                
                    $("#div_mensaje_general").html('<label class="control-label">Fórmula de calibración:  Q ='+data.valor_a+'H² + '+data.valor_b+'H </label><input type="hidden" class="form-control" style="width: 60px;" value='+data.valor_a+' autocomplete="off" id="valor_a" aria-describedby="basic-addon1"><input type="hidden" class="form-control" style="width: 60px;" value='+data.valor_b+' autocomplete="off" id="valor_b"  aria-describedby="basic-addon1">');
                    
                  }
                
                 }

                });
          }
          }else{
            $('#mensaje_general').alert('close')
            $('#div_mensaje_general').html('');
            $(".form-control-kilos").val('')
            $('#boton_guardar').attr('disabled', true);
            $('#calculo_1').val('')
            $('#calculo_2').val('')
            $('#calculo_3').val('')
    
            }
        })
      })

  $(function() { 
        $('.form-control-kilos').change(function() {

          if ($('#riego_aforador').val() != '' ) {

            if ($('#id_finca_usuario').val() == '4'){

              var altura = $(this).val()
              var pars = "altura=" + altura + "&";
              var calc = $(this).attr('name')

              $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');

               $.ajax({
                url : "class/riego/tabla_aforador_mumm.php",
                data : pars,
                dataType : "json",
                type : "get",

                success: function(data){
                    
                  if (data.success == 'true') {

                    $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>No existe caudal para el valor ingresado</div>');       
                    $('#boton_guardar').attr('disabled', true);
                    $('#altura_'+calc).val('')
                  }else {

                    // $(".form-control-kilos").val('')
                    $('#boton_guardar').attr('disabled', false);                
                    $('#calculo_'+calc).val(data.caudal)
                    $("#div_mensaje_general").html('');
                  }
                
                 }

                });    
                

            }else{

              var calc = $(this).attr('name')
              var valor_a = $('#valor_a').val()
              var valor_b = $('#valor_b').val()
              var h = $(this).val()
              var q = (valor_a* Math.pow(h,2)) + (valor_b * h)

              $('#calculo_'+calc).val(q)

            }
         
          }

            if ($(this).val() == ''){
              
              $('#calculo_'+calc).val('')
            
            }
            
          
        })
      })


    
  </script>