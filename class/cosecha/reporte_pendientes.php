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
?>

<h4><span class="label label-default">Remitos de cosecha pendientes</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-7">
   <fieldset>

<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:300px">
<table class="table table-hover" id="Exportar_a_Excel1">
<thead>
<tr style="height:5px">
<th>Fecha</th>
<th>Remito</th>
<th>CIU</th>
<th>Destino</th>
<th>Kilos</th>
<th>Ver</th>
<th>Editar</th>
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
$sqlpendiente = "SELECT
DATE_FORMAT(tb_cosecha.fecha, '%d/%m/%y') AS fecha,
tb_cosecha.ciu as ciu,
substring(tb_cosecha.remito, 6,10) as remito,
tb_cosecha.destino as destino,
ROUND(Sum(tb_cosecha.kilos),2) AS kilos,
tb_cosecha.id_global as id_global
FROM
tb_cosecha
WHERE
tb_cosecha.pendiente = '0' and tb_cosecha.id_finca = '$id_finca_usuario'
GROUP BY
tb_cosecha.id_global
ORDER BY FIELD (kilos, 0) desc,
ciu asc
";

$rspendiente = mysqli_query($conexion, $sqlpendiente);
$cantidad =  mysqli_num_rows($rspendiente);
if ($cantidad > 0) { // si existen pendiente con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rspendiente)){
$fecha=utf8_encode($datos['fecha']);
$remito=utf8_encode($datos['remito']);
$ciu=$datos['ciu'];
$destino=utf8_encode($datos['destino']);
$kilos=utf8_encode($datos['kilos']);
$id_global=utf8_encode($datos['id_global']);

echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$remito.'</td>
<td>'.$ciu.'</td>
<td>'.$destino.'</td>
<td>'.$kilos.'</td>
<td><button type="button" name="'.$id_global.'_ver" id="ver_pendiente" class="ver_riego ver_riego-info ver_riego-xs">
    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
    </button></td>
<td><button type="button" name="'.$id_global.'_editar" id="editar_pendiente" class="ver_riego ver_riego-default ver_riego-xs">
    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
    </button></td>
<td><button type="button" name="'.$id_global.'_elimina" id="elimina_pendiente" class="ver_riego ver_riego-danger ver_riego-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
    </button></td>
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
      
   </fieldset>
 
 </div>
 <div class="col-lg-5">
 
   <fieldset>
      <div id="div_reporte"></div>
   
   </fieldset>
  </div> 
</div>  
</div>


<script type="text/javascript">
$(function() {
  $('.ver_riego-default').click(function() {

  var parte = $(this).attr('name')
  var id_global = parte.substring(0,14)

 
    $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
    $("#div_reporte").load("class/cosecha/actualiza_pendientes.php", {id_global_ciu:id_global});
  
    
  })
})

$(function() {
  $('.ver_riego-danger').click(function() {

  var parte = $(this).attr('name')
  var id_global = parte.substring(0,14)

      var pars = "id_global=" + id_global + "&";
      
    // alert(pars);
        
        $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
        $('#elimina_pendiente').attr('disabled', true);
        $.ajax({
            url : "class/cosecha/elimina_pendientes_bis.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $('#div_reporte').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Parte eliminado!</div>');       
                setTimeout("$('mensaje_general').alert('close')", 1000);
                setTimeout("llama_pendientes_cosecha()", 1050);
              } else {
                $('#div_reporte').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');       
                setTimeout("$('#msj_parametros').alert('close')", 2000);
              }
            
            }

        });
  
    
  })
})

$(function() {
        $('.ver_riego-info').click(function() {

        var parte = $(this).attr('name')
        var parte = parte.substring(0,14)
         
         $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $("#div_reporte").load("class/cosecha/modal.php", {parte:parte});
          
        })
      })

$('#myModal').on('hidden.bs.modal', function (e) {

})
</script>