<?php
/*
 * logout.php,Cierre de sesión
 */
session_start();

//vaciamos todos los datos de sesión (carrito, usuario, rol, email...)
$_SESSION = [];

//si la sesión usa cookie,enviamos la misma cookie al navegador pero con fecha de expiración en el pasado,para que el navegador la borre inmediatamente
if(ini_get("session.use_cookies")){
    $p = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,  // fecha para forzar que expire
        $p["path"], $p["domain"], $p["secure"], $p["httponly"]
    );
}

//destruimos la sesión en el servidor
session_destroy();

// redirigimos al catálogo n y el usuario ya no aparece como logueado
header("Location: catalogo_test.php");
exit;
?>
