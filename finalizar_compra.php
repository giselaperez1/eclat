<?php
session_start();

// 1. Conexiones necesarias
include_once 'config/Database.php';
include_once 'models/Pedido.php';

/**
 * CONTROL DE SEGURIDAD (Muro de Pago)
 * Si el usuario no está logueado, le damos opciones elegantes en lugar de un error feo
 */
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Identificación requerida - Éclat</title>
        <style>
            body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 50px; background: #fff; color: #333; }
            .contenedor-seguridad { max-width: 500px; margin: 0 auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
            h1 { font-weight: 300; text-transform: uppercase; letter-spacing: 2px; }
            .btn { display: inline-block; padding: 12px 25px; margin: 10px; text-decoration: none; font-weight: bold; transition: 0.3s; }
            .btn-negro { background: #000; color: #fff; }
            .btn-dorado { border: 1px solid #b59410; color: #b59410; }
            .btn:hover { opacity: 0.7; }
        </style>
    </head>
    <body>
        <div class="contenedor-seguridad">
            <h1>Casi lo tienes...</h1>
            <p>Para finalizar tu pedido de alta costura, necesitamos saber quién eres.</p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            
            <a href="login_test.php" class="btn btn-negro">YA TENGO CUENTA</a>
            <p>¿Es tu primera vez en Éclat?</p>
            <a href="registro_test.php" class="btn btn-dorado">CREAR UNA CUENTA</a>
            
            <br><br>
            <a href="ver_carrito.php" style="color: #999; font-size: 13px;">← Volver a mi cesta</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// 2. Si el código llega aquí, es que el usuario SÍ está logueado
$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);

$usuario_id = $_SESSION['usuario_id'];
$total = $_POST['total'];
$carrito = $_SESSION['carrito'];

// 3. Procesamos el pedido y actualizamos stock en la BD 
if ($pedido->finalizarPedido($usuario_id, $total, $carrito)) {
    
    // Limpiamos la cesta para que no se duplique el pedido
    unset($_SESSION['carrito']);
    ?>
    
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 100px; }
            .exito { color: #b59410; font-size: 40px; }
        </style>
    </head>
    <body>
        <div class="exito">✔</div>
        <h1>¡Gracias por tu compra en Éclat!</h1>
        <p>Tu pedido se ha procesado correctamente y el stock ha sido actualizado.</p>
        <a href="catalogo_test.php" style="color: #000; font-weight: bold;">Seguir comprando</a>
    </body>
    </html>

    <?php
} else {
    echo "Lo sentimos, hubo un error técnico al procesar tu compra. Por favor, contacta con soporte.";
}
?>