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
$sqlaforador = "SELECT
tb_aforador.id_aforador as id_aforador,
tb_aforador.nombre as aforador
FROM
tb_aforador
LEFT JOIN tb_calibra_formula ON tb_calibra_formula.id_aforador = tb_aforador.id_aforador
WHERE
tb_aforador.id_finca = '$id_finca_usuario'
order by
tb_aforador.nombre ASC
";
$rsaforador = mysqli_query($conexion, $sqlaforador); 
?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_calibracion()">

 <h4><span class="label label-default">Alta Calibración</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
      <label for="inputPassword" class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="calibracion_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="calibracion_nombre" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Aforador</label>
        <div class="col-lg-10">
          <select class="form-control" id="calibracion_aforador" required>
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
      <label  class="control-label">Opción 1: Ingresar valores fórmula Q= aH² + bH </label><br><br>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Valor 'a'</label>
        <div class="col-lg-2">
          <input type="text" class="form-control" autocomplete="off" id="calibracion_a" style="width: 65px;" aria-describedby="basic-addon1" required autofocus="">
        </div>
        <label for="inputPassword" class="col-lg-2 control-label">Valor 'b'</label>
        <div class="col-lg-2">
          <input type="text" class="form-control" autocomplete="off" id="calibracion_b" style="width: 65px;" aria-describedby="basic-addon1" required autofocus="">
        </div>
        <div class="col-lg-2">
          <button type="submit" id="boton_guardar" style="width: 85px; height:30px" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Guardar</button> 
        </div>         
      </div>

<!--       <label  class="control-label">Opción 2: Ver tabla cargada</label><br><br>
      <div class="col-lg-12">
          <div align="left">
          <button class="btn btn-default btn-sm" type="button" onclick="()">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver tabla</button>
          </div>
        </div>
      <br><br> -->
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
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
            <th>Nombre</th>
            <th>Fecha alta</th>
            <th>Aforador</th>
            <th>Ecuación</th>
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

              $sqlcalibracion = "SELECT
              tb_calibra_formula.id_calibra_formula AS id_calibracion,
              tb_calibra_formula.nombre AS calibra,
              DATE_FORMAT(tb_calibra_formula.fecha, '%d/%m/%Y') AS fecha,
              tb_aforador.nombre AS afora,
              CONCAT('Q= ',tb_calibra_formula.valor_a,'H² + ',tb_calibra_formula.valor_b,'H') as formula
              FROM
              tb_calibra_formula
              LEFT JOIN tb_aforador ON tb_aforador.id_aforador = tb_calibra_formula.id_aforador
              WHERE
              tb_aforador.id_finca = '$id_finca_usuario'
              ORDER BY
              afora ASC";
              $rscalibracion = mysqli_query($conexion, $sqlcalibracion);
              
              $cantidad =  mysqli_num_rows($rscalibracion);

              if ($cantidad > 0) { // si existen aforador con de esa aforador se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rscalibracion)){
              $id_calibracion=utf8_encode($datos['id_calibracion']);
              $calibra=utf8_encode($datos['calibra']);
              $fecha=utf8_encode($datos['fecha']);
              $afora=utf8_encode($datos['afora']);
              $formula=$datos['formula'];
              
              echo '

              <tr>
                <td>'.$calibra.'</td>
                <td>'.$fecha.'</td>
                <td>'.$afora.'</td>
                <td>'.$formula.'</td>
                <td><button type="button" class="ver_riego ver_riego-danger ver_riego-xs" value="'.$id_calibracion.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay calibraciones cargadas.";
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
                     
           var pars = "id_calibra_formula=" + numero + "&";

           
          $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
          $.ajax({
              url : "class/altas/elimina_calibracion.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó calibración!</div>');
                  setTimeout("llama_alta_calibracion()", 1050);
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