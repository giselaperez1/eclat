<?php
include_once 'config/Database.php';
include_once 'models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$mensaje = "";

if ($_POST) {
    // 1. Asignamos los datos al modelo
    $usuario->nombre = $_POST['nombre'];
    $usuario->apellidos = $_POST['apellidos'];
    $usuario->email = $_POST['email'];
    $usuario->contrasena = $_POST['contrasena'];
    $usuario->rol = 'cliente';

    // 2. Intentamos registrar
    if ($usuario->registrar()) {
        // En lugar de solo un mensaje, redirigimos al login tras 2 segundos
        $mensaje = "<p style='color:green;'>¡Cuenta creada con éxito! Redirigiendo al inicio de sesión...</p>";
        header("refresh:2;url=login_test.php"); 
    } else {
        $mensaje = "<p style='color:red;'>El correo ya está registrado o hubo un error.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unirse a Éclat - Alta Costura</title>
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
        .registro-card { 
            width: 350px; 
            padding: 40px; 
            border: 1px solid #eee; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        h2 { text-transform: uppercase; letter-spacing: 3px; font-weight: 300; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input { 
            padding: 12px; 
            border: 1px solid #ddd; 
            outline: none; 
            transition: 0.3s;
        }
        input:focus { border-color: #b59410; } /* Dorado Éclat */
        button { 
            padding: 15px; 
            background: #000; 
            color: #fff; 
            border: none; 
            text-transform: uppercase; 
            font-weight: bold; 
            cursor: pointer; 
            letter-spacing: 1px;
        }
        button:hover { background: #b59410; }
        .footer-link { margin-top: 20px; font-size: 13px; color: #888; }
        a { color: #000; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="registro-card">
        <h2>Crear Cuenta</h2>
        <?php echo $mensaje; ?>
        
        <form method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>

        <div class="footer-link">
            ¿Ya tienes cuenta? <a href="login_test.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>