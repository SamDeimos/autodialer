function hiddenAlert(){
	$(".alert").fadeTo(2000, 1000).slideUp(1000, function(){ 
		$(".alert").slideUp(1500); 
	});
}
$(function() {
	load(1);
});
function load(page){
	var query=$("#q").val();
	var per_page=10;
	var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page};
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'src/ajax/listar_camp.php',
		data: parametros,
		 beforeSend: function(objeto){
		$("#loader").html("Cargando...");
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$("#loader").html("");
		}
	})
}
$('#editProductModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var code = button.data('code') 
	$('#edit_code').val(code)
	var name = button.data('name') 
	$('#edit_name').val(name)
	var category = button.data('category') 
	$('#edit_category').val(category)
	var stock = button.data('stock') 
	$('#edit_stock').val(stock)
	var price = button.data('price') 
	$('#edit_price').val(price)
	var id = button.data('id') 
	$('#edit_id').val(id)
})

$('#deleteModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('id') 
	$('#delete_id').val(id)
	var tabla = button.data('tabla') 
	$('#tabla_id').val(tabla)
})

$('#pausaModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('id') 
	$('#pausa_id').val(id)
})

$('#startCampModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('id')
	$('#start_id').val(id)
})


$( "#edit_product" ).submit(function( event ) {
	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "src/ajax/editar_producto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Enviando...");
				},
			success: function(datos){
			$("#resultados").html(datos);
			load(1);
			$('#editProductModal').modal('hide');
			}
	});
	event.preventDefault();
});


$( "#add_camp" ).submit(function( event ){
	var parametros = new FormData();
	parametros.append('csvcustomFile',$('#csvcustomFile')[0].files[0]);
	parametros.append('name',$('#name').prop('value'));
	parametros.append('camp_h_ini',$('#camp_h_ini').prop('value'));
	parametros.append('camp_h_fin',$('#camp_h_fin').prop('value'));
	parametros.append('type_camp',$('#type_camp').prop('value'));
	parametros.append('trunk',$('#trunk').prop('value'));
	parametros.append('context',$('#context').prop('value'));
	parametros.append('time',$('#time').prop('value'));
	parametros.append('prefix',$('#prefix').prop('value'));
	parametros.append('prefix_callerid',$('#prefix_callerid').prop('value'));
	$.ajax({
		type: "POST",
		url: "src/ajax/save_camp.php",
		data: parametros,
		processData: false,
		contentType: false,
		cache: false,
		success: function(datos){
			console.log(datos);
			$("#resultados").html(datos);
			load(1);
			$('#addCampModal').modal('hide');
			hiddenAlert();
		}
	});
	event.preventDefault();
});

$( "#delete" ).submit(function( event ) {
	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "src/ajax/delete.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Enviando...");
				},
			success: function(datos){
			$("#resultados").html(datos);
			load(1);
			$('#deleteModal').modal('hide');
			hiddenAlert();
			}
	});
	event.preventDefault();
});

$( "#pausa" ).submit(function( event ) {
	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "src/ajax/pausa_camp.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Enviando...");
				$('#pausaModal').modal('hide');
				},
			success: function(datos){
			$("#resultados").html(datos);
			load(1);
			$('#pausaModal').modal('hide');
			hiddenAlert();
			}
	});
	//event.preventDefault();
});

$( "#startcamp" ).submit(function(event){
	var parametros = $(this).serialize();
	$.ajax({
		type: "POST",
		url: "src/ajax/start_camp.php",
		data: parametros,
		beforeSend: function(){
			$("#resultados").innerHTML="<img src='src/img/loading.gif' title='cargando...' />";
			$('#startCampModal').modal('hide');
		},
		success: function(datos){
			$("#resultados").html(datos);
			load(1);
			$('#startCampModal').modal('hide');
			hiddenAlert();
		}
	});
	//event.preventDefault();
});