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
						<h2>Administrar <b>Campañas</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="settings.php" class="btn btn-success" title="Configuración"><i class="material-icons">&#xE8B8;</i> <span>Configuración</span></a>
						<a href="#addCampModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Agregar nueva campaña</span></a>
					</div>
                </div>
            </div>
			<div class='clearfix'></div>
			<div id="loader"></div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'></div><!-- Carga de datos ajax aqui -->
            
			
        </div>
    </div>
	<!-- Add Campaña Modal HTML -->
	<?php include("src/modal/modal_add_camp.php");?>
	<!-- Start Campaña Modal HTML -->
	<?php include("src/modal/modal_start.php");?>
	<!-- Delete Modal HTML -->
	<!-- Pausar Campaña Modal HTML -->
	<?php include("src/modal/modal_pausa.php");?>
	<!-- Delete Modal HTML -->
	<?php include("src/modal/modal_delete.php");?>

	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/docs.min.js"></script>
    <script src="js/script_index.js"></script>
    <script src="theme/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>                           		                            