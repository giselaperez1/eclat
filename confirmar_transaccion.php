<?php
session_start();
include_once 'config/Database.php';
include_once 'models/Pedido.php';

$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);

$usuario_id = $_SESSION['usuario_id'] ?? null;
$total = $_POST['total'] ?? 0;
$carrito = $_SESSION['carrito'] ?? [];

// Este archivo actúa como una "puerta trasera" para actualizar la base de datos
if ($usuario_id && !empty($carrito)) {
    if ($pedido->finalizarPedido($usuario_id, $total, $carrito)) {
        unset($_SESSION['carrito']); // Limpiamos la cesta tras el éxito
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en el servidor al actualizar stock']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Sesión expirada o carrito vacío']);
}