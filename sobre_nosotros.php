<?php
// página sobre nosotros 
if(session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Éclat Haute Couture</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; color: #333; }

        .nosotros-wrap { max-width: 1200px; margin: 0 auto; padding: 80px 40px; }

        /* estilos cabecera */
        .cabecera-titulo { text-align: center; margin-bottom: 100px; }
        .cabecera-titulo span { color: #b59410; text-transform: uppercase; letter-spacing: 5px; font-size: .9em; font-weight: 600; }
        .cabecera-titulo h1 { text-transform: uppercase; font-weight: 100; letter-spacing: 12px; font-size: 3.5em; margin: 20px 0; }
        .cabecera-titulo .linea { width: 80px; height: 1px; background: #b59410; margin: 0 auto; }

        /* secciones flex */
        .seccion { display: flex; align-items: center; gap: 80px; margin-bottom: 120px; }
        .seccion-img { flex: 1; min-width: 0; }
        .seccion-img img { width: 100%; height: 500px; object-fit: cover; border-radius: 2px; display: block; }
        .seccion-txt { flex: 1; min-width: 0; }
        .seccion-txt h2 { text-transform: uppercase; font-weight: 300; letter-spacing: 3px; font-size: 2em; margin-bottom: 30px; }
        .seccion-txt p  { line-height: 2; color: #666; font-size: 1.1em; margin-bottom: 20px; }

        /* banner filosofía */
        .banner-filosofia {
            background: #000; color: #fff;
            padding: 100px 60px; margin-bottom: 120px;
            text-align: center;
        }
        .banner-filosofia .inner { max-width: 800px; margin: 0 auto; }
        .banner-filosofia h2 { color: #b59410; text-transform: uppercase; letter-spacing: 5px; margin-bottom: 40px; }
        .banner-filosofia .cita { font-size: 1.4em; font-weight: 100; font-style: italic; line-height: 1.8; margin-bottom: 40px; }
        .banner-filosofia p { color: #aaa; line-height: 1.8; }

        /* grid valores */
        .grid-valores { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; }
        .valor-item { text-align: center; padding: 20px; }
        .valor-item .valor-img { height: 300px; background: #eee; margin-bottom: 25px; overflow: hidden; }
        .valor-item .valor-img img { width: 100%; height: 100%; object-fit: cover; }
        .valor-item h3 { text-transform: uppercase; letter-spacing: 2px; font-size: 1.1em; color: #b59410; margin-bottom: 12px; }
        .valor-item p  { color: #888; font-size: .9em; line-height: 1.6; }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .nosotros-wrap { padding: 60px 25px; }
            .cabecera-titulo { margin-bottom: 60px; }
            .cabecera-titulo h1 { font-size: 2.2em; letter-spacing: 6px; }
            .seccion { flex-direction: column; gap: 35px; margin-bottom: 70px; }
            .seccion.invertir { flex-direction: column; }
            .seccion-img img { height: 320px; }
            .seccion-txt h2 { font-size: 1.5em; }
            .banner-filosofia { padding: 60px 25px; }
            .banner-filosofia .cita { font-size: 1.1em; }
        }

        @media (max-width: 480px) {
            .nosotros-wrap { padding: 50px 15px; }
            .cabecera-titulo h1 { font-size: 1.6em; letter-spacing: 3px; }
            .seccion-img img { height: 240px; }
            .grid-valores { grid-template-columns: 1fr; }
            .valor-item .valor-img { height: 220px; }
        }
    </style>
</head>
<body>
<!-- incluimos el header -->
<?php include_once 'views/layout/header.php'; ?>

<div class="nosotros-wrap">

    <div class="cabecera-titulo">
        <span>Descubra nuestra esencia</span>
        <h1>Éclat Haute Couture</h1>
        <div class="linea"></div>
    </div>

    <!-- Sección 1: origen -->
    <div class="seccion">
        <div class="seccion-img">
            <img src="assets/img/inicios.png" alt="Nuestros inicios">
        </div>
        <div class="seccion-txt">
            <h2>Una Visión de Excelencia</h2>
            <p>Éclat nació en el corazón de la búsqueda de lo extraordinario. En un mundo dominado por la moda efímera, nuestra casa se erigió como un santuario para aquellos que valoran la durabilidad, el detalle y la distinción.</p>
            <p>Desde nuestro primer atelier en Almería, hemos mantenido una promesa inquebrantable: cada costura cuenta una historia de dedicación. No solo vendemos prendas; curamos piezas de arte que realzan la identidad de quien las viste.</p>
        </div>
    </div>

</div>

<!-- Banner filosofía (fuera del wrap para ocupar todo el ancho) -->
<div class="banner-filosofia">
    <div class="inner">
        <h2>Nuestra Filosofía</h2>
        <p class="cita">"La elegancia no consiste en destacar, sino en ser recordado."</p>
        <p>Creemos en el lujo consciente. Seleccionamos tejidos que respetan el medio ambiente sin sacrificar la opulencia de la seda o la estructura del lino. En Éclat, la sostenibilidad y la alta costura caminan de la mano hacia el futuro.</p>
    </div>
</div>

<div class="nosotros-wrap">

    <!-- Sección 3: proceso -->
    <div class="seccion invertir">
        <div class="seccion-txt">
            <h2>Artesanía sin Compromisos</h2>
            <p>Nuestras colecciones se producen en series limitadas para garantizar que la exclusividad sea real. El proceso comienza con la selección manual de materiales en los mejores telares europeos, seguido de un patronaje meticuloso que desafía las convenciones.</p>
            <p>Cada detalle, desde los botones de nácar hasta los bordados invisibles, es supervisado por maestros sastres que llevan décadas perfeccionando su oficio.</p>
        </div>
        <div class="seccion-img">
            <img src="assets/img/proceso.png" alt="Proceso creativo">
        </div>
    </div>

    <!-- Grid valores -->
    <div class="grid-valores">
        <div class="valor-item">
            <div class="valor-img"><img src="assets/img/exclusivdad.png" alt="Exclusividad"></div>
            <h3>Exclusividad</h3>
            <p>Nuestras unidades son limitadas. Una vez que una pieza se agota, se convierte en un tesoro privado de quienes la adquirieron.</p>
        </div>
        <div class="valor-item">
            <div class="valor-img"><img src="assets/img/atencion.png" alt="Atención Atelier"></div>
            <h3>Atención Atelier</h3>
            <p>Ofrecemos asesoramiento personalizado para asegurar que cada prenda se ajuste a su estilo de vida y fisionomía.</p>
        </div>
        <div class="valor-item">
            <div class="valor-img"><img src="assets/img/herencia.png" alt="Herencia"></div>
            <h3>Herencia</h3>
            <p>Diseñamos prendas destinadas a durar generaciones, manteniendo su forma y elegancia a través del tiempo.</p>
        </div>
    </div>

</div>
<!-- incluyo el footer  -->
<?php include_once 'views/layout/footer.php'; ?>

</body>
</html>
