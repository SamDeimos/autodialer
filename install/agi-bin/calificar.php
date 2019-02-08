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
//Variables de fecha
$year = date("Y");
$month = date("m");
$day = date("d");
$fecha = date("Ymd-his");

//Variables de llamada
$callerid = $agiwrapper->get_variable('CALLERID(num)');
$src_num = $callerid['data'];
$callerid = $agiwrapper->get_variable('CALLERID(name)');
$name = $callerid['data'];
$callerid = $agiwrapper->get_variable('CDR(dst)');
$dst = $callerid['data'];
$uniqueid = $agiwrapper->request['agi_uniqueid'];
$recordingfilename = "q-$dst-$src_num-$fecha-$uniqueid.gsm";
$folder = "/var/spool/asterisk/monitor/$year/$month/$day";

//Debug 
$agiwrapper->verbose("uniqueid: $uniqueid");
$agiwrapper->verbose("NAME: $name");
$agiwrapper->verbose("Calleid: $src_num");
$agiwrapper->verbose("DST: $dst");

$agiwrapper->exec("MixMonitor","$folder/$recordingfilename","b");

$query_verify = "UPDATE calloutnumeros SET uniqueid = '$uniqueid', respuesta = 'Llamado', recordingfile = '$recordingfilename' WHERE telefono = $src_num AND respuesta = 'Cola' order by id desc limit 1";
$result_verify = mysql_query($query_verify, $link);
?>	