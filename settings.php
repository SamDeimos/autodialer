<?php
require_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AutoDialer</title>
	<link rel="stylesheet" href="theme/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="theme/bootstrap/css/material-icons.min.css">
	<link rel="stylesheet" href="theme/css/custom.css">
</head>

<body class="d-flex flex-column justify-content-between vh-100">
	<div class="container">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>Configuración<b>Campañas</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="index.php" class="btn btn-success"><i class="material-icons">&#xE5C4;</i> <span>Ver
								Campañas</span></a>
					</div>
				</div>
			</div>
			<div class='clearfix'></div>
			<div id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'>
				<?php
            $sqlsettings = 'SELECT * FROM settings';
$querysettings = mysqli_query($conAutodialer, $sqlsettings);
$resultsettings = mysqli_fetch_array($querysettings);
?>
				<br>
				<form name="save_settings" id="save_settings">
					<div class="row">
						<input type="hidden" value="1" name="id_setting" id="id_setting">
						<div class="form-group col-4">
							<label>MaxRetries</label>
							<input type="text" name="maxretries" id="maxretries" class="form-control form-control-sm"
								value="<?php echo $resultsettings['MaxRetries']; ?>"
								required>
						</div>
						<div class="form-group col-4">
							<label>RetryTime</label>
							<input type="text" name="retrytime" id="retrytime" class="form-control form-control-sm"
								value="<?php echo $resultsettings['RetryTime']; ?>"
								required>
						</div>
						<div class="form-group col-4">
							<label>WaitTime</label>
							<input type="text" name="waittime" id="waittime" class="form-control form-control-sm"
								value="<?php echo $resultsettings['WaitTime']; ?>"
								required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-6">
							<label>Prefijo. Por defecto: vacío</label>
							<input type="text" name="prefix" id="prefix" class="form-control form-control-sm"
								value="<?php echo $resultsettings['Prefix']; ?>">
						</div>
						<div class="form-group col-6">
							<label>Prefijo de CallerID</label>
							<input type="text" name="prefix_callerid" id="prefix_callerid"
								class="form-control form-control-sm"
								value="<?php echo $resultsettings['Prefix_Callerid']; ?>"
								required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-success" value="Guardar configuracin">
					</div>
				</form>
			</div><!-- Carga de datos ajax aqui -->


		</div>
	</div>

	<div class="d-flex justify-content-between py-1 px-4">
		<span class="text-secondary">v1.0.0</span>
		<a href="https://xudo.dev" target="_blank" class="text-secondary">© 2024 - XUDO</a>
	</div>

	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/script_settings.js"></script>
	<script src="theme/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>