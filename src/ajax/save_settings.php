<?php
	require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	if (empty($_POST['maxcall'])){
		$errors[] = "Id vacío.";
	} elseif (!empty($_POST['maxcall'])){
    $maxretries = $_POST['maxretries'];
    $retrytime = $_POST['retrytime'];
    $waittime = $_POST['waittime'];
    $maxcall = $_POST['maxcall'];
    $priority = $_POST['priority'];
	

	// DELETE FROM  database
	$sqlsettings = "UPDATE settings SET MaxRetries='$maxretries', RetryTime='$retrytime', WaitTime='$waittime', Priority='$priority' WHERE id = 1";
    conecta('autodialer');
	$query = mysql_query($sqlsettings);
	
    // if product has been added successfully
    if ($query) {
        $messages[] = "Configuración guardada";
    } else {
        $errors[] = "Lo sentimos, no se guardo la configuración, regrese y vuelva a intentarlo.";
    }
		
	} else 
	{
		$errors[] = "desconocido.";
	}
if (isset($errors)){
			
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
			if (isset($messages)){
				
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