<?php
/*Detalle completo de un pedido

 Muestra todas las prendas de un pedido concreto con sus cantidades y precios.
 También permite cambiar el estado del pedido (pendiente, enviado, completado, cancelado).

 */

include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

// si no viene id en la URL no sé qué pedido mostrar, vuelvo al listado
if(!isset($_GET['id'])){
    header("Location: admin_pedidos.php");
    exit;
}

$idPedido = (int)$_GET['id']; // el id del pedido que quiero ver

// si el admin ha enviado el formulario de cambio de estado lo proceso aquí
if(isset($_POST['actualizar_estado'])){
    $nuevoEstado = $_POST['nuevo_estado'];
    $upd = $conexion->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
    $upd->execute([':estado' => $nuevoEstado, ':id' => $idPedido]);
}

// cargo los datos del pedido junto con el nombre y email del cliente 
$consultaPedido = $conexion->prepare(
    "SELECT p.*, u.nombre as cliente, u.email
     FROM pedidos p
     JOIN usuarios u ON p.usuario_id = u.id
     WHERE p.id = :id"
);
$consultaPedido->execute([':id' => $idPedido]);
$infoPedido = $consultaPedido->fetch(PDO::FETCH_ASSOC); // array con todos los datos del pedido

// cargo las líneas del pedido - cada prenda que compró con su cantidad y precio
$consultaLineas = $conexion->prepare(
    "SELECT dp.*, pr.nombre as producto_nombre
     FROM detalle_pedido dp
     JOIN productos pr ON dp.producto_id = pr.id
     WHERE dp.pedido_id = :id"
);
$consultaLineas->execute([':id' => $idPedido]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #<?php echo $idPedido; ?> - Éclat</title>
    <style>
        /* estilo de pagina y responsive  */
        body { font-family: "Segoe UI", sans-serif; background: #f4f4f4; padding: 40px; }
        @media (max-width: 768px) { body { padding: 15px; } .card { padding: 20px; } .cabecera { flex-direction: column; gap: 20px; } .fila-total td { font-size: 1em; } }
        .card {
            background: white; padding: 40px;
            max-width: 820px; margin: auto;
            border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .cabecera {
            border-bottom: 2px solid #b59410; padding-bottom: 20px;
            margin-bottom: 30px; display: flex;
            justify-content: space-between; align-items: flex-start;
        }
        /* estilos d las tablas  */
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th { padding:12px; font-size:.8em; color:#999; text-transform:uppercase; text-align:left; border-bottom:2px solid #eee; }
        td { padding:12px; border-bottom:1px solid #f0f0f0; }
        .fila-total td { font-size:1.3em; font-weight:bold; color:#b59410; text-align:right; border:none; padding-top:20px; }

        .form-estado { display:flex; flex-direction:column; align-items:flex-end; gap:8px; }
        select { padding:8px 12px; border:1px solid #b59410; outline:none; font-family:inherit; cursor:pointer; }
        .btn-guardar { background:#b59410; color:#fff; border:none; padding:8px 16px; cursor:pointer; text-transform:uppercase; font-size:.75em; font-weight:bold; }
        .btn-guardar:hover { background:#000; }
        .btn-volver { display:inline-block; margin-top:30px; color:#000; font-weight:bold; border:1px solid #000; padding:10px 20px; text-decoration:none; transition:.3s; }
        .btn-volver:hover { background:#000; color:#fff; }
    </style>
</head>
<body>

<div class="card">
    <div class="cabecera">
        <div>
            <h1 style="margin:0; font-weight:300;">Pedido <strong>#<?php echo $idPedido; ?></strong></h1>
            <p style="color:#666; margin:8px 0 0;">
                <?php echo htmlspecialchars($infoPedido['cliente']); ?>
                <span style="color:#bbb;"> · <?php echo $infoPedido['email']; ?></span>
            </p>
        </div>
        <div style="text-align:right;">
            <p style="color:#aaa; font-size:.85em; margin:0 0 12px;">
                <?php echo date('d/m/Y · H:i', strtotime($infoPedido['fecha'])); ?>
            </p>
            <form method="POST" class="form-estado">
                <select name="nuevo_estado">
                    <?php foreach(['pendiente','enviado','completado','cancelado'] as $est): ?>
                        <option value="<?php echo $est; ?>" <?php echo ($infoPedido['estado'] == $est) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($est); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="actualizar_estado" class="btn-guardar">Guardar estado</button>
            </form>
        </div>
    </div>
<!-- tabla con los datos detalle del pedido  -->
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio/ud</th>
                <th>Cant.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php while($linea = $consultaLineas->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($linea['producto_nombre']); ?></td>
                <td><?php echo number_format($linea['precio_unitario'],2); ?> €</td>
                <td>×<?php echo $linea['cantidad']; ?></td>
                <td><?php echo number_format($linea['precio_unitario'] * $linea['cantidad'],2); ?> €</td>
            </tr>
        <?php endwhile; ?>
            <tr class="fila-total">
                <td colspan="3"></td>
                <td>TOTAL: <?php echo number_format($infoPedido['total'],2); ?> €</td>
            </tr>
        </tbody>
    </table>

    <!-- boton volver  -->
    <a href="admin_pedidos.php" class="btn-volver">← Volver a pedidos</a>
</div>

</body>
</html>
