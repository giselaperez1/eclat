<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$mensaje = "";

// 1. Cargamos los datos actuales del producto para que aparezcan en el formulario
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM productos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $p = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$p) {
        header("Location: admin_index.php");
        exit;
    }
}

// 2. Lógica para procesar la actualización
if ($_POST) {
    try {
        $update = "UPDATE productos SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    stock = :stock, 
                    categoria_id = :categoria_id, 
                    color = :color, 
                    talla = :talla 
                   WHERE id = :id";
        
        $stmt_up = $db->prepare($update);
        $stmt_up->bindParam(':nombre', $_POST['nombre']);
        $stmt_up->bindParam(':descripcion', $_POST['descripcion']);
        $stmt_up->bindParam(':precio', $_POST['precio']);
        $stmt_up->bindParam(':stock', $_POST['stock']);
        $stmt_up->bindParam(':categoria_id', $_POST['categoria_id']);
        $stmt_up->bindParam(':color', $_POST['color']);
        $stmt_up->bindParam(':talla', $_POST['talla']);
        $stmt_up->bindParam(':id', $id);

        if ($stmt_up->execute()) {
            $mensaje = "<p style='color:green;'>¡Producto actualizado con éxito!</p>";
            // Refrescamos los datos para mostrar los nuevos en el formulario
            $p['nombre'] = $_POST['nombre'];
            $p['descripcion'] = $_POST['descripcion'];
            $p['precio'] = $_POST['precio'];
            $p['stock'] = $_POST['stock'];
            $p['categoria_id'] = $_POST['categoria_id'];
            $p['color'] = $_POST['color'];
            $p['talla'] = $_POST['talla'];
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
    <title>Editar Prenda - Éclat Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        .form-card { background: white; padding: 30px; max-width: 550px; margin: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-transform: uppercase; color: #b59410; margin-bottom: 5px; }
        p.id-label { color: #aaa; font-size: 0.8em; margin-bottom: 20px; }
        label { font-size: 0.8em; font-weight: bold; color: #555; text-transform: uppercase; }
        input, textarea, select { width: 100%; padding: 12px; margin: 8px 0 20px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #b59410; color: #fff; padding: 15px; width: 100%; border: none; cursor: pointer; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        button:hover { background: #000; }
        .volver { display: block; margin-top: 25px; text-align: center; color: #888; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Editar Producto</h2>
    <p class="id-label">Editando ID: #<?php echo $p['id']; ?></p>
    
    <?php echo $mensaje; ?>

    <form method="POST">
        <label>Nombre del producto</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($p['nombre']); ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="4" required><?php echo htmlspecialchars($p['descripcion']); ?></textarea>
        
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $p['precio']; ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Stock actual</label>
                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="categoria_id" required>
            <?php 
            $cats = [1=>"Vestidos", 2=>"Tops", 3=>"Accesorios", 4=>"Camisetas", 5=>"Chaquetas", 6=>"Pantalones", 7=>"Faldas", 8=>"Zapatos"];
            foreach($cats as $id_cat => $nombre_cat): ?>
                <option value="<?php echo $id_cat; ?>" <?php echo ($p['categoria_id'] == $id_cat) ? 'selected' : ''; ?>>
                    <?php echo $nombre_cat; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Color</label>
                <input type="text" name="color" value="<?php echo htmlspecialchars($p['color']); ?>">
            </div>
            <div style="flex: 1;">
                <label>Talla</label>
                <input type="text" name="talla" value="<?php echo htmlspecialchars($p['talla']); ?>">
            </div>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
    
    <a href="admin_index.php" class="volver">← Cancelar y volver al Inventario</a>
</div>

</body>
</html>