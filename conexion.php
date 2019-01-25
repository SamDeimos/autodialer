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
	
		/************ Conexión a AMI ***********/
		$ip_ami = "127.0.0.1"; 
		$user_ami = "admin"; 
		$pass_ami = "bcga1303";

		/********** Conexión Autodialer **********/
		$con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(!$con){
			die("imposible conectarse: ".mysqli_error($con));
		}
		if (@mysqli_connect_errno()) {
			die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
		}

		/************ Conexiónn a DB de Asterisk ****************/
		$astcon=mysql_connect(DB_HOST, DB_USER, DB_PASS);
		$astdb="asterisk";
		if(!$astcon) { 
		echo "<h3>Error Fatal: No se pudo conectar a BD Asterisk</h3>"; 
		exit;
		}

		/**************** Conexión a cdr de asterisk *******************/
		$cdrcon=mysql_connect(DB_HOST, DB_USER, DB_PASS);
		$cdrdb="asteriskcdrdb";
		if(!$cdrcon) { 
		echo "<h3>Error Fatal: No se pudo conectar a BD Asteriskcdrdb</h3>";
		exit;
		}
?>
