#!/usr/bin/php -q
<?php
require_once('phpagi.php') ;
$agiwrapper = new AGI() ;
ob_implicit_flush(true) ;
set_time_limit(0) ;

//Conexion BD poll
$dbase='autodialer';
$servidor='localhost';
$usuario='root';
$pass='bcga1303';
//$id = $_SERVER['argv'][1];
date_default_timezone_set("America/Guayaquil");
$hora_actual = date("H:i:s");
$link = mysql_connect($servidor,$usuario,$pass);
if(!$link){
	$agiwrapper->verbose("Imposible conectar a la DB");
}
$sel_db = mysql_select_db($dbase);
if(!$sel_db){
	$agiwrapper->verbose("Imposible seleccionar DB");
}

$agiwrapper->exec('Playback',"hang-on-a-second");		//Saludo inicial
$callerid = $agiwrapper->get_variable('CALLERID(num)');
$src_num = $callerid['data'];							//Numero Entrante
$callerid = $agiwrapper->get_variable('CALLERID(name)');
$name = $callerid['data'];								//Numero Entrante
$uniqueid = $agiwrapper->request['agi_uniqueid'];
//Validar informacion
$agiwrapper->verbose("uniqueid: $uniqueid");
$agiwrapper->verbose("NAME: $name");
$agiwrapper->verbose("Calleid: $src_num");

$query_verify = "UPDATE calloutnumeros SET uniqueid = '$uniqueid', respuesta = 'Llamado' WHERE telefono = $src_num AND respuesta = 'Cola' order by id desc limit 1";
$result_verify = mysql_query($query_verify, $link);
?>