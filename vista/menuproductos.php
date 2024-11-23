<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - MERCACHI</title>
   
    <style>
        :root {
            --form-background: rgba(0, 0, 0, 0.7);
            --primary-color: #ffffff;
            --text-color: #ffffff;
            --placeholder-color: #e5e5e5;
            --neon-color: #00ffff;
        }

        @keyframes neon-flicker {
            0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
                text-shadow:
                    -0.2rem -0.2rem 1rem #fff,
                    0.2rem 0.2rem 1rem #fff,
                    0 0 2rem var(--neon-color),
                    0 0 4rem var(--neon-color),
                    0 0 6rem var(--neon-color),
                    0 0 8rem var(--neon-color),
                    0 0 10rem var(--neon-color);
            }
            20%, 24%, 55% {
                text-shadow: none;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background: url('https://files.oaiusercontent.com/file-pdDJGJujAFMehbyCLLPnlWcO?se=2024-11-18T17%3A06%3A24Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3D6610a817-ac1d-4e98-bf77-822334950eb5.webp&sig=KnGNiXOT6Rs%2BnPEQt1lou3NAD75%2BHtVSaxj9Z9IZTEU%3D') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            padding: 20px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); 
            z-index: 0;
        }

        .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 1200px;
        margin-bottom: 40px;
        z-index: 2;
        position: relative;
    }

    #menu-title {     
        font-size: 3em;
        font-weight: bold;
        color: var(--primary-color);
        text-shadow: 0 0 10px var(--neon-color), 0 0 20px var(--neon-color), 0 0 30px var(--neon-color);
        text-align: center;
        position: relative;
        z-index: 2;
        flex-grow: 1;
    }

    #logout-btn {
        background-color: #ff4136;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }

    #logout-btn:hover {
        background-color: #ff1a1a;
    }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            width: 100%;
            margin: 20px auto;
            z-index: 1;
            position: relative;
        }

        .product-item {
            background-color: var(--form-background);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: pulse 2s infinite;
        }

        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        .product-item a {
            font-size: 1.5em;
            font-weight: bold;
            color: var(--neon-color);
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .product-item a:hover {
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--neon-color), 0 0 10px var(--neon-color);
        }

        .product-item p {
            font-size: 14px;
            color: var(--placeholder-color);
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            #menu-title {
                font-size: 2em;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="header">
        <h1 id="menu-title">MENÚ DE PRODUCTOS</h1>
        <button id="logout-btn" onclick="handleLogout()">Cerrar Sesión</button>
    </div>
    
    <div class="container">
        <div class="product-item">
            <a href="bebidas.php">Bebidas</a>
            <p>Las mejores bebidas</p>
        </div>
        <div class="product-item">
            <a href="verduras.php">Verduras</a>
            <p>Todas las verduras que puedas encontrar</p>
        </div>
        <div class="product-item">
            <a href="frutas.php">Frutas</a>
            <p>Frutas frescas</p>
        </div>
        <div class="product-item">
            <a href="granos.php">Granos</a>
            <p>El mejor grano del mercado</p>
        </div>
        <div class="product-item">
            <a href="higiene.php">Higiene</a>
            <p>Productos de higiene</p>
        </div>
        <div class="product-item">
            <a href="carnes.php">Carnes</a>
            <p>Altas en proteina</p>
        </div>
        <div class="product-item">
            <a href="dulces.php">Dulces</a>
            <p>Los mejores dulces, golosinas</p>
        </div>
    </div>

    <script>
        function handleLogout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                window.location.href = 'login.php';
            }
        }
    </script>
</body>
</html>