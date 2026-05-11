<?php
/*
 Panel de administración: gestión de pedidos
 
 Muestra todos los pedidos realizados en la tienda ordenados del más reciente
 al más antiguo. Desde aquí el admin puede cambiar el estado de cada pedido
 directamente sin tener que entrar al detalle.
 
 */

include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

// si viene un POST con cambiar_estado es que el admin pulsó "Guardar" en alguna fila
// actualizo el estado del pedido y recargo la página para que se vea el cambio
if(isset($_POST['cambiar_estado'], $_POST['pedido_id'])){
    $upd = $conexion->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $upd->execute([$_POST['cambiar_estado'], (int)$_POST['pedido_id']]);
    header("Location: admin_pedidos.php");
    exit;
}

// traigo todos los pedidos con el nombre del cliente 
// ORDER BY fecha DESC los más recientes primero
$listaVentas = $conexion->query(
    "SELECT p.id, u.nombre as cliente, p.total, p.fecha, p.estado
     FROM pedidos p
     JOIN usuarios u ON p.usuario_id = u.id
     ORDER BY p.fecha DESC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Éclat Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }

        /* --- sidebar escritorio --- */
        .sidebar {
            width: 230px; height: 100vh; background: #000;
            position: fixed; top: 0; left: 0;
            padding: 25px; box-sizing: border-box;
            z-index: 200; overflow-y: auto;
        }
        .sidebar h2 { font-weight:300; letter-spacing:3px; font-size:1em; margin-bottom:30px; color:#fff; }
        .sidebar a   { color:#888; text-decoration:none; display:block; margin:12px 0; font-size:.9em; transition:.2s; }
        .sidebar a:hover, .sidebar a.activo { color:#b59410; }
        .main-content { margin-left: 280px; padding: 40px; }

        /* --- tabla --- */
        table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 4px 10px rgba(0,0,0,0.05); }
        th { padding:14px; background:#b59410; color:#fff; text-transform:uppercase; font-size:.75em; text-align:left; }
        td { padding:14px; border-bottom:1px solid #eee; vertical-align:middle; }
        tr:hover td { background:#fdfdf9; }

        .id-link { color:#b59410; font-weight:bold; text-decoration:none; }
        .id-link:hover { text-decoration:underline; }

        .tag-estado { padding:3px 8px; border-radius:3px; font-size:.75em; font-weight:bold; text-transform:uppercase; }
        .pendiente  { background:#fff3cd; color:#856404; }
        .enviado    { background:#d1ecf1; color:#0c5460; }
        .completado { background:#d4edda; color:#155724; }
        .cancelado  { background:#f8d7da; color:#721c24; }

        .form-estado { display:flex; align-items:center; gap:6px; }
        .sel-estado {
            padding:5px 8px; border:1px solid #ddd; border-radius:4px;
            font-family:inherit; font-size:.8em; outline:none; cursor:pointer; background:#fff;
        }
        .sel-estado:focus { border-color:#b59410; }
        .btn-guardar-estado {
            padding:5px 10px; background:#000; color:#fff;
            border:none; border-radius:3px; font-size:.75em;
            cursor:pointer; font-weight:bold; transition:.2s; white-space:nowrap;
        }
        .btn-guardar-estado:hover { background:#b59410; }

        /* --- tablet: sidebar barra horizontal --- */
        @media (max-width: 960px) {
            .sidebar {
                width: 100%; height: auto; position: relative;
                display: flex; flex-wrap: wrap;
                align-items: center; gap: 6px 18px;
                padding: 12px 20px; overflow-y: visible;
            }
            .sidebar h2 { margin: 0; font-size: .85em; flex-shrink: 0; }
            .sidebar a  { margin: 0; font-size: .82em; display: inline; }
            .main-content { margin-left: 0; padding: 20px 15px; }
        }

        /* --- móvil: ocultar columnas que no caben --- */
        @media (max-width: 600px) {
            table  { font-size: .85em; width: 100%; }
            th, td { padding: 10px 6px; }
            /* oculto Pedido, Fecha y Cambiar estado - queda Cliente, Total, Estado */
            th:nth-child(1), td:nth-child(1),
            th:nth-child(3), td:nth-child(3),
            th:nth-child(6), td:nth-child(6) { display: none; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT</h2>
    <a href="admin_index.php">📦 Productos</a>
    <a href="admin_usuarios.php">👤 Clientes</a>
    <a href="admin_pedidos.php" class="activo">💰 Pedidos</a>
    <br><br>
    <a href="../../catalogo_test.php" style="font-size:.75em; color:#555;">← Ver tienda</a>
</div>

<div class="main-content">
    <h1 style="font-weight:300; margin-bottom:25px;">Registro de Ventas</h1>
<!-- tabla de ventas  -->
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Cambiar estado</th>
            </tr>
        </thead>
        <tbody>
        <?php while($venta = $listaVentas->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td>
                    <a href="admin_detalle_pedido.php?id=<?php echo $venta['id']; ?>" class="id-link">
                        #<?php echo $venta['id']; ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                <td style="color:#aaa; font-size:.85em;"><?php echo date('d/m/Y · H:i', strtotime($venta['fecha'])); ?></td>
                <td><strong><?php echo number_format($venta['total'],2); ?> €</strong></td>
                <td>
                    <span class="tag-estado <?php echo strtolower($venta['estado']); ?>">
                        <?php echo ucfirst($venta['estado']); ?>
                    </span>
                </td>
                <td>
                    <form method="POST" class="form-estado">
                        <input type="hidden" name="pedido_id" value="<?php echo $venta['id']; ?>">
                        <!-- cambiar estado de pedido  -->
                        <select name="cambiar_estado" class="sel-estado">
                            <?php foreach(['pendiente','enviado','completado','cancelado'] as $est): ?>
                                <option value="<?php echo $est; ?>" <?php echo ($venta['estado'] == $est) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($est); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- boton patra guardar el estado  -->
                        <button type="submit" class="btn-guardar-estado">✓ Guardar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
