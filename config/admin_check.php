<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Si no es admin, lo mandamos al catálogo
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: catalogo_test.php");
    exit;
}
?>