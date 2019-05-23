function hiddenAlert(){
	$(".alert").fadeTo(2000, 1000).slideUp(1000, function(){ 
		$(".alert").slideUp(1500); 
	});
}

$( "#save_settings" ).submit(function( event ) {
	var parametros = $(this).serialize();
	console.log(parametros);
	$.ajax({
			type: "POST",
			url: "src/ajax/save_settings.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Enviando...");
				},
			success: function(datos){
				console.log(datos);
				$("#resultados").html(datos);
				hiddenAlert();
			}
	});
	event.preventDefault();
});