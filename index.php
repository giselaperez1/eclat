<?php
// Inicia la sesión de usuario solo si no ha sido iniciada previamente.
if(session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éclat - Alta Costura</title>
    <style>
        /* fondo negro y tipografía moderna */
        body, html { 
            margin:0; 
            padding:0; 
            background:#000; 
            font-family:'Segoe UI', sans-serif; 
            overflow-x:hidden; /* Evita el scroll horizontal*/
        }

        /* Sección principa ,imagen de fondo a pantalla completa */
        .hero-section {
            /* Degradado oscuro sobre la imagen para que el texto se pueda leer*/
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)),
                              url('assets/img/portada.png');
            background-size: cover;      /* La imagen cubre todo el espacio disponible */
            background-position: center 20%; /* Ajusta la posición vertical de la foto */
            background-repeat: no-repeat;
            min-height: 90vh;            /* Ocupa el 90% de la altura de la pantalla */
            width: 100%;
            display: flex;
            align-items: center;         /* Centra el contenido verticalmente */
            justify-content: center;    /* Centra el contenido horizontalmente */
            text-align: center;
            color: #fff;
            padding: 100px 20px 60px;    /* Margen para no chocar con el header*/
            box-sizing: border-box;
        }

        .hero-content { max-width: 1000px; width: 100%; }

        /* Título principal*/
        .hero-content h1 {
            font-size: clamp(3em, 10vw, 8em); 
            font-weight: 100;
            letter-spacing: clamp(8px, 3vw, 35px); /* Espaciado entre letras*/
            text-transform: uppercase;
            margin: 0;
            line-height: 1;
            /* Sombras para dar profundidad al texto */
            text-shadow: 0 0 20px rgba(255,255,255,.2), 0 10px 50px rgba(0,0,0,1);
            padding-left: clamp(5px, 2vw, 35px);
        }

        /* frase bajo del título */
        .hero-content p {
            color: #fff;
            letter-spacing: clamp(3px, 2vw, 12px);
            text-transform: uppercase;
            margin: 35px auto 50px;
            font-size: clamp(.65em, 2vw, .85em);
            font-weight: 300;
            border-top: 2px solid #b59410; /* Línea dorada decorativa superior */
            padding-top: 20px;
            display: inline-block;
            text-shadow: 0 2px 15px rgba(0,0,0,1);
        }

        /* Botón de llamada a la acción*/
        .btn-explorar {
            display: inline-block;
            text-decoration: none; 
            color: #fff;
            border: 1px solid #fff;
            padding: clamp(14px, 3vw, 22px) clamp(30px, 6vw, 70px);
            text-transform: uppercase;
            letter-spacing: clamp(3px, 1vw, 6px);
            font-size: clamp(.65em, 2vw, .75em);
            font-weight: 600;
            transition: all .5s; /* Animación suave al pasar el ratón */
            background: rgba(0,0,0,.6);
            backdrop-filter: blur(8px); /* Efecto de cristal */
        }

        /* Efecto al pasar el raton por el botón */
        .btn-explorar:hover {
            background: #fff; 
            color: #000;
            transform: translateY(-5px); /* El botón sube  */
            box-shadow: 0 20px 40px rgba(0,0,0,.6);
        }
    </style>
</head>
<body>

    <!-- Inserto menu en navegación superior -->
    <?php include_once 'views/layout/header.php'; ?>

    <!-- Contenido visual principal -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Éclat</h1>
            <p>Donde la elegancia se encuentra con el arte</p>
            <br>
            <a href="catalogo_test.php" class="btn-explorar">Explorar Colección</a>
        </div>
    </section>

    <!-- Inserto footer -->
    <?php include_once 'views/layout/footer.php'; ?>

</body>
</html>