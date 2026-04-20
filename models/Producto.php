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
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método actualizado para filtrar por categoría, orden Y COLOR
    public function leerTodos($categoria = null, $orden = '', $color = null) {
        // Consulta base
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, p.color, p.talla, p.imagen, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE 1=1";

        // Filtro por Categoría
        if ($categoria != null) {
            $query .= " AND p.categoria_id = :cat_id";
        }

        // Filtro por Color (Paso 3)
        if ($color != null) {
            $query .= " AND p.color = :color";
        }

        // Aplicamos el orden
        if ($orden == 'barato') {
            $query .= " ORDER BY p.precio ASC";
        } elseif ($orden == 'caro') {
            $query .= " ORDER BY p.precio DESC";
        } else {
            $query .= " ORDER BY p.id DESC";
        }

        $stmt = $this->conn->prepare($query);

        // Vinculamos los parámetros si existen
        if ($categoria != null) {
            $stmt->bindParam(':cat_id', $categoria);
        }
        
        if ($color != null) {
            $stmt->bindParam(':color', $color);
        }

        $stmt->execute();
        return $stmt;
    }

    // Método para obtener los botones de colores automáticamente
    public function obtenerColoresUnicos() {
        $query = "SELECT DISTINCT color FROM " . $this->table_name . " WHERE color IS NOT NULL AND color != ''";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>