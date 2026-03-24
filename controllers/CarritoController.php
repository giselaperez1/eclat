<?php
session_start(); // Fundamental para que la sesión de la boutique siga activa

class CarritoController {
    
    // Método principal para meter ropa en la cesta
    public function agregar($id, $nombre, $precio, $cantidad, $stockMaximo) {
        
        // Si no hay cesta todavía, la creamos vacía
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        // Miramos si la prenda ya está en la cesta para sumar cantidades
        $cantidadActual = isset($_SESSION['carrito'][$id]) ? $_SESSION['carrito'][$id]['cantidad'] : 0;
        $totalTrasSumar = $cantidadActual + $cantidad;

        // SEGURIDAD: Comprobamos que el cliente no compre más de lo que hay en el almacén
        if ($totalTrasSumar > $stockMaximo) {
            // Si intenta pasarse, le dejamos el máximo que tenemos disponible
            $_SESSION['carrito'][$id] = array(
                "nombre" => $nombre,
                "precio" => $precio,
                "cantidad" => $stockMaximo
            );
        } else {
            // Si hay stock suficiente, guardamos la cantidad solicitada
            $_SESSION['carrito'][$id] = array(
                "nombre" => $nombre,
                "precio" => $precio,
                "cantidad" => $totalTrasSumar
            );
        }
    }

    // Método para quitar una prenda específica (por si se arrepiente)
    public function eliminar($id) {
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }
    }

    // Método para limpiar toda la cesta de golpe
    public function vaciarCesta() {
        if (isset($_SESSION['carrito'])) {
            unset($_SESSION['carrito']);
        }
    }
}
?>