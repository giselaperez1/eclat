<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #fff; border-bottom: 1px solid #eee;">
    <div class="logo">
        <h2 style="margin: 0; letter-spacing: 3px; text-transform: uppercase; font-weight: 300;">Éclat</h2>
    </div>

    <nav style="display: flex; gap: 30px; align-items: center;">
        <a href="index.php" style="text-decoration: none; color: #333; font-size: 0.9em; text-transform: uppercase;">Inicio</a>
        <a href="catalogo_test.php" style="text-decoration: none; color: #333; font-size: 0.9em; text-transform: uppercase;">Colección</a>
        <a href="ver_carrito.php" style="text-decoration: none; color: #333; font-size: 0.9em; text-transform: uppercase;">Cesta</a>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <!-- Si está logueado, mostramos su nombre y el BOTÓN de cerrar sesión -->
            <span style="font-size: 0.8em; color: #888;">
                Hola, <?php echo isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Invitado'; ?>
            </span>
            <a href="logout.php" style="text-decoration: none; color: #d9534f; font-weight: bold; font-size: 0.8em; border: 1px solid #d9534f; padding: 5px 15px; border-radius: 4px; transition: 0.3s;">
                CERRAR SESIÓN
            </a>
        <?php else: ?>
            <!-- Si no está logueado, botón de acceso -->
            <a href="login_test.php" style="text-decoration: none; color: #fff; background: #000; padding: 8px 20px; font-size: 0.8em; border-radius: 4px;">ACCESO</a>
        <?php endif; ?>
    </nav>
</header>