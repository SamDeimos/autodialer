#!/usr/bin/php -q
<?php
require_once('phpagi.php');
$agiwrapper = new AGI();
ob_implicit_flush(true);
set_time_limit(0);

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
$id_num_reg = $agiwrapper->get_variable('idnum');
$id_num= $id_num_reg['data'];
$uniqueid = $agiwrapper->request['agi_uniqueid'];

//Debug
$agiwrapper->verbose("Id de llamada: $id_num");
$agiwrapper->verbose("uniqueid: $uniqueid");
$agiwrapper->verbose("NAME: $name");
$agiwrapper->verbose("Calleid: $src_num");
$agiwrapper->verbose("DST: $dst");

//Grabar llamada
$sql_recording = 'SELECT Recording FROM settings';
$query_recording = mysql_query($sql_recording, $link);
$result_recording = mysql_fetch_array($query_recording);
if($result_recording['Recording'] == 1){
    $recordingfilename = "q-$dst-$src_num-$fecha-$uniqueid.gsm";
    $folder = "/var/spool/asterisk/monitor/$year/$month/$day";
    $recordingfile = $folder.'/'.$recordingfilename;

    //Debug
    $agiwrapper->verbose("RecordFile: $recordingfilename");
    $agiwrapper->verbose("RuteRecordFile: $folder");
    $agiwrapper->verbose("Grabar llamadas: ACTIVADO");

    //inicar grabación
    $agiwrapper->exec("MixMonitor","$recordingfile","b");

}else{
    $recordingfile = '';
    $agiwrapper->verbose("Grabar llamadas: DESACTIVADO");

}

//Insartar datos para calificar llamada
$query_verify = "UPDATE calloutnumeros SET uniqueid = '$uniqueid', respuesta = 'Llamado', recordingfile = '$recordingfile' WHERE telefono = $src_num AND respuesta = 'Cola' order by id desc limit 1";
$result_verify = mysql_query($query_verify, $link);
?>	