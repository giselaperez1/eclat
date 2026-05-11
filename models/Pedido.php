<?php
/*
Se encarga de guardar una compra completa en la base de datos.
 */
class Pedido {

    private $linkBD;   // conexión PDO activa
    private $ultimoId; // guardamos el id del último pedido insertado para devolverlo al JS

    function __construct($conexion){
        $this->linkBD = $conexion;
    }

    /*
     finalizarPedido() - guarda el pedido completo y descuenta el stock

     
     @param $idCliente  id del usuario logueado (de $_SESSION)
     @param $total      importe total de la compra
     @param $cesta      array del carrito guardado en sesión
     @return true cuando todo ha ido bien
     */
    public function finalizarPedido($idCliente, $total, $cesta){

        // insertamos la cabecera del pedido
        // NOW() usa la fecha y hora actual del servidor MySQL automáticamente
        // el estado arranca siempre como 'pendiente' hasta que el admin lo cambie desde el panel
        $insertPedido = $this->linkBD->prepare(
            "INSERT INTO pedidos (usuario_id, fecha, total, estado)
             VALUES (?, NOW(), ?, 'pendiente')"
        );
        $insertPedido->execute([$idCliente, $total]);

        // lastInsertId() devuelve el id auto_increment del último INSERT ejecutado
        // lo necesitamos para relacionar cada línea de detalle con su pedido padre
        $this->ultimoId = $this->linkBD->lastInsertId();
        $idPedido       = $this->ultimoId;

        // recorremos el carrito - la clave del array es el id del producto
        // y el valor es otro array con nombre, precio y cantidad
        foreach($cesta as $idProducto => $prenda){

            // insertamos una línea de detalle por cada prenda diferente en el carrito
            // guardamos el precio_unitario del momento de la compra para que no cambie
            // aunque luego modifiquemos el precio del producto en el panel admin
            $this->linkBD->prepare(
                "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario)
                 VALUES (?, ?, ?, ?)"
            )->execute([$idPedido, $idProducto, $prenda['cantidad'], $prenda['precio']]);

            // descontamos del stock las unidades que acaba de comprar el cliente
            // usamos stock = stock - ? para que sea una operación atómica en MySQL
            // (evita problemas si dos usuarios compran el mismo producto a la vez)
            $actualizaStock = $this->linkBD->prepare(
                "UPDATE productos SET stock = stock - ? WHERE id = ?"
            );
            $actualizaStock->execute([$prenda['cantidad'], $idProducto]);
        }

        return true;
    }

    /*
     * getUltimoId() devuelve el id del pedido recién creado
     Lo usamos en confirmar_transaccion.php para mandárselo al JavaScript
     y que EmailJS lo incluya en el email de confirmación al cliente.
     */
    public function getUltimoId(){
        return $this->ultimoId;
    }
}
?>
