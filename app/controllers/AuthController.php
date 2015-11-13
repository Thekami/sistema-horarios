<?php
class AuthController extends BaseController {
 
    /**
     * Attempt user login
     */
    public function doLogin()
    {
        // Obtenemos el email, borramos los espacios y convertimos todo a minúscula con el sig codigo:

        //$email = mb_strtolower(trim(Input::get('email')));
        
        
        // Obtenemos la contraseña y nombre de ususraio enviados

        $username = Input::get('username');   //obtenemos el username enviado por el form
        $password = Input::get('password');   //obtenemos el password enviado por el form
 
        // Realizamos la autenticación
        if (Auth::attempt(['username' => $username, 'password' => $password]))
        {
            // Aquí también pueden devolver una llamada a otro controlador o
            // devolver una vista

            return Redirect::to('/hidden');
        }
        else{
            return Redirect::back()->with('msg', 'Datos incorrectos, vuelve a intentarlo.');
        }
 
        // La autenticación ha fallado re-direccionamos
        // a la página anterior con los datos enviados
        // y con un mensaje de error
        
    }
 
    public function doLogout()
    {
        //Desconctamos al usuario
        Auth::logout();
 
        //Redireccionamos al inicio del sitio con un mensaje
        return Redirect::to('/inicio')->with('msg2', 'Gracias por visitarnos!.');
    }
 
}
?>