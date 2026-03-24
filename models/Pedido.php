<?php
class Pedido {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function finalizarPedido($usuario_id, $total, $carrito) {
        // 1. Insertar el pedido en la tabla 'pedidos'
        $query = "INSERT INTO pedidos (usuario_id, fecha, total, estado) VALUES (?, NOW(), ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$usuario_id, $total]);
        
        // Obtenemos el ID del pedido que se acaba de crear
        $pedido_id = $this->conn->lastInsertId();

        // 2. Recorremos el carrito para guardar detalles y RESTAR STOCK
        foreach ($carrito as $producto_id => $item) {
            // Guardar en detalle_pedido
            $query_det = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
            $stmt_det = $this->conn->prepare($query_det);
            $stmt_det->execute([$pedido_id, $producto_id, $item['cantidad'], $item['precio']]);

            // Restar stock en la tabla productos (Requisito CU-03)
            $query_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
            $stmt_stock = $this->conn->prepare($query_stock);
            $stmt_stock->execute([$item['cantidad'], $producto_id]);
        }

        return true;
    }
}
?>