<?php
require_once ("../../conexion.php");

$idcamp = $_POST['start_id'];
//$idcamp = 10;
//Validacion de campaña existente
if(empty($idcamp) or $idcamp == "0"){
    ?>
    <div class="alert alert-dander" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>¡Error interno!</strong>Contacte con soporte técnico
    </div>
    <?php
    exit();
}
conecta('autodialer');
$sql ="select * from calloutcampana where idcampana = $idcamp";
$res = mysql_query($sql);
$reg= mysql_fetch_array($res);

/********************* Datos de configuracion de llamada *********************/
conecta('autodialer');
$sqlsettings = "SELECT * FROM settings";
$querysettings = mysql_query($sqlsettings);
$resultsettings = mysql_fetch_array($querysettings);
$Priority = $resultsettings['Priority'];
//Validación de campaña Activa
if($reg[estado] == "terminada"){
    ?>
    <div class="alert alert-danger" role="alert">
        <strong>¡Campaña Terminada!</strong> Debe crear una nueva Campaña
    </div>
    <?php
    exit();
}elseif($reg[estado] == "pausada" or $reg[estado] == "cargada"){
    conecta('autodialer');
    $sqlupdate = "UPDATE calloutcampana SET estado = 'activa' WHERE idcampana = $idcamp";
    $queryupdate = mysql_query($sqlupdate);
    ?>
    <div class="alert alert-success" role="alert">
        <strong>¡Campaña Activada!</strong> campaña activada exitosamente.
    </div>
    <?php
}


/********* DECLARACION DE VARIABLES **********/
$inicio_campana = $reg['fechacreacion'];
$maxcall = $reg['maxcall'];
$hinicio = $reg['hinicio'];
$hfin = $reg['hfin'];
$exten=$reg['extension'];
$MaxRetries= "MaxRetries: " . $resultsettings['MaxRetries'];
$RetryTime= "RetryTime: " . $resultsettings['RetryTime'];
$WaitTime= "WaitTime: " . $resultsettings['WaitTime'];
$Contexto = $reg['context'];
$prefijo = $reg['prefijo'];
$trunk = $reg['trunk'];
$callid= $reg['callid'];
$account = "Account: Autodialer";
$time= $reg['espera'];

//***************** VALIDACION DE TRONCAL DE SALIDA **************** */
if ($trunk == 'PPM'){
    $troncal = "Local/".$prefijo;
    $ctxtPPM = "@".$Contexto;
}else{
    $troncal = $trunk ."/";
    $ctxtPPM = "";
}

/*********************************************/
/****** Lamadas a realizar *******/
conecta('autodialer');
$query_llamar = "SELECT * FROM calloutnumeros WHERE campana = $idcamp AND respuesta = ''";
$result_llamar = mysql_query($query_llamar) or die(msql_error());
$num_rows_llamar = mysql_num_rows($result_llamar);
echo "<script>console.log( 'Llamadas a realizar de la campaña #" . $idcamp . ": " . $num_rows_llamar . "' );</script>";
$cont = 0;
$call = 1;
while ($array = mysql_fetch_array($result_llamar)){

    /*********** VALIDAR CAMPAÑA ACTIVA ***********/
    conecta('autodialer');
    $sql_status ="select * from calloutcampana where idcampana = $idcamp";
    $verify_status = mysql_query($sql_status);
    $reg_status= mysql_fetch_array($verify_status);
    if($reg_status['estado'] != 'activa'){
        echo "<script>console.log( 'Se realizaron: " . $cont . " llamadas.' );</script>";
        break;
    }
    /*******************************************/

    $id = $array['id'];
    $Channel = "Channel: " . $troncal . $array['telefono'] . $ctxtPPM ;
    $Callerid = "Callerid: Autodialer <".$callid.">";
    $app = "Application: Dial";
    $app_data = "Data: " . $troncal . $exten . "@" . $Contexto;
    $hora = date("H:i");
    if($hora>$hinicio and $hora<$hfin){
        /*******************************************/
        conecta('autodialer');
        $upd_sql = "UPDATE calloutnumeros set respuesta = 'Cola' where id = '$id'";
        $upd_query = mysql_query($upd_sql);
        /*******************************************/
        
        $filedest = "/var/spool/asterisk/outgoing/llamada-".$id.".call";

        fopen($filedest, "w");
            $content = $Channel ."\n";
            $content .= $Callerid ."\n";
            $content .= $MaxRetries ."\n";
            $content .= $WaitTime ."\n";
            $content .= $account ."\n";
            $content .= $RetryTime ."\n";
            $content .= $app ."\n";
            $content .= $app_data ."\n";
            $content .= "Set: CALLERID(num)=".$id."\n";
            //$content .= "Archive: yes \n";
        file_put_contents($filedest, $content, FILE_TEXT | LOCK_EX);

        $cont = $cont + 1;
        if ($call < $maxcall){
            $call = $call + 1;
            sleep(5);
        }else{
            $call = 1;
            $timeout = (5 * $maxcall) - 5;
            sleep($time+$timeout);
        }
        /************************************************/
    }else{
        conecta('autodialer');
        $estado = "Fuera de horario"; 
        $sql="update calloutcampana set respuesta = '$estado' where telefono = '$numero' and campana = '$idcamp'";
        $res = mysql_query($sql);
        if(!$res){
            echo "<p>No se pudo procesar</p>";
        }else{
            ?>
            <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> 
                Campaña Fuera de horario.
            </div>
    <?php
        }
    }
}
/*******************************/

/*********** VALIDACION DE CAMPAÑA TERMINADA ************/
if($cont >= $num_rows_llamar){
    conecta('autodialer');
    $sql="update calloutcampana set estado= 'terminada' where idcampana = '$idcamp'";
    $res = mysql_query($sql);
    $messages[] = "Campaña completada correctamente, <strong>" .$cont ."</strong> números llamados de <strong>" .$num_rows ."</strong>";
}elseif($cont < $num_rows_llamar){
    conecta('autodialer');
    $sql="update calloutcampana set estado= 'pausada' where idcampana = '$idcamp'";
    $res = mysql_query($sql);
    $errors[] = "Campaña incompleta, <strong>" .$cont ."</strong> números llamados de <strong>" .$num_rows ."</strong>";
}
/********************************************************/

/************* RESUMEN DE LLAMADAS *************/
if(isset($errors)){
    ?>
    <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> 
            <?php
                foreach ($errors as $error) {
                        echo $error;
                    }
                ?>
    </div>
    <?php 
}
if(isset($messages)){
    ?>
    <div class="alert alert-success" role="alert">
            <strong>¡Bien hecho! </strong>
            <?php
                foreach ($messages as $message) {
                        echo $message;
                    }
                ?>
    </div>
    <?php
}
?>