<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$mensaje = "";

// Lógica para guardar el producto
if ($_POST) {
    try {
        // --- INICIO LÓGICA DE SUBIDA DE IMAGEN ---
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        $carpeta_destino = "../../assets/img/productos/";

        // Creamos un nombre único usando el tiempo actual + nombre original
        $nombre_final_imagen = time() . "_" . $nombre_imagen;
        $ruta_final = $carpeta_destino . $nombre_final_imagen;

        // Intentamos mover el archivo a nuestra carpeta
        if (move_uploaded_file($ruta_temporal, $ruta_final)) {
            
            // Si la foto se sube bien, guardamos todo en la base de datos
            $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, color, talla, imagen) 
                      VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :color, :talla, :imagen)";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':nombre', $_POST['nombre']);
            $stmt->bindParam(':descripcion', $_POST['descripcion']);
            $stmt->bindParam(':precio', $_POST['precio']);
            $stmt->bindParam(':stock', $_POST['stock']);
            $stmt->bindParam(':categoria_id', $_POST['categoria_id']);
            $stmt->bindParam(':color', $_POST['color']);
            $stmt->bindParam(':talla', $_POST['talla']);
            $stmt->bindParam(':imagen', $nombre_final_imagen); // Guardamos el nombre del archivo real

            if ($stmt->execute()) {
                $mensaje = "<p style='color:green;'>¡Producto y foto añadidos con éxito!</p>";
            }
        } else {
            $mensaje = "<p style='color:red;'>Error al subir la imagen. Revisa que la carpeta 'assets/img/productos/' exista.</p>";
        }
        // --- FIN LÓGICA DE IMAGEN ---

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
        h2 { text-transform: uppercase; color: #b59410; margin-bottom: 20px; }
        label { font-size: 0.8em; font-weight: bold; color: #555; display: block; margin-top: 10px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 5px 0 15px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        input[type="file"] { background: #fafafa; border: 1px dashed #b59410; padding: 15px; cursor: pointer; }
        button { background: #000; color: #fff; padding: 15px; width: 100%; border: none; cursor: pointer; font-weight: bold; text-transform: uppercase; margin-top: 10px; transition: 0.3s; }
        button:hover { background: #b59410; }
        .volver { display: block; margin-top: 20px; text-align: center; color: #888; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Añadir a la Colección</h2>
    <?php echo $mensaje; ?>

    <form method="POST" enctype="multipart/form-data">
        
        <label>Nombre del Producto</label>
        <input type="text" name="nombre" placeholder="Ej: Vestido Seda Oro" required>

        <label>Descripción de alta costura</label>
        <textarea name="descripcion" placeholder="Detalles de la prenda..." rows="4" required></textarea>
        
        <label>Fotografía del Producto</label>
        <input type="file" name="imagen" accept="image/*" required>

        <div style="display: flex; gap: 10px;">
            <div style="flex: 1;">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" placeholder="0.00" required>
            </div>
            <div style="flex: 1;">
                <label>Stock Inicial</label>
                <input type="number" name="stock" placeholder="Uds." required>
            </div>
        </div>

        <label>Categoría</label>
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

        <div style="display: flex; gap: 10px;">
            <div style="flex: 1;">
                <label>Color</label>
                <input type="text" name="color" placeholder="Ej: Marfil">
            </div>
            <div style="flex: 1;">
                <label>Talla</label>
                <input type="text" name="talla" value="Talla Única">
            </div>
        </div>

        <button type="submit">Publicar en Éclat</button>
    </form>
    
    <a href="admin_index.php" class="volver">← Volver al Inventario</a>
</div>

</body>
</html>