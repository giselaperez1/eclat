<?php include_once 'views/layout/header.php'; ?>

<div style="max-width: 1200px; margin: 0 auto; padding: 80px 20px; font-family: 'Segoe UI', sans-serif; color: #333;">
    
    <!-- CABECERA DE IMPACTO -->
    <div style="text-align: center; margin-bottom: 100px;">
        <span style="color: #b59410; text-transform: uppercase; letter-spacing: 5px; font-size: 0.9em; font-weight: 600;">Descubra nuestra esencia</span>
        <h1 style="text-transform: uppercase; font-weight: 100; letter-spacing: 12px; font-size: 3.5em; margin: 20px 0;">Éclat Haute Couture</h1>
        <div style="width: 80px; height: 1px; background: #b59410; margin: 0 auto;"></div>
    </div>

    <!-- SECCIÓN 1: EL ORIGEN (Imagen Izquierda - Texto Derecha) -->
    <div style="display: flex; align-items: center; gap: 80px; flex-wrap: wrap; margin-bottom: 120px;">
        <div style="flex: 1; min-width: 400px; position: relative;">
            <img src="assets/img/inicios.png" alt="Nuestros inicios" style="width: 100%; height: 500px; object-fit: cover; border-radius: 2px;">

        </div>
        <div style="flex: 1; min-width: 400px;">
            <h2 style="text-transform: uppercase; font-weight: 300; letter-spacing: 3px; font-size: 2em; margin-bottom: 30px;">Una Visión de Excelencia</h2>
            <p style="line-height: 2; color: #666; font-size: 1.1em; margin-bottom: 20px;">
                Éclat nació en el corazón de la búsqueda de lo extraordinario. En un mundo dominado por la moda efímera, nuestra casa se erigió como un santuario para aquellos que valoran la durabilidad, el detalle y la distinción.
            </p>
            <p style="line-height: 2; color: #666; font-size: 1.1em;">
                Desde nuestro primer atelier en Almería, hemos mantenido una promesa inquebrantable: cada costura cuenta una historia de dedicación. No solo vendemos prendas; curamos piezas de arte que realzan la identidad de quien las viste.
            </p>
        </div>
    </div>

    <!-- SECCIÓN 2: FILOSOFÍA Y DISEÑO (Banner Oscuro) -->
    <div style="background: #000; padding: 100px 60px; margin: 0 -20px 120px -20px; color: #fff; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2 style="color: #b59410; text-transform: uppercase; letter-spacing: 5px; margin-bottom: 40px;">Nuestra Filosofía</h2>
            <p style="font-size: 1.4em; font-weight: 100; font-style: italic; line-height: 1.8; margin-bottom: 40px;">
                "La elegancia no consiste en destacar, sino en ser recordado."
            </p>
            <p style="color: #aaa; line-height: 1.8; font-size: 1em;">
                Creemos en el lujo consciente. Seleccionamos tejidos que respetan el medio ambiente sin sacrificar la opulencia de la seda o la estructura del lino. En Éclat, la sostenibilidad y la alta costura caminan de la mano hacia el futuro.
            </p>
        </div>
    </div>

    <!-- SECCIÓN 3: EL PROCESO (Texto Izquierda - Imagen Derecha) -->
    <div style="display: flex; align-items: center; gap: 80px; flex-wrap: wrap-reverse; margin-bottom: 120px;">
        <div style="flex: 1; min-width: 400px;">
            <h2 style="text-transform: uppercase; font-weight: 300; letter-spacing: 3px; font-size: 2em; margin-bottom: 30px;">Artesanía sin Compromisos</h2>
            <p style="line-height: 2; color: #666; font-size: 1.1em; margin-bottom: 20px;">
                Nuestras colecciones se producen en series limitadas para garantizar que la exclusividad sea real. El proceso comienza con la selección manual de materiales en los mejores telares europeos, seguido de un patronaje meticuloso que desafía las convenciones.
            </p>
            <p style="line-height: 2; color: #666; font-size: 1.1em;">
                Cada detalle, desde los botones de nácar hasta los bordados invisibles, es supervisado por maestros sastres que llevan décadas perfeccionando su oficio.
            </p>
        </div>
        <div style="flex: 1; min-width: 400px;">
            <img src="assets/img/proceso.png" alt="Proceso creativo" style="width: 100%; height: 500px; object-fit: cover; border-radius: 2px;">
        </div>
    </div>

    <!-- SECCIÓN 4: GALERÍA DE VALORES (3 Columnas con Iconos/Fotos) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
        <div style="text-align: center; padding: 20px;">
            <div style="height: 300px; background: #eee; margin-bottom: 25px;">
                <img src="assets/img/exclusivdad.png" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <h3 style="text-transform: uppercase; letter-spacing: 2px; font-size: 1.1em; color: #b59410;">Exclusividad</h3>
            <p style="color: #888; font-size: 0.9em; line-height: 1.6;">Nuestras unidades son limitadas. Una vez que una pieza se agota, se convierte en un tesoro privado de quienes la adquirieron.</p>
        </div>
        <div style="text-align: center; padding: 20px;">
            <div style="height: 300px; background: #eee; margin-bottom: 25px;">
                <img src="assets/img/atencion.png" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <h3 style="text-transform: uppercase; letter-spacing: 2px; font-size: 1.1em; color: #b59410;">Atención Atelier</h3>
            <p style="color: #888; font-size: 0.9em; line-height: 1.6;">Ofrecemos asesoramiento personalizado para asegurar que cada prenda se ajuste a su estilo de vida y fisionomía.</p>
        </div>
        <div style="text-align: center; padding: 20px;">
            <div style="height: 300px; background: #eee; margin-bottom: 25px;">
                <img src="assets/img/herencia.png" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <h3 style="text-transform: uppercase; letter-spacing: 2px; font-size: 1.1em; color: #b59410;">Herencia</h3>
            <p style="color: #888; font-size: 0.9em; line-height: 1.6;">Diseñamos prendas destinadas a durar generaciones, manteniendo su forma y elegancia a través del tiempo.</p>
        </div>
    </div>
</div>

<?php include_once 'views/layout/footer.php'; ?>