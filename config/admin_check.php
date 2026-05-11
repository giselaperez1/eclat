<?php
/*
 * admin_check.php - Control de acceso al panel de administración
 Este archivo lo incluimos al principio de todas las páginas del panel admin.
 Su función es comprobar que el usuario tiene rol 'admin' antes de mostrar nada.
 Si no está logueado o no es admin, lo redirigimos al catálogo directamente.
 */

// iniciamos sesión solo si no hay una ya activa
if(session_status() === PHP_SESSION_NONE) session_start();

// comprobamos que exista el rol en sesión y que sea admin'
// si falla cualquiera de las dos, no tiene acceso
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){

    header("Location: ../../catalogo_test.php");
    exit; // el exit para salir 
}
?>
