<?php
session_start();
if (isset($_SESSION['usuario'])) {
header("Location: index2.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" src="images/logo.png">  <!-- logo -->

    <title>Gestion Fincas</title>

    <link href="_css/bootstrap.min.css" rel="stylesheet">
    <link href="_css/signin.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="_css/cargando.css" />

  
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="conexion/login.php" method="post">
        <h2 class="form-signin-heading"></h2>
        <label for="inputEmail" class="sr-only">Usuario</label>
        <input type="text" name="usuario" id="user" class="form-control" autocomplete="off" placeholder="Usuario" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="pword" name="pass" autocomplete="off" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <!-- <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label> -->
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>
      <div id="divlog"></div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    
  </body>
</html>

