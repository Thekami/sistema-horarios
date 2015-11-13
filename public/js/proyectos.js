function mostrarForm(){
	var text = $('#btnMostrar').text();
	if(text === "Nuevo Proyecto"){
		text = "Cancelar";
		$('#btnMostrar').removeClass('btn-success');
		$('#btnMostrar').addClass('btn-danger');
	}
	else{
		text = "Nuevo Proyecto";
		$('#btnMostrar').removeClass('btn-danger');
		$('#btnMostrar').addClass('btn-success');
	}
	$('#btnMostrar').text(text);
	$('#formCrear').slideToggle(300);
}