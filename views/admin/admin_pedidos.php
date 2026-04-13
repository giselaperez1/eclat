<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Consulta avanzada: Juntamos pedidos con nombres de usuario
$query = "SELECT p.id, u.nombre as cliente, p.total, p.fecha, p.estado 
          FROM pedidos p 
          JOIN usuarios u ON p.usuario_id = u.id 
          ORDER BY p.fecha DESC";

$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Éclat - Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }
        .sidebar { width: 250px; height: 100vh; background: #000; color: #fff; position: fixed; padding: 20px; }
        .main-content { margin-left: 290px; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #b59410; color: white; text-transform: uppercase; font-size: 0.8em; }
        .estado { padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; }
        .completado { background: #e6fffa; color: #2c7a7b; }
        .pendiente { background: #fffaf0; color: #9c4221; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT ADMIN</h2>
    <hr style="border: 0.5px solid #333; margin: 20px 0;">
    <p><a href="admin_index.php" style="color: #888; text-decoration: none;">📦 Productos</a></p>
    <p><a href="admin_usuarios.php" style="color: #888; text-decoration: none;">👤 Usuarios</a></p>
    <p><a href="admin_pedidos.php" style="color: #fff; text-decoration: none; font-weight: bold;">💰 Pedidos</a></p>
    <br>
    <a href="../../index.php" style="color: #666; text-decoration: none; font-size: 0.8em;">← Salir al Inicio</a>
</div>

<div class="main-content">
    <h1>Registro de Ventas</h1>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                <td style="font-weight: bold;"><?php echo number_format($row['total'], 2); ?> €</td>
                <td><span class="estado <?php echo strtolower($row['estado']); ?>"><?php echo $row['estado']; ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>