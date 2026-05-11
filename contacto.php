<?php
// página de contacto con formulario y mapa de Google
if(session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Éclat</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* flex column en el body para que el footer siempre quede abajo */
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        /* usamos un wrapper en vez de body para no moleste con el header.php */
        .contacto-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .pagina-contacto {
            flex: 1; /* ocupa todo el espacio disponible entre header y footer */
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 80px 40px 60px;
        }
/* estilo titulo de contacto  */
        .contacto-titulo {
            text-transform: uppercase;
            font-weight: 300;
            letter-spacing: 4px;
            text-align: center;
            margin-bottom: 60px;
            font-size: 2em;
        }

        .contacto-grid {
            display: flex;
            gap: 60px;
            align-items: flex-start;
        }

        .col-formulario,
        .col-info { flex: 1; }

        .col-titulo {
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-weight: 400;
        }

        .campo {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            font-family: inherit;
            font-size: .95em;
            transition: .25s;
            margin-bottom: 18px;
            display: block;
        }
        .campo:focus { border-color: #b59410; }

        /* estilo de boton de enviar   */
        .btn-enviar {
            background: #000; color: #fff;
            padding: 15px; width: 100%;
            border: none; text-transform: uppercase;
            letter-spacing: 2px; font-weight: bold;
            cursor: pointer; transition: .3s;
            font-family: inherit;
        }
        .btn-enviar:hover { background: #b59410; }

        .info-dato {
            color: #666;
            margin-bottom: 12px;
            font-size: .95em;
            line-height: 1.6;
        }
/* estilos del mapa  */
        .mapa-wrap {
            width: 100%;
            height: 300px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #eee;
            margin-top: 25px;
        }
        /* proporciones del mapa */
        .mapa-wrap iframe { width: 100%; height: 100%; border: 0; }

        /* Responsive */
        @media (max-width: 768px) {
            .pagina-contacto    { padding: 60px 20px 40px; text-align: center; }
            .contacto-titulo    { font-size: 1.4em; letter-spacing: 2px; margin-bottom: 35px; }
            .contacto-grid      { flex-direction: column; gap: 40px; align-items: center; }
            .col-formulario,
            .col-info           { width: 100%; max-width: 420px; text-align: left; }
            .col-titulo         { text-align: center; }
            .info-dato          { text-align: left; }
            .btn-enviar         { max-width: 100%; }
        }

        @media (max-width: 480px) {
            .pagina-contacto { padding: 50px 15px 30px; }
            .col-formulario,
            .col-info        { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="contacto-wrapper">
    <!-- incluimos el header  -->
<?php include_once 'views/layout/header.php'; ?>

<div class="pagina-contacto">
    <h1 class="contacto-titulo">Contacto</h1>

    <div class="contacto-grid">

        <!-- Formulario  con uss datos para el contacto-->
        <div class="col-formulario">
            <h2 class="col-titulo">Envíenos un mensaje</h2>
            <form action="#" method="POST">
                <input  type="text"  class="campo" placeholder="Nombre completo" required>
                <input  type="email" class="campo" placeholder="Correo electrónico" required>
                <textarea            class="campo" placeholder="¿En qué podemos ayudarle?" rows="5"></textarea>
                <button type="submit" class="btn-enviar">Enviar Solicitud</button>
            </form>
        </div>

        <!-- Info  de conatcto y mapa -->
        <div class="col-info">
            <h2 class="col-titulo">Éclat Haute Couture</h2>
            <p class="info-dato"><strong>Dirección:</strong> Puerta de Purchena, 33, 04003 Almería</p>
            <p class="info-dato"><strong>Horario:</strong> Lunes a Sábado · 10:00 – 20:30</p>
            <p class="info-dato"><strong>Teléfono:</strong> +34 912 345 678</p>
<!-- agrego el mapa  -->
            <div class="mapa-wrap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3193.031927422955!2d-2.4640853000000003!3d36.84171140000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7076009610863b%3A0xa4c065baff171ec3!2sPrta%20de%20Purchena%2C%2033%2C%2004003%20Almer%C3%ADa!5e0!3m2!1ses!2ses!4v1777889875541!5m2!1ses!2ses"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</div>
<!-- inclutyo el footer  -->
<?php include_once 'views/layout/footer.php'; ?>
</div>

</body>
</html>
