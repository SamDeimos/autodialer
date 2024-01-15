<?php
require_once '../../conexion.php';

$idcamp = $_POST['start_id'];
// $idcamp = 10;
// Validacion de campaña existente
if (empty($idcamp) or '0' == $idcamp) {
    ?>
<div class="alert alert-dander" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>¡Error interno!</strong>Contacte con soporte técnico
</div>
<?php
    exit;
}

$sql = "select * from calloutcampana where idcampana = $idcamp";
$res = mysqli_query($conAutodialer, $sql);
$reg = mysqli_fetch_array($res);

/********************* Datos de configuracion de llamada *********************/
$sqlsettings = 'SELECT * FROM settings';
$querysettings = mysqli_query($conAutodialer, $sqlsettings);
$resultsettings = mysqli_fetch_array($querysettings);
$Priority = $resultsettings['Priority'];
// Validación de campaña Activa
if ('terminada' == $reg['estado']) {
    ?>
<div class="alert alert-danger" role="alert">
    <strong>¡Campaña Terminada!</strong> Debe crear una nueva Campaña
</div>
<?php
    exit;
} elseif ('pausada' == $reg['estado'] or 'cargada' == $reg['estado']) {
    $sqlupdate = "UPDATE calloutcampana SET estado = 'activa' WHERE idcampana = $idcamp";
    $queryupdate = mysqli_query($conAutodialer, $sqlupdate);
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
$exten = $reg['extension'];
$MaxRetries = 'MaxRetries: '.$resultsettings['MaxRetries'];
$RetryTime = 'RetryTime: '.$resultsettings['RetryTime'];
$WaitTime = 'WaitTime: '.$resultsettings['WaitTime'];
$Contexto = $reg['context'];
$prefijo = $reg['prefijo'];
$trunk = $reg['trunk'];
$callid = $reg['callid'];
$account = 'Account: Autodialer';
$time = $reg['espera'];

// ***************** VALIDACION DE TRONCAL DE SALIDA **************** */
if ('PPM' == $trunk) {
    $troncal = 'Local/'.$prefijo;
    $ctxtPPM = '@'.$Contexto;
} else {
    $troncal = $trunk.'/';
    $ctxtPPM = '';
}

/****** Lamadas a realizar *******/
$query_llamar = "SELECT * FROM calloutnumeros WHERE campana = $idcamp AND respuesta = ''";
$result_llamar = mysqli_query($conAutodialer, $query_llamar);
$num_rows_llamar = mysqli_num_rows($result_llamar);
echo "<script>console.log( 'Llamadas a realizar de la campaña #".$idcamp.': '.$num_rows_llamar."' );</script>";
$cont = 0;
$call = 1;
while ($array = mysqli_fetch_array($result_llamar)) {
    /*********** VALIDAR CAMPAÑA ACTIVA ***********/
    $sql_status = "select * from calloutcampana where idcampana = $idcamp";
    $verify_status = mysqli_query($conAutodialer, $sql_status);
    $reg_status = mysqli_fetch_array($verify_status);
    if ('activa' != $reg_status['estado']) {
        echo "<script>console.log( 'Se realizaron: ".$cont." llamadas.' );</script>";
        break;
    }

    $id = $array['id'];
    $Channel = 'Channel: '.$troncal.$array['telefono'].$ctxtPPM;
    $Callerid = 'Callerid: Autodialer <'.$callid.'>';
    $app = 'Application: Dial';
    $app_data = 'Data: '.$troncal.$exten.'@'.$Contexto;
    $hora = date('H:i');
    if ($hora > $hinicio && $hora < $hfin) {
        $upd_sql = "UPDATE calloutnumeros set respuesta = 'Cola' where id = '$id'";
        $upd_query = mysqli_query($conAutodialer, $upd_sql);

        $filedest = '/var/spool/asterisk/outgoing/llamada-'.$id.'.call';

        $fp = fopen($filedest, 'a');
        fwrite($fp,
            $Channel.PHP_EOL.
            $Callerid.PHP_EOL.
            $MaxRetries.PHP_EOL.
            $WaitTime.PHP_EOL.
            $account.PHP_EOL.
            $RetryTime.PHP_EOL.
            $app.PHP_EOL.
            $app_data.PHP_EOL.
            'Set: CALLERID(num)='.$id.PHP_EOL);
            // 'Archive: Yes'.PHP_EOL
        fclose($fp);

        $cont = $cont + 1;
        if ($call < $maxcall) {
            $call = $call + 1;
            sleep(5);
        } else {
            $call = 1;
            $timeout = (5 * $maxcall) - 5;
            sleep($time + $timeout);
        }
    } else {
        $estado = 'Fuera de horario';
        $sql = "update calloutcampana set respuesta = '$estado' where telefono = '$numero' and campana = '$idcamp'";
        $res = mysqli_query($conAutodialer, $sql);
        if (!$res) {
            echo '<p>No se pudo procesar</p>';
        } else {
            ?>
<div class="alert alert-danger" role="alert">
    <strong>Error!</strong>
    Campaña Fuera de horario.
</div>
<?php
        }
    }
}

/*********** VALIDACION DE CAMPAÑA TERMINADA ************/
if ($cont >= $num_rows_llamar) {
    $sql = "update calloutcampana set estado= 'terminada' where idcampana = '$idcamp'";
    $res = mysqli_query($conAutodialer, $sql);
    $messages[] = 'Campaña completada correctamente, <strong>'.$cont.'</strong> números llamados de <strong>'.$num_rows.'</strong>';
} elseif ($cont < $num_rows_llamar) {
    $sql = "update calloutcampana set estado= 'pausada' where idcampana = '$idcamp'";
    $res = mysqli_query($conAutodialer, $sql);
    $errors[] = 'Campaña incompleta, <strong>'.$cont.'</strong> números llamados de <strong>'.$num_rows.'</strong>';
}

/************* RESUMEN DE LLAMADAS *************/
if (isset($errors)) {
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
if (isset($messages)) {
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