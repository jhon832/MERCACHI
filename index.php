<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MERCACHI Supermercado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --background: #f0f8ff;
            --form-background: rgba(255, 255, 255, 0.95);
            --primary-color: #2c3e50;
            --text-color: #34495e;
            --accent-color: #e74c3c;
            --button-color: #27ae60;
            --button-hover: #2ecc71;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            width: 90%;
            background-color: var(--form-background);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 20px var(--shadow);
            text-align: center;
            transform: scale(0.98);
            animation: fadeIn 1.2s ease-in-out forwards;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            animation: logoBounce 1.5s ease infinite;
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
            animation: rotateLogo 2s infinite;
        }

        @keyframes logoBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes rotateLogo {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .brand-name {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        h1 {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 30px;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        p {
            font-size: 18px;
            color: var(--text-color);
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .button {
            padding: 15px 30px;
            background-color: var(--button-color);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: var(--button-hover);
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .manual-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .manual-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .logo {
                width: 70px;
                height: 70px;
            }

            .brand-name {
                font-size: 30px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <button class="manual-btn" onclick="window.open('../vista/MANUALUSUARIO .pdf', '_blank')">Manual de Usuario</button>
    <div class="container">
        <div class="logo-container"> 
            <img src="https://img.freepik.com/premium-vector/supermarket-logo_23-2148490224.jpg" alt="MERCACHI Logo" class="logo">
            <span class="brand-name">MERCACHI</span>
        </div>
        <h1>Bienvenido a MERCACHI Supermercado</h1>
        <p>Tu destino para compras de calidad y precios increíbles.</p>
        <div class="button-container">
            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="../vista/login.php" class="button">Iniciar Sesión</a>
                
            <?php else: ?>
               
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
