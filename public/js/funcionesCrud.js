$(document).ready(function() {
	 $("#myModal").modal("show");
});


$(document).on('click', '#loginButton', function(event) {
	event.preventDefault();
	var username =$('#username').val();
	var password =$('#password').val();

	$.ajax({
		url:'ajax/login',
		type:'post',
		data: {username: username, password: password},
		dataType:'json',
		success: function(loggedIn){
			if(loggedIn){
				//Redirect a la parte de /hidden
				window.location = 'hidden';
			}
			else{
				//Limpia el campo de texto de la contraseña
				$('#password').val("");
				//Carga el texto de la alerta
				$('#msgText').html("Datos incorrectos, vuelve a intentarlo.");
				//Muestra la alerta
				$('#loginMsg').slideDown(400);
				alert(password);
			}
		}
	});
});