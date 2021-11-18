<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: index.php");
}
if ($_SESSION['id_finca_usuario'] == '0') {
session_destroy();
header("Location: index.php");
}
include 'conexion/conexion.php';
$deposito=$_SESSION['deposito'];
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="Pablo Alanis" content="">
    <link rel="shortcut icon" src="images/logo.png">


    <title>Gestión</title>

    <!-- Bootstrap core CSS -->
<!--     <link href="_css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="_css/bootstrap.css" rel="stylesheet">
    <link href="_css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="_css/_bootswatch.scss" rel="stylesheet">
    <link href="_css/_variables.scss" rel="stylesheet">
    <link href="_css/bootswatch.less" rel="stylesheet">
    <link href="_css/variables.less" rel="stylesheet">
<!--     <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->
    <link href="_css/tablas.css" rel="stylesheet">
    <link href="_css/cargando.css" rel="stylesheet">
    <link href="_css/formato.css" rel="stylesheet">

  </head>

  <body>

    <?php

        mysqli_select_db($conexion,'pernot_ricard');
        $sql = "DELETE FROM tb_consumo_insumos_".$deposito." WHERE estado = '0'";
        mysqli_query($conexion,$sql);

    ?>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img style="max-width:30px; margin-top: -14px; margin-left: -30px; margin-right: 20px;"
             src="images/logo.png"></a>
         <!--  <a class="navbar-brand" href="#">Desarrollos</a> -->

        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/software_gestion">Inicio</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Parte Diario <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="llama_parte_diario()" onmouseup="cerrar()">Nuevo</a></li>
                <li role="separator" class="divider"></li>
                <li><a>Reporte</a></li>
                <li><a href="#" onclick="llama_reporte_diario()" onmouseup="cerrar()">Reporte - Parte diario</a></li>
                <li><a href="#" onclick="llama_reporte_diario_insumo()" onmouseup="cerrar()">Reporte - Insumos por parte diario</a></li>
                <li><a href="#" onclick="llama_reporte_diario_labores()" onmouseup="cerrar()">Reporte - Labores por Cuartel</a></li>
                <li><a href="#" onclick="llama_reporte_diario_insumocuartel()" onmouseup="cerrar()">Reporte - Insumos por Cuartel</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_reporte_diario_eliminar()" onmouseup="cerrar()">Eliminar</a></li>

                                
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cosecha <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="llama_parte_cosecha()" onmouseup="cerrar()" >Nuevo parte de cosecha</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_pendientes_cosecha()" onmouseup="cerrar()">Remitos pendientes</a></li>
                <li><a href="#" onclick="llama_terminados_cosecha()" onmouseup="cerrar()">Remitos terminados</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_reporte_jornales()" onmouseup="cerrar()">Reporte jornales</a></li>
                <li><a href="#" onclick="llama_reporte_cosecha()" onmouseup="cerrar()">Reporte global</a></li>
                                
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Riego <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="llama_riego()" onmouseup="cerrar()">Nuevo parte de riego-goteo</a></li>
                <li><a href="#" onclick="llama_riego_manto()" onmouseup="cerrar()">Nuevo parte de riego-manto</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_reporte_riego()" onmouseup="cerrar()">Reporte goteo</a></li>
                <li><a href="#" onclick="llama_reporte_riego_global()" onmouseup="cerrar()">Reporte global goteo</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_reporte_manto()" onmouseup="cerrar()">Reporte manto</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_reporte_riego_eliminar()" onmouseup="cerrar()">Eliminar parte-goteo</a></li>
                <li><a href="#" onclick="llama_reporte_riego_eliminar_manto()" onmouseup="cerrar()">Eliminar parte-manto</a></li>
                                
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Insumos <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="llama_remitos()" onmouseup="cerrar()">Remitos</a></li>
                <li><a href="#" onclick="llama_traspasos()" onmouseup="cerrar()">Traspasos - Salidas</a></li>
                <li><a href="#" onclick="llama_traspasos_re()" onmouseup="cerrar()">Traspasos - Ingresos</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_existencias()" onmouseup="cerrar()">Reporte de existencias</a></li>
                <li><a href="#" onclick="llama_reporte_insumos_consumos()" onmouseup="cerrar()">Reporte de consumos</a></li>
                                
              </ul>
            </li>
            
            <?php  if (strtolower($_SESSION['tipo_user']) == 'admin') {

              echo '
              <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Altas <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="llama_alta_finca()" onmouseup="cerrar()">Finca</a></li>
                <li><a href="#" onclick="llama_alta_variedad()" onmouseup="cerrar()">Variedad</a></li>
                <li><a href="#" onclick="llama_alta_cuartel()" onmouseup="cerrar()">Cuartel</a></li>  
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_alta_personal()" onmouseup="cerrar()">Personal</a></li>
                <li><a href="#" onclick="llama_alta_labor()" onmouseup="cerrar()">Labor</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_alta_insumos()" onmouseup="cerrar()">Insumo</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_alta_caudalimetro()" onmouseup="cerrar()">Caudalímetro</a></li>
                <li><a href="#" onclick="llama_alta_valvula()" onmouseup="cerrar()">Válvula</a></li>
                <li><a href="#" onclick="llama_alta_operacion()" onmouseup="cerrar()">Operación</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_alta_aforador()" onmouseup="cerrar()">Aforador</a></li>
                <li><a href="#" onclick="llama_alta_calibracion()" onmouseup="cerrar()">Calibraciones</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" onclick="llama_alta_transporte()" onmouseup="cerrar()">Transporte</a></li>
                <li><a href="#" onclick="llama_alta_cosechadora()" onmouseup="cerrar()">Cosechadora</a></li>                                 
              </ul>
              </li>
              <li><a href="#" onclick="llama_parametros()" onmouseup="cerrar()">Parámetros</a></li>

              ';
                }else{
            
                  if (strtolower($_SESSION['tipo_user']) == 'admin4' or strtolower($_SESSION['tipo_user']) == 'admin4'){
                  
                  echo '
                  <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Altas <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#" onclick="llama_alta_personal()" onmouseup="cerrar()">Personal</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#" onclick="llama_alta_insumos()" onmouseup="cerrar()">Insumo</a></li>
                   </ul>
                  </li>
                  
                  ';
                      
              
                  }
            
            }

            ?>

          <?php  if (strtolower($_SESSION['tipo_user']) == 'admin' or strtolower($_SESSION['tipo_user']) == 'admin3') {  
          echo '<li ><a href="index_finca.php">Cambiar finca</a></li>';
          }
          ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="navbar-brand" ><?php echo utf8_encode(substr($_SESSION['finca_usuario'],0,12));?></li>
            <li class="navbar-brand" ><?php echo utf8_encode($_SESSION['usuario'])?></li>     
            <li class="active"><a href="conexion/logout.php">Log Out <span class="sr-only"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="page-header"></div>

    <div class="container" id="panel_inicio">
      
      <!-- Aqui se cargan los paneles de trabajo -->

    </div>

    <!-- <footer class="footer">
      <div class="container">
        <p class="text-muted">Desarrollado por APSS</p>
      </div>
    </footer> -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/fx.js"></script>
    <script src="js/bootstrap-toggle.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->


    <script type="text/javascript">
    
  
    function cerrar(){

      $('.navbar-collapse').collapse('hide');
    }


    </script>


  </body>
</html>
