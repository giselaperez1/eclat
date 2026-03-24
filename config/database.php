<?php
class Database {
    //datos del servidor 
    private $host = "localhost";
    private $db_name = "eclat_db"; // nombre del sql
    private $username = "root";
    private $password = "";
    public $conn;

    // Método para conectar
    public function getConnection() {
        $this->conn = null;
        try {
            //pdo mas seguro
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>