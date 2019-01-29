<?php
	/* Connect To Database*/
	require_once ("conexion.php");
	$camp = $_GET['camp'];
	conecta('autodialer');
	$sqlcamp = "SELECT nombre FROM calloutcampana WHERE idcampana = $camp";
	$querycamp = mysql_query($sqlcamp);
	$resultcamp = mysql_fetch_array($querycamp);
	$nom_camp = $resultcamp['nombre'];
	$tables= "autodialer.calloutnumeros a
	left join asteriskcdrdb.cdr b
	on a.uniqueid = b.uniqueid";
	$campos="a.campana, a.telefono, a.nombre, a.cedula, a.mora, a.monto, b.disposition, b.duration";
	$sWhere="where campana = $camp";
	$sWhere.="";
		//Count the total number of row in your table*/
		$per_page = 10;
		$ini = ($_GET['pagina']-1)*$per_page;
		$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables $sWhere");
		if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
		else {echo mysqli_error($con);}
		$total_pages = ceil($numrows/$per_page);
		//calcular primer y ultimo numero
		$primera = $_GET['pagina'] - ($_GET['pagina'] % 4) + 2;
		if ($primera >= $_GET['pagina']){
			$primera = $primera - 4;
		}
		if ($primera < 1){
			$primera = 1;
		}
		$ultima = $primera + 10 > $total_pages ? $total_pages : $primera + 5;
		//main query to fetch the data
		$query = mysqli_query($con,"SELECT $campos FROM $tables $sWhere limit $ini,$per_page");
		//loop through fetched data

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
						<h2>Reporte <b>Campaña - <?php echo $nom_camp?></b></h2>
					</div>
					<div class="col-sm-6">
						<a href="src/ajax/export_csv.php?id=<?php echo $camp ?>" class="btn btn-success" target="_blank"><i class="material-icons">&#xE2C4;</i> <span>Exportar .CSV</span></a>
						<a href="index.php" class="btn btn-success"><i class="material-icons">&#xE5C4;</i> <span>Ver Campañas</span></a>
					</div>
                </div>
            </div>
			<div class='clearfix'></div>
			<div id="loader">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th class='text-center'>Campaña</th>
										<th>Teléfono </th>
										<th>Nombre </th>
										<th>Cédula </th>
										<th>Mora</th>
										<th>Monto</th>
										<th>Respuesta</th>
										<th>Duración</th>
									</tr>
								</thead>
								<tbody>	
										<?php 
										$finales=0;
										while($row = mysqli_fetch_array($query)){						
											$finales++;
										?>	
										<tr>
											<td class='text-center'><?php echo $row['campana'];?></td>
											<td ><?php echo $row['telefono'];?></td>
											<td ><?php echo $row['nombre'];?></td>
											<td ><?php echo $row['cedula'];?></td>
											<td ><?php echo $row['mora'];?></td>
											<td ><?php echo $row['monto'];?></td>
											<td ><?php echo $row['disposition'];?></td>
											<td class="text-center"><?php echo $row['duration'];?></td>
										</tr>
										<?php }?>
										<tr>
											<td colspan='8'> 
												<?php 
													$inicios=$offset+1;
													$finales+=$inicios -1;
													echo "<strong>Mostrando $inicios al $finales de $numrows registros</strong>";
												?>
												<ul class="pagination   pull-right">
													<?php if($_GET['pagina'] == 1): ?>
														<li class='page-item disabled'><a>&lsaquo; Anterior</a></li>
													<?php else: ?>
														<li class='page-item'><a href="registry.php?camp=<?php echo $camp;?>&pagina=<?php echo $_GET['pagina']-1;?>">&lsaquo; Anterior</a></li>
													<?php endif ?>

													<?php for($i=$primera;$i<=($ultima);$i++): ?>
													<li class='page-item <?php echo $_GET['pagina']==$i ? 'active' : "" ?>'><a href="registry.php?camp=<?php echo $camp;?>&pagina=<?php echo $i;?>"><?php echo $i ?></a></li>
													<?php endfor ?>

													<?php if($_GET['pagina'] == $total_pages): ?>
														<li class='page-item disabled'><a>Siguiente &rsaquo;</a></li>
													<?php else: ?>
														<li class='page-item'><a href="registry.php?camp=<?php echo $camp;?>&pagina=<?php echo $_GET['pagina']+1;?>">Siguiente &rsaquo;</a></li>
													<?php endif ?>
												</ul>
											</td>
										</tr>
								</tbody>			
							</table>
						</div>
					<?php	
			?>
			</div><!-- Carga de datos ajax aqui -->
			<div id="resultados"></div><!-- Carga de datos ajax aqui -->
			<div class='outer_div'></div><!-- Carga de datos ajax aqui -->
            
			
        </div>
    </div>
		<!-- export Campaña Modal HTML -->
		<?php include("src/modal/modal_export.php");?>

	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/script_registry.js"></script>
    <script src="theme/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>                                		                            