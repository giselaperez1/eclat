<?php
session_start(); // Iniciamos la sesión para recordar al usuario
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
        // 2. Comparamos la contraseña escrita con la de la BD
        if (password_verify($password_login, $usuario->contrasena)) {
            // ¡ÉXITO! Guardamos los datos en la sesión
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['rol'] = $usuario->rol;
            
            $mensaje = "<p style='color:green;'>¡Bienvenido, " . $usuario->nombre . "! Has iniciado sesión correctamente.</p>";
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
    <title>Login Éclat</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; }
        form { display: flex; flex-direction: column; width: 300px; gap: 10px; }
        input { padding: 10px; }
        button { padding: 10px; background: #000; color: #fff; cursor: pointer; }
    </style>
</head>
<body>
    <div>
        <h2>Iniciar Sesión en Éclat</h2>
        <?php echo $mensaje; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>