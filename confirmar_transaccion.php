<?php
/*

 Usamos fetch en lugar de un formulario normal porque así podemos
 mostrar feedback visual al usuario (el botón cambia a "Procesando...")
 mientras se guarda el pedido, sin recargar la página .

 */
session_start();
//incluimos la bd y pedido 
include_once 'config/Database.php';
include_once 'models/Pedido.php';

//inciiamos la conexion con la bd
$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();
$pedido    = new Pedido($conexion);

// recuperamos los datos necesarios para procesar el pedido
// el id del cliente viene de la sesión,si no hay sesión activa no procesamos nada
$idCliente = $_SESSION['usuario_id'] ?? null;

// el total lo manda el Js desde la página de pago
$totalPago = $_POST['total'] ?? 0;

// el carrito completo está en sesión como array con todas las prendas
$cesta = $_SESSION['carrito'] ?? [];

// solo procesamos si hay un cliente logueado y tiene algo en el carrito
if($idCliente && !empty($cesta)){

    if($pedido->finalizarPedido($idCliente, $totalPago, $cesta)){

        // obtenemos el id del pedido para incluirlo en el email
        $idPedido = $pedido->getUltimoId();

        // vaciamos el carrito de sesión cuando la compra esta competa 
        unset($_SESSION['carrito']);

        // devolvemos éxito con el id del pedido para que EmailJS lo use en la plantilla
        echo json_encode(['success' => true, 'id_pedido' => $idPedido]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el pedido en la bbdd']);
    }

} else {
    // puede pasar si la sesión expiró mientras el cliente rellenaba el formulario de pago
    echo json_encode(['success' => false, 'message' => 'Sesión expirada o carrito vacío']);
}
?>
