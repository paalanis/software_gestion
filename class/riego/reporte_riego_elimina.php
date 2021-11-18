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
      <th>Parte Número</th>
      <th>Fecha</th>
      <th>Finca</th>
      <th>Caudalímetro</th>
      <th>Milímetros</th>
      <th></th>
      </tr>
  </thead>
  <tbody>
   
        <?php
        
        $desde=$_POST['desde'];
        $finca=$_POST['finca'];
        $caudalimetro=$_POST['caudalimetro'];

        $consulta_finca = "";
        $consulta_caudalimetro = "";
     

        if ($finca != "") {
        $consulta_finca = "AND tb_caudalimetro.id_finca = '$finca' ";
        }
        if ($caudalimetro != "") {
        $consulta_caudalimetro = "AND tb_caudalimetro.id_caudalimetro = '$caudalimetro' ";
        }
        

        $sqlmilimetros = "SELECT
                          DATE_FORMAT(tb_milimetros_riego.fecha, '%d/%m/%y') AS fecha,
                          tb_finca.nombre AS finca,
                          tb_caudalimetro.nombre AS caudalimetro,
                          FORMAT(SUM(tb_milimetros_riego.mm_regados), 2) AS mm,
                          tb_milimetros_riego.id_global as id_global
                          FROM
                          tb_milimetros_riego
                          INNER JOIN tb_caudalimetro ON tb_caudalimetro.id_caudalimetro = tb_milimetros_riego.id_caudalimetro
                          INNER JOIN tb_finca ON tb_caudalimetro.id_finca = tb_finca.id_finca
                          WHERE
                          tb_milimetros_riego.fecha = '$desde' $consulta_finca$consulta_caudalimetro
                          GROUP BY
                          tb_milimetros_riego.id_global
                          ORDER BY
                          fecha ASC,
                          finca ASC
                          ";
        $rsmilimetros = mysqli_query($conexion, $sqlmilimetros);
        
        $cantidad =  mysqli_num_rows($rsmilimetros);

        if ($cantidad > 0) { // si existen milimetros con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rsmilimetros)){
          $id_global=utf8_encode($datos['id_global']);
          $fecha=utf8_encode($datos['fecha']);
          $finca=utf8_encode($datos['finca']);
          $caudalimetro=utf8_encode($datos['caudalimetro']);
          $mm=utf8_encode($datos['mm']);
             
          
          echo '

          <tr>
            <td>'.$id_global.'</td>
            <td>'.$fecha.'</td>
            <td>'.$finca.'</td>
            <td>'.$caudalimetro.'</td>
            <td>'.$mm.'</td>
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
              url : "class/riego/elimina_parte_riego.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó el parte!</div>');
                  setTimeout("reporte_riego_eliminar()", 1050);
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