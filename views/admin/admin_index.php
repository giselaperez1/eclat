<?php
include_once '../../config/admin_check.php'; // Seguridad
include_once '../../config/database.php';
include_once '../../models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);
$stmt = $producto->leerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Éclat - Gestión</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #000; color: #fff; position: fixed; padding: 20px; }
        .main-content { margin-left: 290px; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #b59410; color: white; text-transform: uppercase; font-size: 0.8em; }
        .btn-nuevo { background: #b59410; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
        .btn-edit { color: #b59410; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #d9534f; text-decoration: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT ADMIN</h2>
    <hr style="border: 0.5px solid #333;">
    <p><a href="../../catalogo_test.php" style="color: #888; text-decoration: none;">← Volver a la Tienda</a></p>
    <p><strong>Gestión</strong></p>
<ul style="list-style: none; padding: 0;">
    <li><a href="admin_index.php" style="color: #888; text-decoration: none;">📦 Productos</a></li>
    <li><a href="admin_usuarios.php" style="color: #fff; text-decoration: none;">👤 Usuarios</a></li>
    <li><a href="admin_pedidos.php" style="color: #888; text-decoration: none;">💰 Pedidos</a></li>
</ul>
</div>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Inventario de Productos</h1>
        <a href="nuevo_producto.php" class="btn-nuevo">+ Añadir Producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo $row['nombre']; ?></strong></td>
                <td><?php echo $row['categoria_nombre']; ?></td>
                <td><?php echo number_format($row['precio'], 2); ?> €</td>
                <td><?php echo $row['stock']; ?> uds.</td>
                <td>
                    <a href="editar_producto.php?id=<?php echo $row['id']; ?>" class="btn-edit">Editar</a>
                    <a href="borrar_producto.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('¿Seguro que quieres eliminar este producto?')">Borrar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>