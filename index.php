<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Éclat - Alta Costura</title>
    <style>
        body, html { margin: 0; padding: 0; background-color: #000; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }

        .hero-section {
            /* Subimos a 0.5 arriba y 0.8 abajo: Máximo contraste */
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), 
                              url('assets/img/portada.png'); 
            background-size: cover;
            background-position: center 20%; 
            background-repeat: no-repeat;
            height: 90vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .hero-content {
            max-width: 1000px;
        }

        .hero-content h1 { 
            font-size: 8em; /* Impacto masivo */
            font-weight: 100; 
            letter-spacing: 35px; /* Espaciado premium */
            text-transform: uppercase; 
            margin: 0;
            line-height: 1;
            /* Resplandor suave para que la letra 'brille' sobre el negro */
            text-shadow: 0px 0px 20px rgba(255,255,255,0.2), 0px 10px 50px rgba(0,0,0,1);
            padding-left: 35px;
        }

        .hero-content p { 
            color: #fff; 
            letter-spacing: 12px; 
            text-transform: uppercase; 
            margin-top: 45px;
            margin-bottom: 65px;
            font-size: 0.85em;
            font-weight: 300;
            /* Línea dorada sólida para cortar la oscuridad */
            border-top: 2px solid #b59410;
            padding-top: 25px;
            display: inline-block;
            text-shadow: 0px 2px 15px rgba(0,0,0,1);
        }
            
        .btn-explorar {
            display: block;
            margin: 0 auto;
            width: fit-content;
            text-decoration: none; 
            color: #fff; 
            border: 1px solid #fff;
            padding: 22px 70px; 
            text-transform: uppercase; 
            letter-spacing: 6px; 
            font-size: 0.75em;
            font-weight: 600;
            transition: all 0.5s ease;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px); /* Aumentamos el desenfoque del fondo */
        }

        .btn-explorar:hover {
            background: #fff;
            color: #000;
            transform: translateY(-5px); /* Pequeño salto de elegancia */
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body>

    <?php include_once 'views/layout/header.php'; ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Éclat</h1>
            <p>Donde la elegancia se encuentra con el arte</p>
            <a href="catalogo_test.php" class="btn-explorar">Explorar Colección</a>
        </div>
    </section>

    <?php include_once 'views/layout/footer.php'; ?>

</body>
</html>