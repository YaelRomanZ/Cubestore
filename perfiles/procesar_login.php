<?php
session_start();
include("../configuraciones/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = mysqli_real_escape_string($conex, trim($_POST['email']));
    $password = trim($_POST['password']);

    // Buscar usuario por correo
    $consulta = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conex, $consulta);

    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        // Verificar contraseña
        if (password_verify($password, $usuario['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido_paterno'] = $usuario['apellido_paterno'];
            $_SESSION['correo'] = $usuario['correo'];

            // Redirigir al inicio o a un panel
            header("Location: inicio.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo '<script>alert("Contraseña incorrecta."); window.location="iniciosesion.php";</script>';
        }
    } else {
        // No existe el correo
        echo '<script>alert("El correo no está registrado."); window.location="iniciosesion.php";</script>';
    }
} else {
    // Si no viene de un formulario POST
    header("Location: iniciosesion.php");
    exit();
}
?>
