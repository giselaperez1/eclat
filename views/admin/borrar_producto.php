<?php
/*
 * borrar_producto.php - Elimina un producto del catálogo
 */
include_once '../../config/admin_check.php'; // verificamos que es admin antes de borrar nada
include_once '../../config/database.php';

// si no viene id en la URL no sabemos qué borrar volvemos al inventario
if(!isset($_GET['id'])){ header("Location: admin_index.php"); exit; }

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();

// convertimos el id a entero con (int) para evitar inyección SQL por la URL
$id = (int)$_GET['id'];

try {
    // eliminamos las líneas de detalle_pedido que referencian este producto
    // borrar el producto si hay pedidos que lo contienen
    $borraDetalle = $conexion->prepare("DELETE FROM detalle_pedido WHERE producto_id = ?");
    $borraDetalle->execute([$id]);

    //ahora sí podemos borrar el producto
    $borraProducto = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $borraProducto->execute([$id]);

    // volvemos al inventario con un parámetro para mostrar el mensaje de confirmación
    header("Location: admin_index.php?mensaje=eliminado");

} catch(PDOException $e){
    header("Location: admin_index.php?mensaje=error");
}

exit;
?>
