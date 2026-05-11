<?php
/*Panel de administración: gestión de clientes
Lista todos los usuarios registrados con sus datos y opciones de gestión.


 */
// gestión de clientes registrados - se puede cambiar el rol o eliminar cuentas
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

// las acciones llegan por GET en la URL: ?accion=hacer_admin&id=5
// (int) en el id para evitar inyección SQL por si alguien manipula la URL
if(isset($_GET['accion'], $_GET['id'])){
    $idUs = (int)$_GET['id'];

    if($_GET['accion'] == 'hacer_admin')
        $conexion->query("UPDATE usuarios SET rol = 'admin' WHERE id = $idUs");

    elseif($_GET['accion'] == 'hacer_cliente')
        $conexion->query("UPDATE usuarios SET rol = 'cliente' WHERE id = $idUs");

    elseif($_GET['accion'] == 'borrar')
        $conexion->query("DELETE FROM usuarios WHERE id = $idUs");

    header("Location: admin_usuarios.php"); // recargo para que se vean los cambios
    exit;
}

// traigo todos los usuarios ordenados por id descendente (los más nuevos primero)
$listaClientes = $conexion->query("SELECT id, nombre, email, rol FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Éclat Admin</title>
    <style>
        /* estulos  */
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; }

        .sidebar {
            width: 230px; height: 100vh; background: #000;
            position: fixed; top: 0; left: 0;
            padding: 25px; box-sizing: border-box;
            z-index: 200; overflow-y: auto;
        }
        .sidebar h2 { font-weight:300; letter-spacing:3px; font-size:1em; margin-bottom:30px; color:#fff; }
        .sidebar a { color:#888; text-decoration:none; display:block; margin:12px 0; font-size:.9em; transition:.2s; }
        .sidebar a:hover, .sidebar a.activo { color:#b59410; }
        .main-content { margin-left: 280px; padding: 40px; }
/* estilos de la tabla */
        table { width:100%; border-collapse:collapse; background:#fff; }
        th { padding:14px; background:#000; color:#fff; text-transform:uppercase; font-size:.75em; text-align:left; }
        td { padding:14px; border-bottom:1px solid #eee; }

        .btn { padding:4px 10px; text-decoration:none; font-size:.75em; border-radius:3px; margin-right:4px; font-weight:bold; }
        .btn-historial { background:#b59410; color:#fff; }
        .btn-rol       { background:#eee; color:#333; }
        .btn-borrar    { background:#ffe0e0; color:#c00; }

        .rol-tag { font-size:.7em; padding:3px 7px; border-radius:3px; font-weight:bold; text-transform:uppercase; }
        .admin   { background:#fff9e6; color:#b59410; border:1px solid #b59410; }
        .cliente { background:#f4f4f4; color:#666; }
/* responsive  */
        /* tablet */
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

        /* móvil: ocultar ID y Email, quedan Nombre + Rol + Acciones */
        @media (max-width: 600px) {
            table  { font-size: .85em; width: 100%; }
            th, td { padding: 10px 6px; }
            th:nth-child(1), td:nth-child(1),
            th:nth-child(3), td:nth-child(3) { display: none; }
            .acciones-col { display: flex; flex-direction: column; gap: 5px; align-items: flex-start; }
            .btn { padding: 4px 8px; font-size: .72em; margin-right: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ÉCLAT</h2>
    <a href="admin_index.php">📦 Productos</a>
    <a href="admin_usuarios.php" class="activo">👤 Clientes</a>
    <a href="admin_pedidos.php">💰 Pedidos</a>
    <br><br>
    <a href="../../catalogo_test.php" style="font-size:.75em; color:#555;">← Ver tienda</a>
</div>

<div class="main-content">
    <h1 style="font-weight:300; margin-bottom:25px;">Clientes registrados</h1>
<!-- tabla de clientees registrados  -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($cliente = $listaClientes->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td style="color:#aaa;">#<?php echo $cliente['id']; ?></td>
                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                <td style="color:#888;"><?php echo htmlspecialchars($cliente['email']); ?></td>
                <td>
                    <span class="rol-tag <?php echo $cliente['rol']; ?>"><?php echo $cliente['rol']; ?></span>
                </td>
                <td><div class="acciones-col">
                    <a href="admin_historial_usuario.php?id=<?php echo $cliente['id']; ?>" class="btn btn-historial">Compras</a>

                    <?php if($cliente['rol'] == 'cliente'): ?>
                        <a href="?accion=hacer_admin&id=<?php echo $cliente['id']; ?>"   class="btn btn-rol">→ Admin</a>
                    <?php else: ?>
                        <a href="?accion=hacer_cliente&id=<?php echo $cliente['id']; ?>" class="btn btn-rol">→ Cliente</a>
                    <?php endif; ?>
<!-- boton para eliminar el suuario  -->
                    <a href="?accion=borrar&id=<?php echo $cliente['id']; ?>" class="btn btn-borrar"
                       onclick="return confirm('¿Eliminar este usuario?')">Borrar</a>
                </div></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
