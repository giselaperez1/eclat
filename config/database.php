<?php
/* conexión a la base de datos
 */
class Database {

    // datos de conexión al servidor MySQL
    // en local XAMPP siempre es localhost con usuario root y sin contraseña
    private $servidor = "localhost";
    private $nombreBD = "eclat_db";  // nombrede la base de datos en phpMyAdmin
    private $usuario  = "root";
    private $clave    = "";          

    public $conn;  

    /*
    getConnection() - establece y devuelve la conexión activa
    Usamos un bloque try/catch para capturar cualquier error de conexión sin que la página pete.
     */
    public function getConnection(){
        $this->conn = null;

        try {
            // construimos el DSN (Data Source Name) que le indica a PDO
            // qué motor de bbdd usar, en qué servidor y qué base de datos
            $dsn = "mysql:host=".$this->servidor.";dbname=".$this->nombreBD;
            $this->conn = new PDO($dsn, $this->usuario, $this->clave);

            // forzamos UTF-8 para que los acentos, la ñ y caracteres especiales
            // se guarden y muestren correctamente en toda la aplicación
            $this->conn->exec("set names utf8");

        } catch(PDOException $e){
            // mostramos el error solo en desarrollo
            echo "Fallo de conexión: ".$e->getMessage();
        }

        return $this->conn;
    }
}
?>
