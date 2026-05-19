<?php
/* página  donde Muestra todas las prendas disponibles y permite filtrarlas por categoría, color, precio y texto 
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// incluimos la bsae de datos y producto 
include_once 'config/database.php';
include_once 'models/Producto.php';

//iniciamos conexion con la bd 
$baseDatos  = new Database();
$conexion   = $baseDatos->getConnection();
$producto   = new Producto($conexion);

// recogemos los filtros activos de la URL - si no vienen, usamos valores por defecto
$categoria_id  = isset($_GET['cat'])   ? $_GET['cat']   : null;
$ordenar_por   = isset($_GET['ord'])   ? $_GET['ord']   : '';
$color_filtro  = isset($_GET['color']) ? $_GET['color'] : null;

// $busqueda solo se activa si el campo de texto viene con contenido
// trim() elimina espacios al principio y al final que pudiera escribir el usuario
$busqueda      = isset($_GET['q']) && $_GET['q'] != '' ? trim($_GET['q']) : null;

// ejecutamos la consulta con todos los filtros - los null se ignoran dentro del modelo
$listaPrendas  = $producto->leerTodos($categoria_id, $ordenar_por, $color_filtro, $busqueda);

// traemos los colores para rellenar el desplegable de filtro de color
$listaColores  = $producto->obtenerColoresUnicos();

// array con las categorías y la clave es el id en bbdd y el valor el nombre visible
// orden de cada categoria 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Éclat - Alta Costura</title>
    <style>
        /* variables CSS globalespara toda la pagina  */
        :root {
            --dorado: #b59410;
            --fondo-gris: #f4f4f4;
        }

        body { 
            font-family: 'Segoe UI', sans-serif;  /*tipo de letra */
            margin: 0; 
            background-color: var(--fondo-gris); 
            color: #333; 
        }
        
        /* fondo con puntos para el catálogo */
        .catalog-wrapper {
            background-image: radial-gradient(#d1d1d1 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        .main-container { padding: 60px 40px; max-width: 1700px; margin: 0 auto; }
        /* estilos de la cabecera del catalogo  */
        .header-catalogo { text-align: center; margin-bottom: 50px; }
        .header-catalogo h1 { font-weight: 200; letter-spacing: 10px; text-transform: uppercase; margin-bottom: 10px; font-size: 2.5em; }
        .header-catalogo .linea { width: 50px; height: 1px; background: var(--dorado); margin: 20px auto; }

        /* barra de filtros y categorías */
        .controles { 
            background: white; padding: 25px; border-radius: 8px; 
            margin-bottom: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }
        
        .fila-superior { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 30px; }
        
        /* navegación de categorías con subrayado dorado al activarse */
        .nav-categorias { display: flex; flex-wrap: wrap; gap: 15px; }
        .nav-categorias a { 
            text-decoration: none; color: #555; font-weight: 400; 
            text-transform: uppercase; font-size: 0.75em; letter-spacing: 2px; 
            transition: 0.3s; padding: 8px 15px; border-bottom: 1px solid transparent;
        }
        .nav-categorias a:hover, .nav-categorias a.activo { color: var(--dorado); border-bottom-color: var(--dorado); }

        .nav-filtros { display: flex; align-items: center; gap: 15px; }

        /* estilo para los selectores de color y orden */
        .select-estilizado {
            padding: 10px 15px; border: 1px solid #eee; border-radius: 0;
            background: white; font-family: inherit; font-size: 0.75em;
            text-transform: uppercase; letter-spacing: 1px; color: #222; 
            cursor: pointer; outline: none; transition: 0.3s;
        }

        /* grid  para que se adapte automáticamente al ancho disponible para productos  */
        .contenedor-productos { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(420px, 1fr)); 
            gap: 50px; 
        }

        /* tarjeta de producto con hover */
        .producto { 
            background: white; 
            padding: 0; 
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
        
        /* contenedor de imagen con altura fija para que todas las tarjetas sean iguales */
        .img-contenedor { 
            width: 100%; 
            height: 600px; 
            overflow: hidden; 
            background-color: #fff; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 10px;
            box-sizing: border-box;
        }

        /* object-fit: contain para que la imagen no se recorte aunque tenga tamaños distintos */
        .img-contenedor img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain;
            transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        
        /* zoom suave en la imagen al pasar el ratón */
        .producto:hover .img-contenedor img { 
            transform: scale(1.03); 
        }

        .producto h3 { 
            font-size: 1.1em; 
            margin: 15px 20px 5px 20px; 
            font-weight: 400; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            color: #000; 
        }
        
        .detalles { 
            color: #999; 
            font-size: 0.75em; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-bottom: 10px; 
        }

        /* estilo para la descripción del producto */
        .descripcion-prenda {
            color: #aaa;
            font-size: 1em;
            margin: 5px 20px 10px;
            line-height: 1.6;
            font-style: italic;
        }

        /* estilo para el precio  */
        .precio { 
            font-weight: 300; 
            color: var(--dorado); 
            font-size: 1.4em; 
            margin: 5px 0 20px 0; 
        }
        
        /* margin-top: auto empuja el footer de la tarjeta al fondo siempre */
        .footer-tarjeta { 
            margin-top: auto; 
            padding: 0 30px 30px 30px;
            padding-top: 20px;
        }

        /* estilos para el boton de comprar  */
        .btn-add { 
            background: #000; 
            color: #fff; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            transition: 0.3s; 
            cursor: pointer; 
            font-size: 0.8em; 
        }
        .btn-add:hover { background: var(--dorado); }
        
        .qty-input { padding: 10px; border: 1px solid #eee; width: 60px; text-align: center; margin-bottom: 15px; outline: none; }

        /* ── Responsive ── */

        /* tablet: reducimos el tamaño mínimo de columna para que quepan más */
        @media (max-width: 1024px) {
            .main-container { padding: 40px 20px; }
            .contenedor-productos { grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
            .img-contenedor { height: 420px; }
        }

        /* móvil grande: dos columnas y ajustes de tipografía */
        @media (max-width: 768px) {
            .main-container { padding: 30px 15px; }
            .fila-superior { flex-direction: column; align-items: flex-start; gap: 15px; }
            .nav-categorias { gap: 8px; }
            .nav-categorias a { font-size: .7em; padding: 6px 10px; }
            .contenedor-productos { grid-template-columns: repeat(2, 1fr); gap: 20px; }
            .img-contenedor { height: 280px; }
            .producto h3 { font-size: .9em; letter-spacing: 1px; }
            .precio { font-size: 1.1em; }
            .footer-tarjeta { padding: 0 15px 20px; }
        }

        /* móvil pequeño: una sola columna para que el producto se vea bien */
        @media (max-width: 480px) {
            .contenedor-productos { grid-template-columns: 1fr; gap: 25px; }
            .img-contenedor { height: 380px; }
            .controles { padding: 15px; }
            .nav-filtros form { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
<!-- incluimos el header  -->
    <?php include_once 'views/layout/header.php'; ?>

    <div class="catalog-wrapper">
        <div class="main-container">

            <div class="header-catalogo">
                <h1>Colección Éclat</h1>
                <div class="linea"></div>
                <p style="color: #888; font-style: italic; letter-spacing: 1px;">Alta Costura & Excelencia</p>
            </div>
            
            <div class="controles">
                <div class="fila-superior">

                    <!-- navegación por categorías,la clase 'activo' marca la categoría seleccionada -->
                    <nav class="nav-categorias">
                        <a href="?" class="<?= !$categoria_id ? 'activo' : ''; ?>">Todo</a>
                        <?php foreach($categorias_lista as $id => $nombre): ?>
                            <a href="?cat=<?= $id ?>" class="<?= ($categoria_id == $id ? 'activo' : '') ?>">
                                <?= $nombre ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="nav-filtros">

                        <!-- buscador de texto,busca en nombre y descripción de la prenda -->
                        <form method="GET" action="" style="display: flex; align-items: center; gap: 0;">
                            <!-- si hay categoría activa la mantenemos al buscar -->
                            <?php if($categoria_id): ?>
                                <input type="hidden" name="cat" value="<?= $categoria_id ?>">
                            <?php endif; ?>
                            <!-- cuadro de texto y estilos  -->
                            <input
                                type="text" name="q"
                                value="<?= htmlspecialchars($busqueda ?? '') ?>"
                                placeholder="Buscar prenda..."
                                style="padding: 10px 14px; border: 1px solid #eee; border-right: none; outline: none; font-family: inherit; font-size: .75em; width: 180px; letter-spacing: .5px;">
                            <button type="submit" style="padding: 10px 14px; background: #000; color: #fff; border: none; cursor: pointer; font-size: .8em; transition: .3s;" onmouseover="this.style.background='#b59410'" onmouseout="this.style.background='#000'">
                                🔍
                            </button>
                        </form>

                        <!-- filtros de color y orden,envía el formulario automáticamente sin botón -->
                        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                            <!-- mantenemos la búsqueda activa si la hay al cambiar color u orden -->
                            <?php if($busqueda): ?>
                                <input type="hidden" name="q" value="<?= htmlspecialchars($busqueda) ?>">
                            <?php endif; ?>
                            <?php if($categoria_id): ?>
                                <input type="hidden" name="cat" value="<?= $categoria_id ?>">
                            <?php endif; ?>

                            <!-- desplegable de colores generado desde la bbdd -->
                            <select name="color" onchange="this.form.submit()" class="select-estilizado">
                                <option value="">Color</option>
                                <?php while ($col = $listaColores->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= htmlspecialchars($col['color']) ?>" <?= ($color_filtro == $col['color']) ? 'selected' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($col['color'])) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <!-- desplegable de ordenación por precio -->
                            <select name="ord" onchange="this.form.submit()" class="select-estilizado">
                                <option value="">Ordenar</option>
                                <option value="barato" <?= $ordenar_por == 'barato' ? 'selected' : '' ?>>Precio ↓</option>
                                <option value="caro"   <?= $ordenar_por == 'caro'   ? 'selected' : '' ?>>Precio ↑</option>
                            </select>

                            <!-- botón limpiar(aparece si hay algún filtro activo) -->
                            <?php if($color_filtro || $ordenar_por || $busqueda): ?>
                                <a href="?<?= $categoria_id ? 'cat='.$categoria_id : '' ?>" style="text-decoration:none; color:#000; font-size:0.8em;">✕ Limpiar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- $hayResultados para saber si el while devolvió algo o no -->
            <?php $hayResultados = false; ?>
            <div class="contenedor-productos">
                <?php while ($prenda = $listaPrendas->fetch(PDO::FETCH_ASSOC)):
                    $hayResultados = true; // si entramos aquí es que hay al menos un resultado ?>
                    <div class="producto">
                        <div class="img-contenedor">
                            <?php 
                            // si la imagen empieza por http es una URL externa, si no construimos la ruta local
                            if (!empty($prenda['imagen'])): 
                                $rutaImg = (strpos($prenda['imagen'], 'http') === 0) ? $prenda['imagen'] : "assets/img/productos/" . $prenda['imagen'];
                            ?>
                                <img src="<?= $rutaImg; ?>" alt="<?= htmlspecialchars($prenda['nombre']); ?>">
                            <?php else: ?>
                                <!-- si no tiene imagen mostramos el nombre de la marca como placeholder -->
                                <div style="color: #ccc; letter-spacing: 5px;">ÉCLAT</div>
                            <?php endif; ?>
                        </div>

                        <!-- mostramos color y talla encima del nombre como datos secundarios -->
                        <p class="detalles"><?= htmlspecialchars($prenda['color'] ?? 'N/A'); ?> — <?= htmlspecialchars($prenda['talla'] ?? 'Talla Única'); ?></p>
                        <h3><?= htmlspecialchars($prenda['nombre']); ?></h3>

                        <!-- descripción de la prenda, solo aparece si tiene texto en la bbdd -->
                        <?php if(!empty($prenda['descripcion'])): ?>
                            <p class="descripcion-prenda"><?= htmlspecialchars($prenda['descripcion']); ?></p>
                        <?php endif; ?>

                        <p class="precio"><?= number_format($prenda['precio'], 2); ?> €</p>
                        
                        <div class="footer-tarjeta">
                            <?php if ($prenda['stock'] > 0): ?>
                                <!-- formulario que envía los datos al carrito_accion.php por POST -->
                                <!-- los campos hidden pasan la info del producto sin mostrarla al usuario -->
                                <form method="POST" action="carrito_accion.php">
                                    <input type="hidden" name="id"        value="<?= $prenda['id']; ?>">
                                    <input type="hidden" name="nombre"    value="<?= $prenda['nombre']; ?>">
                                    <input type="hidden" name="precio"    value="<?= $prenda['precio']; ?>">
                                    <!-- stock_max lo usamos en el controlador para no vender más de lo disponible -->
                                    <input type="hidden" name="stock_max" value="<?= $prenda['stock']; ?>">
                                    
                                    <div style="margin-bottom: 15px;">
                                        <!-- min y max en el input number para que no puedan poner 0 o más del stock -->
                                        <input type="number" name="cantidad" value="1" min="1" max="<?= $prenda['stock']; ?>" class="qty-input">
                                    </div>
                                    <button type="submit" name="accion" value="agregar" class="btn-add">Añadir a la Cesta</button>
                                </form>
                            <?php else: ?>
                                <!-- si no hay stock mostramos el botón desactivado -->
                                <button disabled style="background: #eee; color: #aaa; cursor: not-allowed; border: none; padding: 15px; width: 100%; text-transform: uppercase; letter-spacing: 2px;">Agotado</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- mensaje de sin resultados - solo aparece si el while no devolvió ninguna prenda -->
            <?php if(!$hayResultados): ?>
                <div style="text-align:center; padding: 80px 20px; color: #aaa;">
                    <p style="font-size: 2em; margin-bottom: 15px;">✦</p>
                    <p style="text-transform: uppercase; letter-spacing: 3px; font-size: .9em; margin-bottom: 20px;">
                        <?php if($busqueda): ?>
                            No encontramos prendas para "<strong style="color:#333;"><?= htmlspecialchars($busqueda) ?></strong>"
                        <?php else: ?>
                            No hay prendas en esta categoría
                        <?php endif; ?>
                    </p>
                    <a href="?" style="color:#000; font-size:.8em; text-transform:uppercase; letter-spacing:2px; border-bottom: 1px solid #000; text-decoration:none;">Ver toda la colección</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
<!-- incluimos el footer  -->
    <?php include_once 'views/layout/footer.php'; ?>

</body>
</html>
