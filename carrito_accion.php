<?php
include_once 'controllers/CarritoController.php';
$carrito = new CarritoController();

// Recogemos la acción (agregar, eliminar o limpiar)
$accion = $_POST['accion'] ?? '';

if ($accion == 'agregar') {
    // Usamos el stock real 
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $stockReal = $_POST['stock_max']; 

    $carrito->agregar($id, $nombre, $precio, $cantidad, $stockReal);
} 

if ($accion == 'quitar') {
    // Usamos el método eliminar que creamos en el controlador
    $carrito->eliminar($_POST['id']);
}

if ($accion == 'limpiar') {
    // Usamos vaciarCesta para podeer vaciar la cesta 
    $carrito->vaciarCesta();
}

// Siempre volvemos a la cesta para ver los cambios
header("Location: ver_carrito.php");
exit;
?>