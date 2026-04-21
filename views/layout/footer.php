<footer class="footer-eclat">
    <div class="footer-content">
        <div class="footer-section">
            <h3 style="color: #fff; font-weight: 100; letter-spacing: 5px; text-transform: uppercase; margin-bottom: 25px; font-size: 1.6em;">ÉCLAT</h3>
            <p>Nuestra boutique representa la cumbre de la alta costura contemporánea, ofreciendo piezas únicas para quienes no aceptan menos que la perfección.</p>
        </div>
        
        <div class="footer-section">
            <h4>Contacto</h4>
            <ul>
                <li>Teléfono: <a href="tel:+34912345678" style="color:var(--dorado-eclat);">+34 912 345 678</a></li>
                <li>Email: <a href="mailto:atelier@eclatboutique.com" style="color:var(--dorado-eclat);">atelier@eclatboutique.com</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Newsletter</h4>
            <p>Suscríbase para recibir acceso anticipado a nuestras nuevas colecciones.</p>
        <div style="margin-top: 20px;">
            <a href="registro_test.php" class="btn-club">Unirse al Club Éclat</a>
        </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 ÉCLAT Haute Couture
    </div>
</footer>

<style>
    :root {
        --negro-profundo: #000000;
        --dorado-eclat: #b59410;
        --blanco-puro: #ffffff;
    }

    .footer-eclat {
        background-color: var(--negro-profundo);
        color: var(--blanco-puro); /* Todo el texto ahora es blanco */
        padding: 80px 0 40px 0;
        font-family: 'Segoe UI', sans-serif;
        border-top: 1px solid #111;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 60px;
        padding: 0 40px;
    }

    .footer-section {
        flex: 1;
        min-width: 280px;
    }

    .footer-section h4 {
        color: var(--dorado-eclat);
        text-transform: uppercase;
        font-size: 0.8em;
        letter-spacing: 3px;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .footer-section p {
        font-size: 0.9em;
        line-height: 1.8;
        font-weight: 300;
        color: var(--blanco-puro);
    }

    .footer-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-section ul li {
        margin-bottom: 15px;
        font-size: 0.9em;
        color: var(--blanco-puro);
    }

    .footer-section a {
        color: inherit;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-section a:hover {
        color: var(--dorado-eclat);
    }

.btn-club {
    color: #fff; 
    font-size: 0.75em; 
    text-transform: uppercase; 
    letter-spacing: 2px; 
    border-bottom: 1px solid var(--dorado-eclat); 
    cursor: pointer; 
    padding-bottom: 5px;
    text-decoration: none; /* Esto quita el subrayado azul de enlace */
    transition: 0.3s;
    display: inline-block; /* Para que el padding y el borde funcionen bien */
}

.btn-club:hover {
    color: var(--dorado-eclat);
    border-bottom-color: #fff; /* El borde cambia a blanco al pasar el ratón */
}

    .footer-bottom {
        text-align: center;
        margin-top: 100px;
        padding: 30px 0;
        border-top: 1px solid #111;
        font-size: 0.7em;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #555;
    }
</style>