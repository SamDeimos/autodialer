<?php
	require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	if (empty($_POST['id_setting'])){
		$errors[] = "Id vacío.";
	} else {
		$maxretries = $_POST['maxretries'];
		$retrytime = $_POST['retrytime'];
		$waittime = $_POST['waittime'];
		$Prefix = $_POST['prefix'];
		$Prefix_Callerid = $_POST['prefix_callerid'];
		
		// DELETE FROM  database
		$sqlsettings = "UPDATE settings SET MaxRetries='$maxretries', RetryTime='$retrytime', WaitTime='$waittime', Prefix='$Prefix', Prefix_Callerid='$Prefix_Callerid' WHERE id = 1";
		$query = mysqli_query($conAutodialer, $sqlsettings);
		
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