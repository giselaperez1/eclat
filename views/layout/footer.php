<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
$es_index      = ($pagina_actual == 'index.php');
$es_admin      = (strpos($_SERVER['PHP_SELF'], '/views/admin/') !== false);
$prefijo       = $es_admin ? '../../' : '';

// en el index el footer va pegado al hero - en el resto va abajo del flujo normal
$footer_style  = $es_index
    ? 'background-color: rgba(0,0,0,0.75); position: relative;'
    : 'background-color: #000; position: relative; border-top: 1px solid #222;';
?>
<style>
    .footer-wrap {
        color: #fff;
        padding: 50px 40px 30px;
        font-family: 'Segoe UI', sans-serif;
        box-sizing: border-box;
    }
    .footer-grid {
        max-width: 1200px; margin: 0 auto;
        display: flex; justify-content: space-between;
        flex-wrap: wrap; gap: 40px;
    }
    .footer-col { flex: 1; min-width: 200px; }
    .footer-col h3 { text-transform:uppercase; letter-spacing:4px; font-weight:300; margin-bottom:15px; }
    .footer-col h4 { text-transform:uppercase; font-size:.75em; letter-spacing:2px; color:#b59410; margin-bottom:15px; }
    .footer-col p  { font-size:.85em; color:rgba(255,255,255,.7); line-height:1.8; }
    .footer-col ul { list-style:none; padding:0; font-size:.85em; line-height:2.2; margin:0; }
    .footer-link   { text-decoration:none; color:rgba(255,255,255,.75); transition:.3s; }
    .footer-link:hover { color:#b59410; }
    .footer-copy   { text-align:center; margin-top:40px; font-size:.7em; color:rgba(255,255,255,.4); letter-spacing:1px; }

    @media (max-width: 768px) {
        .footer-wrap { padding: 40px 20px 25px; text-align: center; }
        .footer-grid { flex-direction: column; gap: 30px; align-items: center; }
        .footer-col  { min-width: unset; width: 100%; }
        .footer-col ul { padding: 0; }
    }
</style>

<footer class="footer-wrap" style="<?php echo $footer_style; ?>">
    <div class="footer-grid">

        <div class="footer-col">
            <h3>Éclat</h3>
            <p>Redefiniendo la elegancia contemporánea a través de la alta costura artesanal.</p>
        </div>

        <div class="footer-col">
            <h4>Información</h4>
            <ul>
                <li><a href="<?php echo $prefijo; ?>faq.php"            class="footer-link">Preguntas Frecuentes</a></li>
                <li><a href="<?php echo $prefijo; ?>aviso_legal.php"    class="footer-link">Aviso Legal</a></li>
                <li><a href="<?php echo $prefijo; ?>privacidad.php"     class="footer-link">Política de Privacidad</a></li>
                <li><a href="<?php echo $prefijo; ?>sobre_nosotros.php" class="footer-link">Sobre Nosotros</a></li>
                <li><a href="<?php echo $prefijo; ?>contacto.php"       class="footer-link">Contacto</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Newsletter</h4>
            <p>Suscríbase para recibir acceso anticipado a nuestras nuevas colecciones.</p>
            <a href="<?php echo $prefijo; ?>registro_test.php" class="footer-link" style="text-decoration:underline; font-size:.8em; text-transform:uppercase; letter-spacing:1px;">
                Unirse al Club Éclat
            </a>
        </div>

    </div>
    <div class="footer-copy">&copy; <?php echo date('Y'); ?> ÉCLAT HAUTE COUTURE.</div>
</footer>
