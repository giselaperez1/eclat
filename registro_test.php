<?php
// 1. Incluimos la conexión y el modelo
include_once 'config/Database.php';
include_once 'models/Usuario.php';

// 2. Preparamos la base de datos
$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$mensaje = "";

// 3. Si el usuario pulsa el botón "Registrar"
if ($_POST) {
    $usuario->nombre = $_POST['nombre'];
    $usuario->apellidos = $_POST['apellidos'];
    $usuario->email = $_POST['email'];
    $usuario->contrasena = $_POST['contrasena'];
    $usuario->rol = 'cliente'; // Por defecto todos son clientes [cite: 179]

    if ($usuario->registrar()) {
        $mensaje = "<p style='color:green;'>¡Éxito! Usuario guardado en la base de datos.</p>";
    } else {
        $mensaje = "<p style='color:red;'>Error al registrar el usuario.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Éclat</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; }
        form { display: flex; flex-direction: column; width: 300px; gap: 10px; }
        input { padding: 10px; }
        button { padding: 10px; background: #000; color: #fff; cursor: pointer; }
    </style>
</head>
<body>
    <div>
        <h2>Crear Cuenta en Éclat</h2>
        <?php echo $mensaje; ?>
        <form method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>
    </div>
</body>
</html>