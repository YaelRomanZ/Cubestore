<?php
$host = 'localhost';
$user = 'root';
$password = ''; 
$database = 'cubestore';

$conex = new mysqli($host, $user, $password, $database);

if ($conex->connect_error) {
    die("Conexión fallida: " . $conex->connect_error);
}

?>
