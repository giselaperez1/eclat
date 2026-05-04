<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// LÓGICA DE DISEÑO DINÁMICO
$pagina_actual = basename($_SERVER['PHP_SELF']);
$es_index = ($pagina_actual == 'index.php');

$header_bg = $es_index ? 'background-color: rgba(0,0,0,0.3);' : 'background-color: #000;';
$header_position = $es_index ? 'position: absolute;' : 'position: relative;';
?>

<style>
    /* RESET CRÍTICO: Elimina huecos blancos en los bordes de la pantalla */
    body {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden; /* Evita scroll horizontal innecesario */
    }

    .nav-link {
        text-decoration: none; 
        color: #fff; 
        font-size: 0.85em; 
        text-transform: uppercase; 
        letter-spacing: 1.5px;
        transition: 0.3s;
        font-weight: 300;
        text-shadow: 0px 2px 4px rgba(0,0,0,0.3);
    }
    .nav-link:hover {
        color: #b59410;
        text-shadow: none;
    }
    .btn-acceso {
        text-decoration: none; 
        color: #000; 
        background: #fff; 
        padding: 8px 18px; 
        font-size: 0.75em; 
        font-weight: bold; 
        text-transform: uppercase; 
        transition: 0.3s;
        margin-left: 10px;
    }
    .btn-acceso:hover {
        background: #b59410;
        color: #fff;
    }
</style>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; <?php echo $header_bg; ?> <?php echo $header_position; ?> top: 0; left: 0; width: 100%; border: none; margin: 0; height: 70px; z-index: 1000; box-sizing: border-box; transition: background-color 0.5s ease;">
    
    <div class="logo" style="position: absolute; left: 40px; top: 50%; transform: translateY(-50%);">
        <a href="index.php" style="text-decoration: none;">
            <img src="assets/img/logo.png" alt="Éclat Logo" style="width: 150px; height: auto; display: block; filter: drop-shadow(0px 0px 5px rgba(0,0,0,0.5));">
        </a>
    </div>

    <div style="width: 160px;"></div>

    <nav style="display: flex; gap: 22px; align-items: center;">
        <a href="index.php" class="nav-link">Inicio</a>
        <a href="catalogo_test.php" class="nav-link">Colección</a>
        <a href="sobre_nosotros.php" class="nav-link">Nosotros</a>
        <a href="contacto.php" class="nav-link">Contacto</a>
        
        <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.2); margin: 0 10px;"></div>

        <a href="ver_carrito.php" class="nav-link" style="position: relative;">
            Cesta
            <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                <span style="position: absolute; top: -10px; right: -12px; background: #b59410; color: #fff; font-size: 0.6em; padding: 2px 5px; border-radius: 50%; min-width: 10px; text-align: center;">
                    <?php echo count($_SESSION['carrito']); ?>
                </span>
            <?php endif; ?>
        </a>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="views/admin/admin_index.php" style="text-decoration: none; color: #b59410; font-weight: bold; font-size: 0.7em; border: 1px solid #b59410; padding: 5px 12px; border-radius: 2px; text-transform: uppercase; transition: 0.3s; margin-left: 10px;">
                    Panel Control
                </a>
            <?php endif; ?>

            <span style="font-size: 0.7em; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-left: 10px;">
                Hola, <strong style="color: #fff;"><?php echo (isset($_SESSION['usuario_nombre'])) ? $_SESSION['usuario_nombre'] : 'Admin'; ?></strong>
            </span>
            
            <a href="logout.php" style="text-decoration: none; color: #ff4d4d; font-weight: bold; font-size: 0.7em; border: 1px solid #ff4d4d; padding: 5px 12px; border-radius: 2px; text-transform: uppercase; transition: 0.3s; margin-left: 5px;">
                Salir
            </a>
        <?php else: ?>
            <a href="login_test.php" class="btn-acceso">Acceso</a>
        <?php endif; ?>
    </nav>
</header>