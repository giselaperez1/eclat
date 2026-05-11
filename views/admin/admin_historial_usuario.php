<?php
/*
admin_historial_usuario.php - Historial de compras de un cliente

 Muestra todos los pedidos realizados por un cliente concreto.
 Llegamos aquí desde admin_usuarios.php al pulsar "Compras" en un cliente.
 */

include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

$idCliente = (int)$_GET['id']; // id del client 

//  busco el nombre del cliente para mostrarlo en el título de la página
$q = $conexion->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$q->execute([$idCliente]);
$nombreCliente = $q->fetchColumn(); // fetchColumn() devuelve solo el primer campo, sin array

// traigo todos los pedidos de este cliente ordenados del más reciente al más antiguo
$historial = $conexion->prepare(
    "SELECT id, fecha, total, estado FROM pedidos
     WHERE usuario_id = :uid
     ORDER BY fecha DESC"
);
$historial->execute([':uid' => $idCliente]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial · <?php echo htmlspecialchars($nombreCliente); ?></title>
    <style>
        /* estilo de la pagina y responsive  */
        body { font-family: "Segoe UI", sans-serif; background: #f4f4f4; padding: 40px; }
        @media (max-width: 768px) { body { padding: 15px; } .card { padding: 20px; } .cabecera { flex-direction: column; gap: 20px; } .fila-total td { font-size: 1em; } }
        .card { background:#fff; padding:30px; border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.08); max-width:780px; margin:auto; }
        h1 { color:#b59410; margin-bottom:5px; font-size:1.5em; }
        /* estilo de tabla  */
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th { padding:12px; font-size:.8em; color:#999; text-align:left; border-bottom:2px solid #eee; text-transform:uppercase; }
        td { padding:12px; border-bottom:1px solid #f0f0f0; }
        .lnk { color:#b59410; text-decoration:none; font-weight:bold; font-size:.85em; }
        .lnk:hover { text-decoration:underline; }
        .volver { color:#888; text-decoration:none; font-size:.85em; }
        .volver:hover { color:#000; }
    </style>
</head>
<body>

<div class="card">
    <a href="admin_usuarios.php" class="volver">← Volver a clientes</a>

    <h1 style="margin-top:20px;"><?php echo htmlspecialchars($nombreCliente); ?></h1>
    <p style="color:#aaa; margin:0 0 20px; font-size:.9em;">Historial de compras</p>
<!-- tabla con los datos  -->
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $hayPedidos = false;
        while($pedido = $historial->fetch(PDO::FETCH_ASSOC)):
            $hayPedidos = true;
        ?>
            <tr>
                <td style="color:#aaa;">#<?php echo $pedido['id']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?></td>
                <td><strong><?php echo number_format($pedido['total'],2); ?> €</strong></td>
                <td><?php echo $pedido['estado']; ?></td>
                <td><a href="admin_detalle_pedido.php?id=<?php echo $pedido['id']; ?>" class="lnk">Ver detalle</a></td>
            </tr>
        <?php endwhile; ?>
        <!-- si  no hay pedidos lo muestra  -->
        <?php if(!$hayPedidos): ?>
            <tr><td colspan="5" style="color:#bbb; text-align:center; padding:30px;">Sin pedidos registrados</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
