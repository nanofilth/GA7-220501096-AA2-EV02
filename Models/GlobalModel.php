<?php
require_once 'DataBase.php';

class GlobalModel {
    
    private $connect = NULL;
    private $pdo = NULL;
    
    public function __construct() {
        // Crear una instancia de la clase DataBase para manejar la conexión a la base de datos.
        $this->connect = new DataBase();
        // Obtener la conexión PDO de la instancia de DataBase.
        $this->pdo = $this->connect::connect();
        // Establecer el modo de obtención de datos predeterminado a FETCH_ASSOC.
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function getAll($table, $order, $paginate, $start_from)
    {
        try {
            // Preparar una consulta para obtener todos los registros de una tabla y ordenarlos según una columna especificada.
            $sth = $this->pdo->prepare("SELECT * FROM $table ORDER BY $order");// LIMIT $start_from, $paginate");
            // Ejecutar la consulta.
            $sth->execute();
            // Obtener todos los resultados en un arreglo asociativo.
            $result = $sth->fetchAll();
            // Desconectar la base de datos.
            DataBase::disconnect();
            // Devolver el resultado.
            return $result;
        } catch (PDOException  $e ){
            // En caso de error, mostrar un mensaje de error.
            echo "Error: ".$e;
        }
        // Devolver un valor por defecto (en este caso, $result no está definido aquí).
        return $result;
    }

    public function getById($table, $id) {
        try{
            // Preparar una consulta para obtener un registro por su ID en una tabla especificada.
            $sth = $this->pdo->prepare("SELECT * FROM $table WHERE id = $id");
            // Ejecutar la consulta.
            $sth->execute();
            // Obtener un solo resultado.
            $result = $sth->fetch();
            // Desconectar la base de datos.
            DataBase::disconnect();
        }catch(PDOException  $e ){
            // En caso de error, mostrar un mensaje de error.
            echo "Error: ".$e;
        }
        // Devolver el resultado (o NULL si hubo un error).
        return ($result);
    }

    public function processData( $data, $process ){
        // Preparar datos para una inserción o actualización en la base de datos.
        $cols = array_keys( $data );
        $quest = [];
        $colvalsquest = [];
        foreach( $cols as $col){
            $quest[] = '?';
            if( $col != 'id')
                $colvalsquest[] = $col.' = ?';
        }
        $columns = implode(", ", $cols);
        $questions = implode(', ', $quest);
        $colvals = implode(', ', $colvalsquest);
        $values = array_values( $data );
        $parts = [];
        if( $process == 'insert' )
            $parts = ['columns' => $columns, 'questions' => $questions, 'values' => $values];
        else
            $parts = ['id' => $data['id'], 'columns' => $colvals, 'values' => array_splice($values, 1, count($values)-1)];
        
        return $parts;
    }

    public function create( $table, $data ) {        
        try {
            // Procesar datos y preparar una consulta de inserción.
            unset($data['id']);
            $parts = $this->processData( $data, 'insert' );
            //var_dump($parts); exit();
            $sql = "INSERT INTO $table ( $parts[columns] ) VALUES ( $parts[questions] )";
            $stmt = $this->pdo->prepare( $sql );
            // Ejecutar la consulta de inserción y devolver el resultado.
            return $stmt->execute( $parts['values'] );
            // Desconectar la base de datos (esta línea nunca se ejecutará debido al retorno anterior).
            DataBase::disconnect();;
        } catch (Exception $e) {
            // En caso de error, desconectar la base de datos y lanzar una excepción.
            DataBase::disconnect();
            throw $e;
        }
    }

     public function update( $table, $data ) {        
        try {
            // Procesar datos y preparar una consulta de actualización.
            $parts = $this->processData( $data, 'update' );
            $sql = "UPDATE $table SET $parts[columns] WHERE id = $parts[id]";
            $stmt = $this->pdo->prepare( $sql );
            // Ejecutar la consulta de actualización y devolver el resultado.
            return $stmt->execute( $parts['values'] );
            // Desconectar la base de datos (esta línea nunca se ejecutará debido al retorno anterior).
            DataBase::disconnect();
        } catch (Exception $e) {
            // En caso de error, desconectar la base de datos y lanzar una excepción.
            DataBase::disconnect();
            throw $e;
        }
    }

    public function delete( $table, $data ) {     
        try {
            // Preparar una consulta para eliminar un registro por su ID en una tabla especificada.
            $sql = "DELETE FROM $table WHERE id = $data[id]";
            $stmt = $this->pdo->prepare( $sql );
            // Ejecutar la consulta de eliminación y devolver el resultado.
            return $stmt->execute( $parts['values'] ); // Esto debería ser $stmt->execute() en lugar de $parts['values'].
            // Desconectar la base de datos (esta línea nunca se ejecutará debido al retorno anterior).
            DataBase::disconnect();
        } catch (Exception $e) {
            // En caso de error, desconectar la base de datos y lanzar una excepción.
            DataBase::disconnect();
            throw $e;
        }
    }

    public function getUser($username, $password) {
        try{
            // Preparar una consulta para obtener un usuario por nombre de usuario y contraseña.
            $sth = $this->pdo->prepare("SELECT * FROM users WHERE username = '".$username."' AND password = '".$password."' ");
            // Ejecutar la consulta.
            $sth->execute();
            // Obtener un solo resultado.
            $result = $sth->fetch();
            // Desconectar la base de datos.
            DataBase::disconnect();
        }catch(PDOException  $e ){
            // En caso de error, mostrar un mensaje de error.
            echo "Error: ".$e;
        }
        // Devolver el resultado (o NULL si hubo un error).
        return $result;
    }

    public function getColumns( $table ){
        try {
            // Obtener información sobre las columnas de una tabla.
            $sth = $this->pdo->prepare("DESCRIBE $table");
            // Ejecutar la consulta.
            $sth->execute();
            // Obtener todos los resultados.
            $result = $sth->fetchAll();
            // Desconectar la base de datos.
            DataBase::disconnect();
            // Devolver el resultado.
            return $result;
        } catch (PDOException  $e ){
            // En caso de error, mostrar un mensaje de error.
            echo "Error: ".$e;
        }
    }

    public function paginator ($table, $limit)
    {
        try {
            // Preparar una consulta para contar el número total de registros en una tabla.
            $sth = $this->pdo->prepare("SELECT COUNT(*) FROM $table");
            // Ejecutar la consulta.
            $sth->execute();
            // Obtener el resultado del conteo.
            $result = $sth->fetchColumn();

            // Desconectar la base de datos.
            DataBase::disconnect();
            
            // Calcular el número total de páginas en función del límite especificado.
            $total_pages = ceil($result / $limit);
            // Devolver el número total de páginas.
            return $total_pages;
        } catch (PDOException  $e ){
            // En caso de error, mostrar un mensaje de error.
            echo "Error: ".$e;
        }
    }

}