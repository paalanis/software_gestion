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
$sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as finca
              FROM
              tb_finca
              where
              tb_finca.id_finca = '$id_finca_usuario'
              order by
              tb_finca.nombre ASC";
$rsfinca = mysqli_query($conexion, $sqlfinca); 
?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_aforadores()">

 <h4><span class="label label-default">Alta Aforador</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_aforador" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Finca</label>
        <div class="col-lg-10">
          <select class="form-control" id="alta_aforador_finca" required>
          <option value=''></option>   
              <?php
              while ($sql_finca = mysqli_fetch_array($rsfinca)){
                $idfinca= $sql_finca['id_finca'];
                $finca = $sql_finca['finca'];
                echo utf8_encode('<option value='.$idfinca.'>'.$finca.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Detalle</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="alta_aforador_detalle" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
         
          </div>
          
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
          
        </div>
      </div>
      
      
   </fieldset>
 
 </div>
 <div class="col-lg-7">
 
   <fieldset>
    
      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:180px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Aforador</th>
            <th>Finca</th>
            <th>Detalle</th>
            <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              
              
              include '../../conexion/conexion.php';

               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlaforador = "SELECT
              tb_aforador.id_aforador AS id_aforador,
              tb_aforador.nombre AS aforador,
              tb_finca.nombre AS finca,
              tb_aforador.detalle AS detalle
              FROM
              tb_aforador
              LEFT JOIN tb_finca ON tb_finca.id_finca = tb_aforador.id_finca
              WHERE
              tb_aforador.id_finca = '$id_finca_usuario'
              ORDER BY
              finca ASC,
              aforador ASC";
              $rsaforador = mysqli_query($conexion, $sqlaforador);
              
              $cantidad =  mysqli_num_rows($rsaforador);

              if ($cantidad > 0) { // si existen aforador con de esa aforador se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsaforador)){
              $id_aforador=utf8_encode($datos['id_aforador']);
              $aforador=utf8_encode($datos['aforador']);
              $finca=utf8_encode($datos['finca']);
              $detalle=utf8_encode($datos['detalle']);
              
              echo '

              <tr>
                <td>'.$aforador.'</td>
                <td>'.$finca.'</td>
                <td>'.$detalle.'</td>
                <td><button type="button" class="ver_riego ver_riego-danger ver_riego-xs" value="'.$id_aforador.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay aforadores cargados.";
              }
      ?>
      </div>
      </div>  
         
   </fieldset>
  </div> 
 </div>  
 </div>
</form>

<script type="text/javascript">

  $(function() {
        $('.ver_riego-danger').click(function() {

           var numero = $(this).val()
                     
           var pars = "id_aforador=" + numero + "&";

           
          $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
          $.ajax({
              url : "class/altas/elimina_aforador.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó aforador!</div>');
                  setTimeout("llama_alta_aforador()", 1050);
                  setTimeout("$('#mensaje_general').alert('close')", 2000);


                } else {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');        
                  setTimeout("$('#mensaje_general').alert('close')", 2000);
                }
              
              }

          });

              
        })
      })

 </script>