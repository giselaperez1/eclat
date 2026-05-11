<?php
session_start();
include_once 'config/Database.php';
include_once 'models/Pedido.php';

// si no está logueado, mostrar pantalla de login/registro
if(!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación requerida - Éclat</title>
    <!-- estilos para el login -->
    <style>
        body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 50px; background: #fff; color: #333; }
        .caja { max-width: 480px; margin: 60px auto; border: 1px solid #eee; padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        h1  { font-weight: 300; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
        p   { color: #888; font-size: 0.9em; }
        .btn { display: inline-block; padding: 12px 28px; margin: 8px; text-decoration: none; font-weight: bold; transition: .3s; font-size: 0.85em; letter-spacing: 1px; text-transform: uppercase; }
        .btn-negro  { background: #000; color: #fff; }
        .btn-negro:hover  { background: #b59410; }
        .btn-dorado { border: 1px solid #b59410; color: #b59410; }
        .btn-dorado:hover { background: #b59410; color: #fff; }
    </style>
</head>
<body>
<div class="caja">
    <h1>Un momento...</h1>
    <p>Para completar tu pedido necesitamos saber quién eres.</p>
    <hr style="border:0; border-top:1px solid #f0f0f0; margin: 25px 0;">
    <!-- botones de login y registrarse con sus hiperenlaces -->
    <a href="login_test.php"    class="btn btn-negro">Ya tengo cuenta</a>
    <br>
    <a href="registro_test.php" class="btn btn-dorado">Crear cuenta nueva</a>
    <br><br>
    <!-- botonn para volver a la cesta  -->
    <a href="ver_carrito.php" style="color:#bbb; font-size:12px;">← Volver a la cesta</a>
</div>
</body>
</html>
<?php
    exit;
}
//conexion con la bd 
$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();
$pedido    = new Pedido($conexion);

$idCliente = $_SESSION['usuario_id'];
$totalPago = $_POST['total'];
$cesta     = $_SESSION['carrito'];

//cuando el pedido se realiza se vacia el carro
if($pedido->finalizarPedido($idCliente, $totalPago, $cesta)) {
    unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido confirmado - Éclat</title>
    <!-- estilos para pagina cuando se confirma el epdido  -->
    <style>
        body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 100px 40px; background: #fff; }
        .icono { font-size: 48px; color: #b59410; }
        h1 { font-weight: 300; letter-spacing: 2px; text-transform: uppercase; }
        a { color: #000; font-weight: bold; }
    </style>
</head>
<body>
    <!-- se realiza el pedidoo y se muestra lo siguiente  -->
    <div class="icono">✔</div>
    <h1>¡Gracias por tu compra!</h1>
    <p style="color:#888;">Tu pedido se ha procesado correctamente.</p>
    <br>
    <a href="catalogo_test.php">Seguir comprando</a>
</body>
</html>
<!-- si da error imprimimos lo siguiente : -->
<?php
} else {
    echo "Hubo un error técnico al procesar tu compra. Por favor contacta con soporte.";
}
?>
