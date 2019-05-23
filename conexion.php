<?php
	/*-------------------------
	Autor: Santiago Gutierrez
	Web: Xudo.store
	Mail: zam.2014.sg@gmail.com
	---------------------------*/
	include('src/funciones/funciones.php');
	date_default_timezone_set('America/Bogota');
	// DB credentials.
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','bcga1303');
	define('DB_NAME','autodialer');

		/********** Conexión Autodialer **********/
		$con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(!$con){
			die("imposible conectarse: ".mysqli_error($con));
		}
		if (@mysqli_connect_errno()) {
			die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
		}
?>
