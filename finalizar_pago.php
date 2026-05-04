<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include_once 'views/layout/header.php'; 
?>

<div style="max-width: 900px; margin: 60px auto; padding: 20px; font-family: 'Segoe UI', sans-serif;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="text-transform: uppercase; font-weight: 300; letter-spacing: 5px;">Finalizar Pedido</h1>
        <div style="width: 50px; height: 2px; background: #b59410; margin: 20px auto;"></div>
    </div>

    <div style="display: flex; gap: 40px; flex-wrap: wrap; align-items: flex-start;">
        
        <!-- Formulario de Tarjeta -->
        <div style="flex: 1.5; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f0f0f0;">
            <h2 style="text-transform: uppercase; letter-spacing: 2px; font-size: 1.1em; margin-bottom: 30px; color: #222;">Detalles de Pago</h2>
            
            <form id="form-pago" onsubmit="procesarTransaccion(event)" style="display: flex; flex-direction: column; gap: 25px;">
                <div class="campo">
                    <label style="font-size: 0.75em; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;">Titular de la tarjeta</label>
                    <input type="text" placeholder="Nombre completo" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; outline: none; transition: 0.3s;">
                </div>

                <div class="campo">
                    <label style="font-size: 0.75em; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;">Número de tarjeta</label>
                    <input type="text" placeholder="0000 0000 0000 0000" maxlength="16" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                </div>

                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label style="font-size: 0.75em; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;">Caducidad</label>
                        <input type="text" placeholder="MM/AA" maxlength="5" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 0.75em; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block;">CVC</label>
                        <input type="text" placeholder="123" maxlength="3" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                    </div>
                </div>

                <button id="btn-pago" type="submit" style="background: #000; color: #fff; border: none; padding: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; cursor: pointer; transition: 0.4s; margin-top: 10px;">
                    Confirmar Transacción
                </button>
            </form>
        </div>
        
        <!-- Resumen Lateral -->
        <div style="flex: 1; background: #fafafa; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
            <h3 style="text-transform: uppercase; font-size: 0.8em; letter-spacing: 2px; color: #888; margin-bottom: 20px;">Resumen de compra</h3>
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <span style="color: #666;">Importe</span>
                <span style="font-weight: 600;"><?php echo number_format($_SESSION['total_carrito'] ?? 0, 2); ?> €</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <span style="color: #666;">Gastos de envío</span>
                <span style="color: #b59410; font-size: 0.8em; font-weight: bold; text-transform: uppercase;">Cortesía Éclat</span>
            </div>
            <div style="border-top: 1px solid #ddd; margin-top: 20px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: bold; text-transform: uppercase;">Total</span>
                <span style="font-size: 1.8em; color: #b59410; font-weight: bold;"><?php echo number_format($_SESSION['total_carrito'] ?? 0, 2); ?> €</span>
            </div>
        </div>
    </div>
</div>

<script>
function procesarTransaccion(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-pago');
    
    // UI: Feedback visual de procesamiento
    btn.disabled = true;
    btn.style.background = "#555";
    btn.innerHTML = "Verificando fondos...";

    // Llamada al nuevo archivo profesional que resta el stock
    fetch('confirmar_transaccion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({ 
            'total': '<?php echo $_SESSION['total_carrito'] ?? 0; ?>' 
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            btn.innerHTML = "¡Pago Aceptado!";
            btn.style.background = "#b59410";
            
            setTimeout(() => {
                alert("Su pedido ha sido procesado con éxito. Gracias por confiar en la excelencia de Éclat.");
                window.location.href = "index.php";
            }, 1500);
        } else {
            alert("Atención: " + data.message);
            btn.disabled = false;
            btn.style.background = "#000";
            btn.innerHTML = "Confirmar Transacción";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un problema de conexión con el servidor.");
        btn.disabled = false;
        btn.style.background = "#000";
    });
}

// Estilos de foco para los inputs
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', () => { input.style.borderColor = '#b59410'; });
    input.addEventListener('blur', () => { input.style.borderColor = '#ddd'; });
});
</script>

<?php include_once 'views/layout/footer.php'; ?>