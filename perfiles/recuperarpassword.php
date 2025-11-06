<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CubeStore - Recuperar Contraseña</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="../css/estilo.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

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
      <img src="../img/banner.png" alt="CubeStore Logo" height="100" class="navbar-logo mb-2">
    </a>
  </div>
</nav>

<div class="reset-container mt-5">
  <h2><i class="fa-solid fa-unlock-keyhole"></i> Recuperar Contraseña</h2>
  <p>Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

  <form id="recuperarForm">
    <div class="form-group">
      <label for="correo">Correo electrónico</label>
      <input type="email" class="form-control" id="correo" name="correo" placeholder="Tu correo registrado" required>
    </div>
    <button type="submit" class="btn btn-reset mt-4">Enviar enlace</button>
    <div id="mensaje" class="mt-3"></div>
  </form>

  <div class="extra-links mt-3">
    <p><a href="iniciosesion.php">Volver al inicio de sesión</a></p>
  </div>
</div>

<footer class="footer">
  <div class="footer-content text-center">
    <p class="text">Copyright © 2025 CubeStore Inc. Todos los derechos reservados.</p>
  </div>
</footer>

<script>
const form = document.getElementById('recuperarForm');
const mensaje = document.getElementById('mensaje');
let timerInterval;

function startTimer(segundos){
    clearInterval(timerInterval);
    let tiempo = segundos;
    timerInterval = setInterval(()=>{
        if(tiempo <= 0){
            mensaje.innerHTML = '';
            clearInterval(timerInterval);
        } else {
            mensaje.innerHTML = `<div class="alert alert-warning">Debes esperar ${tiempo} segundos antes de solicitar otro enlace.</div>`;
            tiempo--;
        }
    }, 1000);
}

form.addEventListener('submit', function(e){
    e.preventDefault();

    const correo = document.getElementById('correo').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(correo)){
        mensaje.innerHTML = '<div class="alert alert-danger">Correo no válido.</div>';
        return;
    }

    const formData = new FormData(form);
    mensaje.innerHTML = '<div class="alert alert-info">Procesando...</div>';

    fetch('../procesar_recuperacion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success'){
            mensaje.innerHTML = `<div class="alert alert-success">${data.msg}</div>`;
            startTimer(data.faltan);
        } else if(data.status === 'wait'){
            startTimer(data.faltan);
        } else {
            mensaje.innerHTML = `<div class="alert alert-danger">${data.msg}</div>`;
        }
    })
    .catch(error => {
        mensaje.innerHTML = '<div class="alert alert-danger">Ocurrió un error.</div>';
        console.error(error);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
