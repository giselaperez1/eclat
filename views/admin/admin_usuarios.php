<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Consulta para traer a todos los usuarios
$query = "SELECT id, nombre, email, rol FROM usuarios ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes Éclat - Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #000; color: #fff; position: fixed; padding: 20px; }
        .main-content { margin-left: 290px; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #000; color: white; text-transform: uppercase; font-size: 0.8em; letter-spacing: 1px; }
        .rol-tag { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .rol-admin { background: #fff9e6; color: #b59410; border: 1px solid #ffeeba; }
        .rol-cliente { background: #f0f0f0; color: #666; }
        .volver-tienda { color: #888; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT ADMIN</h2>
    <hr style="border: 0.5px solid #333; margin: 20px 0;">
    <p><a href="admin_index.php" style="color: #fff; text-decoration: none;">📦 Productos</a></p>
    <p><a href="admin_usuarios.php" style="color: #b59410; text-decoration: none; font-weight: bold;">👤 Usuarios</a></p>
    <p><a href="#" style="color: #888; text-decoration: none;">💰 Pedidos</a></p>
    <br>
    <a href="../../index.php" class="volver-tienda">← Salir al Inicio</a>
</div>

<div class="main-content">
    <h1>Gestión de Clientes</h1>
    <p style="color: #666; margin-bottom: 30px;">Base de datos de usuarios registrados en la plataforma.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rango / Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>#<?php echo $user['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($user['nombre']); ?></strong></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="rol-tag <?php echo ($user['rol'] == 'admin') ? 'rol-admin' : 'rol-cliente'; ?>">
                        <?php echo $user['rol']; ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>