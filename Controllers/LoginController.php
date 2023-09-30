<?php
// Iniciar la sesión para poder usar variables de sesión
session_start();
// Incluir el archivo que contiene la clase GlobalModel
require_once '../Models/GlobalModel.php';

// Crear una instancia del controlador LoginController
$loginObject = new LoginController();

// Determinar el método de solicitud y llamar a la función correspondiente
switch( $_REQUEST['method'] ){
    case 'access':
        // Llamar al método 'access' del controlador LoginController
        $loginObject->access( $_REQUEST );
        break;
    case 'logout':
        // Llamar al método 'logout' del controlador LoginController
        $loginObject->logout();
        break;
    default:
        // Establecer un mensaje de error en la variable de sesión si no se permite el método en la solicitud
        $_SESSION['ERRMSG'] = "No method allowed in the request";
        break;
}

// Definición de la clase LoginController
class LoginController{
    private $globalService = NULL;

    public function __construct() {
        // Crear una instancia del modelo GlobalModel
        $this->globalService = new GlobalModel();
    }

    public function access( $request ){
        // Obtener un usuario válido utilizando el nombre de usuario y contraseña proporcionados en la solicitud
        $validUser = $this->globalService->getUser($_REQUEST['username'], $_REQUEST['password']);
        $locate = "/Views/list.php";
        
        // Si se encuentra un usuario válido
        if( $validUser ){
            // Establecer variables de sesión para el usuario autenticado
            $_SESSION['UID'] = $validUser['id'];
            $_SESSION['USERNAME'] = $validUser['username'];
            $_SESSION['ERRMSG'] = "";
            // Establecer opciones de menú en variables de sesión
            $_SESSION['OPTIONS'] = [
                [ "name" => "users", "route" => "users", "class" => "fa-user-o" ],
                [ "name" => "products", "route" => "products", "class" => "fa-lightbulb-o" ],
                [ "name" => "categories", "route" => "category", "class" => "fa-list" ],
                [ "name" => "sector", "route" => "sector", "class" => "fa-tag" ],
                [ "name" => "templates", "route" => "template", "class" => "fa-file-o" ]
            ];
            // Cerrar la sesión actual
            session_write_close();
        }else{
            // Si no se encuentra un usuario válido, establecer un mensaje de error en la variable de sesión
            $_SESSION['ERRMSG'] = "Invalid username or password";
            session_write_close();
            // Redirigir al usuario a la página de inicio de sesión
            $locate = "/Views/login-form.php";
        }
        // Redirigir al usuario a la ubicación especificada
        header("location: ".$locate);
    }

    public function logout(){
        // Destruir la sesión actual
        session_destroy();
        // Redirigir al usuario a la página de inicio
        header('location: /');
    }

}
?>