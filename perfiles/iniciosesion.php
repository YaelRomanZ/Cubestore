<?php
session_start();
include '../Configuraciones/conexion.php';


$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario en la base de datos
    $stmt = $conex->prepare("SELECT id, nombre, apellido_paterno, correo, contrasena FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        // Verificar contraseña (suponiendo que está hasheada con password_hash)
        if (password_verify($password, $usuario['contrasena'])) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido_paterno'] = $usuario['apellido_paterno'];
            $_SESSION['correo'] = $usuario['correo'];

            // Redirigir al index
            header("Location: ../index.php"); 
            exit();
        } else {
            $mensaje = "Correo o contraseña incorrectos";
        }
    } else {
        $mensaje = "Correo o contraseña incorrectos";
    }

    $stmt->close();
    $conex->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CubeStore - Iniciar Sesión</title>
<!-- Bootstrap y Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <link rel="stylesheet" href="../css/estilo.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
body { min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center; background:linear-gradient(135deg,#f5f6fa,#dfe9f3); }
.login-container { background:#fff; padding:2.5rem 4rem; border-radius:20px; box-shadow:0 1px 16px 4px rgba(0,0,0,0.15); width:100%; max-width:400px; text-align:center; }
.login-container h2 { font-weight:700; margin-bottom:3.5rem; color:#ff8479; }
.form-control { border-radius:12px; border:1px solid #ddd; transition:0.3s; }
.form-control:focus { border-color:#ff8479; box-shadow:0 0 8px rgba(255,132,121,0.3); }
.password-container { position:relative; }
.toggle-password { position:absolute; right:10px; top:75%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#888; }
.btn-login { background:#ff8479; color:#fff; border:none; width:100%; padding:0.6rem; border-radius:12px; font-weight:600; }
.btn-login:hover { filter:opacity(0.9); color: #ff8479; outline: 1px solid #000;}
.extra-links a { color:#ff8479; text-decoration:none; }
.extra-links a:hover { text-decoration:underline; }
.mensaje-error { color:red; margin-bottom:1rem; }
.footer {
  position: absolute;
  bottom: 0;
  width: 100vw;
}
nav img {
  border-radius: 10px;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white py-3 fixed-top justify-content-center">
    <div class="container d-flex flex-column align-items-center">
      <a class="navbar-brand" href="../index.php">
        <img src="../img/banner.png" alt="CubeStore Logo" height="100" class="navbar-logo mb-2">
      </a>
      <button class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span><i id="barras" class="fa-solid fa-bars"></i></span>
      </button>
    </div>
  </nav>

<div class="login-container">
    <h2><i class="fa-solid fa-user"></i> Iniciar Sesión</h2>

    <?php if($mensaje): ?>
        <p class="mensaje-error"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group mb-4">
            <label for="email">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
        </div>

        <div class="form-group mb-5 password-container">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            <button type="button" class="toggle-password" onclick="togglePassword()">
                <i class="fa-solid fa-eye" id="eyeIcon"></i>
            </button>
        </div>

        <button type="submit" class="btn btn-login mb-3">Iniciar Sesión</button>

        <div class="extra-links">
            <p><a href="recuperarpassword.php">¿Olvidaste tu contraseña?</a></p>
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
        </div>
    </form>
</div>
<footer class="footer">
  <div class="footer-content">
    <p class="text">Copyright © 2025 CubeStore Inc. Todos los derechos reservados.</p>
    <ul class="menu">
      <li class="menu-elem"><a href="" class="menu-icon">Aviso legal</a></li>
      <li>|</li>
      <li class="menu-elem"><a href="" class="menu-icon">Políticas de privacidad</a></li>
      <li>|</li>
      <li class="menu-elem"><a href="" class="menu-icon">Condiciones de compra</a></li>
    </ul>
    <div class="footer-line"></div>
  </div>
</footer>
<script>
function togglePassword() {
  const passwordInput = document.getElementById("password");
  const eyeIcon = document.getElementById("eyeIcon");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    eyeIcon.classList.replace("fa-eye","fa-eye-slash");
  } else {
    passwordInput.type = "password";
    eyeIcon.classList.replace("fa-eye-slash","fa-eye");
  }
}
</script>

</body>
</html>
