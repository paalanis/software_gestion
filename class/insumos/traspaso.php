<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../index.php");
}
$deposito=$_SESSION['deposito'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
mysqli_select_db($conexion,'pernot_ricard');
$sql = "DELETE FROM tb_consumo_insumos_".$deposito." WHERE estado = '0'";
mysqli_query($conexion,$sql);
$sqldeposito_e = "SELECT
                tb_deposito.id_deposito as id,
                tb_deposito.nombre as nombre
                FROM
                tb_deposito
                WHERE
                tb_deposito.nombre = '$deposito'
                ORDER BY  
                tb_deposito.nombre ASC
                ";
$rsdeposito_e = mysqli_query($conexion, $sqldeposito_e);
$sqldeposito_i = "SELECT
                tb_deposito.id_deposito as id,
                tb_deposito.nombre as nombre
                FROM
                tb_deposito
                ORDER BY
                tb_deposito.nombre ASC
                ";
$rsdeposito_i = mysqli_query($conexion, $sqldeposito_i);
$sqlegreso = "SELECT
                tb_insumo.id_insumo as id,
                CONCAT(tb_insumo.nombre_comercial, ' - ',tb_unidad.nombre) as nombre
                FROM
                tb_insumo
                INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
                ORDER BY
                tb_insumo.nombre_comercial ASC
                ";
$rsegreso = mysqli_query($conexion, $sqlegreso); 
?>
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); traspasos()">
 <h4><span class="label label-default">Traspaso de insumos a otro depósito</span></h4>
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="traspaso_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumos</label>
        <div class="col-lg-6">
          <select class="form-control" id="insumo_e" required onchange="saldo()">   
              <option value=""></option>
              <?php
              while ($sql_egreso = mysqli_fetch_array($rsegreso)){
                $idinsumos= $sql_egreso['id'];
                $insumos = $sql_egreso['nombre'];


                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
                
              }
              ?>
            </select>
            <div id="div_saldo"></div>
        </div>
        <div class="col-lg-4">
          <div class="input-group input-group-sm">
            <input class="form-control" autocomplete="off" placeholder="cantidad" id="cantidad_e" type="text" required>
          </div>
        </div>
      </div>

    <label  class="control-label">Egreso</label><br>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Depósito</label>
          <div class="col-lg-6">
            <select class="form-control" id="deposito_e" required>   
                <?php
                while ($deposito_e = mysqli_fetch_array($rsdeposito_e)){
                  $iddeposito_e= $deposito_e['id'];
                  $deposito_ee = $deposito_e['nombre'];


                  echo utf8_encode('<option value='.$iddeposito_e.'>'.$deposito_ee.'</option>');
                  
                }
                ?>
              </select>
          </div>
      </div>
    <label  class="control-label">Ingreso</label><br>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Depósito</label>
          <div class="col-lg-6">
            <select class="form-control" id="deposito_i" required>   
                <option value=""></option>
                <?php
                while ($deposito_i = mysqli_fetch_array($rsdeposito_i)){
                  $iddeposito_i= $deposito_i['id'];
                  $deposito_ii = $deposito_i['nombre'];


                  echo utf8_encode('<option value='.$iddeposito_i.'>'.$deposito_ii.'</option>');
                  
                }
                ?>
              </select>
          </div>
      </div>
  
      <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Observación</label>
        <div class="col-lg-10">
          <textarea class="form-control" autocomplete="off" rows="1" id="traspaso_obs"></textarea>
          <span class="help-block">En caso de ser necesario detalle el traspaso.</span>
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
 <div class="col-lg-6">
 
   <fieldset id="div_remitos">
    
      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:371px">
      <table class="table table-hover ">
        <thead>
          <tr style="height:5px">
            <th>Fecha</th>
            <th>Insumo</th>
            <th>U. Medida</th>
            <th>Cantidad</th>
            <th>Destino</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
         
              <?php
              include '../../conexion/conexion.php';
               if (mysqli_connect_errno()) {
               printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
               exit();
      }

              $sqlingreso = "SELECT
              tb_consumo_insumos_".$deposito.".id_consumo_insumos AS id,
              DATE_FORMAT(tb_consumo_insumos_".$deposito.".fecha, '%d/%m/%y') AS fecha,
              tb_insumo.nombre_comercial AS insumo,
              tb_consumo_insumos_".$deposito.".id_insumo AS id_insumo,
              tb_unidad.nombre AS unidad,
              FORMAT(tb_consumo_insumos_".$deposito.".egreso, 2) AS cantidad,
              tb_consumo_insumos_".$deposito.".egreso AS cantidad_real,
              tb_deposito.nombre as destino,
              tb_consumo_insumos_".$deposito.".id_parte_diario_global as id_global
              FROM
              tb_consumo_insumos_".$deposito."
              INNER JOIN tb_insumo ON tb_consumo_insumos_".$deposito.".id_insumo = tb_insumo.id_insumo
              INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
              INNER JOIN tb_deposito ON tb_deposito.id_deposito = tb_consumo_insumos_".$deposito.".id_deposito_destino
              ORDER BY
              id DESC
              ";
              $rsingreso = mysqli_query($conexion, $sqlingreso);
              
              $cantidad =  mysqli_num_rows($rsingreso);

              if ($cantidad > 0) { // si existen ingreso con de esa ingreso se muestran, de lo contrario queda en blanco  
                
              while ($datos = mysqli_fetch_array($rsingreso)){
              $fecha=utf8_encode($datos['fecha']);
              $insumo=utf8_encode($datos['insumo']);
              $id_insumo=utf8_encode($datos['id_insumo']);
              $unidad=utf8_encode($datos['unidad']);
              $cantidad=utf8_encode($datos['cantidad']);
              $cantidad_real=utf8_encode($datos['cantidad_real']);
              $id=utf8_encode($datos['id']);
              $destino=utf8_encode($datos['destino']);
              $id_global=utf8_encode($datos['id_global']);

              
              echo '

              <tr>
                <td>'.$fecha.'</td>
                <td>'.$insumo.'</td>
                <td>'.$unidad.'</td>
                <td>'.$cantidad.'</td>
                <td>'.$destino.'</td>
                <td><button type="button" class="ver_riego ver_riego-danger ver_riego-xs" id='.$id_global.' name="'.$id_insumo.'" value="'.$id_global.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
                <input class="form-control" id="cantidad_ingresar_'.$id_global.'" value='.$cantidad_real.' type="hidden">
                <input class="form-control" id="deposito_elimina_'.$id_global.'" value='.$destino.' type="hidden">
               </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay traspasos.";
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
  $(document).ready(function () {
  
  $('#cantidad_e').mask("##.00", {reverse: true});
  $('#cantidad_i').mask("##.00", {reverse: true});
 
  });

  $(function() {
        $('.ver_riego-danger').click(function() {

           var id_global = $(this).val()
           var id_insumo = $(this).attr('name')
           var deposito = $('#deposito_elimina_'+id_global).val()
           var cantidad = $('#cantidad_ingresar_'+id_global).val()
                     
           var pars = "id_global=" + id_global + "&" + "id_insumo=" + id_insumo + "&" + "deposito=" + deposito + "&" + "cantidad=" + cantidad + "&";

          $('#div_remitos').html('<div class="text-center"><div class="loadingsm"></div></div>');
          $.ajax({
              url : "class/insumos/elimina_traspaso.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó traspaso!</div>');
                  setTimeout("llama_traspasos()", 1050);
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