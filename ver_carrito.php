<?php
session_start();
// Iniciamos el contador de la compra en 0
$totalCompra = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cesta - Éclat Haute Couture</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px; color: #333; background-color: #fcfcfc; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid #000; text-transform: uppercase; font-size: 14px; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        .total-seccion { text-align: right; margin-top: 30px; }
        
        /* Estilos de botones */
        .btn-pagar { background: #000; color: #fff; padding: 15px 40px; border: none; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-pagar:hover { background: #b59410; }
        
        .btn-vaciar { background: #f4f4f4; color: #666; padding: 10px 20px; border: 1px solid #ddd; cursor: pointer; margin-right: 10px; }
        .btn-vaciar:hover { background: #e0e0e0; color: #333; }
        
        .btn-eliminar { color: #d9534f; border: none; background: none; cursor: pointer; font-weight: bold; text-decoration: underline; }
        .btn-eliminar:hover { color: #c9302c; }

        a { color: #888; text-decoration: none; font-size: 14px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h1>Tu Cesta de Compra</h1>

    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) { ?>
        
        <table>
            <thead>
                <tr>
                    <th>Prenda</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrito'] as $id => $prenda) { 
                    $subtotal = $prenda['precio'] * $prenda['cantidad'];
                    $totalCompra += $subtotal;
                ?>
                    <tr>
                        <td><strong><?php echo $prenda['nombre']; ?></strong></td>
                        <td><?php echo number_format($prenda['precio'], 2); ?> €</td>
                        <td><?php echo $prenda['cantidad']; ?></td>
                        <td><?php echo number_format($subtotal, 2); ?> €</td>
                        <td>
                            <form action="carrito_accion.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="submit" name="accion" value="quitar" class="btn-eliminar">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-seccion">
            <h2>Total: <?php echo number_format($totalCompra, 2); ?> €</h2>
            
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 15px;">
                <form action="carrito_accion.php" method="POST">
                    <button type="submit" name="accion" value="limpiar" class="btn-vaciar" onclick="return confirm('¿Seguro que quieres vaciar toda la cesta?')">Vaciar Cesta</button>
                </form>

                <form action="finalizar_compra.php" method="POST">
                    <input type="hidden" name="total" value="<?php echo $totalCompra; ?>">
                    <button type="submit" class="btn-pagar">FINALIZAR COMPRA</button>
                </form>
            </div>
            
            <p style="margin-top: 20px;">
                <a href="catalogo_test.php">← Continuar comprando en la boutique</a>
            </p>
        </div>

    <?php } else { ?>
        
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 18px; color: #666;">Tu cesta está vacía actualmente.</p>
            <a href="catalogo_test.php" style="font-size: 16px; color: #000; font-weight: bold;">Ver catálogo de temporada</a>
        </div>

    <?php } ?>

</body>
</html>