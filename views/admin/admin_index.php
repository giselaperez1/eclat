<?php
/*
Panel de administración: inventario de productos
 
 Página principal del panel admin. Muestra todos los productos del catálogo
 en una tabla con opciones para editar o borrar cada uno.

 */
include_once '../../config/admin_check.php';
include_once '../../config/database.php';
include_once '../../models/Producto.php';

$baseDatos      = new Database();
$conexion       = $baseDatos->getConnection();
$producto       = new Producto($conexion);
$listaProductos = $producto->leerTodos(); // sin filtros trae todos los productos para el inventario

// si vengo desde borrar_producto.php con ?mensaje=eliminado muestro el aviso verde
$avisoElim = isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'
    ? "<p style='color:green; padding:10px; background:#f0fff0; border:1px solid #c3e6c3; margin-bottom:20px;'>Producto eliminado del catálogo.</p>"
    : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Éclat Admin</title>
    <style>
        /* estilso de inventario  */
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }

        .sidebar {
            width: 230px; height: 100vh; background: #000;
            position: fixed; top: 0; left: 0;
            padding: 25px; box-sizing: border-box;
            z-index: 200; overflow-y: auto;
        }
        .sidebar h2 { font-weight: 300; letter-spacing: 3px; font-size: 1em; margin-bottom: 30px; color:#fff; }
        .sidebar a { color: #888; text-decoration: none; display: block; margin: 12px 0; font-size: .9em; transition:.2s; }
        .sidebar a:hover, .sidebar a.activo { color: #b59410; }
        .main-content { margin-left: 280px; padding: 40px; }
/* estilos de tabla */
        table { width:100%; border-collapse:collapse; background:#fff; }
        th { text-align:left; padding:14px; background:#b59410; color:#fff; text-transform:uppercase; font-size:.75em; letter-spacing:1px; }
        td { padding:14px; border-bottom:1px solid #f0f0f0; font-size:.9em; }
        tr:hover td { background: #fafafa; }
/* estilos d ebotone  */
        .btn-nuevo { background:#000; color:#fff; padding:10px 20px; text-decoration:none; font-size:.85em; font-weight:bold; letter-spacing:1px; transition:.3s; }
        .btn-nuevo:hover { background:#b59410; }
        .lnk-edit { color:#b59410; text-decoration:none; margin-right:10px; font-size:.85em; }
        .lnk-del  { color:#c00; text-decoration:none; font-size:.85em; }
        .lnk-edit:hover, .lnk-del:hover { text-decoration:underline; }

        /* responsive*/
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
            .btn-nuevo { padding: 8px 14px; font-size: .8em; }
        }

        /* móvil: ocultar ID y Categoría */
        @media (max-width: 600px) {
            table  { font-size: .85em; width: 100%; }
            th, td { padding: 10px 6px; }
            th:nth-child(1), td:nth-child(1),
            th:nth-child(3), td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT</h2>
    <a href="admin_index.php"    class="activo">📦 Productos</a>
    <a href="admin_usuarios.php">👤 Clientes</a>
    <a href="admin_pedidos.php" >💰 Pedidos</a>
    <br><br>
    <a href="../../catalogo_test.php" style="font-size:.75em; color:#555;">← Ver tienda</a>
</div>

<div class="main-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1 style="font-weight:300; margin:0;">Inventario</h1>
        <!-- nos lleva a agregar producto  -->
        <a href="nuevo_producto.php" class="btn-nuevo">+ Nuevo Producto</a>
    </div>

    <?php echo $avisoElim; ?>
<!-- tabla para agregarlo  -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($prenda = $listaProductos->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td style="color:#aaa;">#<?php echo $prenda['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($prenda['nombre']); ?></strong></td>
                <td><?php echo $prenda['categoria_nombre'] ?? '—'; ?></td>
                <td><?php echo number_format($prenda['precio'],2); ?> €</td>
                <td><?php echo $prenda['stock']; ?> uds.</td>
                <td>
                    <!-- enalce que lleva a editar producto  eliminar  -->
                    <a href="editar_producto.php?id=<?php echo $prenda['id']; ?>" class="lnk-edit">Editar</a>
                    <a href="borrar_producto.php?id=<?php echo $prenda['id']; ?>" class="lnk-del"
                       onclick="return confirm('¿Borrar este producto?')">Borrar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
