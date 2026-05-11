<?php
/*controlador del carrito de compra
 */
if(session_status() === PHP_SESSION_NONE) session_start();

class CarritoController {

    /*
     agregar() - añade una prenda al carrito o incrementa su cantidad si ya estaba
     Controlamos que no se puedan añadir más unidades de las que hay en stock.
     Esto es importante para prendas de edición limitada donde el stock es muy bajo.
     
     @param $idPrenda       id del producto en la bbdd - actúa como clave única en el array
     @param $nombre         nombre de la prenda para mostrarlo en el carrito
     @param $precio         precio unitario en el momento de añadir (puede cambiar después)
     @param $cantidad       unidades que quiere añadir el cliente ahora
     @param $stockDisponible unidades reales en almacén - no podemos vender más de esto
     */
    public function agregar($idPrenda, $nombre, $precio, $cantidad, $stockDisponible){

        // si es la primera vez que el usuario añade algo, inicializamos el array del carrito
        if(!isset($_SESSION['carrito']))
            $_SESSION['carrito'] = [];

        // si el producto ya estaba en el carrito cogemos la cantidad que había, si no 0
        $habia = isset($_SESSION['carrito'][$idPrenda]) ? $_SESSION['carrito'][$idPrenda]['cantidad'] : 0;

        // sumamos lo que había más lo que quiere añadir ahora
        $total = $habia + $cantidad;

        // si la suma supera el stock disponible, limitamos al máximo que hay en la  bd 
        if($total > $stockDisponible)
            $total = $stockDisponible;

        // guardamos o actualizamos la prenda en el array de sesión
        // al usar $idPrenda como índice, si ya existía simplemente lo sobreescribe
        // evitando así que aparezca duplicado en el carrito
        $_SESSION['carrito'][$idPrenda] = [
            "nombre"   => $nombre,
            "precio"   => $precio,
            "cantidad" => $total
        ];
    }

    /*
     eliminar() - quita una prenda específica del carrito
     Comprobamos que exista antes de intentar borrarla 
     */
    public function eliminar($idPrenda){
        if(isset($_SESSION['carrito'][$idPrenda]))
            unset($_SESSION['carrito'][$idPrenda]);
    }

    /*
     vaciarCesta() elimina el carrito completo de la sesión
     Se usa cuando el cliente pulsa "Vaciar cesta" o cuando finaliza la compra
     */
    public function vaciarCesta(){
        unset($_SESSION['carrito']);
    }
}
?>
