<?php
if (empty($_POST['name'])) {
    $errors[] = 'Algo anda mal, por favor comunicar al equipo de XUDO.';
} elseif (!empty($_POST['name'])) {
    require_once '../../conexion.php'; // Contiene funcion que conecta a la base de datos
    
	$dir = $_SERVER['DOCUMENT_ROOT'] . '/' . 'files_csv/';
    $name_camp = $_POST['name'];
    $camp_h_ini = $_POST['camp_h_ini'];
    $camp_h_fin = $_POST['camp_h_fin'];
    $type_camp = $_POST['type_camp'];
    $trunk = $_POST['trunk'];
    $camp_troncal = $_POST['camp_troncal'];
    $context = $_POST['context'];
    $time = $_POST['time'];
    $maxcall = $_POST['maxcall'];
    $grabar = $_POST['grabar'][0];

    $createdate = date('YmdHis');
    // File .CSV
    $file_type = $_FILES['csvcustomFile']['type'];
    $file_name = $_FILES['csvcustomFile']['name'];
    /********************* Datos de configuracion de llamada *********************/

    $sqlsettings = 'SELECT * FROM settings';
    $querysettings = mysqli_query($conAutodialer, $sqlsettings);
    $resultsettings = mysqli_fetch_array($querysettings);
    $MaxRetries = $resultsettings['MaxRetries'];
    $RetryTime = $resultsettings['RetryTime'];
    $WaitTime = $resultsettings['WaitTime'];
    $Priority = $resultsettings['Priority'];
    $Prefix = $resultsettings['Prefix'];
    $Prefix_Callerid = $resultsettings['Prefix_Callerid'];

    /******************** CREACION DE CAMPAÑA ********************/

    $sqlcamp = "INSERT INTO calloutcampana(nombre,tipo,context,fechacreacion,extension,prefijo,maxcall,trunk,callid,espera,recording,hinicio,hfin,estado) value('".$name_camp."','Autodialer','".$context."','".$createdate."','".$type_camp."','".$Prefix."','".$maxcall."','".$trunk."','".$Prefix_Callerid."','".$time."','".$grabar."','".$camp_h_ini."','".$camp_h_fin."','cargada')";
    $query = mysqli_query($conAutodialer, $sqlcamp);

    $sql = 'SELECT idcampana FROM calloutcampana ORDER BY idcampana DESC LIMIT 1';
    $query = mysqli_query($conAutodialer, $sql);
    $result = mysqli_fetch_array($query);
    $camp_id = $result['idcampana'];
    $new_file_name = $camp_id.'-'.date('mdy').'-'.date('His').'-'.$file_name;

    $sqlupda = "UPDATE calloutcampana SET archivo = '$new_file_name' where idcampana = $camp_id";
    $queryupda = mysqli_query($conAutodialer, $sqlupda);

    // Debugs
    echo "<script>console.log( 'Debug Name Campaña: ".$name_camp."' );</script>";
    echo "<script>console.log( 'Debug ID Campaña: ".$camp_id."' );</script>";
    echo "<script>console.log( 'Debug NewFileName: ".$new_file_name."' );</script>";
    echo "<script>console.log( 'Debug Hora Inicio: ".$camp_h_ini."' );</script>";
    echo "<script>console.log( 'Debug Hora Fin: ".$camp_h_fin."' );</script>";
    echo "<script>console.log( 'Debug Campaña: ".$type_camp."' );</script>";
    echo "<script>console.log( 'Debug Troncal de salida: ".$trunk."' );</script>";
    echo "<script>console.log( 'Debug Contexto: ".$context."' );</script>";
    echo "<script>console.log( 'Debug Duracion de llamada: ".$time."' );</script>";
    echo "<script>console.log( 'Debug Prefijo: ".$prefix."' );</script>";
    echo "<script>console.log( 'Debug Prefijo CallerID: ".$prefix_callerid."' );</script>";
    echo "<script>console.log( 'Debug Ruta de archivo: ".$dir.$new_file_name."' );</script>";
    echo "<script>console.log( '----------------------------------' );</script>";
    echo "<script>console.log( 'Debug MaxRetries: ".$MaxRetries."' );</script>";
    echo "<script>console.log( 'Debug RetryTime: ".$RetryTime."' );</script>";
    echo "<script>console.log( 'Debug WaitTime: ".$WaitTime."' );</script>";
    echo "<script>console.log( 'Debug MaxCall: ".$MaxCall."' );</script>";
    echo "<script>console.log( 'Debug Priority: ".$Priority."' );</script>";

    if ('application/vnd.ms-excel' != $file_type && 'text/csv' != $file_type) {
        echo "<script>alert('Formato del archivo invalido, solo se admiten archivos .csv');</script>";
    } else {
        move_uploaded_file($_FILES['csvcustomFile']['tmp_name'], $dir.$new_file_name);

        if (($handle = fopen($dir.$new_file_name, 'r')) !== false) { // Cargar números del csv
            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                $num = count($data);
                ++$row;
                $numero = 0;
                $nombre = 1;
                $cedula = 2;
                $option1 = 3;
                $option2 = 4;
                $number = $prefix_callerid.$Prefix.$data[$numero];

                $sql = "INSERT INTO calloutnumeros(campana,telefono,nombre,cedula,option1,option2,callid,respuesta) VALUE ('".$camp_id."','".$data[$numero]."','".$data[$nombre]."','".$data[$cedula]."','".$data[$option1]."','".$data[$option2]."','$number','')";
                $query = mysqli_query($conAutodialer, $sql);
            }	// Fin del While
        }
        if ($query) {
            $messages[] = 'Los números se han cargado correctamente.';
        } else {
            $errors[] = 'No fue posible realizar la carga de los números.';
        }
    }
} else {
    $errors[] = 'desconocido.';
}

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
	<strong>¡Bien hecho!</strong>
	<?php
                foreach ($messages as $message) {
                    echo $message;
                }
    ?>
</div>
<?php
}
?>