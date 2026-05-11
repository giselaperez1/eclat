<?php
// página de inicio de sesión
// comprueba que el email exista y que la contraseña sea correcta
session_start();
include_once 'config/Database.php';
include_once 'models/Usuario.php';

$baseDatos = new Database();
$conexion  = $baseDatos->getConnection();
$usuario   = new Usuario($conexion); // objeto que tiene los métodos de consulta de usuarios

$aviso = ""; // mensaje de error que se muestra si algo falla

if($_POST){
    // cargo el email en el objeto para que emailExiste() lo use en la consulta
    $usuario->email   = $_POST['email'];
    $claveIntroducida = $_POST['contrasena']; // la contraseña que escribió el usuario

    if($usuario->emailExiste()){
        // emailExiste() carga el hash de la bbdd en $usuario->contrasena
        // password_verify() compara la clave escrita con el hash guardado
        if(password_verify($claveIntroducida, $usuario->contrasena)){

            // login correcto - guardo los datos del usuario en sesión
            $_SESSION['usuario_id']     = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombre;
            $_SESSION['usuario_email']  = $usuario->email; // lo necesito para emailjs
            $_SESSION['rol']            = $usuario->rol; // cliente o admin 

            // si venía de meter algo en el carrito, que vuelva ahí en vez de al catálogo
            if(!empty($_SESSION['carrito']))
                header("Location: ver_carrito.php");
            else
                header("Location: catalogo_test.php");
            exit;

        } else {
            $aviso = "<p style='color:#c00; font-size:0.85em; margin:0 0 10px;'>Contraseña incorrecta.</p>";
        }
    } else {
        $aviso = "<p style='color:#c00; font-size:0.85em; margin:0 0 10px;'>Este correo no está registrado.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Éclat</title>
    <style>
        /* estilos de login  */
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background: #fff; margin: 0;
        }
        /* tarjeta de login  */
        .login-card {
            width: 320px; padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }
        h2 { text-transform: uppercase; letter-spacing: 3px; font-weight: 300; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input { padding: 12px; border: 1px solid #ddd; outline: none; font-family: inherit; width: 100%; }
        input:focus { border-color: #b59410; }
        button {
            padding: 15px; background: #000; color: #fff;
            border: none; text-transform: uppercase;
            cursor: pointer; font-weight: bold; letter-spacing: 1px;
        }
        button:hover { background: #b59410; }
        .pie { margin-top: 25px; font-size: 13px; color: #888; }
        a { color: #000; text-decoration: none; font-weight: bold; }
        a:hover { color: #b59410; }
        /* responsive  */
        @media (max-width: 400px) {
            .login-card { width: 90vw; padding: 30px 20px; }
        }
    </style>
</head>
<body>
<div class="login-card">
    <h2>Identificarse</h2>

    <?php echo $aviso; /* muestra el error si lo hay */ ?>

    <!-- login  -->
    <form method="post">
        <input type="email"    name="email"      placeholder="Email"      required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>

    <div class="pie">
        ¿Primera vez en Éclat? <a href="registro_test.php">Crea tu cuenta</a>
    </div>
</div>
</body>
</html>
