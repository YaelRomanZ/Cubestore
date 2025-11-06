<?php
session_start();

if (isset($_POST['index'])) {
    $index = intval($_POST['index']);
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // reindexar
    }
}

// Marcar para no animar al volver al carrito
$_SESSION['sin_animacion'] = true;

header("Location: carrito.php");
exit;
?>
