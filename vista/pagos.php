<?php
session_start();
require_once '../modelo/conexion.php';

$cartData = isset($_GET['cart']) ? json_decode(urldecode($_GET['cart']), true) : [];
$total = 0;

// Calculate the total
foreach ($cartData as $item) {
    $total += floatval($item['precio']) * intval($item['cantidad']);
}

// Generate the Nequi payment link
$nequiLink = "https://recarga.nequi.com.co/bdigitalpsl/#!/?valor=" . $total;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago con Nequi - MERCACHI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .payment-window {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 600px;
        }
        .back-to-menu {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-to-menu:hover {
            background-color: #0056b3;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .instructions {
            margin-top: 20px;
            color: #666;
            text-align: left;
        }
        .cart-summary {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            text-align: left;
        }
        .cart-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="payment-window">
        <h2>Pago con Nequi - MERCACHI</h2>           
        <div class="nequi-barcode">
            <div id="qrcode"></div>
        </div>
        <div class="instructions">
            <p>1. Abre tu app de Nequi</p>
            <p>2. Escanea el código QR</p>
            <p>3. Confirma el pago en tu app</p>
        </div>
        <div class="cart-summary">
            <h3>Resumen de la compra:</h3>
            <?php foreach ($cartData as $item): ?>
                <div class="cart-item">
                    <strong><?php echo htmlspecialchars($item['nombre']); ?></strong><br>
                    Cantidad: <?php echo intval($item['cantidad']); ?><br>
                    Precio unitario: $<?php echo number_format(floatval($item['precio']), 2); ?><br>
                    Subtotal: $<?php echo number_format(floatval($item['precio']) * intval($item['cantidad']), 2); ?><br>
                    <em><?php echo htmlspecialchars($item['descripcion']); ?></em>
                </div>
            <?php endforeach; ?>
            <div class="total">
                Total a pagar: $<?php echo number_format($total, 2); ?>
            </div>
        </div>
        <a href="menuproductos.php" class="back-to-menu">Volver al Menú Principal</a>
    </div>

    <script>
        window.onload = function() {
            var qr = qrcode(0, 'M');
            qr.addData("<?php echo $nequiLink; ?>");
            qr.make();
            document.getElementById('qrcode').innerHTML = qr.createImgTag(6, 8);
        };
    </script>
</body>
</html>