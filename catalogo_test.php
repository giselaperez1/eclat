<?php
include_once 'config/Database.php';
include_once 'models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

$stmt = $producto->leerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Éclat</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .producto { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; width: 300px; }
        .precio { font-weight: bold; color: #b59410; } /* Color dorado boutique */
    </style>
</head>
<body>
    <h1>Catálogo de Alta Costura</h1>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="producto">
            <h3><?php echo $row['nombre']; ?></h3>
            <p><?php echo $row['descripcion']; ?></p>
            <p>Categoría: <?php echo $row['categoria_nombre']; ?></p>
            <p class="precio"><?php echo $row['precio']; ?> €</p>
            <p>Stock disponible: <?php echo $row['stock']; ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>