<?php
require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos

$idcamp = $_POST['start_id'];
//$idcamp = 8;
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

/******* VISTA DETALLES DE CAMPAÑA *******/
echo "<h1>DETALLES DE CAMPAÑA</h1>";
echo "<h2>Campaña # " .$idcamp ."</h2>";
echo "Nombre: " .$reg['nombre'] ."<br>";
echo "Estado: " .$reg['estado'] ."<br>";
echo "Fecha creación: " .$reg['fechacreacion'] ."<br>";
echo "Fecha inicio: " .$reg['hinicio'] ."<br>";
echo "Fecha fin: " .$reg['hfin'] ."<br>";
echo "<hr>";

/*********************/

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
}


/********* DECLARACION DE VARIABLES **********/
$inicio_campana = $reg['fechacreacion'];
$hinicio = $reg['hinicio'];
$hfin = $reg['hfin'];
$exten=$reg['extension'];
$MaxRetries= "MaxRetries: " . $resultsettings['MaxRetries'];  	        //NUMERO DE REINTENTOS- campo retries
$RetryTime= "RetryTime: " . $resultsettings['RetryTime']; 		        //SEGUNDO ENTRE INTENTOS
$WaitTime= "WaitTime: " . $resultsettings['WaitTime']; 			        //SEGUNDOS ANTES DE COLGAR LA LLAMADA
$Contexto = $reg['context'];
$Priority= "Priority: " . $Priority = $resultsettings['Priority'];  	//PRIORIDAD DENTRO DE LA EXTENSION
$prefijo= $reg['prefijo'];			
$trunk= $reg['trunk']; 				            //Troncal de Salida /a-central1
$maxcall= $resultsettings['MaxCall'];			//Simultaneos
$callid= $reg['callid'];				        // CallerID
$account = "Account: Autodialer";
$async = "Async: 0";
$time= $reg['espera'];		//Tiempo entre llamadas									// Analoga o VoIP- campo troncal 1,2									// Analoga o VoIP- campo troncal 1,2

//***************** VALIDACION DE TRONCAL DE SALIDA **************** */
if ($trunk == 'PPM'){
    $troncal = "Local/".$prefijo;
    $ctxtPPM = "@".$Contexto;
}else{
    $troncal = $trunk;
    $ctxtPPM = "";
}

/*********************************************/

/********** VISTA DEPARAMETROS PARA LLAMADA ************/
echo "<h1>PARAMETROS DE LLAMADA</h1>";
echo "<h2>Parametros para Realizar llamadas</h2>";
echo "Extencion: " .$exten ."<br>";
echo $MaxRetries ."<br>";
echo $RetryTime ."<br>";
echo $WaitTime ."<br>";
echo "Contexto: " .$Contexto ."<br>";
echo $Priority ."<br>";
echo "Prefijo: " .$prefijo ."<br>";
echo "Llamadas Simultaneas: " .$maxcall ."<br>";
echo "Identificador de Llamada: " .$callid ."<br>";
echo $account ."<br>";
echo $async ."<br>";
echo "Tiempo de espera entre llamada: " .$time ."<br>";
echo "Trunk: " .$trunk ."<br>";
echo "Troncal: " .$troncal ."<br>";
echo "Plantilla de llamadas: " .$troncal ."(#)".$ctxtPPM ."<br>";
echo "<hr>";

/*******************************************************/

/****** REALIZAR LLAMDAS *******/
//echo "<h1>Consulta a la base</h1>";
conecta('autodialer');
$query = "SELECT * FROM calloutnumeros WHERE campana = $idcamp AND respuesta = ''";
$result = mysql_query($query);
$num_rows = mysql_num_rows($result);
echo "<script>console.log( 'Resultado de query: " . $num_rows . "' );</script>";
$cont = 1;
$call = 1;
while ($array = mysql_fetch_array($result)){

    /*********** VALIDAR CAMPAÑA ACTIVA ***********/
    conecta('autodialer');
    $sql_status ="select * from calloutcampana where idcampana = $idcamp";
    $verify_status = mysql_query($sql_status);
    $reg_status= mysql_fetch_array($verify_status);
    if($reg_status['estado'] != 'activa'){
        echo "<script>console.log( 'Terminada en llamada: " . $cont . "' );</script>";
        break;
    }

    /*******************************************/

    $id=$array['id'];
    $numero=$array['telefono'];
    $Channel= "Channel: " . $troncal. $numero .$ctxtPPM ;
    $num_src = $callid. $prefijo . $numero;		// Se utiliza para buscar en el src del CDR
    $Callerid= "Callerid: Autodialer <".$callid.">";
    $aapp = "Application: Dial";
    $app_data = "Data: Local/".$exten."@".$Contexto; 
    $app_var1 = "Set: PassedInfo=15551234567";
    $hora = date("H:i");
    if($hora>$hinicio and $hora<$hfin){
        /***************** DEBUG ******************/
        echo "<hr>";
        echo "Llamada #: " .$cont ."<br>";
        echo "Id de llamada: " .$id ."<br>";
        echo $Channel ."<br>";
        echo $Callerid ."<br>";
        echo $MaxRetries ."<br>";
        echo $RetryTime ."<br>";
        echo $WaitTime ."<br>";
        echo $account ."<br>";
        echo $app_var1 ."<br>";
        echo $aapp ."<br>";
        echo $app_data ."<br>";
        echo "Tiempo espera: " .$time ."<br>";
        echo "Llamada simultanea #: " .$call ."<br>";
        echo "<hr>";

        /*******************************************/
        conecta('autodialer');
        $upd_sql = "UPDATE calloutnumeros set respuesta = 'Cola' where id = '$id'";
        $upd_query = mysql_query($upd_sql);

        /*******************************************/
        
        $fp = fopen("/var/spool/asterisk/outgoing/myarchivo$id.call","a");
        fwrite($fp, 
        $Channel . PHP_EOL . 
        $Callerid . PHP_EOL . 
        $MaxRetries . PHP_EOL . 
        $RetryTime . PHP_EOL . 
        $WaitTime . PHP_EOL . 
        $account . PHP_EOL .
        $app_var1 . PHP_EOL . 
        $aapp . PHP_EOL .
        $app_data . PHP_EOL ); 
        fclose($fp);
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
        }
    }
}

/*******************************/

/*********** VALIDACION DE CAMPAÑA TERMINADA ************/
if($cont-1 >= $num_rows){
    conecta('autodialer');
    $sql="update calloutcampana set estado= 'terminada' where idcampana = '$idcamp'";
    $res = mysql_query($sql);
    $messages[] = "Campaña completada correctamente, <strong>" .$cont ."</strong> números llamados de <strong>" .$num_rows ."</strong>";
}elseif($cont < $num_rows){
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