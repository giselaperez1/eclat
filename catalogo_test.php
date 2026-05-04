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
    1 => 'Monos y Vestidos',
    2 => 'Tops',
    4 => 'Camisetas',
    5 => 'Chaquetas',
    6 => 'Pantalones',
    7 => 'Faldas',
    8 => 'Zapatos',
    3 => 'Accesorios',
    9 => 'Evento' 
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
            --fondo-gris: #f4f4f4; /* Gris seda muy suave */
        }

        body { 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            background-color: var(--fondo-gris); /* Fondo mejorado */
            color: #333; 
        }
        
        /* Efecto de textura sutil para el fondo */
        .catalog-wrapper {
            background-image: radial-gradient(#d1d1d1 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        .main-container { padding: 60px 40px; max-width: 1400px; margin: 0 auto; }
        
        .header-catalogo { text-align: center; margin-bottom: 50px; }
        .header-catalogo h1 { font-weight: 200; letter-spacing: 10px; text-transform: uppercase; margin-bottom: 10px; font-size: 2.5em; }
        .header-catalogo .linea { width: 50px; height: 1px; background: var(--dorado); margin: 20px auto; }

        .controles { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            margin-bottom: 50px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }
        
        .fila-superior { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 30px; }
        
        .nav-categorias { display: flex; flex-wrap: wrap; gap: 15px; }
        .nav-categorias a { 
            text-decoration: none; 
            color: #555; 
            font-weight: 400; 
            text-transform: uppercase; 
            font-size: 0.75em; 
            letter-spacing: 2px; 
            transition: 0.3s; 
            padding: 8px 15px;
            border-bottom: 1px solid transparent;
        }
        .nav-categorias a:hover, .nav-categorias a.activo { color: var(--dorado); border-bottom-color: var(--dorado); }
        
        .nav-categorias a.nav-evento {
            color: var(--dorado);
            border: 1px solid var(--dorado);
            border-radius: 2px;
        }

        .nav-filtros { display: flex; align-items: center; gap: 15px; }

        .select-estilizado {
            padding: 10px 15px; border: 1px solid #eee; border-radius: 0;
            background: white; font-family: inherit; font-size: 0.75em;
            text-transform: uppercase; letter-spacing: 1px;
            color: #222; cursor: pointer; outline: none; transition: 0.3s;
        }
        .select-estilizado:hover { border-color: var(--dorado); }
        
        .btn-limpiar {
            text-decoration: none; background: #000; color: #fff;
            width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
            font-size: 0.7em; transition: 0.3s;
        }
        .btn-limpiar:hover { background: var(--dorado); }

        .contenedor-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 40px; }

        .producto { 
            background: white; 
            padding: 20px; 
            border-radius: 0; 
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); 
            display: flex; 
            flex-direction: column; 
            text-align: center;
            border: 1px solid #f0f0f0;
        }
        
        .producto:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 30px 60px rgba(0,0,0,0.1); 
            border-color: transparent;
        }
        
        .img-contenedor { 
            width: 100%; 
            height: 450px; 
            overflow: hidden; 
            margin-bottom: 20px; 
            background-color: #fff; 
        }

        .img-contenedor img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            object-position: center top; 
            transition: transform 1.2s ease; 
        }
        .producto:hover .img-contenedor img { transform: scale(1.08); }

        .producto h3 {
            font-size: 1em; margin: 15px 0 5px 0; font-weight: 400;
            text-transform: uppercase; letter-spacing: 1px;
            color: #000;
        }

        .detalles { 
            color: #999; font-size: 0.7em; text-transform: uppercase; 
            letter-spacing: 2px; margin-bottom: 15px; 
        }
        
        .precio { font-weight: 300; color: var(--dorado); font-size: 1.3em; margin: 10px 0 20px 0; }
        
        .footer-tarjeta { 
            margin-top: auto; 
            padding-top: 20px;
            border-top: 1px solid #f9f9f9;
        }

        .btn-add { 
            background: #000; color: #fff; border: none; padding: 15px; 
            width: 100%; font-weight: bold; text-transform: uppercase; 
            letter-spacing: 2px; transition: 0.3s; cursor: pointer; 
            font-size: 0.8em;
        }
        .btn-add:hover { background: var(--dorado); }
        
        .qty-input {
            padding: 10px; border: 1px solid #eee; width: 60px; 
            text-align: center; margin-bottom: 15px; outline: none;
        }
    </style>
</head>
<body>

    <?php include_once 'views/layout/header.php'; ?>

    <div class="catalog-wrapper">
        <div class="main-container">
            <div class="header-catalogo">
                <h1>Colección Éclat</h1>
                <div class="linea"></div>
                <p style="color: #888; font-style: italic; letter-spacing: 1px;">Piezas exclusivas de confección artesanal</p>
            </div>
            
            <div class="controles">
                <div class="fila-superior">
                    <nav class="nav-categorias">
                        <a href="?" class="<?= !$categoria_id ? 'activo' : ''; ?>">Todo</a>
                        <?php foreach($categorias_lista as $id => $nombre): ?>
                            <a href="?cat=<?= $id ?>" 
                               class="<?= ($categoria_id == $id ? 'activo' : '') . ($id == 9 ? ' nav-evento' : ''); ?>">
                                <?= $nombre ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="nav-filtros">
                        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                            <?php if($categoria_id): ?>
                                <input type="hidden" name="cat" value="<?= $categoria_id ?>">
                            <?php endif; ?>

                            <select name="color" onchange="this.form.submit()" class="select-estilizado">
                                <option value="">Color</option>
                                <?php while ($col = $stmt_colores->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= htmlspecialchars($col['color']) ?>" <?= ($color_filtro == $col['color']) ? 'selected' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($col['color'])) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <select name="ord" onchange="this.form.submit()" class="select-estilizado">
                                <option value="">Ordenar</option>
                                <option value="barato" <?= $ordenar_por == 'barato' ? 'selected' : '' ?>>Precio ↓</option>
                                <option value="caro" <?= $ordenar_por == 'caro' ? 'selected' : '' ?>>Precio ↑</option>
                            </select>

                            <?php if($color_filtro || $ordenar_por): ?>
                                <a href="?<?= $categoria_id ? 'cat='.$categoria_id : '' ?>" class="btn-limpiar" title="Limpiar">✕</a>
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
                                $img_src = (strpos($row['imagen'], 'http') === 0) ? $row['imagen'] : "assets/img/productos/" . $row['imagen']; 
                            ?>
                                <img src="<?= $img_src; ?>" alt="<?= htmlspecialchars($row['nombre']); ?>">
                            <?php else: ?>
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f9f9f9; color: #ccc;">ÉCLAT</div>
                            <?php endif; ?>
                        </div>

                        <p class="detalles"><?= htmlspecialchars($row['color'] ?? 'N/A'); ?> — <?= htmlspecialchars($row['talla'] ?? 'Única'); ?></p>
                        <h3><?= htmlspecialchars($row['nombre']); ?></h3>
                        <p class="precio"><?= number_format($row['precio'], 2); ?> €</p>
                        
                        <div class="footer-tarjeta">
                            <?php if ($row['stock'] > 0): ?>
                                <form method="POST" action="carrito_accion.php">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <input type="hidden" name="nombre" value="<?= $row['nombre']; ?>">
                                    <input type="hidden" name="precio" value="<?= $row['precio']; ?>">
                                    <input type="hidden" name="stock_max" value="<?= $row['stock']; ?>">
                                    
                                    <div style="margin-bottom: 15px;">
                                        <label style="font-size: 0.6em; color: #bbb; letter-spacing: 2px; display: block; margin-bottom: 5px;">CANTIDAD</label>
                                        <input type="number" name="cantidad" value="1" min="1" max="<?= $row['stock']; ?>" class="qty-input">
                                    </div>
                                    <button type="submit" name="accion" value="agregar" class="btn-add">Añadir a la Cesta</button>
                                </form>
                            <?php else: ?>
                                <button disabled style="background: #eee; color: #aaa; cursor: not-allowed; border: none; padding: 15px; width: 100%; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8em;">Agotado</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include_once 'views/layout/footer.php'; ?>

</body>
</html>