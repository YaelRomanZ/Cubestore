<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';
include '../ComercioE2/Configuraciones/conexion.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])){
    $correo = trim($_POST['correo']);

    // Validación rápida
    if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        echo json_encode(['status'=>'error','msg'=>'Correo no válido.']);
        exit;
    }

    $stmt = $conex->prepare("SELECT id, nombre, reset_expiry FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $userName = $row['nombre'];
        $lastExpiry = strtotime($row['reset_expiry']);

        $ahora = time();
        $waitTime = 60; // segundos entre soli

        // Control de espera de 60s
        if($lastExpiry && ($ahora < $lastExpiry - 3600 + $waitTime)){
            $faltan = ($lastExpiry - 3600 + $waitTime) - $ahora;
            echo json_encode(['status'=>'wait','msg'=>"Debes esperar $faltan segundos antes de solicitar otro enlace.", 'faltan'=>$faltan]);
            exit;
        }

        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt2 = $conex->prepare("UPDATE usuarios SET reset_token=?, reset_expiry=? WHERE id=?");
        $stmt2->bind_param("ssi", $token, $expiry, $userId);
        $stmt2->execute();

        $resetLink = "http://localhost/Comercioe2/resetpassword.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'froigama@gmail.com';
            $mail->Password   = 'pdkp cfrd ecyi jcxg';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom('no-reply@cubestore.com', 'CubeStore');
            $mail->addAddress($correo, $userName);

            // Incrustamos el logo
            $mail->addEmbeddedImage('img/banner.png', 'logo_cubestore');

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña CubeStore';

            $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: 'Poppins', sans-serif; background:#f5f6fa; margin:0; padding:0; }
                    .container { max-width:600px; margin:40px auto; background:#fff; border-radius:15px; padding:30px; box-shadow:0 10px 25px rgba(0,0,0,0.1); }
                    .header { text-align:center; margin-bottom:20px; }
                    .header img { max-width:120px; }
                    h2 { color:#ff8479; text-align:center; }
                    p { color:#555; line-height:1.6; }
                    .btn { display:inline-block; border: 2px solid #000; background:#fff; color:#fff; padding:12px 25px; border-radius:8px; text-decoration:none; font-weight:600; margin-top:20px; }
                    .footer { text-align:center; font-size:12px; color:#aaa; margin-top:30px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <img src='cid:logo_cubestore' alt='CubeStore Logo'>
                    </div>
                    <h2>Recuperación de contraseña</h2>
                    <p>Hola <strong>$userName</strong>,<br>
                    Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                    <div style='text-align:center;'>
                        <a href='$resetLink' style='display:inline-block; background:#ff8479; color:#fff; padding:12px 25px; border-radius:8px; text-decoration:none; font-weight:600; margin-top:20px;'>Restablecer contraseña</a>
                    </div>
                    <p>Este enlace expirará en 1 hora. Si no solicitaste este cambio, puedes ignorar este correo.</p>
                    <div class='footer'>
                        &copy; 2025 CubeStore Inc. Todos los derechos reservados.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
            echo json_encode(['status'=>'success','msg'=>'Se ha enviado un correo con el enlace de recuperación.','faltan'=>$waitTime]);

        } catch (Exception $e) {
            echo json_encode(['status'=>'error','msg'=>'Error al enviar el correo: '.$mail->ErrorInfo]);
        }

    } else {
        echo json_encode(['status'=>'error','msg'=>'El correo no está registrado.']);
    }
}
?>
