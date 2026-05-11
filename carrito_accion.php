<?php
/* archivo paraa procesador de acciones del carrito*/
include_once 'controllers/CarritoController.php';

// instanciamos el controlador que gestiona la sesión del carrito
$cesta  = new CarritoController();

// recogemos la acción que viene del formulario el ?? '' evita error si no viene nada
$accion = $_POST['accion'] ?? '';

// acción agregar: pasamos todos los datos del producto al controlador
// el stock_max viene del campo hidden del formulario para validar que no se pase
if($accion == 'agregar'){
    $cesta->agregar(
        $_POST['id'],        // id del producto en la bbdd
        $_POST['nombre'],    // nombre para mostrar en el carrito
        $_POST['precio'],    // precio unitario
        $_POST['cantidad'],  // unidades que quiere añadir
        $_POST['stock_max']  // stock real del producto para no vender de más
    );
}

// acción quitar: eliminamos solo esa prenda del carrito por su id
if($accion == 'quitar')  $cesta->eliminar($_POST['id']);

// acción limpiar: vaciamos toda la cesta 
if($accion == 'limpiar') $cesta->vaciarCesta();

// después de cualquier acción redirigimos al carrito para ver el resultado
header("Location: ver_carrito.php");
exit;
?>
