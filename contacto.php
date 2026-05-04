<?php include_once 'views/layout/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px; font-family: 'Segoe UI', sans-serif;">
    <h1 style="text-transform: uppercase; font-weight: 300; letter-spacing: 4px; text-align: center; margin-bottom: 60px;">Contacto</h1>

    <div style="display: flex; gap: 80px; flex-wrap: wrap;">
        <!-- Formulario -->
        <div style="flex: 1; min-width: 350px;">
            <h2 style="font-size: 1.2em; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Envíenos un mensaje</h2>
            <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <input type="text" placeholder="Nombre completo" style="padding: 15px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                <input type="email" placeholder="Correo electrónico" style="padding: 15px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                <textarea placeholder="¿En qué podemos ayudarle?" rows="5" style="padding: 15px; border: 1px solid #ddd; border-radius: 4px; outline: none; font-family: inherit;"></textarea>
                <button type="submit" style="background: #000; color: #fff; padding: 15px; border: none; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; cursor: pointer; transition: 0.3s;">Enviar Solicitud</button>
            </form>
        </div>

        <!-- Ubicación y Mapa -->
        <div style="flex: 1; min-width: 350px;">
            <h2 style="font-size: 1.2em; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Éclat Haute Couture</h2>
            <p style="color: #666; margin-bottom: 10px;"><strong>Dirección:</strong> Prta de Purchena, 33, 04003 Almería</p>
            <p style="color: #666; margin-bottom: 10px;"><strong>Horario:</strong> Lunes a Sábado: 10:00 - 20:30</p>
            <p style="color: #666; margin-bottom: 30px;"><strong>Teléfono:</strong> +34 912 345 678</p>
            
            <!-- Mapa de Google (Iframe) -->
            <div style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden; border: 1px solid #eee;">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3193.031927422955!2d-2.4640853000000003!3d36.84171140000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7076009610863b%3A0xa4c065baff171ec3!2sPrta%20de%20Purchena%2C%2033%2C%2004003%20Almer%C3%ADa!5e0!3m2!1ses!2ses!4v1777889875541!5m2!1ses!2ses" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>

<style>
    input:focus, textarea:focus { border-color: #b59410 !important; }
    form button:hover { background: #b59410 !important; }
</style>

<?php include_once 'views/layout/footer.php'; ?>