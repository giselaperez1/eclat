<?php
include_once 'config/Database.php';
include_once 'models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

// Obtenemos todos los productos actualizados de la BD
$stmt = $producto->leerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Éclat - Alta Costura</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px; background-color: #f9f9f9; }
        .producto { 
            background: white; 
            border: 1px solid #eee; 
            padding: 20px; 
            margin-bottom: 20px; 
            width: 320px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .precio { font-weight: bold; color: #b59410; font-size: 1.2em; }
        .agotado { color: #d9534f; font-weight: bold; text-transform: uppercase; }
        button { 
            background: #000; 
            color: #fff; 
            border: none; 
            padding: 10px 15px; 
            cursor: pointer; 
        }
        button:disabled { background: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>

    <h1>Colección de Temporada</h1>
    <p><a href="ver_carrito.php">Ver mi cesta</a></p>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="producto">
            <h3><?php echo $row['nombre']; ?></h3>
            <p><?php echo $row['descripcion']; ?></p>
            <p><strong>Categoría:</strong> <?php echo $row['categoria_nombre']; ?></p>
            <p class="precio"><?php echo $row['precio']; ?> €</p>
            
            <?php if ($row['stock'] > 0): ?>
                <p>Stock disponible: <?php echo $row['stock']; ?></p>
                
                <form method="POST" action="carrito_accion.php">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                    <input type="hidden" name="stock_max" value="<?php echo $row['stock']; ?>">
                    
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" value="1" min="1" max="<?php echo $row['stock']; ?>" style="width: 50px;">
                    <br><br>
                    <button type="submit" name="accion" value="agregar">Añadir a la cesta</button>
                </form>
            <?php else: ?>
                <p class="agotado">Agotado temporalmente</p>
                <button disabled>Sin stock</button>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

</body>
</html>