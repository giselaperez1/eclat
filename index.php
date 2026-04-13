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
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Segoe UI', sans-serif; }
        
        /* Fondo de Pantalla Completa */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&q=80&w=2070'); 
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 5em;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 15px;
            margin: 0;
            animation: fadeIn 2s ease-in;
        }

        .hero-content p {
            font-size: 1.2em;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 40px;
            color: #d4af37; /* Dorado */
        }

        /* El Botón Dorado de Lujo */
        .btn-explorar {
            text-decoration: none;
            color: #fff;
            border: 2px solid #d4af37;
            padding: 15px 40px;
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 3px;
            transition: 0.4s;
            background: transparent;
        }

        .btn-explorar:hover {
            background: #d4af37;
            color: #000;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.6);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Incluimos el header para que el usuario pueda hacer login desde la portada -->
    <?php include_once 'views/layout/header.php'; ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Éclat</h1>
            <p>Donde la elegancia se encuentra con el arte</p>
            <a href="catalogo_test.php" class="btn-explorar">Explorar Colección</a>
        </div>
    </section>

</body>
</html>