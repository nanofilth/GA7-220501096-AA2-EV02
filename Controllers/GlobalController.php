<?php
// Iniciar la sesión para poder usar variables de sesión
session_start();
// Incluir el archivo que contiene la clase GlobalModel
require_once '../Models/GlobalModel.php';

// Crear una instancia del controlador GlobalController
$globalObject = new GlobalController();

// Comprobar si se ha enviado un método en la solicitud
if( isset($_REQUEST['method']) ){
    switch( $_REQUEST['method'] ){
        case 'newRecord':
            // Llamar al método 'newRecord' del controlador GlobalController
            $globalObject->newRecord( $_REQUEST );
            break;
        case 'updateRecord':
            // Llamar al método 'updateRecord' del controlador GlobalController
            $globalObject->updateRecord( $_REQUEST );
            break;
        case 'dropRecord':
            // Llamar al método 'dropRecord' del controlador GlobalController
            $globalObject->dropRecord( $_REQUEST );
            break;
        default:
            // Establecer un mensaje de error en la variable de sesión si no se permite el método en la solicitud
            $_SESSION['ERRMSG'] = "No method allowed in the request";
            break;
    }
}

// Definición de la clase GlobalController
class GlobalController
{
    private $globalService = NULL;

    public function __construct() {
        // Crear una instancia del modelo GlobalModel
        $this->globalService = new GlobalModel();
    }

    public function buildGrid( $source ){
        // Construir una cuadrícula de datos (no se utiliza en este código)
        $result = [];
        $result['columns'] = $this->globalService->getColumns( $source );
        $result['rows'] = $this->globalService->getAll( $source, 'id', 20, 1 );
        return $result;
    }

    public function newRecord( $data ){
        // Eliminar el campo 'method' de los datos recibidos
        unset( $data['method'] );
        // Obtener el nombre de la tabla a partir de los datos
        $table = $data['table'];
        unset( $data['table'] );
        // Crear un nuevo registro en la tabla y obtener el resultado
        $result = $this->globalService->create( $table, $data);
        if( !$result )
            // Establecer un mensaje de error en la variable de sesión si hay un error al agregar un nuevo registro
            $_SESSION['ERRMSG'] = "Error adding new record";
        // Redirigir a la página de lista de registros
        header("location: /Views/list.php?source=$table");
    }

    public function updateRecord( $data ){
        // Eliminar el campo 'method' de los datos recibidos
        unset( $data['method'] );
        // Obtener el nombre de la tabla a partir de los datos
        $table = $data['table'];
        unset( $data['table'] );
        // Actualizar un registro en la tabla y obtener el resultado
        $result = $this->globalService->update( $table, $data);
        if( !$result )
            // Establecer un mensaje de error en la variable de sesión si hay un error al actualizar un registro
            $_SESSION['ERRMSG'] = "Error updating record";
        // Redirigir a la página de lista de registros
        header("location: /Views/list.php?source=$table");
    }

    public function dropRecord( $data ){
        // Eliminar el campo 'method' de los datos recibidos
        unset( $data['method'] );
        // Obtener el nombre de la tabla a partir de los datos
        $table = $data['table'];
        unset( $data['table'] );
        // Eliminar un registro de la tabla y obtener el resultado
        $result = $this->globalService->delete( $table, $data);
        if( !$result )
            // Establecer un mensaje de error en la variable de sesión si hay un error al eliminar un registro
            $_SESSION['ERRMSG'] = "Error updating record";
        // Redirigir a la página de lista de registros
        header("location: /Views/list.php?source=$table");
    }

}
?>