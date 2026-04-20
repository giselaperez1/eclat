<?php
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_GET['id'])) {
    header("Location: admin_pedidos.php");
    exit;
}

$pedido_id = $_GET['id'];

// --- NUEVA LÓGICA PARA ACTUALIZAR EL ESTADO ---
if (isset($_POST['actualizar_estado'])) {
    $nuevo_estado = $_POST['nuevo_estado'];
    $update_query = "UPDATE pedidos SET estado = :estado WHERE id = :id";
    $stmt_up = $db->prepare($update_query);
    $stmt_up->bindParam(':estado', $nuevo_estado);
    $stmt_up->bindParam(':id', $pedido_id);
    
    if ($stmt_up->execute()) {
        // Mostramos un aviso visual al usuario
        echo "<script>alert('Estado del pedido actualizado a: " . strtoupper($nuevo_estado) . "');</script>";
    }
}

// 1. Obtener datos generales del pedido (lo volvemos a consultar para tener el estado actualizado)
$query_pedido = "SELECT p.*, u.nombre as cliente, u.email 
                 FROM pedidos p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 WHERE p.id = :id";
$stmt_p = $db->prepare($query_pedido);
$stmt_p->bindParam(':id', $pedido_id);
$stmt_p->execute();
$pedido = $stmt_p->fetch(PDO::FETCH_ASSOC);

// 2. Obtener los productos
$query_items = "SELECT dp.*, pr.nombre as producto_nombre 
                FROM detalle_pedido dp 
                JOIN productos pr ON dp.producto_id = pr.id 
                WHERE dp.pedido_id = :id";
$stmt_i = $db->prepare($query_items);
$stmt_i->bindParam(':id', $pedido_id);
$stmt_i->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Pedido #<?php echo $pedido_id; ?> - Éclat</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        .detail-card { background: white; padding: 40px; max-width: 800px; margin: auto; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header-detalle { border-bottom: 2px solid #b59410; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-start; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { text-transform: uppercase; font-size: 0.8em; color: #888; }
        .total-row { font-size: 1.4em; font-weight: bold; color: #b59410; text-align: right; }
        .btn-volver { display: inline-block; margin-top: 30px; text-decoration: none; color: #000; font-weight: bold; border: 1px solid #000; padding: 10px 20px; transition: 0.3s; }
        .btn-volver:hover { background: #000; color: #fff; }
        
        /* Estilos para el selector de estado */
        .status-form { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .status-select { padding: 8px; border: 1px solid #b59410; border-radius: 4px; outline: none; font-family: inherit; }
        .btn-update { background: #b59410; color: white; border: none; padding: 8px 15px; cursor: pointer; text-transform: uppercase; font-size: 0.7em; font-weight: bold; border-radius: 4px; }
        .btn-update:hover { background: #000; }
    </style>
</head>
<body>

<div class="detail-card">
    <div class="header-detalle">
        <div>
            <h1 style="margin:0;">Pedido #<?php echo $pedido_id; ?></h1>
            <p style="color: #666;">Cliente: <strong><?php echo htmlspecialchars($pedido['cliente']); ?></strong> (<?php echo $pedido['email']; ?>)</p>
        </div>
        <div style="text-align: right;">
            <p style="margin:0; color: #aaa; margin-bottom: 15px;">Fecha: <?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?></p>
            
            <form method="POST" class="status-form">
                <select name="nuevo_estado" class="status-select">
                    <option value="pendiente" <?php echo ($pedido['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="enviado" <?php echo ($pedido['estado'] == 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                    <option value="completado" <?php echo ($pedido['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                    <option value="cancelado" <?php echo ($pedido['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                </select>
                <button type="submit" name="actualizar_estado" class="btn-update">Actualizar Estado</button>
            </form>
        </div>
    </div>

    <h3>Artículos en el pedido:</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Unit.</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $stmt_i->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['producto_nombre']); ?></td>
                <td><?php echo number_format($item['precio_unitario'], 2); ?> €</td>
                <td>x<?php echo $item['cantidad']; ?></td>
                <td><?php echo number_format($item['precio_unitario'] * $item['cantidad'], 2); ?> €</td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="3" style="border:none;"></td>
                <td class="total-row">TOTAL: <?php echo number_format($pedido['total'], 2); ?> €</td>
            </tr>
        </tbody>
    </table>

    <a href="admin_pedidos.php" class="btn-volver">← VOLVER AL LISTADO</a>
</div>

</body>
</html>