<?php
include('conexion.php');
$troncales = listar_trunk();
?>
<div id="addCampModal" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form name="add_camp" id="add_camp">
					<div class="modal-header">					
						<h4 class="modal-title">Agregar Campaña</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Nombre de campaña</label>
									<input type="text" name="name" id="name" class="form-control form-control-sm" required>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
								<label for="">Selecione una campaña</label>
								<div class="input-group input-group-sm mb-3">
									<div class="input-group-prepend">
									<label class="input-group-text">Campaña</label>
									</div>
									<select class="form-control form-control-sm" id="type_camp" name="type_camp">
										<option value="4010" selected="selected">Cobranza</option>
            							<option value="4011">Marketing</option>
            							<option value="4012">Actualización</option>
            							<option value="1234">Prueba</option>
									</select>
								</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-6">
								<label for="">Selecione una troncal</label><!-- Troncal a usar en campaña -->
								<div class="input-group input-group-sm mb-3">
									<div class="input-group-prepend">
									<label class="input-group-text">Troncal</label>
									</div>
									<select class="form-control form-control-sm" id="trunk" name="trunk">
										<option value="PPM">(Plan Por Marcado)</option>
										<?php foreach($troncales as $fila){?>
										<option value="<?php echo strtoupper($fila['tech']) ."/" .$fila['channelid'] ?>">
										<?php echo $fila['name'] ?>
										</option>
										<?php 
										} 
										?>
									</select>
								</div>
							</div>
							<div class="form-group col-6">
								<label for="">Horario</label>
								<div class="container">
									<div class="row">
										<input class="form-control form-control-sm col" id="camp_h_ini" name="camp_h_ini" type="time" value="00:00">
										<p class="col-3 text-center">Hasta</p>
										<input class="form-control form-control-sm col" id="camp_h_fin" name="camp_h_fin" type="time" value="23:59">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-6">
								<label for="">Selecione un contexto</label><!-- Troncal a usar en campaña -->
								<div class="input-group input-group-sm mb-3">
									<div class="input-group-prepend">
									<label class="input-group-text">Context</label>
									</div>
									<select class="form-control form-control-sm" name="context" id="context">
										<option value="from-internal" selected="selected">from-internal</option>
										<option value="call-file-test">call-file-test</option>
										<option value="from-internal-custom">from-internal-custom</option>
									</select>
								</div>
							</div>
							<div class="form-group col-6">
								<div class="row">
									<div class="form-group col-12">
										<label>Duración de llamada</label>
										<input type="text" name="time" id="time" class="form-control form-control-sm" value="35" required>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-6">
								<label for="maxcall">Llamadas simultaneas</label>
								<input type="text" name="maxcall" id="maxcall" class="form-control form-control-sm" value="1" required>
							</div>
							<div class="form-group col-6">
								<label>Grabar llamadas</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="1" name="grabar" id="grabar">
									<label class="form-check-label" for="defaultCheck1">
										Habilitar
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="0" name="grabar" id="grabar" checked>
									<label class="form-check-label" for="defaultCheck1">
										Deshabilitar
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-12">
								<h5>Cargar .CSV</h5>
								<div class="custom-file">
									<input type="file" name="csvcustomFile" id="csvcustomFile" class="custom-file-input" required>
									<label class="custom-file-label" for="csvcustomFile" data-browse="Buscar archivo">Seleccionar Archivo</label>
								</div>
							</div>
						</div>		
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
						<input type="submit" class="btn btn-success" value="Guardar datos">
					</div>
				</form>
			</div>
		</div>
	</div>