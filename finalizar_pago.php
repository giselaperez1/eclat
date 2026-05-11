<?php
if(session_status() === PHP_SESSION_NONE) session_start();
$totalSesion = $_SESSION['total_carrito'] ?? 0;

// construyo el resumen del carrito para mandarlo en el email
$resumenEmail = '';
if(!empty($_SESSION['carrito'])){
    foreach($_SESSION['carrito'] as $p){
        $resumenEmail .= $p['nombre'].' x'.$p['cantidad'].' — '.number_format($p['precio'] * $p['cantidad'], 2).' €\n';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- EmailJS para envío de confirmación de pedido -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script>emailjs.init("FpC4rZozeJpA-7VuO");</script>
    <title>Finalizar Pedido - Éclat</title>
    <style>
        * { box-sizing: border-box; }
        .checkout-wrap { max-width: 980px; margin: 60px auto; padding: 0 20px; font-family: 'Segoe UI', sans-serif; }
        .checkout-titulo { text-align:center; margin-bottom:50px; }
        .checkout-titulo h1 { text-transform:uppercase; font-weight:300; letter-spacing:5px; margin-bottom:10px; }
        .linea-dorada { width:50px; height:2px; background:#b59410; margin:0 auto; }

        .checkout-grid { display:flex; gap:35px; flex-wrap:wrap; align-items:flex-start; }

        .bloque-datos {
            flex:1.6; min-width:320px;
            background:#fff; padding:35px;
            border-radius:10px;
            box-shadow:0 8px 25px rgba(0,0,0,0.06);
            border:1px solid #f0f0f0;
        }
        .bloque-datos h2 {
            text-transform:uppercase; letter-spacing:2px;
            font-size:1em; margin:0 0 25px; color:#222;
            padding-bottom:15px; border-bottom:1px solid #f0f0f0;
        }

        .campo-label {
            font-size:.72em; color:#999;
            text-transform:uppercase; letter-spacing:1px;
            margin-bottom:6px; display:block;
        }
        .campo-input {
            width:100%; padding:11px 14px;
            border:1px solid #ddd; border-radius:4px;
            outline:none; font-family:inherit;
            font-size:.9em; transition:.25s;
            margin-bottom:18px;
        }
        .campo-input:focus { border-color:#b59410; }

        .fila-campos { display:flex; gap:15px; }
        .fila-campos > div { flex:1; }

        .separador { border:none; border-top:1px solid #f0f0f0; margin:25px 0; }

        #btn-pago {
            background:#000; color:#fff; border:none;
            padding:17px; width:100%; font-weight:bold;
            text-transform:uppercase; letter-spacing:3px;
            cursor:pointer; transition:.3s; margin-top:10px;
            font-size:.9em;
        }
        #btn-pago:hover { background:#b59410; }

        .bloque-resumen {
            flex:1; min-width:260px;
            background:#fafafa; padding:30px;
            border-radius:10px; border:1px solid #eee;
            position:sticky; top:30px;
        }
        .bloque-resumen h3 {
            text-transform:uppercase; font-size:.8em;
            letter-spacing:2px; color:#888; margin:0 0 20px;
        }
        .linea-resumen { display:flex; justify-content:space-between; margin-bottom:14px; font-size:.9em; }
        .linea-resumen span:first-child { color:#666; }
        .linea-total {
            border-top:1px solid #ddd; margin-top:20px;
            padding-top:20px; display:flex;
            justify-content:space-between; align-items:center;
        }
        .linea-total span:first-child { font-weight:bold; text-transform:uppercase; font-size:.9em; }
        .importe-final { font-size:1.9em; color:#b59410; font-weight:bold; }
        .aviso-seguro { margin-top:20px; font-size:.75em; color:#aaa; text-align:center; line-height:1.6; }
        /* reponsive  */

        @media (max-width: 768px) {
            .checkout-wrap { margin: 30px auto; padding: 0 15px; }
            .checkout-grid { flex-direction: column; }
            .bloque-datos { padding: 25px 20px; }
            .bloque-resumen { position: static; }
            .fila-campos { flex-direction: column; gap: 0; }
        }
        @media (max-width: 480px) {
            .checkout-titulo h1 { font-size: 1.4em; letter-spacing: 3px; }
            .bloque-datos { padding: 20px 15px; }
        }
    </style>
</head>
<body>
<!-- incluyo el header  -->
<?php include_once 'views/layout/header.php'; ?>

<div class="checkout-wrap">
    <div class="checkout-titulo">
        <h1>Finalizar Pedido</h1>
        <div class="linea-dorada"></div>
    </div>

    <div class="checkout-grid">
        <!-- formulario para el  pago que recoge sus datos  -->

        <div class="bloque-datos">
            <form id="form-pago" onsubmit="procesarTransaccion(event)">

                <h2>Dirección de envío</h2>

                <div class="fila-campos">
                    <div>
                        <label class="campo-label">Nombre</label>
                        <input type="text" class="campo-input" placeholder="Nombre" required
                               value="<?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?>">
                    </div>
                    <div>
                        <label class="campo-label">Apellidos</label>
                        <input type="text" class="campo-input" placeholder="Apellidos" required>
                    </div>
                </div>

                <label class="campo-label">Dirección</label>
                <input type="text" class="campo-input" placeholder="Calle y número" required>

                <label class="campo-label">Piso / Puerta (opcional)</label>
                <input type="text" class="campo-input" placeholder="Ej: 3º B">

                <div class="fila-campos">
                    <div>
                        <label class="campo-label">Código Postal</label>
                        <input type="text" class="campo-input" placeholder="00000" maxlength="5" required>
                    </div>
                    <div>
                        <label class="campo-label">Ciudad</label>
                        <input type="text" class="campo-input" placeholder="Ciudad" required>
                    </div>
                </div>

                <div class="fila-campos">
                    <div>
                        <label class="campo-label">Provincia</label>
                        <input type="text" class="campo-input" placeholder="Provincia">
                    </div>
                    <div>
                        <label class="campo-label">País</label>
                        <input type="text" class="campo-input" placeholder="España" value="España">
                    </div>
                </div>

                <label class="campo-label">Teléfono de contacto</label>
                <input type="tel" class="campo-input" placeholder="+34 600 000 000">

                <hr class="separador">

                <h2>Datos de pago</h2>

                <label class="campo-label">Titular de la tarjeta</label>
                <input type="text" class="campo-input" placeholder="Nombre completo" required>
<!-- maximo 19 caracteres  -->
                <label class="campo-label">Número de tarjeta</label>
                <input type="text" class="campo-input" id="num-tarjeta" placeholder="0000 0000 0000 0000" maxlength="19" required>

                <div class="fila-campos">
                    <div>
                        <label class="campo-label">Caducidad</label>
                        <input type="text" class="campo-input" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div>
                        <label class="campo-label">CVC</label>
                        <input type="text" class="campo-input" placeholder="123" maxlength="3" required>
                    </div>
                </div>

                <button type="submit" id="btn-pago">Confirmar y Pagar</button>

            </form>
        </div>
<!-- mostramos el resumen del pedido  -->
        <div class="bloque-resumen">
            <h3>Resumen del pedido</h3>

            <?php if(!empty($_SESSION['carrito'])): ?>
                <?php foreach($_SESSION['carrito'] as $p): ?>
                <div class="linea-resumen">
                    <span><?php echo htmlspecialchars($p['nombre']); ?> ×<?php echo $p['cantidad']; ?></span>
                    <span><?php echo number_format($p['precio'] * $p['cantidad'],2); ?> €</span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
<!-- estilos para el resumen del pedido  -->
            <div class="linea-resumen" style="margin-top:10px; padding-top:10px; border-top:1px dashed #e0e0e0;">
                <span>Gastos de envío</span>
                <span style="color:#b59410; font-weight:bold; font-size:.8em;">GRATIS</span>
            </div>

            <div class="linea-total">
                <span>Total</span>
                <span class="importe-final"><?php echo number_format($totalSesion,2); ?> €</span>
            </div>

            <p class="aviso-seguro">🔒 Pago seguro · Tus datos están protegidos</p>
        </div>

    </div>
</div>

<script>
document.getElementById('num-tarjeta').addEventListener('input', function(){
    // Elimina cualquier carácter que no sea un número (\D)
    // Limita la longitud a 16 dígitos (substring)
    let v = this.value.replace(/\D/g,'').substring(0,16);
    // Agrupa los números de 4 en 4 y los separa
    // Si no hay valor, devuelve la cadena vacía
    this.value = v.match(/.{1,4}/g)?.join(' ') || v;
});

/**Función principal para procesar el pago y la transacción
 * Se activa al enviar el formulario (evento submit)
 */

function procesarTransaccion(e){
    // Evita que la página se recargue automáticamente al enviar el formulario
    e.preventDefault();
    const btn = document.getElementById('btn-pago');
    btn.disabled = true;
    btn.style.background = "#555";
    btn.innerHTML = "Procesando...";

    // primero guardo el pedido en la bbdd
    fetch('confirmar_transaccion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ 'total': '<?php echo $totalSesion; ?>' })
    })
    .then(r => r.json()) // Convierte la respuesta del servidor a formato JSON
    // Si el servidor confirma que el pedido se guardó se actualiza el estado del boton 
    .then(data => {
        if(data.success){
            btn.innerHTML = "✓ Enviando confirmación...";
            btn.style.background = "#b59410";

            // pedido guardado , ahora mando el email de confirmación con EmailJS
            emailjs.send("service_s10r9yu", "template_athvpoi", {
                email:          "<?php echo $_SESSION['usuario_email'] ?? ''; ?>",
                nombre_cliente: "<?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Cliente'); ?>",
                id_pedido:      data.id_pedido ?? '—',
                resumen_pedido: <?php echo json_encode($resumenEmail); ?>,
                total:          "<?php echo number_format($totalSesion, 2); ?>"
            })
            .then(() => {
                // email enviado
                btn.innerHTML = "✓ Pedido confirmado";
                setTimeout(() => { window.location.href = "index.php"; }, 1500);
            })
            .catch(() => {
                // el pedido se guardó bien aunque el email fallara
                btn.innerHTML = "✓ Pedido confirmado";
                setTimeout(() => { window.location.href = "index.php"; }, 1500);
            });

        } else {
            alert("Error: " + data.message);
            btn.disabled = false;
            btn.style.background = "#000";
            btn.innerHTML = "Confirmar y Pagar";
        }
    })
    .catch(() => {
        alert("Problema de conexión. Inténtalo de nuevo.");
        btn.disabled = false;
        btn.style.background = "#000";
        btn.innerHTML = "Confirmar y Pagar";
    });
}

document.querySelectorAll('.campo-input').forEach(i => {
    // Cuando el usuario hace clic/foco en el input Borde dorado
    i.addEventListener('focus', () => i.style.borderColor = '#b59410');
    // Cuando el usuario sale del input Borde gris
    i.addEventListener('blur',  () => i.style.borderColor = '#ddd');
});
</script>
 <!-- incluimos el footer  -->
<?php include_once 'views/layout/footer.php'; ?>

</body>
</html>
