//Muestra alertas dependiendo del tipo que sea
function ShowAlert(type, text){
	$('.alert').removeClass('alert-success').removeClass('alert-error');
	switch(type){
		//Info...
		case 0:
		$('.alert').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
		//Success
		case 1:
		$('.alert').addClass('alert-success').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
		//Error
		case 2:
			$('.alert').addClass('alert-error').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
	}
}