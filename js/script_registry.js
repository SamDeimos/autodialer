$('#exportModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var id = button.data('id') 
	$('#id_camp_csv').val(id)
})

$( "#export_csv" ).submit(function( event ) {
	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "src/ajax/export_csv.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Enviando...");
				},
			success: function(datos){
			$("#resultados").html(datos);
			load(1);
			$('#exportModal').modal('hide');
			hiddenAlert();
			}
	});
	//event.preventDefault();
});