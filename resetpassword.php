<?php
include '../ComercioE2/Configuraciones/conexion.php';
session_start();

if (!isset($_GET['token'])) {
    die("Token inválido.");
}

$token = $_GET['token'];

// Verificar token y expiración
$stmt = $conex->prepare("SELECT id, nombre, reset_expiry FROM usuarios WHERE reset_token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token inválido o no encontrado.");
}

$user = $result->fetch_assoc();
$expiry = strtotime($user['reset_expiry']);

if (time() > $expiry) {
    die("El enlace ha expirado. Solicita uno nuevo.");
}

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else if (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt2 = $conex->prepare("UPDATE usuarios SET contrasena=?, reset_token=NULL, reset_expiry=NULL WHERE id=?");
        $stmt2->bind_param("si", $hashed, $user['id']);
        $stmt2->execute();

        $success = "Contraseña cambiada exitosamente. <a href='perfiles/iniciosesion.php'>Inicia sesión</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CubeStore - Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
body { min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;
       background: linear-gradient(135deg,#f5f6fa,#dfe9f3);}
.reset-container {background:#fff; padding:2.5rem 3rem; border-radius:20px;
                  box-shadow:0 15px 35px rgba(0,0,0,0.15); width:100%; max-width:400px; text-align:center;}
.reset-container h2 {font-weight:700; margin-bottom:1rem; color:#ff8479;}
.reset-container p {color:#555; margin-bottom:1.5rem;}
.form-control {border-radius:12px; border:1px solid #ddd;}
.form-control:focus {border-color:#ff8479; box-shadow:0 0 8px rgba(255,132,121,0.3);}
.btn-reset {background:#ff8479; color:#fff; border:none; width:100%; padding:0.6rem; border-radius:12px; font-weight:600;}
.btn-reset:hover {filter:opacity(0.9);}
.extra-links a {color:#ff8479; text-decoration:none;}
.extra-links a:hover {text-decoration:underline;}
.footer {position:absolute; bottom:0; width:100vw;}
.navbar-logo {border-radius:10px;}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white py-3 fixed-top justify-content-center">
  <div class="container d-flex flex-column align-items-center">
    <a class="navbar-brand" href="../index.php">
      <img src="img/banner.png" alt="CubeStore Logo" height="100" class="navbar-logo mb-2">
    </a>
  </div>
</nav>
<div class="reset-container">
    <h2><i class="fa-solid fa-unlock-keyhole"></i> Cambiar Contraseña</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php else: ?>
        <form method="POST">
            <div class="mb-3">
                <label>Nueva contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label>Confirmar contraseña</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-reset mt-2">Cambiar contraseña</button>
        </form>
    <?php endif; ?>
</div>
<footer class="footer">
  <div class="footer-content text-center">
    <p class="text">Copyright © 2025 CubeStore Inc. Todos los derechos reservados.</p>
  </div>
</footer>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
