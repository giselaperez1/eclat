<?php
// Iniciamos sesión para reconocer al usuario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'config/database.php'; 
include_once 'models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

// Capturamos filtros y orden de la URL
$categoria_id = isset($_GET['cat']) ? $_GET['cat'] : null;
$ordenar_por = isset($_GET['ord']) ? $_GET['ord'] : '';

// Cargamos los productos con los filtros aplicados
$stmt = $producto->leerTodos($categoria_id, $ordenar_por);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Éclat - Alta Costura</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f9f9f9; color: #333; }
        
        /* Contenedor principal para separar del header */
        .main-container { padding: 40px; }

        /* Barra de Navegación Unificada */
        .controles { 
            margin-bottom: 40px; 
            padding: 15px 0; 
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .nav-categorias a { 
            text-decoration: none; 
            color: #888; 
            margin-right: 18px; 
            font-weight: 600; 
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 1px;
            transition: 0.3s;
            padding-bottom: 5px;
        }

        .nav-categorias a:hover, .nav-categorias a.activo { 
            color: #b59410; 
            border-bottom: 2px solid #b59410;
        }

        .nav-ordenar {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.85em;
        }

        .nav-ordenar span { color: #aaa; text-transform: uppercase; letter-spacing: 1px; }

        .btn-orden {
            text-decoration: none;
            color: #555;
            background: #eee;
            padding: 6px 12px;
            border-radius: 4px;
            transition: 0.3s;
        }

        .btn-orden:hover { background: #d4af37; color: #fff; }

        /* Contenedor de Productos */
        .contenedor-productos {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: flex-start;
        }

        .producto { 
            background: white; 
            border: 1px solid #eee; 
            padding: 25px; 
            width: 280px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border-radius: 8px;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            min-height: 480px; 
        }
        .producto:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.08); }
        
        .detalles { color: #999; font-size: 0.7em; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid #f5f5f5; padding-bottom: 10px; margin-bottom: 15px; }
        
        .descripcion-contenedor {
            flex-grow: 1; 
            font-size: 0.9em; 
            color: #666; 
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .categoria-tag { font-size: 0.75em; color: #b59410; background: #fff9e6; border: 1px solid #ffeeba; padding: 3px 10px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
        .precio { font-weight: bold; color: #222; font-size: 1.4em; margin-bottom: 20px; }
        .agotado { color: #d9534f; font-weight: bold; text-transform: uppercase; font-size: 0.85em; margin-bottom: 10px; }
        
        .footer-tarjeta { margin-top: auto; }

        button { 
            background: #000; 
            color: #fff; 
            border: none; 
            padding: 14px; 
            cursor: pointer; 
            width: 100%;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            border-radius: 4px;
        }
        button:hover:not(:disabled) { background: #d4af37; }
        button:disabled { background: #ddd; cursor: not-allowed; color: #888; }
        
        input[type="number"] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 50px; text-align: center; }
    </style>
</head>
<body>

    <!-- Incluimos el Header-->
    <?php include_once 'views/layout/header.php'; ?>

    <div class="main-container">
        <h1 style="font-weight: 300; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;">Colección de Temporada</h1>
        <p style="color: #888; margin-bottom: 40px;">Éclat | Alta Costura Permanente</p>
        
        <div class="controles">
            <nav class="nav-categorias">
                <a href="?" class="<?php echo !$categoria_id ? 'activo' : ''; ?>">Todo</a>
                <a href="?cat=1" class="<?php echo $categoria_id == 1 ? 'activo' : ''; ?>">Vestidos</a>
                <a href="?cat=2" class="<?php echo $categoria_id == 2 ? 'activo' : ''; ?>">Tops</a>
                <a href="?cat=4" class="<?php echo $categoria_id == 4 ? 'activo' : ''; ?>">Camisetas</a>
                <a href="?cat=5" class="<?php echo $categoria_id == 5 ? 'activo' : ''; ?>">Chaquetas</a>
                <a href="?cat=6" class="<?php echo $categoria_id == 6 ? 'activo' : ''; ?>">Pantalones</a>
                <a href="?cat=7" class="<?php echo $categoria_id == 7 ? 'activo' : ''; ?>">Faldas</a>
                <a href="?cat=8" class="<?php echo $categoria_id == 8 ? 'activo' : ''; ?>">Zapatos</a>
                <a href="?cat=3" class="<?php echo $categoria_id == 3 ? 'activo' : ''; ?>">Accesorios</a>
            </nav>

            <nav class="nav-ordenar">
                <span>Ordenar</span>
                <a href="?cat=<?php echo $categoria_id; ?>&ord=barato" class="btn-orden">Precio ↓</a>
                <a href="?cat=<?php echo $categoria_id; ?>&ord=caro" class="btn-orden">Precio ↑</a>
            </nav>
        </div>

        <div class="contenedor-productos">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="producto">
                    <p class="detalles">
                        <?php echo htmlspecialchars($row['color'] ?? 'N/A'); ?> | 
                        <?php echo htmlspecialchars($row['talla'] ?? 'Talla Única'); ?>
                    </p>
                    
                    <h3 style="font-size: 1.1em; margin-bottom: 10px;"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                    
                    <div class="descripcion-contenedor">
                        <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                    </div>
                    
                    <div><span class="categoria-tag"><?php echo htmlspecialchars($row['categoria_nombre']); ?></span></div>
                    
                    <p class="precio"><?php echo number_format($row['precio'], 2); ?> €</p>
                    
                    <div class="footer-tarjeta">
                        <?php if ($row['stock'] > 0): ?>
                            <form method="POST" action="carrito_accion.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
                                <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                                <input type="hidden" name="stock_max" value="<?php echo $row['stock']; ?>">
                                
                                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                    <label style="font-size: 0.8em; color: #888;">CANTIDAD</label>
                                    <input type="number" name="cantidad" value="1" min="1" max="<?php echo $row['stock']; ?>">
                                </div>
                                
                                <button type="submit" name="accion" value="agregar">Añadir a la Cesta</button>
                            </form>
                        <?php else: ?>
                            <p class="agotado">Sin Existencias</p>
                            <button disabled>Agotado</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>