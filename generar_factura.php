<?php
ob_start();
session_start();
include 'Configuraciones/conexion.php';
require('../ComercioE2/fpdf186/fpdf.php');

if (!isset($_SESSION['usuario_id']) || !isset($_GET['pedido_id'])) {
    header("Location: perfil.php");
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']);
$pedido_id = intval($_GET['pedido_id']);

$sql = "
    SELECT p.*, d.calle, d.ciudad, d.estado, d.cp,
           u.nombre, u.apellido_paterno, u.apellido_materno, u.correo
    FROM pedidos p
    LEFT JOIN direcciones d ON p.direccion_id = d.id
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.id = $pedido_id AND p.usuario_id = $usuario_id
    LIMIT 1
";
$pedido = $conex->query($sql)->fetch_assoc();

if (!$pedido) {
    ob_end_clean();
    die("Pedido no encontrado o sin permiso.");
}

$detalle = $conex->query("SELECT * FROM detalle_pedidos WHERE pedido_id = $pedido_id");

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('img/logo.png', 10, 3, 25);
        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(33,37,41);
        $this->Cell(0, 10, utf8_decode('Factura de Compra - CubeStore'), 0, 1, 'C');
        $this->Ln(5);
        // Línea separadora debajo del título
        $this->SetDrawColor(180,180,180);
        $this->Line(10, 30, 200, 30);
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',9);
        $this->SetTextColor(120,120,120);
        $this->Cell(0,10,utf8_decode('Gracias por tu compra en CubeStore'),0,1,'C');
        $this->Cell(0,5,'© '.date('Y').' CubeStore - Todos los derechos reservados',0,0,'C');
    }

    function InfoCliente($pedido) {
        $this->SetFont('Arial','B',12);
        $this->Cell(0,8,'Datos del Cliente',0,1,'L');
        $this->SetFont('Arial','',11);
        $this->SetFillColor(245,245,245);

        $nombreCompleto = trim(($pedido['nombre'] ?? '') . ' ' . ($pedido['apellido_paterno'] ?? '') . ' ' . ($pedido['apellido_materno'] ?? ''));
        $correo = $pedido['correo'] ?? '';
        $direccion = trim(($pedido['calle'] ?? '') . ', ' . ($pedido['ciudad'] ?? '') . ', ' . ($pedido['estado'] ?? '') . ' CP:' . ($pedido['cp'] ?? ''));

        $text = utf8_decode("Nombre: $nombreCompleto\nCorreo: $correo\nDirección: $direccion");
        $this->MultiCell(0,7, $text, 0, 'L', true);
        $this->Ln(6);

        $this->SetFont('Arial','B',12);
        $this->Cell(0,8,'Detalles del Pedido',0,1,'L');
        $this->SetFont('Arial','',11);
        $infoPedido = "Pedido ID: ".$pedido['id']."    Fecha: ".$pedido['fecha'];
        $this->Cell(0,7,utf8_decode($infoPedido),0,1,'L',true);
        $this->Ln(6);
    }

    function TablaProductos($detalle) {
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(230,230,230);
        $this->Cell(82.5,10,'Producto',1,0,'C',true);
        $this->Cell(32.5,10,'Precio',1,0,'C',true);
        $this->Cell(22.5,10,'Cant.',1,0,'C',true);
        $this->Cell(42.5,10,'Total',1,1,'C',true);

        $this->SetFont('Arial','',11);
        $fill = false;
        while ($item = $detalle->fetch_assoc()) {
            $nombre = utf8_decode($item['producto_nombre'] ?? 'Producto');
            $precio = isset($item['producto_precio']) ? floatval($item['producto_precio']) : 0.00;
            $cantidad = isset($item['cantidad']) ? intval($item['cantidad']) : 0;
            $total  = isset($item['total']) ? floatval($item['total']) : $precio * $cantidad;

            $this->Cell(82.5,8,$nombre,1,0,'L',$fill);
            $this->Cell(32.5,8,'$'.number_format($precio,2),1,0,'C',$fill);
            $this->Cell(22.5,8,$cantidad,1,0,'C',$fill);
            $this->Cell(42.5,8,'$'.number_format($total,2),1,1,'C',$fill);

            $fill = !$fill;
        }
        $this->Ln(8);
    }

    function Totales($pedido) {
        $this->SetFont('Arial','B',12);
        $this->SetFillColor(245,245,245);
        $this->SetX(120);

        $this->Cell(40,8,'Subtotal',1,0,'R',true);
        $this->Cell(30,8,'$'.number_format($pedido['subtotal'],2),1,1,'R');

        $this->SetX(120);
        $this->Cell(40,8,'Envio',1,0,'R',true);
        $this->Cell(30,8,'$'.number_format($pedido['envio'],2),1,1,'R');

        $this->SetX(120);
        $this->Cell(40,8,'IVA (16%)',1,0,'R',true);
        $this->Cell(30,8,'$'.number_format($pedido['iva'],2),1,1,'R');

        $this->SetX(120);
        $this->SetFillColor(230,230,250);
        $this->Cell(40,10,'TOTAL',1,0,'R',true);
        $this->Cell(30,10,'$'.number_format($pedido['total'],2),1,1,'R',true);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->InfoCliente($pedido);
$pdf->TablaProductos($detalle);
$pdf->Totales($pedido);
ob_end_clean();
$pdf->Output('D', 'Factura_Pedido_'.$pedido_id.'.pdf');
exit;
?>
