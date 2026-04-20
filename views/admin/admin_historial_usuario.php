<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$user_id = $_GET['id'];

// Consultamos los datos del usuario
$q_u = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$q_u->execute([$user_id]);
$usuario_nombre = $q_u->fetchColumn();

// Consultamos sus pedidos
$query = "SELECT id, fecha, total, estado FROM pedidos WHERE usuario_id = :uid ORDER BY fecha DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':uid', $user_id);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de <?php echo $usuario_nombre; ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        h1 { color: #b59410; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .btn-ver { color: #b59410; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="card">
    <a href="admin_usuarios.php" style="text-decoration: none; color: #888;">← Volver a usuarios</a>
    <h1>Historial de Compras</h1>
    <p>Cliente: <strong><?php echo htmlspecialchars($usuario_nombre); ?></strong></p>

    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                <td><strong><?php echo number_format($row['total'], 2); ?> €</strong></td>
                <td><?php echo $row['estado']; ?></td>
                <td><a href="admin_detalle_pedido.php?id=<?php echo $row['id']; ?>" class="btn-ver">Ver Detalle</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>