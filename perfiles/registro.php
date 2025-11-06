<?php 
include("../Configuraciones/conexion.php");

$mensaje = ''; // Mensaje de error del backend

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['nombre'], $_POST['apellidop'], $_POST['apellidom'], 
              $_POST['correo'], $_POST['password'], $_POST['confirmar_password'])) {

        // Validar campos no vacíos
        if (!empty($_POST['nombre']) && !empty($_POST['correo'])) {

            $nombre = mysqli_real_escape_string($conex, trim($_POST['nombre']));
            $apellidop = mysqli_real_escape_string($conex, trim($_POST['apellidop']));
            $apellidom = mysqli_real_escape_string($conex, trim($_POST['apellidom']));
            $correo = mysqli_real_escape_string($conex, trim($_POST['correo']));
            $telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conex, trim($_POST['telefono'])) : '';
            $password = trim($_POST['password']);
            $confirmar = trim($_POST['confirmar_password']);

            // Verificar que las contraseñas coincidan
            if ($password !== $confirmar) {
                $mensaje = "Las contraseñas no coinciden.";
            } else {
                // Verificar si el correo ya está registrado
                $verificar = mysqli_query($conex, "SELECT * FROM usuarios WHERE correo='$correo'");
                if (mysqli_num_rows($verificar) > 0) {
                    $mensaje = "El correo ya está registrado.";
                } else {
                    // Encriptar contraseña
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    // Insertar en la BD
                    $consulta = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, correo, contrasena, telefono) 
                                 VALUES ('$nombre', '$apellidop', '$apellidom', '$correo', '$hash', '$telefono')";
                    
                    $resultado = mysqli_query($conex, $consulta);

                    if ($resultado) {
                      header("Location: iniciosesion.php");
                      exit();
                    } else {
                        $mensaje = "¡Ups! Ha ocurrido un error.";
                        // Depuración opcional:
                        // $mensaje .= " Error SQL: " . mysqli_error($conex);
                    }
                }
            }

        } else {
            $mensaje = "Por favor completa todos los campos requeridos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CubeStore - Crear cuenta</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/estilo.css">

  <style>
    :root { --accent: #ff8479; --muted: #6c757d; }
    * {
      transition: all 1s;
    }
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg,#f5f6fa,#dfe9f3); padding-top: 140px; display:flex; flex-direction:column; align-items:center; min-height:100vh; margin:0 1rem; }
    .navbar-logo { height:100px; border-radius: 10px; object-fit:contain; }
    .register-container {
  background:#fff;
  padding:2rem 2.25rem;
  border-radius:18px;
  box-shadow:0 8px 24px rgba(17,24,39,0.08);
  width:100%;
  max-width:460px;
  text-align:left;
  transition: none;
  margin-top:1rem;
}
    .register-container:hover {
  transform: none;
  box-shadow:0 8px 24px rgba(17,24,39,0.08);
}
    .mensaje-error {
  color: #842029;
  background: #f8d7da;
  border: 1px solid #f5c2c7;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  min-height: 48px;
  opacity: 0;
  transition: opacity 0.3s ease;
}
    .mensaje-error.show {
  opacity: 1;
}
    .register-container h2 { font-weight:600; margin-bottom:0.75rem; text-align:center; color: var(--primary-color); }
    .step-indicator { display:flex; justify-content:center; gap:10px; margin:0 auto 1rem auto; }
    .step-dot { width:12px; height:12px; border-radius:50%; background:#000; transition: transform .18s ease, background .18s ease; }
    .step-dot.active { background: var(--accent); transform: scale(1.25); }
    form .form-group label { font-weight:500; margin-bottom:0.35rem; }
    .form-control { border-radius:10px; border:1px solid #e6e6e6; padding:0.6rem 0.75rem; transition: box-shadow .15s ease, border-color .15s ease; }
    .form-control:focus { border-color: var(--accent); box-shadow:0 6px 18px rgba(255,132,121,0.12); outline:none; }
    .btn-register, .btn-back { border-radius:10px; padding:0.6rem 0.9rem; font-weight:600; }
    .btn-register { background: var(--accent); color:#fff; border:none; }
    .btn-register:hover { background:#ff6f61; color:#fff; }
    .btn-back { background:#6c757d; color:#fff; border:none; }
    .password-container { position:relative; }
    .toggle-password { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#666; padding:0.2rem 0.4rem; font-size:1rem; }
    .toggle-password:focus { outline:none; }
    footer.footer { margin-top:auto; padding:2rem 0 1rem 0; width:100%; text-align:center; color:#495057; font-size:.9rem; }
    @media (max-width:480px) { body { padding-top:120px; } .register-container { padding:1.5rem; border-radius:14px; } }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg bg-white py-3 fixed-top d-flex justify-content-center">
    <div class="container d-flex flex-column align-items-center">
      <a class="navbar-brand" href="../index.php">
        <img src="../img/banner.png" alt="CubeStore Logo" class="navbar-logo mb-1">
      </a>
    </div>
  </nav>

  <div class="register-container">
    <h2 class="text-center"><i class="fa-solid fa-user-plus"></i> Crear Cuenta</h2>

    <div class="step-indicator">
      <div class="step-dot active" id="dot1"></div>
      <div class="step-dot" id="dot2"></div>
    </div>

    <p class="mensaje-error <?php if($mensaje) echo 'show'; ?>" id="mensajeError"><?php echo $mensaje; ?></p>

    <form action="" method="POST" id="registerForm" novalidate>
      <div class="form-step" id="step1">
        <div class="mb-3">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
        </div>
        <div class="mb-3">
          <label for="apellidop">Apellido Paterno</label>
          <input type="text" class="form-control" id="apellidop" name="apellidop" placeholder="Tu apellido paterno" required value="<?php echo isset($_POST['apellidop']) ? htmlspecialchars($_POST['apellidop']) : ''; ?>">
        </div>
        <div class="mb-3">
          <label for="apellidom">Apellido Materno</label>
          <input type="text" class="form-control" id="apellidom" name="apellidom" placeholder="Tu apellido materno" required value="<?php echo isset($_POST['apellidom']) ? htmlspecialchars($_POST['apellidom']) : ''; ?>">
        </div>
        <div class="d-grid gap-2">
          <button type="button" class="btn-register" onclick="nextStep()">Siguiente</button>
        </div>
      </div>

      <div class="form-step d-none" id="step2">
        <div class="mb-3">
          <label for="correo">Correo electrónico</label>
          <input type="email" class="form-control" id="correo" name="correo" placeholder="Tu correo electrónico" required value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
        </div>

        <div class="mb-3">
          <label for="telefono">Teléfono (opcional)</label>
          <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Tu número de teléfono" value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
        </div>

        <div class="mb-3 password-container">
          <label for="password">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Crea una contraseña" required>
          <button type="button" class="toggle-password" onclick="togglePassword('password','eyeIcon1')" aria-label="Mostrar contraseña">
            <i class="fa-solid fa-eye" id="eyeIcon1"></i>
          </button>
        </div>

        <div class="mb-3 password-container">
          <label for="confirmar_password">Confirmar contraseña</label>
          <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" placeholder="Repite tu contraseña" required>
          <button type="button" class="toggle-password" onclick="togglePassword('confirmar_password','eyeIcon2')" aria-label="Mostrar contraseña">
            <i class="fa-solid fa-eye" id="eyeIcon2"></i>
          </button>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-secondary btn-back w-50" onclick="prevStep()">Atrás</button>
          <button type="submit" class="btn-register w-50">Registrarse</button>
        </div>
      </div>

      <div class="extra-links mt-3 text-center">
        <p class="mb-0">¿Ya tienes una cuenta? <a href="iniciosesion.php">Inicia sesión</a></p>
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
    document.addEventListener('DOMContentLoaded', function () {
      if(document.getElementById('mensajeError').textContent.trim() !== '') {
        showStep(2); // mostrar step 2 si hubo error
      } else {
        showStep(1);
      }
    });

    let currentStep = 1;

    function showStep(step) {
      const step1 = document.getElementById('step1');
      const step2 = document.getElementById('step2');
      const dot1 = document.getElementById('dot1');
      const dot2 = document.getElementById('dot2');

      if(step===1){
        step1.classList.remove('d-none');
        step2.classList.add('d-none');
        dot1.classList.add('active');
        dot2.classList.remove('active');
      } else {
        step1.classList.add('d-none');
        step2.classList.remove('d-none');
        dot1.classList.remove('active');
        dot2.classList.add('active');
      }
      currentStep = step;
    }

    function mostrarError(mensaje){
      const mensajeError = document.getElementById('mensajeError');
      mensajeError.textContent = mensaje;
      mensajeError.classList.add('show');
      mensajeError.scrollIntoView({behavior:'smooth', block:'center'});
      setTimeout(()=>{ mensajeError.classList.remove('show'); }, 5000);
    }

    function nextStep(){
      const nombre = document.getElementById('nombre').value.trim();
      const apellidop = document.getElementById('apellidop').value.trim();
      const apellidom = document.getElementById('apellidom').value.trim();
      if(!nombre || !apellidop || !apellidom){
        mostrarError('Por favor completa todos los campos antes de continuar');
        return;
      }
      showStep(2);
    }

    function prevStep(){ showStep(1); }

    document.getElementById('registerForm').addEventListener('submit', function(e){
      const correo = document.getElementById('correo').value.trim();
      const password = document.getElementById('password').value.trim();
      const confirmar = document.getElementById('confirmar_password').value.trim();

      if(!correo || !password || !confirmar){
        e.preventDefault(); mostrarError('Por favor completa todos los campos requeridos'); return false;
      }
      if(password !== confirmar){ e.preventDefault(); mostrarError('Las contraseñas no coinciden'); return false; }
      if(password.length<6){ e.preventDefault(); mostrarError('La contraseña debe tener al menos 6 caracteres'); return false; }
    });

    function togglePassword(inputId, iconId){
      const input=document.getElementById(inputId);
      const icon=document.getElementById(iconId);
      if(!input || !icon) return;
      if(input.type==='password'){ input.type='text'; icon.classList.replace('fa-eye','fa-eye-slash'); }
      else { input.type='password'; icon.classList.replace('fa-eye-slash','fa-eye'); }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
