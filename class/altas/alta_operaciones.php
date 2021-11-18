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

$sqlvalvulas = "SELECT
        tb_valvula.nombre as valvula,
        tb_valvula.id_valvula as id_valvula,
        CAST(tb_valvula.nombre AS SIGNED) as valvula_orden
        FROM
        tb_valvula
        INNER JOIN tb_caudalimetro ON tb_valvula.id_caudalimetro = tb_caudalimetro.id_caudalimetro
        WHERE
        tb_caudalimetro.id_finca = '$id_finca_usuario' and tb_valvula.estado_op = '0'
        ORDER BY
        valvula_orden ASC
        ";
$rsvalvulas = mysqli_query($conexion, $sqlvalvulas);

$cantidad =  mysqli_num_rows($rsvalvulas);

$sqloperacion = "SELECT tb_operacion.nombre as nombre, tb_operacion.id_operacion as id FROM tb_operacion
WHERE tb_operacion.id_finca = '$id_finca_usuario' ORDER BY nombre ASC";
$rsoperacion = mysqli_query($conexion, $sqloperacion); 

$sqloperacion_dos = "SELECT tb_operacion.nombre as nombre, tb_operacion.id_operacion as id FROM tb_operacion
WHERE tb_operacion.id_finca = '$id_finca_usuario' ORDER BY nombre ASC";
$rsoperacion_dos = mysqli_query($conexion, $sqloperacion_dos); 

$sqloperacion_tres = "SELECT tb_operacion.nombre as nombre, tb_operacion.id_operacion as id FROM tb_operacion
WHERE tb_operacion.id_finca = '$id_finca_usuario' ORDER BY nombre ASC";
$rsoperacion_tres = mysqli_query($conexion, $sqloperacion_tres);

$sqlcaudalimetro = "SELECT tb_caudalimetro.nombre as nombre, tb_caudalimetro.id_caudalimetro as id
FROM tb_caudalimetro INNER JOIN tb_finca ON tb_caudalimetro.id_finca = tb_finca.id_finca
WHERE tb_caudalimetro.id_finca = '$id_finca_usuario' and tb_caudalimetro.dilucion = '0'
ORDER BY nombre ASC";
$rscaudalimetro = mysqli_query($conexion, $sqlcaudalimetro);

 ?>

<form class="form-horizontal" role="form" onsubmit="event.preventDefault(); alta_operacion_asigna()">
<input type="hidden" class="form-control" value="<?php echo $id_finca_usuario; ?>" id="id_finca">
 
 <h4><span class="label label-default">Alta Operaciones</span></h4>
 <div class="well bs-component">
  <div class="row">
     <div class="col-lg-6">
       <fieldset>
          <label  class="control-label">Paso 1: Dar de alta las operaciones a sus respectivos caudalímetros</label><br><br>
             <div class="form-group form-group-sm">
              <label  class="col-lg-2 control-label">Caudalímetro</label>
              <div class="col-lg-10">
                <select class="form-control" id="alta_id_cauda">   
                   <option value="0"></option> 
                    <?php
                      while ($sql_caudalimetro = mysqli_fetch_array($rscaudalimetro)){
                          $caudalimetro =$sql_caudalimetro['nombre'];
                          $id_caudalimetro =$sql_caudalimetro['id'];
                                                              
                          echo utf8_encode('<option value="'.$id_caudalimetro.'">'.$caudalimetro.'</option>');
                      }
                      ?>
                  </select>
              </div>
            </div>
             <div class="form-group form-group-sm">
              <label for="inputPassword" class="col-lg-2 control-label">Nueva operación</label>
              <div class="col-lg-10">
                <div class="col-md-6">            
                 <div class="input-group input-group-sm">
                    <input type="text" class="form-control" autocomplete="off" id="alta_operacion" placeholder="Nuevo">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="alta_opera()"><span class="glyphicon glyphicon-ok" aria-hidden="true"></button>
                    </span>
                 </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group input-group-sm">
                  <select class="form-control"  id="modifica_operacion" >
                    <option value="0">Editar nombre op.</option>
                                      <?php
                                      while ($sql_operacion = mysqli_fetch_array($rsoperacion)){
                                          $operacion =$sql_operacion['nombre'];
                                          $id_operacion =$sql_operacion['id'];
                                                                              
                                          echo utf8_encode('<option value="'.$id_operacion.'">'.$operacion.'</option>');
                                      }
                                      ?>
                  </select>
                  <span class="input-group-btn">
                    <button class="ver_riego ver_riego-default ver_riego-sm" value="operacion" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                    </span>
                  </div>
                    <div id="div_modifica"></div>
                </div>
              </div>
            </div>

          <label  class="control-label">Paso 2: Asignar valvulas a sus respectivas operaciones</label><br><br>
            <div class="form-group form-group-sm">
              <label  class="col-lg-2 control-label">Operaciones</label>
              <div class="col-lg-10">
                <select class="form-control" id="alta_operacion_bis" required>   
                   <option value="0"></option> 
                    <?php
                      while ($sql_operacion_dos = mysqli_fetch_array($rsoperacion_dos)){
                          $operacion_dos =$sql_operacion_dos['nombre'];
                          $id_operacion_dos =$sql_operacion_dos['id'];
                                                              
                          echo utf8_encode('<option value="'.$id_operacion_dos.'">'.$operacion_dos.'</option>');
                      }
                      ?>
                  </select>
              </div>
            </div>
            <div class="form-group form-group-sm">
              <label  class="col-lg-2 control-label">Válvula</label>
              <div class="col-lg-10">
               <div class="input-group input-group-sm">
               <select class="form-control" id="alta_operacion_valvula" required>   
                <option value=""></option>
                <?php
                if ($cantidad > 0) { // si existen cuarteles con de esa finca se muestran, de lo contrario queda en blanco  
                while ($sql_valvulas = mysqli_fetch_array($rsvalvulas)){
                  $idvalvula= $sql_valvulas['id_valvula'];
                  $valvula = $sql_valvulas['valvula'];
                  echo utf8_encode('<option value='.$idvalvula.'>'.$valvula.'</option>');
                }
                }else{
                  echo '<option value="0">Sin válvulas para asignar</option>';
                }  
                ?>
              </select>
              <span class="input-group-btn">
            <button class="btn btn-default" type="submit" >Asignar</button>
          </span>
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
                <!-- <button type="reset" class="btn btn-default">Borrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>   -->
                </div>
                
              </div>
            </div>
       </fieldset>
     </div> <!-- columna principal izquierda -->



     <div class="col-lg-6">
       <fieldset>
                <div class="col-lg-12">
                 
                      <div class="panel panel-default">
                      <div class="panel-body" id="Panel1" style="height:130px">
                      <table class="table table-hover ">
                        <thead>
                          <tr style="height:5px">
                          <th>Operacion</th>
                          <th>Cauda</th>
                          <!-- <th>Elimirar</th> -->
                          </tr>
                        </thead>
                        <tbody>
                        <?php

                        $sqloperacion_cuatro = "SELECT
                        tb_caudalimetro.nombre as cauda,
                        tb_operacion.id_operacion as id,
                        tb_operacion.nombre as nombre
                        FROM
                        tb_operacion
                        LEFT JOIN tb_caudalimetro ON tb_caudalimetro.id_caudalimetro = tb_operacion.id_caudalimetro
                        WHERE tb_operacion.id_finca = '$id_finca_usuario'
                        ORDER BY nombre ASC";
                        $rsoperacion_cuatro = mysqli_query($conexion, $sqloperacion_cuatro);

                        $cantidad =  mysqli_num_rows($rsoperacion_cuatro);

                        if ($cantidad > 0) { // si existen operaciones con de esa finca se muestran, de lo contrario queda en blanco  

                        while ($datos = mysqli_fetch_array($rsoperacion_cuatro)){
                        $operacion=utf8_encode($datos['nombre']);
                        $id_operacion=utf8_encode($datos['id']);
                        $cauda=utf8_encode($datos['cauda']);

                        echo '

                        <tr>
                        <td>'.$operacion.'</td>
                        <td>'.$cauda.'</td>
                        </tr>
                        </tr>
                        ';

                        }   
                        }
                        ?>
                        </tbody>
                      </table> 
                      <?php
                      if ($cantidad == 0){

                      echo "No hay operaciones con válvulas asignadas.";
                      }
                      ?>
                      </div>
                      </div>
               
                </div> <!-- termina tabla operaciones -->

                


                 
                <div class="col-lg-12">
               
                    <div class="panel panel-default">
                    <div class="panel-body" id="Panel1" style="height:130px">
                    <table class="table table-hover ">
                    <thead>
                    <tr style="height:5px">
                    <th>Operacion</th>
                    <th>Válvula asignada</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sqloperaciones = "SELECT
                                tb_operacion_asignada.id_operacion_asignada AS id_operacion,
                                tb_valvula.nombre AS valvula,
                                CAST(tb_valvula.nombre AS SIGNED) as orden_valvula,
                                tb_operacion.nombre AS operacion,
                                tb_valvula.id_valvula as id_valvula
                                FROM
                                tb_operacion_asignada
                                INNER JOIN tb_valvula ON tb_valvula.id_valvula = tb_operacion_asignada.id_valvula
                                INNER JOIN tb_operacion ON tb_operacion.id_operacion = tb_operacion_asignada.id_operacion
                                WHERE
                                tb_operacion_asignada.id_finca = '$id_finca_usuario'
                                ORDER BY
                                operacion ASC,
                                orden_valvula ASC";
                    $rsoperaciones = mysqli_query($conexion, $sqloperaciones);

                    $cantidad =  mysqli_num_rows($rsoperaciones);

                    if ($cantidad > 0) { // si existen operaciones con de esa finca se muestran, de lo contrario queda en blanco  

                    while ($datos = mysqli_fetch_array($rsoperaciones)){
                    $operacion=utf8_encode($datos['operacion']);
                    $valvula=utf8_encode($datos['valvula']);
                    $id_operacion=utf8_encode($datos['id_operacion']);
                    $id_valvula=utf8_encode($datos['id_valvula']);

                    echo '

                    <tr>
                    <td>'.$operacion.'</td>
                    <td>'.$valvula.'</td>
                    </tr>
                    ';

                    }   
                    }
                    ?>
                    </tbody>
                    </table> 
                    <?php
                    if ($cantidad == 0){

                    echo "No hay operaciones con válvulas asignadas.";
                    }
                    ?>
                    </div>
                    </div>
                  
                </div><!-- termina tabla valvulas -->
                  </fieldset>
     </div><!-- columna principal derecha -->

    


  </div>  <!-- row 1 -->
 </div> <!-- well -->
</form>

<script type="text/javascript">

 $(function() {
        $('.ver_riego-default').click(function() {

          var idoperacion = $('#modifica_operacion').val()
          var operacion = document.getElementById("modifica_operacion")
          var operacion = operacion.options[operacion.selectedIndex].text;
          var cual = $(this).val()

          
      switch(cual){

        case 'operacion':
        if (idoperacion != "0") {
        $("#div_modifica").load("class/altas/modifica_operacion.php", {id: idoperacion, texto: operacion, opcion: cual});
        }
        break;
      }


        })


      })
  </script>