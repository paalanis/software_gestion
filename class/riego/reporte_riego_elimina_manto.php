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

<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Parte N°</th>
      <th>Aforador</th>
      <th>Fecha-I</th>
      <th>Lect-I</th>
      <th>Calc-I</th>
      <th>Fecha-M</th>
      <th>Lect-M</th>
      <th>Calc-M</th>
      <th>Fecha-F</th>
      <th>Lect-F</th>
      <th>Calc-F</th>
      <th></th>
      </tr>
  </thead>
  <tbody>
   
        <?php
        
        $desde=$_POST['desde'];
        $finca=$_POST['finca'];
        $aforador=$_POST['aforador'];

        $consulta_finca = "";
        $consulta_aforador = "";
     

        if ($finca != "") {
        $consulta_finca = "AND tb_aforador.id_finca = '$finca' ";
        }
        if ($aforador != "") {
        $consulta_aforador = "AND tb_aforador.id_aforador = '$aforador' ";
        }
        

        $sqlmilimetros = "SELECT
                        tb_riego_manto.id_global AS id_global,
                        tb_aforador.nombre AS aforador,
                        GROUP_CONCAT(tb_cuartel.nombre ORDER BY tb_cuartel.nombre ASC ) AS cuartel,
                        DATE_FORMAT(tb_riego_manto.fe_ho_1, '%d/%m/%Y - %H:%i') AS fecha_i,
                        tb_riego_manto.altura_1 AS lectura_i,
                        tb_riego_manto.calculo_1 AS calculo_i,
                        DATE_FORMAT(tb_riego_manto.fe_ho_2, '%d/%m/%Y - %H:%i') AS fecha_m,
                        tb_riego_manto.altura_2 AS lectura_m,
                        tb_riego_manto.calculo_2 AS calculo_m,
                        DATE_FORMAT(tb_riego_manto.fe_ho_3, '%d/%m/%Y - %H:%i') AS fecha_f,
                        tb_riego_manto.altura_3 AS lectura_f,
                        tb_riego_manto.calculo_3 AS calculo_f
                        FROM
                        tb_riego_manto
                        LEFT JOIN tb_aforador ON tb_riego_manto.id_aforador = tb_aforador.id_aforador
                        LEFT JOIN tb_cuartel ON tb_cuartel.id_cuartel = tb_riego_manto.id_cuartel
                        WHERE
                        DATE_FORMAT(tb_riego_manto.fe_ho_1, '%Y-%m-%d') = '$desde' $consulta_finca$consulta_aforador
                        GROUP BY
                        tb_riego_manto.id_global
                        ORDER BY
                        tb_riego_manto.fe_ho_1 DESC";
        $rsmilimetros = mysqli_query($conexion, $sqlmilimetros);
        
        $cantidad =  mysqli_num_rows($rsmilimetros);

        if ($cantidad > 0) { // si existen milimetros con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rsmilimetros)){
          $id_global=$datos['id_global'];
          $aforador=utf8_encode($datos['aforador']);
          $cuartel=utf8_encode($datos['cuartel']);
          $fecha_i=utf8_encode($datos['fecha_i']);
          $lectura_i=utf8_encode($datos['lectura_i']);
          $calculo_i=utf8_encode($datos['calculo_i']);
          $fecha_m=utf8_encode($datos['fecha_m']);
          $lectura_m=utf8_encode($datos['lectura_m']);
          $calculo_m=utf8_encode($datos['calculo_m']);
          $fecha_f=utf8_encode($datos['fecha_f']);
          $lectura_f=utf8_encode($datos['lectura_f']);
          $calculo_f=utf8_encode($datos['calculo_f']);
             
          
          echo '

          <tr>
            <td>'.$id_global.'</td>
            <td>'.$aforador.'</td>
            <td>'.$cuartel.'</td>
            <td>'.$fecha_i.'</td>
            <td>'.$lectura_i.'</td>
            <td>'.$calculo_i.'</td>
            <td>'.$fecha_m.'</td>
            <td>'.$lectura_m.'</td>
            <td>'.$calculo_m.'</td>
            <td>'.$fecha_f.'</td>
            <td>'.$lectura_f.'</td>
            <td>'.$calculo_f.'</td>
            <td><button type="button" class="ver_riego ver_riego-danger ver_riego-xs" value="'.$id_global.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
            </tr>
          ';
      
          }   
        
        }
        ?>
  </tbody>
</table> 
<?php
 if ($cantidad == 0){

          echo "No hay registros";
        }
?>
</div>
</div>

<script type="text/javascript">


  $(function() {
        $('.ver_riego-danger').click(function() {

           var numero = $(this).val()
                     
           var pars = "id_global=" + numero + "&";

           
          $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
          $.ajax({
              url : "class/riego/elimina_parte_riego_manto.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó el parte!</div>');
                  setTimeout("reporte_riego_eliminar_manto()", 1050);
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