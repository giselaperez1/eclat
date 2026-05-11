<?php
/*
Formulario para editar un producto existente
 Carga los datos actuales del producto en el formulario y los actualiza
 cuando el admin envía los cambios.
 */
// formulario de edición de un producto existente
// llego aquí desde el panel de inventario al pulsar "Editar" en cualquier prenda
include_once '../../config/admin_check.php';
include_once '../../config/database.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

$aviso = ""; // mensaje que aparece arriba del formulario tras guardar

// array de categorías - lo uso para generar el <select> del formulario
$cats = [1=>"Vestidos", 2=>"Tops", 3=>"Accesorios", 4=>"Camisetas", 5=>"Chaquetas", 6=>"Pantalones", 7=>"Faldas", 8=>"Zapatos"];

// si no viene id en la URL no sé qué producto editar, vuelvo al inventario
if(!isset($_GET['id'])){ header("Location: admin_index.php"); exit; }

$idPrenda = (int)$_GET['id']; // id de la prenda que voy a editar

// cargo los datos actuales del producto para rellenar el formulario con los valores que ya tiene
$q = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$q->execute([$idPrenda]);
$datosPrenda = $q->fetch(PDO::FETCH_ASSOC); // array con todos los campos del producto

// si el id no existe en la bbdd, vuelvo al inventario para evitar errores
if(!$datosPrenda){ header("Location: admin_index.php"); exit; }

if($_POST){
    try {
        // actualizo todos los campos del producto con los nuevos valores del formulario
        $upd = $conexion->prepare(
            "UPDATE productos SET
                nombre       = :nombre,
                descripcion  = :descripcion,
                precio       = :precio,
                stock        = :stock,
                categoria_id = :categoria_id,
                color        = :color,
                talla        = :talla
             WHERE id = :id"
        );
        $upd->execute([
            ':nombre'       => $_POST['nombre'],
            ':descripcion'  => $_POST['descripcion'],
            ':precio'       => $_POST['precio'],
            ':stock'        => $_POST['stock'],
            ':categoria_id' => $_POST['categoria_id'],
            ':color'        => $_POST['color'],
            ':talla'        => $_POST['talla'],
            ':id'           => $idPrenda
        ]);

        // en vez de volver a consultar la bbdd, fusiono el array viejo con los nuevos valores del POST
        // así el formulario se actualiza inmediatamente con lo que acaba de guardar
        $datosPrenda = array_merge($datosPrenda, $_POST);
        $aviso = "<p style='color:green;'>Cambios guardados.</p>";

    } catch(PDOException $e){
        $aviso = "<p style='color:red;'>Error: ".$e->getMessage()."</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar #<?php echo $idPrenda; ?> - Éclat</title>
    <style>
        /* estilos y responsive  */
        body { font-family: "Segoe UI", sans-serif; background: #f4f4f4; padding: 40px; }
        @media (max-width: 768px) { body { padding: 15px; } .card { padding: 20px; } .cabecera { flex-direction: column; gap: 20px; } .fila-total td { font-size: 1em; } }
        .form-card { background:#fff; padding:35px; max-width:540px; margin:auto; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
        h2 { text-transform:uppercase; color:#b59410; margin:0 0 5px; }
        .sub { color:#bbb; font-size:.8em; margin:0 0 25px; }
        label { font-size:.8em; font-weight:bold; color:#555; display:block; margin:15px 0 5px; text-transform:uppercase; }
        input, textarea, select {
            width:100%; padding:11px; border:1px solid #ddd;
            border-radius:4px; box-sizing:border-box;
            font-family:inherit; font-size:.9em; outline:none;
        }
        input:focus, textarea:focus, select:focus { border-color:#b59410; }
        .fila { display:flex; gap:15px; }
        .fila > div { flex:1; }
        button { background:#b59410; color:#fff; padding:15px; width:100%; border:none; cursor:pointer; font-weight:bold; text-transform:uppercase; letter-spacing:1px; margin-top:20px; transition:.3s; }
        button:hover { background:#000; }
        .volver { display:block; margin-top:18px; text-align:center; color:#888; text-decoration:none; font-size:.9em; }
        .volver:hover { color:#000; }
    </style>
</head>
<body>
<!-- formulario para editar culaquier campo del produ to  -->
<div class="form-card">
    <h2>Editar Producto</h2>
    <p class="sub">ID #<?php echo $idPrenda; ?></p>

    <?php echo $aviso; ?>

    <form method="POST">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($datosPrenda['nombre']); ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" rows="4" required><?php echo htmlspecialchars($datosPrenda['descripcion']); ?></textarea>

        <div class="fila">
            <div>
                <label>Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $datosPrenda['precio']; ?>" required>
            </div>
            <div>
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo $datosPrenda['stock']; ?>" required>
            </div>
        </div>

        <label>Categoría</label>
        <select name="categoria_id" required>
            <?php foreach($cats as $idCat => $nomCat): ?>
                <option value="<?php echo $idCat; ?>" <?php echo ($datosPrenda['categoria_id'] == $idCat) ? 'selected' : ''; ?>>
                    <?php echo $nomCat; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="fila">
            <div>
                <label>Color</label>
                <input type="text" name="color" value="<?php echo htmlspecialchars($datosPrenda['color']); ?>">
            </div>
            <div>
                <label>Talla</label>
                <input type="text" name="talla" value="<?php echo htmlspecialchars($datosPrenda['talla']); ?>">
            </div>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
<!-- boton para volve  -->
    <a href="admin_index.php" class="volver">← Cancelar</a>
</div>

</body>
</html>
