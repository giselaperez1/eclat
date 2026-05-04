<?php 
// LÓGICA DE DISEÑO DINÁMICO (Solo para la transparencia y posición en Index)
$pagina_actual = basename($_SERVER['PHP_SELF']);
$es_index = ($pagina_actual == 'index.php');

// Si es index, lo pegamos al fondo de la imagen de portada
$footer_style = $es_index 
    ? 'background-color: transparent; position: absolute; bottom: 0; width: 100%; border: none;' 
    : 'background-color: #000; position: relative; border-top: 1px solid #222;';

$text_shadow = $es_index ? 'text-shadow: 0px 2px 4px rgba(0,0,0,0.8);' : '';
?>

<footer style="<?php echo $footer_style; ?> color: #fff; padding: 40px; font-family: 'Segoe UI', sans-serif; z-index: 10; box-sizing: border-box;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 40px;">
        
        <!-- Marca -->
        <div style="flex: 1; min-width: 250px;">
            <h3 style="text-transform: uppercase; letter-spacing: 4px; font-weight: 300; margin-bottom: 20px; <?php echo $text_shadow; ?>">Éclat</h3>
            <p style="font-size: 0.85em; color: rgba(255,255,255,0.8); line-height: 1.8; <?php echo $text_shadow; ?>">
                Redefiniendo la elegancia contemporánea a través de la alta costura artesanal.
            </p>
        </div>

        <!-- Enlaces -->
        <div style="flex: 0.5; min-width: 150px;">
            <h4 style="text-transform: uppercase; font-size: 0.75em; letter-spacing: 2px; color: #b59410; margin-bottom: 20px; <?php echo $text_shadow; ?>">Información</h4>
            <ul style="list-style: none; padding: 0; font-size: 0.85em; line-height: 2;">
                <li><a href="#" class="footer-link" style="<?php echo $text_shadow; ?>">Preguntas Frecuentes</a></li>
                <li><a href="#" class="footer-link" style="<?php echo $text_shadow; ?>">Aviso Legal</a></li>
                <li><a href="#" class="footer-link" style="<?php echo $text_shadow; ?>">Política de Privacidad</a></li>
            </ul>
        </div>

        <!-- Newsletter -->
        <div style="flex: 1; min-width: 250px;">
            <h4 style="text-transform: uppercase; font-size: 0.75em; letter-spacing: 2px; color: #b59410; margin-bottom: 20px; <?php echo $text_shadow; ?>">Newsletter</h4>
            <p style="font-size: 0.8em; color: rgba(255,255,255,0.7); margin-bottom: 15px; <?php echo $text_shadow; ?>">Suscríbase para recibir acceso anticipado a nuestras nuevas colecciones.</p>
            <a href="#" style="color: #fff; text-decoration: underline; font-size: 0.8em; text-transform: uppercase; letter-spacing: 1px; <?php echo $text_shadow; ?>">Unirse al Club Éclat</a>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px; font-size: 0.7em; color: rgba(255,255,255,0.5); letter-spacing: 1px; <?php echo $text_shadow; ?>">
        &copy; <?php echo date('Y'); ?> ÉCLAT HAUTE COUTURE.
    </div>
</footer>

<style>
    .footer-link {
        text-decoration: none;
        color: rgba(255,255,255,0.8);
        transition: 0.3s;
    }
    .footer-link:hover {
        color: #b59410;
    }
</style>