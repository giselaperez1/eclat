<?php
session_start();
include_once 'config/Database.php';
include_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$mensaje = "";

if ($_POST) {
    $usuario->email = $_POST['email'];
    $password_login = $_POST['contrasena'];

    // 1. Verificamos si el email existe
    if ($usuario->emailExiste()) {
        // 2. Comparamos la contraseña (Hash)
        if (password_verify($password_login, $usuario->contrasena)) {
            
            // ¡ÉXITO! Guardamos los datos clave en la sesión
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['rol'] = $usuario->rol;
            
            // --- LÓGICA DE REDIRECCIÓN INTELIGENTE ---
            // Si tiene productos en la cesta, vamos al carrito. Si no, al catálogo.
            if (!empty($_SESSION['carrito'])) {
                header("Location: ver_carrito.php");
            } else {
                header("Location: catalogo_test.php");
            }
            exit; // Cortamos la ejecución aquí para que la redirección funcione

        } else {
            $mensaje = "<p style='color:red;'>Contraseña incorrecta.</p>";
        }
    } else {
        $mensaje = "<p style='color:red;'>El correo no está registrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Clientes - Éclat</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background: #fff; 
            margin: 0;
        }
        .login-card { 
            width: 320px; 
            padding: 40px; 
            border: 1px solid #eee; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        h2 { text-transform: uppercase; letter-spacing: 3px; font-weight: 300; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input { padding: 12px; border: 1px solid #ddd; outline: none; }
        input:focus { border-color: #b59410; }
        button { 
            padding: 15px; 
            background: #000; 
            color: #fff; 
            border: none; 
            text-transform: uppercase; 
            cursor: pointer; 
            font-weight: bold;
        }
        button:hover { background: #b59410; }
        .footer-links { margin-top: 25px; font-size: 13px; color: #888; }
        a { color: #000; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Identificarse</h2>
        <?php echo $mensaje; ?>
        
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>

        <div class="footer-links">
            ¿Nuevo en Éclat? <a href="registro_test.php">Crea tu cuenta aquí</a>
        </div>
    </div>
</body>
</html>