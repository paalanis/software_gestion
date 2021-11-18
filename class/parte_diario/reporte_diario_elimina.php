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
?>
<div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover ">
  <thead>
    <tr style="height:5px">
      <th>Parte Número</th>
      <th>Fecha</th>
      <th>Finca</th>
      <th>Labor</th>
      <th>Personal</th>
      <th>Horas</th>
      <th></th>
      </tr>
  </thead>
  <tbody>
   
        <?php
        
        $desde=$_POST['desde'];
        $finca=$_POST['finca'];
        $labor=$_POST['labor'];
        $personal=$_POST['personal'];

        $consulta_finca = "";
        $consulta_personal = "";
        $consulta_labor = "";


        if ($finca != "") {
        $consulta_finca = "AND tb_parte_diario.id_finca = '$finca' ";
        }
        if ($personal != "") {
        $consulta_personal = "AND tb_parte_diario.id_personal = '$personal' ";
        }
        if ($labor != "") {
        $consulta_labor = "AND tb_parte_diario.id_labor = '$labor' ";
        }

       $sqlcuarteles = "SELECT
                          tb_parte_diario.id_parte_diario_global AS id_global,
                          DATE_FORMAT(tb_parte_diario.fecha, '%d/%m/%y') as fecha,
                          tb_finca.nombre AS finca,
                          tb_labor.nombre AS labor,
                          CONCAT(tb_personal.apellido,', ', tb_personal.nombre) as personal,
                          FORMAT(Sum(tb_parte_diario.horas_trabajadas), 2) AS horas
                          FROM
                          tb_parte_diario
                          INNER JOIN tb_finca ON tb_finca.id_finca = tb_parte_diario.id_finca
                          INNER JOIN tb_labor ON tb_labor.id_labor = tb_parte_diario.id_labor
                          INNER JOIN tb_personal ON tb_parte_diario.id_personal = tb_personal.id_personal
                          WHERE
                          tb_parte_diario.fecha = '$desde' $consulta_finca$consulta_personal$consulta_labor
                          GROUP BY
                          tb_parte_diario.id_parte_diario_global,
                          tb_parte_diario.fecha,
                          tb_finca.nombre,
                          tb_labor.nombre,
                          tb_personal.apellido
                          ORDER BY
                          fecha DESC,
                          labor ASC,
                          finca ASC
                          ";
        $rscuarteles = mysqli_query($conexion, $sqlcuarteles);
        
        $cantidad =  mysqli_num_rows($rscuarteles);

        if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
        
          while ($datos = mysqli_fetch_array($rscuarteles)){
          $id_global=utf8_encode($datos['id_global']);
          $fecha=utf8_encode($datos['fecha']);
          $finca=utf8_encode($datos['finca']);
          $labor=utf8_encode($datos['labor']);
          $personal=utf8_encode($datos['personal']);
          $horas=utf8_encode($datos['horas']);
          
          
          echo '

          <tr>
            <td>'.$id_global.'</td>
            <td>'.$fecha.'</td>
            <td>'.$finca.'</td>
            <td>'.$labor.'</td>
            <td>'.$personal.'</td>
            <td>'.$horas.'</td>
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
              url : "class/parte_diario/elimina_parte.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó el parte!</div>');
                  setTimeout("reporte_diario_eliminar()", 1050);
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