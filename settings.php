<?php
require_once ('conexion.php');
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
<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2>Configuración<b>Campañas</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="index.php" class="btn btn-success"><i class="material-icons">&#xE5C4;</i> <span>Ver Campañas</span></a>
					</div>
                </div>
            </div>
			<div class='clearfix'></div>
			<div id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'>
            <?php
            conecta('autodialer');
            $sqlsettings = "SELECT * FROM settings";
            $querysettings = mysql_query($sqlsettings);
            $resultsettings = mysql_fetch_array($querysettings);
            ?>
            <br>
				<form name="save_settings" id="save_settings">
						<div class="row">
							<input type="hidden" value="1" name="id_setting" id="id_setting">
							<div class="form-group col-4">
									<label>MaxRetries</label>
									<input type="text" name="maxretries" id="maxretries" class="form-control form-control-sm" value="<?php echo $resultsettings['MaxRetries'] ?>" required>
							</div>
							<div class="form-group col-4">
									<label>RetryTime</label>
									<input type="text" name="retrytime" id="retrytime" class="form-control form-control-sm" value="<?php echo $resultsettings['RetryTime'] ?>" required>
                            </div>
                            <div class="form-group col-4">
									<label>WaitTime</label>
									<input type="text" name="waittime" id="waittime" class="form-control form-control-sm" value="<?php echo $resultsettings['WaitTime'] ?>" required>
							</div>
                        </div>
                        <div class="row">
							<div class="form-group col-6">
									<label>Priority</label>
									<input type="text" name="priority" id="priority" class="form-control form-control-sm" value="<?php echo $resultsettings['Priority'] ?>" required>
							</div>
							<div class="form-group col-6">
								<label>Grabar llamadas</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="1" name="grabar" id="grabar" <?php echo ($resultsettings['Recording'] == 1) ? 'checked' : '' ?>>
									<label class="form-check-label" for="defaultCheck1">
										Habilitar
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="0" name="grabar" id="grabar" <?php echo ($resultsettings['Recording'] == 0) ? 'checked' : '' ?>>
									<label class="form-check-label" for="defaultCheck1">
										Deshabilitar
									</label>
								</div>
							</div>
                        </div>
                    <div class="modal-footer">
						<input type="submit" class="btn btn-success" value="Guardar configuracin">
					</div>
				</form>
            </div><!-- Carga de datos ajax aqui -->
            
			
        </div>
    </div>

	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
	<script src="js/script_settings.js"></script>
    <script src="theme/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>