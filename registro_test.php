<?php
// página de registro de nuevos clientes
// recoge el formulario, valida y guarda el usuario en la bbdd
include_once 'config/Database.php';
include_once 'models/Usuario.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();
$usuario   = new Usuario($conexion);

$aviso = ""; // mensaje de feedback para el usuario

if($_POST){
    // cargo los datos del formulario en el objeto usuario
    $usuario->nombre     = $_POST['nombre'];
    $usuario->apellidos  = $_POST['apellidos'];
    $usuario->email      = $_POST['email'];
    $usuario->contrasena = $_POST['contrasena']; // el modelo se encarga de hashearlo antes de guardar
    $usuario->rol        = 'cliente';            // todos los registros nuevos son clientes, nunca admin

    if($usuario->registrar()){
        $aviso = "<p style='color:green; font-size:0.85em;'>¡Cuenta creada! Redirigiendo...</p>";
        header("refresh:2;url=login_test.php"); // espera y lleva al login
    } else {
        // miuestra aviso ,puede que el email ya exista o que haya fallado algo en la bbdd
        $aviso = "<p style='color:#c00; font-size:0.85em;'>El correo ya existe o hubo un error.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Éclat</title>
    <style>
        /* estilos para registro  */
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background: #fff; margin: 0;
        }
        /* estilo tarjets  */
        .registro-card {
            width: 350px; padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        h2 { text-transform: uppercase; letter-spacing: 3px; font-weight: 300; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 14px; }
        input { padding: 12px; border: 1px solid #ddd; outline: none; font-family: inherit; width: 100%; }
        input:focus { border-color: #b59410; }
        button {
            padding: 15px; background: #000; color: #fff;
            border: none; font-weight: bold; text-transform: uppercase;
            cursor: pointer; letter-spacing: 1px;
        }
        button:hover { background: #b59410; }
        .pie { margin-top: 20px; font-size: 13px; color: #888; }
        a { color: #000; font-weight: bold; text-decoration: none; }
        a:hover { color: #b59410; }
        /* responsive  */
        @media (max-width: 400px) {
            .registro-card { width: 90vw; padding: 30px 20px; }
        }
    </style>
</head>
<body>
<div class="registro-card">
    <h2>Crear Cuenta</h2>

    <?php echo $aviso; /* feedback del registro */ ?>

    <form method="post">
        <input type="text"     name="nombre"     placeholder="Nombre"    required>
        <input type="text"     name="apellidos"  placeholder="Apellidos" required>
        <input type="email"    name="email"      placeholder="Email"     required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Registrarme</button>
    </form>

    <!-- si tenemos cuneta nos lleva al login  -->
    <div class="pie">
        ¿Ya tienes cuenta? <a href="login_test.php">Inicia sesión</a>
    </div>
</div>
</body>
</html>
