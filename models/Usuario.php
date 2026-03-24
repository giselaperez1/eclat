<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Atributos de la base de datos 
    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $contrasena;
    public $rol;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar un nuevo cliente
    public function registrar() {
        // La consulta SQL para insertar
        $query = "INSERT INTO " . $this->table_name . " 
                (nombre, apellidos, email, contrasena, rol) 
                VALUES (:nombre, :apellidos, :email, :contrasena, :rol)";

        $stmt = $this->conn->prepare($query);

        // Limpieza de datos 
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Encriptamos la contraseña por seguridad
        $password_hash = password_hash($this->contrasena, PASSWORD_BCRYPT);

        // Unimos los datos con la consulta
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contrasena", $password_hash);
        $stmt->bindParam(":rol", $this->rol);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Método para verificar si el email existe y obtener sus datos
public function emailExiste() {
    $query = "SELECT id, nombre, apellidos, contrasena, rol 
              FROM " . $this->table_name . " 
              WHERE email = :email LIMIT 0,1";

    $stmt = $this->conn->prepare($query);
    $this->email = htmlspecialchars(strip_tags($this->email));
    $stmt->bindParam(":email", $this->email);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->nombre = $row['nombre'];
        $this->apellidos = $row['apellidos'];
        $this->contrasena = $row['contrasena'];
        $this->rol = $row['rol'];
        return true;
    }
    return false;
}

}
?>