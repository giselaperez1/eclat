<?php
class Producto {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categoria_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los productos del catálogo
    public function leerTodos() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>