<?php
	
	/* Connect To Database*/
	require_once ("../../conexion.php");

	
//$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
$action = 'ajax';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($conAutodialer,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

	$tables= "calloutcampana";
	$campos="*";
	$sWhere="";
	$sWhere.=" order by tblprod.prod_name";
	
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($conAutodialer,"SELECT count(*) AS numrows FROM $tables");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($conAutodialer);}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$query = mysqli_query($conAutodialer,"SELECT $campos FROM  $tables order by idcampana DESC LIMIT $offset,$per_page");
	//loop through fetched data
	


		
	
	if ($numrows>0){
		
	?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Nombre </th>
						<th>Creación </th>
						<th>Archivo </th>
						<th>Estado</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$camp_id=$row['idcampana'];
							$camp_name=$row['nombre'];
							$fecha_crearte=$row['fechacreacion'];
							$status=$row['estado'];						
							$file=$row['archivo'];						
							$finales++;
						?>	
						<tr>
							<td><?php echo $camp_name;?></td>
							<td><?php echo $fecha_crearte;?></td>
							<td><?php echo $file;?></td>
							<td><?php echo $status;?></td>
							<td width="150">
								<a href="#" data-target="<?php echo $status=='terminada' ? '' : '#startCampModal'?>" class="start_camp <?php echo $status=='terminada' || $status=='Fuera de horario' ? 'end_camp' : ''?>  <?php echo $status=='activa' ? 'd-none' : ''?> " data-toggle="modal" data-id="<?php echo $camp_id?>"><i class="material-icons disabled" data-toggle="tooltip" title="Iniciar llamadas" onclick="setTimeout('load(1)',2000);">&#xE0B0;</i></a>
								<a href="#" data-target="#pausaModal" class="edit <?php echo $status=='pausada' || $status=='terminada' || $status=='cargada' ? 'd-none' : ''?>" data-toggle="modal" data-id="<?php echo $camp_id?>"><i class="material-icons" data-toggle="tooltip" title="Pausar llamada" >&#xE620;</i></a>
								<a href="registry.php?camp=<?php echo $camp_id;?>&pagina=1" class="registry"><i class="material-icons" data-toggle="tooltip" title="Reporte">&#xE0BC;</i></a>
								<a href="#" data-target="#deleteModal" class="delete" data-toggle="modal" data-tabla="calloutcampana" data-id="<?php echo $camp_id;?>"><i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
                    		</td>
						</tr>
						<?php }?>
						<tr>
							<td colspan='6'> 
								<?php 
									$inicios=$offset+1;
									$finales+=$inicios -1;
									echo "<strong>Mostrando $inicios al $finales de $numrows registros</strong>";
									echo paginate( $page, $total_pages, $adjacents);
								?>
							</td>
						</tr>
				</tbody>			
			</table>
		</div>
	<?php	
	}	
}
?>          
		  
