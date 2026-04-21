<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'config/database.php'; 
include_once 'models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

$categoria_id = isset($_GET['cat']) ? $_GET['cat'] : null;
$ordenar_por = isset($_GET['ord']) ? $_GET['ord'] : '';
$color_filtro = isset($_GET['color']) ? $_GET['color'] : null;

$stmt = $producto->leerTodos($categoria_id, $ordenar_por, $color_filtro);
$stmt_colores = $producto->obtenerColoresUnicos();

$categorias_lista = [
    1 => 'Vestidos',
    2 => 'Tops',
    4 => 'Camisetas',
    5 => 'Chaquetas',
    6 => 'Pantalones',
    7 => 'Faldas',
    8 => 'Zapatos',
    3 => 'Accesorios'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Éclat - Alta Costura</title>
    <style>
        :root {
            --dorado: #b59410;
        }

        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f9f9f9; color: #333; }
        
        /* Contenedor principal ajustado para que el footer no se pegue */
        .main-container { padding: 40px; max-width: 1300px; margin: 0 auto; min-height: 70vh; }
        
        .controles { margin-bottom: 40px; padding: 15px 0; border-bottom: 1px solid #ddd; }
        .fila-superior { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        
        .nav-categorias a { text-decoration: none; color: #888; margin-right: 18px; font-weight: 600; text-transform: uppercase; font-size: 0.85em; letter-spacing: 1px; transition: 0.3s; padding-bottom: 5px; }
        .nav-categorias a:hover, .nav-categorias a.activo { color: var(--dorado); border-bottom: 2px solid var(--dorado); }
        
        .nav-filtros { display: flex; align-items: center; gap: 10px; }

        .select-estilizado {
            padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px;
            background: white; font-family: inherit; font-size: 0.85em;
            color: #555; cursor: pointer; outline: none; transition: 0.3s;
        }
        .select-estilizado:hover { border-color: var(--dorado); }
        
        .btn-limpiar {
            text-decoration: none; background: #eee; color: #888;
            width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 0.75em; transition: 0.3s;
        }
        .btn-limpiar:hover { background: #ffdfdf; color: #c00; }

        .contenedor-productos { display: flex; flex-wrap: wrap; gap: 40px; justify-content: center; }

        .producto { 
            background: white; border: 1px solid #eee; padding: 25px; 
            width: 320px; min-height: 780px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
            border-radius: 12px; transition: all 0.4s ease; 
            display: flex; flex-direction: column; text-align: center;
            cursor: pointer; overflow: hidden;
        }
        
        .producto:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        
        .img-contenedor { 
            width: 100%; height: 320px; overflow: hidden; 
            border-radius: 8px; margin-bottom: 15px; 
            background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; 
        }
        .img-contenedor img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .producto:hover .img-contenedor img { transform: scale(1.1); }

        .producto h3 {
            font-size: 1.2em; margin: 0 0 10px 0; font-weight: 500;
            height: 2.4em; overflow: hidden; display: flex; align-items: center; justify-content: center;
            line-height: 1.2;
        }

        .detalles { 
            color: #999; font-size: 0.75em; text-transform: uppercase; 
            letter-spacing: 2px; border-bottom: 1px solid #f5f5f5; 
            padding-bottom: 12px; margin-bottom: 15px; 
        }
        
        .descripcion-contenedor { 
            height: 70px; margin-bottom: 10px;
            display: flex; flex-direction: column; justify-content: flex-start;
            align-items: center; font-size: 0.9em; color: #666; line-height: 1.3;
            overflow: hidden; padding: 0 10px;
        }
        
        .categoria-tag { font-size: 0.7em; color: var(--dorado); background: #fff9e6; border: 1px solid #ffeeba; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 10px; text-transform: uppercase; font-weight: bold; }
        
        .precio { font-weight: bold; color: #222; font-size: 1.4em; margin: 5px 0 15px 0; }
        
        .footer-tarjeta { 
            margin-top: auto; height: 170px; 
            display: flex; flex-direction: column; justify-content: flex-end; align-items: center; 
        }

        button { background: #000; color: #fff; border: none; padding: 15px; width: 100%; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; border-radius: 4px; cursor: pointer; }
        button:hover:not(:disabled) { background: var(--dorado); }
        button:disabled { background: #ccc; color: #888; cursor: not-allowed; }
        
        input[type="number"] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 50px; text-align: center; margin-left: 10px;}
    </style>
</head>
<body>

    <?php include_once 'views/layout/header.php'; ?>

    <div class="main-container">
        <h1 style="font-weight: 300; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;">Colección de Temporada</h1>
        <p style="color: #888; margin-bottom: 30px;">Éclat | Alta Costura Permanente</p>
        
        <div class="controles">
            <div class="fila-superior">
                <nav class="nav-categorias">
                    <a href="?" class="<?= !$categoria_id ? 'activo' : ''; ?>">Todo</a>
                    <?php foreach($categorias_lista as $id => $nombre): ?>
                        <a href="?cat=<?= $id ?>" class="<?= $categoria_id == $id ? 'activo' : ''; ?>"><?= $nombre ?></a>
                    <?php endforeach; ?>
                </nav>

                <div class="nav-filtros">
                    <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                        <?php if($categoria_id): ?>
                            <input type="hidden" name="cat" value="<?= $categoria_id ?>">
                        <?php endif; ?>

                        <select name="color" onchange="this.form.submit()" class="select-estilizado">
                            <option value="">Color: Todos</option>
                            <?php while ($col = $stmt_colores->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?= htmlspecialchars($col['color']) ?>" <?= ($color_filtro == $col['color']) ? 'selected' : '' ?>>
                                    <?= ucfirst(htmlspecialchars($col['color'])) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <select name="ord" onchange="this.form.submit()" class="select-estilizado">
                            <option value="">Ordenar por...</option>
                            <option value="barato" <?= $ordenar_por == 'barato' ? 'selected' : '' ?>>Precio ↓</option>
                            <option value="caro" <?= $ordenar_por == 'caro' ? 'selected' : '' ?>>Precio ↑</option>
                        </select>

                        <?php if($color_filtro || $ordenar_por): ?>
                            <a href="?<?= $categoria_id ? 'cat='.$categoria_id : '' ?>" class="btn-limpiar" title="Limpiar filtros">✕</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="contenedor-productos">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="producto">
                    <div class="img-contenedor">
                        <?php 
                        if (!empty($row['imagen'])): 
                            $img_src = (strpos($row['imagen'], 'http') === 0) 
                                       ? $row['imagen'] 
                                       : "assets/img/productos/" . $row['imagen']; 
                        ?>
                            <img src="<?= $img_src; ?>" alt="<?= htmlspecialchars($row['nombre']); ?>">
                        <?php else: ?>
                            <span class="img-no-disponible">Sin Imagen</span>
                        <?php endif; ?>
                    </div>

                    <p class="detalles">
                        <?= htmlspecialchars($row['color'] ?? 'N/A'); ?> | <?= htmlspecialchars($row['talla'] ?? 'Talla Única'); ?>
                    </p>
                    
                    <h3><?= htmlspecialchars($row['nombre']); ?></h3>
                    
                    <div class="descripcion-contenedor">
                        <p><?= htmlspecialchars($row['descripcion']); ?></p>
                    </div>
                    
                    <div class="footer-tarjeta">
                        <div><span class="categoria-tag"><?= htmlspecialchars($row['categoria_nombre']); ?></span></div>
                        <p class="precio"><?= number_format($row['precio'], 2); ?> €</p>
                        
                        <?php if ($row['stock'] > 0): ?>
                            <form method="POST" action="carrito_accion.php">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <input type="hidden" name="nombre" value="<?= $row['nombre']; ?>">
                                <input type="hidden" name="precio" value="<?= $row['precio']; ?>">
                                <input type="hidden" name="stock_max" value="<?= $row['stock']; ?>">
                                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 5px;">
                                    <label style="font-size: 0.7em; color: #888; letter-spacing: 1px;">CANTIDAD</label>
                                    <input type="number" name="cantidad" value="1" min="1" max="<?= $row['stock']; ?>">
                                </div>
                                <button type="submit" name="accion" value="agregar">Añadir a la Cesta</button>
                            </form>
                        <?php else: ?>
                            <div style="width: 100%; margin-bottom: 20px;">
                                <button disabled>Sin existencias</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include_once 'views/layout/footer.php'; ?>

</body>
</html>