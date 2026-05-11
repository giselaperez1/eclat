<?php
/*
Formulario para añadir un producto al catálogo
Gestiona tanto la subida de la imagen como el INSERT en la bbdd.

 */
// formulario para añadir un producto nuevo al catálogo
// gestiona también la subida de la imagen a la carpeta de assets
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

$aviso = ""; // mensaje de resultado que se muestra encima del formulario

if($_POST){
    // datos del archivo de imagen que subió el admin
    $nombreArchivo  = $_FILES['imagen']['name'];     // nombre original del archivo
    $rutaTemporal   = $_FILES['imagen']['tmp_name']; // donde PHP lo deja temporalmente
    $carpetaDestino = "../../assets/img/productos/"; // carpeta definitiva donde lo quiero

    // añado time() al nombre para que no haya duplicados si dos prendas tienen el mismo nombre de foto
    $nombreFinal = time()."_".$nombreArchivo;

    // move_uploaded_file mueve la imagen de la carpeta temporal a la carpeta definitiva
    if(move_uploaded_file($rutaTemporal, $carpetaDestino.$nombreFinal)){
        try {
            // guardo el producto en la bbdd con el nombre final de la imagen
            $ins = $conexion->prepare(
                "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, color, talla, imagen)
                 VALUES (:nombre, :descripcion, :precio, :stock, :categoria_id, :color, :talla, :imagen)"
            );
            $ins->execute([
                ':nombre'       => $_POST['nombre'],
                ':descripcion'  => $_POST['descripcion'],
                ':precio'       => $_POST['precio'],
                ':stock'        => $_POST['stock'],
                ':categoria_id' => $_POST['categoria_id'],
                ':color'        => $_POST['color'],
                ':talla'        => $_POST['talla'],
                ':imagen'       => $nombreFinal  // solo el nombre, la ruta se monta en la vista
            ]);
            $aviso = "<p style='color:green;'>Producto añadido correctamente.</p>";
        } catch(PDOException $e){
            $aviso = "<p style='color:red;'>Error: ".$e->getMessage()."</p>";
        }
    } else {
        // revisar que la carpeta assets/img/productos/ exista y tenga permisos de escritura
        $aviso = "<p style='color:red;'>No se pudo subir la imagen. Revisa los permisos de la carpeta.</p>";
    }
}

// array de categorías para el desplegable del formulario - clave = id en bbdd, valor = nombre visible
$cats = [1=>"Vestidos", 2=>"Tops", 3=>"Accesorios", 4=>"Camisetas", 5=>"Chaquetas", 6=>"Pantalones", 7=>"Faldas", 8=>"Zapatos"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto - Éclat</title>
    <style>
        /* estilso para el body y resposnsive  */
        body { font-family: "Segoe UI", sans-serif; background: #f4f4f4; padding: 40px; }
        @media (max-width: 768px) { body { padding: 15px; } .card { padding: 20px; } .cabecera { flex-direction: column; gap: 20px; } .fila-total td { font-size: 1em; } }
        .form-card { background:#fff; padding:35px; max-width:520px; margin:auto; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
        h2 { text-transform:uppercase; color:#b59410; margin:0 0 25px; letter-spacing:1px; }
        label { font-size:.8em; font-weight:bold; color:#555; display:block; margin:15px 0 5px; text-transform:uppercase; letter-spacing:.5px; }
        input, textarea, select {
            width:100%; padding:11px; border:1px solid #ddd;
            border-radius:4px; box-sizing:border-box;
            font-family:inherit; font-size:.9em; outline:none;
        }
        /* estilos para lso campos y los botonee  */
        input:focus, textarea:focus, select:focus { border-color:#b59410; }
        input[type="file"] { border:1px dashed #b59410; background:#fafafa; padding:14px; cursor:pointer; }
        .fila { display:flex; gap:15px; }
        .fila > div { flex:1; }
        button { background:#000; color:#fff; padding:15px; width:100%; border:none; cursor:pointer; font-weight:bold; text-transform:uppercase; letter-spacing:1px; margin-top:20px; transition:.3s; }
        button:hover { background:#b59410; }
        .volver { display:block; margin-top:18px; text-align:center; color:#888; text-decoration:none; font-size:.9em; }
        .volver:hover { color:#000; }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Añadir Producto</h2>
    <?php echo $aviso; ?>
<!-- formulaario con los datos para agregae el producto -->
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre</label>
        <input type="text" name="nombre" placeholder="Ej: Vestido Seda Oro" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="4" placeholder="Detalles de la prenda..." required></textarea>

        <label>Fotografía</label>
        <input type="file" name="imagen" accept="image/*" required>

        <div class="fila">
            <div>
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" placeholder="0.00" required>
            </div>
            <div>
                <label>Stock</label>
                <input type="number" name="stock" placeholder="Uds." required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="categoria_id" required>
            <option value="">Seleccionar...</option>
            <?php foreach($cats as $id => $nom): ?>
                <option value="<?php echo $id; ?>"><?php echo $nom; ?></option>
            <?php endforeach; ?>
        </select>

        <div class="fila">
            <div>
                <label>Color</label>
                <input type="text" name="color" placeholder="Ej: Marfil">
            </div>
            <div>
                <label>Talla</label>
                <input type="text" name="talla" value="Talla Única">
            </div>
        </div>

        <button type="submit">Publicar en Éclat</button>
    </form>
<!-- enlace para volver al inventario  -->
    <a href="admin_index.php" class="volver">← Volver al inventario</a>
</div>

</body>
</html>
