<?php
// Primero, nos aseguramos de que la sesión esté abierta para poder leer el carrito.
// Inicializamos el contador de la compra en 0.
if(session_status() === PHP_SESSION_NONE) session_start();
$totalCompra = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cesta - Éclat</title>
    <style>
        /* fondo suave y  letra */
        body { font-family: 'Segoe UI', sans-serif; color: #333; background: #fcfcfc; }
        .carrito-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .main-content { flex:1; padding: 40px; box-sizing: border-box; }

        /* Estilo de la tabla de productos*/
        table { width:100%; border-collapse:collapse; margin-top:20px; background:white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th { text-align:left; padding:15px; border-bottom: 2px solid #000; text-transform:uppercase; font-size:13px; }
        td { padding:15px; border-bottom:1px solid #eee; }

        /* Botón de pago principal: negro que cambia a dorado al pasar el ratón */
        .btn-pagar {
            background:#000; color:#fff; padding:15px 40px;
            border:none; cursor:pointer; font-size:15px; font-weight:bold;
            text-decoration:none; display:inline-block; transition:.3s;
            text-transform:uppercase; letter-spacing:1px;
        }
        .btn-pagar:hover { background:#b59410; }

        /* Botón  para vaciar todo el carrito */
        .btn-vaciar {
            background:#f4f4f4; color:#666; padding:10px 20px;
            border: 1px solid #ddd; cursor:pointer; transition:.3s;
            font-family: inherit;
        }
        .btn-vaciar:hover { background:#ddd; }

        /* Botón rojo para eliminar un solo artículo */
        .btn-quitar { color:#d9534f; border:none; background:none; cursor:pointer; font-weight:bold; text-decoration:underline; font-family:inherit; }
        .btn-quitar:hover { color:#a00; }

        /* responsive */
        @media (max-width: 768px) {
            .main-content { padding: 20px 15px; }
            table { font-size: .85em; }
            th, td { padding: 10px 8px; }
            .btn-pagar { padding: 12px 20px; font-size: .8em; }
        }
        /* En pantallas muy pequeñas ocultamos la columna de cantidad para ahorrar espacio */
        @media (max-width: 480px) {
            th:nth-child(3), td:nth-child(3) { display: none; }
            .btn-pagar { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
<div class="carrito-wrapper">
<?php include_once 'views/layout/header.php'; ?>

<div class="main-content">
    <div style="max-width:1100px; margin:0 auto;">
        <h1 style="font-weight:300; letter-spacing:2px; text-transform:uppercase;">Tu Cesta</h1>

        <!-- Comprobamos si hay productos guardados en la sesión -->
        <?php if(!empty($_SESSION['carrito'])): ?>

            <table>
                <thead>
                    <tr>
                        <th>Prenda</th>
                        <th>Precio</th>
                        <th>Cant.</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                // Recorremos cada artículo que el usuario añadió
                foreach($_SESSION['carrito'] as $idPrenda => $prenda):
                    $subtotal     = $prenda['precio'] * $prenda['cantidad'];
                    $totalCompra += $subtotal; // Vamos sumando al total global
                ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($prenda['nombre']); ?></strong></td>
                        <td><?php echo number_format($prenda['precio'],2); ?> €</td>
                        <td><?php echo $prenda['cantidad']; ?></td>
                        <td><?php echo number_format($subtotal,2); ?> €</td>
                        <td>
                            <!-- Formulario pequeño para eliminar este producto específico -->
                            <form action="carrito_accion.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $idPrenda; ?>">
                                <button type="submit" name="accion" value="quitar" class="btn-quitar">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
                // Guardamos el total final en la sesión para usarlo en la página de pago
                $_SESSION['total_carrito'] = $totalCompra; ?>
                </tbody>
            </table>

            <!-- Resumen final y botones de acción -->
            <div style="text-align:right; margin-top:30px;">
                <h2 style="font-weight:300;">
                    Total: <span style="font-weight:bold; color:#b59410;"><?php echo number_format($totalCompra,2); ?> €</span>
                </h2>

                <div style="display:flex; justify-content:flex-end; align-items:center; gap:15px; margin-top:20px;">
                    <!-- Botón para borrar todo el carrito con una confirmación de seguridad -->
                    <form action="carrito_accion.php" method="POST">
                        <button type="submit" name="accion" value="limpiar" class="btn-vaciar"
                                onclick="return confirm('¿Vaciar toda la cesta?')">
                            Vaciar Cesta
                        </button>
                    </form>
                    <!-- Enlace para ir al formulario de pago -->
                    <a href="finalizar_pago.php" class="btn-pagar">Finalizar Compra</a>
                </div>

                <p style="margin-top:20px;">
                    <a href="catalogo_test.php" style="color:#999; font-size:13px;">← Seguir comprando</a>
                </p>
            </div>

        <?php else: ?>
            <!-- Mensaje que se muestra si el carrito está vacío -->
            <div style="text-align:center; padding:100px 40px;">
                <p style="font-size:20px; color:#999; font-weight:300;">Tu cesta está vacía.</p>
                <div style="margin-top:30px;">
                    <a href="catalogo_test.php" style="background:#000; color:#fff; padding:15px 40px; text-transform:uppercase; letter-spacing:2px; font-weight:bold; text-decoration:none;">
                        Ver Colección
                    </a>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>
<!-- incluimos el footer  -->
<?php include_once 'views/layout/footer.php'; ?>
</div>
</body>
</html>