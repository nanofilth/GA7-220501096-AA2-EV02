<?php

// Definición de la clase DataBase
class DataBase extends PDO
{
    // Variables estáticas para la configuración de la base de datos
    private static $dbName = 'maker' ;
    private static $dbHost = '127.0.0.1' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';

    // Variable estática para mantener una única instancia de la conexión a la base de datos
    private static $cont  = null;

    // Constructor privado para evitar la inicialización de la clase
    public function __construct()
    {
        // Die se encuentra comentado, lo que significa que no se permite la inicialización de la clase
        //die('Init function is not allowed');
    }

    // Método estático para establecer una conexión a la base de datos
    public static function connect()
    {
        // Establecer una única conexión para toda la aplicación si no existe una conexión activa
        if (null == self::$cont)
        {
            try
            {
                // Crear una nueva instancia de PDO para la conexión a la base de datos
                self::$cont = new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
            }
            catch(PDOException $e)
            {
                // En caso de error al conectar, mostrar el mensaje de error
                die($e->getMessage());
            }
        }
        // Devolver la instancia de la conexión a la base de datos
        return self::$cont;
    }

    // Método estático para desconectar la base de datos estableciendo la instancia como nula
    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>