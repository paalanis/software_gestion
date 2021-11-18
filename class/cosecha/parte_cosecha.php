<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: ../../../index.php");
}
$deposito=$_SESSION['deposito'];
$id_finca_usuario=$_SESSION['id_finca_usuario'];
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
$sqlfinca = "SELECT
              tb_finca.id_finca as id_finca,
              tb_finca.nombre as finca
              FROM
              tb_finca
              WHERE
              tb_finca.id_finca = '$id_finca_usuario'";
$rsfinca = mysqli_query($conexion, $sqlfinca);
$sqlvariedad = "SELECT
              tb_variedad.nombre as variedad_,
              tb_variedad.id_variedad as id_variedad_
              FROM
              tb_cuartel
              LEFT JOIN tb_variedad ON tb_variedad.id_variedad = tb_cuartel.id_variedad
              WHERE
              tb_cuartel.id_finca = '$id_finca_usuario'
              GROUP BY
              tb_cuartel.id_variedad";
$rsvariedad = mysqli_query($conexion, $sqlvariedad); 
$sqlinsumos = "SELECT
                tb_insumo.id_insumo as id,
                CONCAT(tb_insumo.nombre_comercial, ' - ',tb_unidad.nombre) as nombre
                FROM
                tb_insumo
                INNER JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
                WHERE
                tb_insumo.id_insumo = '57'
                ORDER BY
                tb_insumo.nombre_comercial ASC
                ";
$rsinsumos = mysqli_query($conexion, $sqlinsumos);
$sqltransporte = "SELECT
                          tb_transporte.id_transporte as id,
                          tb_transporte.razon_social as razon_s
                          FROM
                          tb_transporte
                          ORDER BY
                          tb_transporte.razon_social ASC
                          ";
$rstransporte = mysqli_query($conexion, $sqltransporte);
mysqli_select_db($conexion,'pernot_ricard');
$sql = "DELETE FROM tb_consumo_insumos_".$deposito." WHERE estado = '0'";
mysqli_query($conexion,$sql);
echo '<input class="form-control" id="deposito" value="'.$deposito.'"  type="hidden">';
?>
<script src="js/bootstrap-toggle.min.js"></script>
<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); carga_cosecha()">
 <h4><span class="label label-default">Parte Cosecha</span></h4> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      
    <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: left;">Variedad</label>
        <div class="col-lg-10">
          <select class="form-control" id="diario_variedad"  autofocus="" required>   
              <option value="0" >Seleccione</option>
              <?php
              while ($sql_variedad = mysqli_fetch_array($rsvariedad)){
                $idvariedad= $sql_variedad['id_variedad_'];
                $variedad = $sql_variedad['variedad_'];
                echo utf8_encode('<option value='.$idvariedad.'>'.$variedad.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-1 control-label" style="text-align: left;">Fecha</label>
        <div class="col-lg-4">
          <input type="date" class="form-control" id="diario_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="diario_remito" style="width: 135px;" placeholder="R N°0000-00000000" value="" aria-describedby="basic-addon1" required  autofocus="">
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="diario_ciu" placeholder="CIU 00000000" style="background-color: #c9302c; color: white" value="" aria-describedby="basic-addon1">
        </div>
        <div class="col-lg-1" id="control_ciu_rto"></div>
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" style="text-align: left;" class="col-lg-2 control-label">Transporte</label>
        <div class="col-lg-4">
          <select class="form-control" id="diario_transporte" required  autofocus="">   
              <option value="0" >Seleccione</option>
              <?php
              while ($datos = mysqli_fetch_array($rstransporte)){
              $transporte=utf8_encode($datos['razon_s']);
              $id=utf8_encode($datos['id']);
              echo utf8_encode('<option value='.$id.'>'.$transporte.'</option>');
              }
              ?>
          </select>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="diario_chofer" placeholder="Chofer" value="" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="diario_patente" placeholder="Patente" value="" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: left;">Origen</label>
        <div class="col-lg-5">
          <select class="form-control" id="diario_finca"  autofocus="" required>   
              <?php
              while ($sql_finca = mysqli_fetch_array($rsfinca)){
                $idfinca= $sql_finca['id_finca'];
                $finca = $sql_finca['finca'];
                echo utf8_encode('<option value='.$idfinca.'>'.$finca.'</option>');
              }
              ?>
            </select>
        </div>
        <div class="col-lg-5">
          <input type="text" class="form-control" autocomplete="off" id="diario_destino" placeholder="Destino" value="" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
      </div>
      
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Tipo de cosecha</label>
        <div class="col-lg-10" id="">
        <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Mecánica</label>
        <div class="col-lg-4">
          <div align="left">
          <input class="form-control_cosecha" style="width: 25px; height:25px" type="checkbox"  value="mecanica" id="mecanica">
          </div>
        </div>
        <label for="inputPassword" class="col-lg-2 control-label">Manual</label>
        <div class="col-lg-4">
          <div align="left">
          <input class="form-control_cosecha" style="width: 25px; height:25px" type="checkbox"  value="manual" id="manual">
          </div>
        </div>
        </div>       
        </div>
      </div>

      <label><span class="label label-default">Cosecha Mecanica</span></label>

      <div class="form-group form-group-sm">
        <div class="col-lg-4" >
          <input type="checkbox" id="boton_mecanica" data-width="180" data-onstyle="warning" data-offstyle="info" data-height="29" data-size="mini" data-toggle="toggle" data-on="Propia" data-off="Tercerizada">
          </div>
        <div class="col-lg-8" id="div_mecanica">
          <select class="form-control" id="diario_mecanica" required disabled><option value="0" >Seleccione</option></select>
          </div>
      </div>  
      
      <label><span class="label label-default">Cosecha Manual</span></label>
      <div class="form-group form-group-sm" id="div_cosecha_manual">
        <div class="col-lg-3">
          <input class="form-control" type="number" autocomplete="off" min='0' placeholder="Propios" id="diario_propia">
          </div>
        <div class="col-lg-3">
          <input class="form-control" type="number" autocomplete="off" min='0' placeholder="Tercerizado" id="diario_eventual">
          </div>
        <div class="col-lg-3">
          <input class="form-control" type="number" autocomplete="off" min='0' placeholder="Fichas"  autofocus="" id="diario_fichas" required>
          </div>
        <div class="col-lg-3">
          <input class="form-control" type="text" autocomplete="off" placeholder="Precio" id="diario_precio"  autofocus="" required>
          </div>    
      </div>    
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: left;">Insumos</label>
        <div class="col-lg-6">
          <select class="form-control" id="diario_insumo" onchange="saldo()">   
              <option value=""></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['nombre'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
              }
              ?>
            </select>
            <div id="div_saldo"></div>
        </div>
        <div class="col-lg-4">
          <div class="input-group input-group-sm">
            <input class="form-control" id="diario_insumo_cantidad" autocomplete="off" type="text">
            <input class="form-control" id="tamano_cuadro" value="90" type="hidden">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button" id='boton_insumo' onclick="carga_insumo()">Ok</button>
            </span>
          </div>
        </div>
      </div>
      <!-- <div class="form-group form-group-sm">
        <label for="text" class="col-lg-2 control-label" style="text-align: left;">Insumos cargados</label> -->
        <div id="div_insumos_cargados">
        </div>
      <!-- </div> -->
         
      
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>

        <label>Cuarteles</label>
      <div class="form-group form-group-sm">
        <div class="col-lg-12" id="div_cuarteles">
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-5">
        <div class="form-group form-group-sm">
          <label  class="col-lg-5 control-label">Horas</label>
          <div class="col-lg-6">
          <input class="form-control" type="text" autocomplete="off" id="diario_horas" autofocus="" required>  
          </div>
        </div>
        <div class="form-group form-group-sm">
          <label  class="col-lg-5 control-label">Kilos</label>
          <div class="col-lg-6">
          <input class="form-control" type="text" autocomplete="off" id="diario_kilos">  
          </div>
        </div>
        </div>
        <div class="col-lg-7">
          <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-3 control-label">Observación</label>
        <div class="col-lg-9">
          <textarea class="form-control" autocomplete="off" rows="1" id="diario_obs"></textarea>
          <span class="help-block">Detalle se ser necesario.</span>
        </div>
      </div>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
          </div>
        </div>
        <div class="col-lg-5">
          <div align="right">
         <!--  <button type="reset" class="btn btn-default">Borrar</button> -->
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
        </div>
      </div>  
   </fieldset>
  </div> 
 </div>  
 </div>
</form>
<script type="text/javascript">
 $(document).ready(function () {
  // var finca = $('#diario_finca').val();
  // $("#div_cuarteles").html('<div class="text-center"><div class="loadingsm"></div></div>');
  // $("#div_cuarteles").load("class/parte_diario/cuarteles.php", {finca: finca});
  $('#diario_kilos').mask("##.00", {reverse: true});
  $('#diario_horas').mask("##.00", {reverse: true});
  $('#diario_precio').mask("##.00", {reverse: true});
  $('#diario_insumo_cantidad').mask("##.00", {reverse: true});
  $('#div_cosecha_manual').find('*').prop('disabled', true);
  $('#boton_mecanica').bootstrapToggle('disable')
  $('#diario_remito').mask("0000-00000000", {clearIfNotMatch: true});
  $('#diario_ciu').mask("00000000", {clearIfNotMatch: true});

  });

 
 
 $(function() {
    $('#boton_mecanica').change(function() {
     
      if ($(this).prop('checked') == true) {

       $("#div_mecanica").html('<div class="text-center"><div class="loadingsm"></div></div>');
       $("#div_mecanica").load("class/cosecha/tipo_cosecha.php", {tipo: '1'});  
      }else{
       $("#div_mecanica").html('<div class="text-center"><div class="loadingsm"></div></div>'); 
       $("#div_mecanica").load("class/cosecha/tipo_cosecha.php", {tipo: '0'});
     }  

      
    })
  })

$(function() {
    $('.form-control_cosecha').change(function() {
     
      if ($(this).prop('checked') == true) {

        var tilde = $(this).val()
        if (tilde == "mecanica") {

          $('#div_cosecha_manual').find('*').prop('disabled', true);
          $('#boton_mecanica').bootstrapToggle('enable')
          $('#manual').prop('checked', false)
          // $('#boton_mecanica').bootstrapToggle('off')

          if ($('#boton_mecanica').prop('checked') == true) {
            $("#div_mecanica").html('<div class="text-center"><div class="loadingsm"></div></div>');
            $("#div_mecanica").load("class/cosecha/tipo_cosecha.php", {tipo: '1'});
          }else{
            $("#div_mecanica").html('<div class="text-center"><div class="loadingsm"></div></div>');
            $("#div_mecanica").load("class/cosecha/tipo_cosecha.php", {tipo: '0'});
          }


        }else{

          // $('#boton_mecanica').bootstrapToggle('off')
          $('#div_cosecha_manual').find('*').prop('disabled', false);
          $('#boton_mecanica').bootstrapToggle('disable')
          $('#mecanica').prop('checked', false)
          $('#div_mecanica').html('<select class="form-control" id="diario_mecanica" required disabled><option value="0" >Seleccione</option></select>')

        }
        
      }else{

        var tilde = $(this).val()
        if (tilde == "mecanica") {

          $('#div_mecanica').html('<select class="form-control" id="diario_mecanica" required disabled><option value="0" >Seleccione</option></select>')
          $('#boton_mecanica').bootstrapToggle('disable')

        }else{

          $('#div_cosecha_manual').find('*').prop('disabled', true);
          
        }  


      }  

      
    })
  })

  $(function() {
    $('#diario_variedad').change(function() {
     var finca = $('#diario_finca').val();
     var variedad = $('#diario_variedad').val();
     $("#div_cuarteles").html('<div class="text-center"><div class="loadingsm"></div></div>');
     $("#div_cuarteles").load("class/cosecha/cuarteles_kilos.php", {finca: finca, variedad: variedad}); 
      
    })
  })

  $(function() { 
        $('#diario_ciu').blur(function() {

          var ciu = $(this).val()

          if (ciu != ''){

          var pars = "ciu=" + ciu + "&";

          $("#control_ciu_rto").html('<div class="text-center"><div class="loadingsm"></div></div>');

      $.ajax({
            url : "class/cosecha/control_ciu.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $("#control_ciu_rto").html('<div class="text-right"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></div>');
                $('#diario_ciu').tooltip('destroy');
              } else {
                
                $("#control_ciu_rto").html('<div class="text-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>');
                $('#diario_ciu').tooltip({title: "Ya existe", placement: "right"});
                      $('#diario_ciu').tooltip('show');
                      $('#diario_ciu').val('')
                
              }
            
            }

            });
          }else {$("#control_ciu_rto").html('');}
    
        })


      })

  $(function() { 
        $('#diario_remito').blur(function() {

          var remito = $(this).val()

          if (remito != ''){

          var pars = "remito=" + remito + "&";

          $("#control_ciu_rto").html('<div class="text-center"><div class="loadingsm"></div></div>');

      $.ajax({
            url : "class/cosecha/control_rto.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $("#control_ciu_rto").html('<div class="text-right"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></div>');
                $('#diario_remito').tooltip('destroy');
              } else {
                
                $("#control_ciu_rto").html('<div class="text-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>');
                $('#diario_remito').tooltip({title: "Ya existe", placement: "right"});
                      $('#diario_remito').tooltip('show');
                      $('#diario_remito').val('')
                
              }
            
            }

            });
          }else {$("#control_ciu_rto").html('');}
    
        })


      })


  </script>
  <script src="js/bootstrap-toggle.min.js"></script>

