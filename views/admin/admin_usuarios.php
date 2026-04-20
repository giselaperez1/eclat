<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Lógica para cambiar rol o borrar si llega una petición
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_u = $_GET['id'];
    if ($_GET['accion'] == 'hacer_admin') {
        $db->query("UPDATE usuarios SET rol = 'admin' WHERE id = $id_u");
    } elseif ($_GET['accion'] == 'hacer_cliente') {
        $db->query("UPDATE usuarios SET rol = 'cliente' WHERE id = $id_u");
    } elseif ($_GET['accion'] == 'borrar') {
        $db->query("DELETE FROM usuarios WHERE id = $id_u");
    }
    header("Location: admin_usuarios.php");
    exit;
}

$query = "SELECT id, nombre, email, rol FROM usuarios ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes - Éclat</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #000; color: #fff; position: fixed; padding: 20px; }
        .main-content { margin-left: 290px; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #000; color: white; text-transform: uppercase; font-size: 0.8em; }
        .btn { padding: 5px 10px; text-decoration: none; font-size: 0.75em; border-radius: 3px; margin-right: 5px; font-weight: bold; }
        .btn-rol { background: #e2e2e2; color: #333; }
        .btn-historial { background: #b59410; color: #fff; }
        .btn-borrar { background: #ffdfdf; color: #c00; }
        .rol-tag { font-size: 0.7em; padding: 3px 6px; border-radius: 3px; text-transform: uppercase; font-weight: bold; }
        .admin { background: #fff9e6; color: #b59410; border: 1px solid #b59410; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT ADMIN</h2>
    <hr style="border: 0.5px solid #333; margin: 20px 0;">
    <p><a href="admin_index.php" style="color: #888; text-decoration: none;">📦 Productos</a></p>
    <p><a href="admin_usuarios.php" style="color: #b59410; text-decoration: none; font-weight: bold;">👤 Usuarios</a></p>
    <p><a href="admin_pedidos.php" style="color: #888; text-decoration: none;">💰 Pedidos</a></p>
</div>

<div class="main-content">
    <h1>Gestión de Clientes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>#<?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="rol-tag <?php echo $user['rol']; ?>"><?php echo $user['rol']; ?></span>
                </td>
                <td>
                    <a href="admin_historial_usuario.php?id=<?php echo $user['id']; ?>" class="btn btn-historial">Ver Compras</a>
                    
                    <?php if($user['rol'] == 'cliente'): ?>
                        <a href="?accion=hacer_admin&id=<?php echo $user['id']; ?>" class="btn btn-rol">Hacer Admin</a>
                    <?php else: ?>
                        <a href="?accion=hacer_cliente&id=<?php echo $user['id']; ?>" class="btn btn-rol">Hacer Cliente</a>
                    <?php endif; ?>

                    <a href="?accion=borrar&id=<?php echo $user['id']; ?>" class="btn btn-borrar" onclick="return confirm('¿Eliminar este usuario?')">Borrar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>