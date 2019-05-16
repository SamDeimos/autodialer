<?php
	require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	if (empty($_POST['id_setting'])){
		$errors[] = "Id vacío.";
	} else {
		$maxretries = $_POST['maxretries'];
		$retrytime = $_POST['retrytime'];
		$waittime = $_POST['waittime'];
		$priority = $_POST['priority'];
		$grabar = $_POST['grabar'][0];
		
		// DELETE FROM  database
		$sqlsettings = "UPDATE settings SET MaxRetries='$maxretries', RetryTime='$retrytime', WaitTime='$waittime', Priority='$priority', Recording='$grabar' WHERE id = 1";
		conecta('autodialer');
		$query = mysql_query($sqlsettings);
		
		// if product has been added successfully
		if ($query) {
			$messages[] = "Configuración guardada";
		} else {
			$errors[] = "Lo sentimos, no se guardo la configuración, regrese y vuelva a intentarlo.";
		}
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