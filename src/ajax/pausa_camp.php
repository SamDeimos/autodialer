<?php
	require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	if (empty($_POST['pausa_id'])){
		$errors[] = "Id vacío.";
	} elseif (!empty($_POST['pausa_id'])){
    $id = intval($_POST['pausa_id']);
	

	// DELETE FROM  database
	$sqlpausa = "UPDATE calloutcampana SET estado = 'pausada' WHERE idcampana='$id'";
    conecta('autodialer');
	$query = mysql_query($sqlpausa);
    // if product has been added successfully
    if ($query) {
        $messages[] = "La campaña ha sido Pausada.";
    } else {
        $errors[] = "Lo sentimos, la campaña no puede ser pausada, regrese y vuelva a intentarlo.";
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