<?php
if(session_status() === PHP_SESSION_NONE) session_start();

$pagina_actual = basename($_SERVER['PHP_SELF']);
$es_index      = ($pagina_actual == 'index.php');
$header_bg     = $es_index ? 'background-color: rgba(0,0,0,0.3);' : 'background-color: #000;';
$header_pos    = $es_index ? 'position: absolute;' : 'position: relative;';
?>
<style>
    body { margin: 0 !important; padding: 0 !important; overflow-x: hidden; }

    .nav-link {
        text-decoration: none; color: #fff;
        font-size: .85em; text-transform: uppercase;
        letter-spacing: 1.5px; transition: .3s;
        font-weight: 300;
        white-space: nowrap;
    }
    .nav-link:hover { color: #b59410; }

    .btn-acceso {
        text-decoration: none; color: #000; background: #fff;
        padding: 8px 18px; font-size: .75em; font-weight: bold;
        text-transform: uppercase; transition: .3s; white-space: nowrap;
    }
    .btn-acceso:hover { background: #b59410; color: #fff; }

    /* --- hamburguesa --- */
    .hamburger {
        display: none;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        z-index: 2000;
        background: none;
        border: none;
        padding: 8px;
        margin-left: auto;
    }
    .hamburger span {
        display: block; width: 25px; height: 2px;
        background: #fff; transition: .3s; border-radius: 2px;
    }
    /* animación X al abrir */
    .hamburger.activo span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.activo span:nth-child(2) { opacity: 0; }
    .hamburger.activo span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* --- overlay oscuro --- */
    #nav-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.6);
        z-index: 1500;
    }
    #nav-overlay.visible { display: block; }

    /* --- menú desplegable móvil --- */
    #nav-menu {
        display: flex;
        gap: 22px;
        align-items: center;
    }

    @media (max-width: 860px) {
        .hamburger { display: flex; }
        .nav-separador { display: none; }

        #nav-menu {
            /* oculto por defecto en móvil */
            display: none;
            flex-direction: column;
            align-items: flex-start;
            gap: 0;

            position: fixed;
            top: 0; right: 0;
            width: 280px; height: 100vh;
            background: #111;
            z-index: 1600;
            padding: 80px 30px 30px;
            box-sizing: border-box;
            overflow-y: auto;
            box-shadow: -4px 0 20px rgba(0,0,0,.6);
        }
        #nav-menu.abierto { display: flex; }

        #nav-menu .nav-link {
            font-size: 1em;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,.08);
            width: 100%;
        }
        #nav-menu .btn-acceso {
            margin: 16px 0 0 0;
            width: 100%;
            text-align: center;
            padding: 12px;
        }
        #nav-menu span[style*="font-size:.7em"] {
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,.08);
            width: 100%;
            font-size: .85em !important;
        }
        #nav-menu a[style*="ff4d4d"],
        #nav-menu a[style*="b59410"] {
            margin: 10px 0 0 0 !important;
            width: 100%;
            text-align: center;
            display: block;
        }
    }

    @media (max-width: 480px) {
        .logo img { width: 110px !important; }
    }
</style>

<header style="display:flex; justify-content:space-between; align-items:center; padding:0 40px; <?php echo $header_bg; ?> <?php echo $header_pos; ?> top:0; left:0; width:100%; height:70px; z-index:1000; box-sizing:border-box; transition:background-color .5s;">

    <!-- Logo -->
    <a href="index.php" style="text-decoration:none; flex-shrink:0;">
        <img src="assets/img/logo.png" alt="Éclat" style="width:140px; height:auto; display:block; filter:drop-shadow(0 0 5px rgba(0,0,0,.5));">
    </a>

    <!-- Overlay (cierra el menú al tocar fuera) -->
    <div id="nav-overlay" onclick="cerrarMenu()"></div>

    <!-- Nav -->
    <nav id="nav-menu">
        <a href="index.php"          class="nav-link">Inicio</a>
        <a href="catalogo_test.php"  class="nav-link">Colección</a>
        <a href="sobre_nosotros.php" class="nav-link">Nosotros</a>
        <a href="contacto.php"       class="nav-link">Contacto</a>

        <div class="nav-separador" style="width:1px; height:20px; background:rgba(255,255,255,.2); margin:0 5px;"></div>

        <a href="ver_carrito.php" class="nav-link" style="position:relative;">
            Cesta
            <?php if(!empty($_SESSION['carrito'])): ?>
                <span style="position:absolute; top:-8px; right:-14px; background:#b59410; color:#fff; font-size:.6em; padding:2px 5px; border-radius:50%; min-width:10px; text-align:center;">
                    <?php echo count($_SESSION['carrito']); ?>
                </span>
            <?php endif; ?>
        </a>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="views/admin/admin_index.php" style="text-decoration:none; color:#b59410; font-weight:bold; font-size:.7em; border:1px solid #b59410; padding:5px 12px; text-transform:uppercase; white-space:nowrap;">
                    Panel
                </a>
            <?php endif; ?>
            <span style="font-size:.7em; color:rgba(255,255,255,.7); text-transform:uppercase; letter-spacing:1px; white-space:nowrap;">
                Hola, <strong style="color:#fff;"><?php echo $_SESSION['usuario_nombre'] ?? 'Admin'; ?></strong>
            </span>
            <a href="logout.php" style="text-decoration:none; color:#ff4d4d; font-weight:bold; font-size:.7em; border:1px solid #ff4d4d; padding:5px 12px; text-transform:uppercase; white-space:nowrap;">
                Salir
            </a>
        <?php else: ?>
            <a href="login_test.php" class="btn-acceso">Acceso</a>
        <?php endif; ?>
    </nav>

    <!-- Botón hamburguesa -->
    <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>

</header>

<script>
function toggleMenu() {
    var menu  = document.getElementById('nav-menu');
    var overlay = document.getElementById('nav-overlay');
    var btn   = document.getElementById('hamburger');
    menu.classList.toggle('abierto');
    overlay.classList.toggle('visible');
    btn.classList.toggle('activo');
    // bloquea scroll del body mientras el menú está abierto
    document.body.style.overflow = menu.classList.contains('abierto') ? 'hidden' : '';
}
function cerrarMenu() {
    document.getElementById('nav-menu').classList.remove('abierto');
    document.getElementById('nav-overlay').classList.remove('visible');
    document.getElementById('hamburger').classList.remove('activo');
    document.body.style.overflow = '';
}
// cerrar con tecla Escape
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') cerrarMenu(); });
</script>
