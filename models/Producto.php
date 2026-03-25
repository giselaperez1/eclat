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
    public $color;
    public $talla;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método editado para filtrar por categoría y ordenar por precio
    public function leerTodos($categoria = null, $orden = '') {
        // Consulta base con JOIN para traer el nombre de la categoría
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, p.color, p.talla, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE 1=1";

        // Si el usuario elige una categoría, filtramos
        if ($categoria != null) {
            $query .= " AND p.categoria_id = :cat_id";
        }

        // Aplicamos el orden según el precio
        if ($orden == 'barato') {
            $query .= " ORDER BY p.precio ASC";
        } elseif ($orden == 'caro') {
            $query .= " ORDER BY p.precio DESC";
        } else {
            $query .= " ORDER BY p.id DESC"; // Por defecto, lo último subido
        }

        $stmt = $this->conn->prepare($query);

        if ($categoria != null) {
            $stmt->bindParam(':cat_id', $categoria);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>