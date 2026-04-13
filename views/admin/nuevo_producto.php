<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$mensaje = "";

// Lógica para guardar el producto
if ($_POST) {
    try {
        $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, color, talla) 
                  VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :color, :talla)";
        
        $stmt = $db->prepare($query);

        $stmt->bindParam(':nombre', $_POST['nombre']);
        $stmt->bindParam(':descripcion', $_POST['descripcion']);
        $stmt->bindParam(':precio', $_POST['precio']);
        $stmt->bindParam(':stock', $_POST['stock']);
        $stmt->bindParam(':categoria_id', $_POST['categoria_id']);
        $stmt->bindParam(':color', $_POST['color']);
        $stmt->bindParam(':talla', $_POST['talla']);

        if ($stmt->execute()) {
            $mensaje = "<p style='color:green;'>¡Producto añadido con éxito!</p>";
        }
    } catch (PDOException $e) {
        $mensaje = "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto - Éclat Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        .form-card { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-transform: uppercase; color: #b59410; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #000; color: #fff; padding: 15px; width: 100%; border: none; cursor: pointer; font-weight: bold; text-transform: uppercase; }
        button:hover { background: #b59410; }
        .volver { display: block; margin-top: 20px; text-align: center; color: #888; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Añadir a la Colección</h2>
    <?php echo $mensaje; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre de la prenda" required>
        <textarea name="descripcion" placeholder="Descripción de alta costura" rows="4" required></textarea>
        
        <div style="display: flex; gap: 10px;">
            <input type="number" step="0.01" name="precio" placeholder="Precio (€)" required>
            <input type="number" name="stock" placeholder="Stock" required>
        </div>

        <select name="categoria_id" required>
            <option value="">Seleccionar Categoría</option>
            <option value="1">Vestidos</option>
            <option value="2">Tops</option>
            <option value="4">Camisetas</option>
            <option value="5">Chaquetas</option>
            <option value="6">Pantalones</option>
            <option value="7">Faldas</option>
            <option value="8">Zapatos</option>
            <option value="3">Accesorios</option>
        </select>

        <input type="text" name="color" placeholder="Color (Ej: Negro Éclat)">
        <input type="text" name="talla" value="Talla Única" placeholder="Talla">

        <button type="submit">Publicar Producto</button>
    </form>
    
    <a href="admin_index.php" class="volver">← Volver al Inventario</a>
</div>

</body>
</html>