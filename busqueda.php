<?php
header('Content-Type: application/json');
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cubestore";

// Conexión
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Recibir la búsqueda
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if($q === ''){
    echo json_encode([]);
    exit;
}

// Buscar en la tabla Catalogo
$sql = "SELECT NomProducto AS nom, Precio AS precio, ImgProducto AS img 
        FROM Catalogo 
        WHERE NomProducto LIKE '%$q%' 
        LIMIT 4";  // Limita 5 resultados
$res = $conn->query($sql);

$resultados = [];
if($res){
    while($row = $res->fetch_assoc()){
        // Ajusta la ruta de la imagen si es necesario
        $resultados[] = $row;
    }
}

echo json_encode($resultados);
$conn->close();
?>
