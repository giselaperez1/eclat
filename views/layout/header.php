<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; background-color: #000; border: none; margin: 0; height: 60px; position: relative; z-index: 1000;">
    
    <div class="logo" style="position: absolute; left: 40px; top: 50%; transform: translateY(-50%);">
        <a href="index.php" style="text-decoration: none;">
            <img src="assets/img/logo.png" alt="Éclat Logo" style="width: 150px; height: auto; display: block; filter: drop-shadow(0px 0px 5px rgba(0,0,0,0.5));">
        </a>
    </div>

    <!-- Espaciador para equilibrar el logo absoluto -->
    <div style="width: 160px;"></div>

    <nav style="display: flex; gap: 20px; align-items: center;">
        <a href="index.php" class="nav-link">Inicio</a>
        <a href="catalogo_test.php" class="nav-link">Colección</a>
        <!-- NUEVOS ENLACES -->
        <a href="sobre_nosotros.php" class="nav-link">Nosotros</a>
        <a href="contacto.php" class="nav-link">Contacto</a>
        
        <div style="width: 1px; height: 20px; background: #333; margin: 0 10px;"></div>

        <a href="ver_carrito.php" class="nav-link">Cesta</a>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="views/admin/admin_index.php" style="text-decoration: none; color: #b59410; font-weight: bold; font-size: 0.7em; border: 1px solid #b59410; padding: 4px 10px; border-radius: 2px; text-transform: uppercase; transition: 0.3s;">
                    Panel Control
                </a>
            <?php endif; ?>

            <span style="font-size: 0.7em; color: #aaa; text-transform: uppercase; letter-spacing: 1px;">
                Hola, <strong style="color: #fff;"><?php echo (isset($_SESSION['usuario_nombre'])) ? $_SESSION['usuario_nombre'] : 'Admin'; ?></strong>
            </span>
            
            <a href="logout.php" style="text-decoration: none; color: #ff4d4d; font-weight: bold; font-size: 0.7em; border: 1px solid #ff4d4d; padding: 4px 10px; border-radius: 2px; text-transform: uppercase; transition: 0.3s;">
                CERRAR SESIÓN
            </a>
        <?php else: ?>
            <a href="login_test.php" style="text-decoration: none; color: #000; background: #fff; padding: 6px 15px; font-size: 0.75em; font-weight: bold; text-transform: uppercase; transition: 0.3s;" onmouseover="this.style.background='#b59410'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#000';">ACCESO</a>
        <?php endif; ?>
    </nav>
</header>

<style>
    .nav-link {
        text-decoration: none; 
        color: #fff; 
        font-size: 0.8em; 
        text-transform: uppercase; 
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .nav-link:hover {
        color: #b59410;
    }
</style>