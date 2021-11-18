function logeo () {

	var usuario = $('#usuario').val();
	var pass = $('#pass').val();

	// alert(usuario)
	// alert(pass)


	if (usuario != '' && pass != '') {

		$("#divlog").html('<div class="text-center"><div class="loadingsm"></div></div>');
				
		$("#divlog").load("../conexion/logeo.php", {usuario: usuario, pass: pass});

		alert(usuario)

		};

}