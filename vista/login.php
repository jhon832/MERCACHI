<?php
session_start();
$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
require_once '../modelo/conexion.php';

$conn = getDbConnection();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'];

    if (empty($correo) || empty($contrasena)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        $sql = "SELECT id, contraseña FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['id'];
                $contrasena_bd = $row['contraseña'];

                if (password_verify($contrasena, $contrasena_bd)) {
                    $_SESSION['user_id'] = $user_id;
                    header("Location: menuproductos.php");
                    exit();
                } else {
                    $error_message = "Correo o contraseña incorrectos.";
                }
            } else {
                $error_message = "Correo o contraseña incorrectos.";
            }
        } else {
            $error_message = "Error al ejecutar consulta: " . $conn->errorInfo()[2];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MERCACHI Supermercado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --background: #f0f8ff;
            --form-background: rgba(255, 255, 255, 0.95);
            --primary-color: #2c3e50;
            --text-color: #34495e;
            --placeholder-color: #7f8c8d;
            --accent-color: #e74c3c;
            --button-color: #27ae60;
            --button-hover: #2ecc71;
            --error-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f8ff;
            color: #34495e;
            position: relative;
        }

        .login-box {
            display: flex;
            max-width: 800px;
            width: 90%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .manual-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: var(--button-color);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }

        .manual-btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
        }

        .background-image {
            flex: 1;
            background-image: url('https://dicocustom.com/wp-content/themes/yootheme/cache/4f/IMG_6093-1-4f52ef63.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 400px;
        }

        .login-container {
            flex: 1;
            padding: 30px;
            background-color: var(--form-background);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-right: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            background-color: rgba(46, 204, 113, 0.1);
            border: 1px solid #2ecc71;
            color: #2ecc71;
            position: absolute;
            top: 10px; /* Ajusta la posición vertical */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            text-align: center;
            width: 80%;
            max-width: 300px; /* Reduce el ancho del mensaje */
            font-size: 14px; /* Reduce el tamaño de la fuente */
        }
        
        .form-container {
           
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }
        
        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        h1 {
            font-size: 22px;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 10px 15px 10px 35px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 16px;
            color: var(--text-color);
            transition: border-color 0.3s ease, transform 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .input-group input::placeholder {
            color: var(--placeholder-color);
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 38px;
            color: var(--placeholder-color);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--button-color);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .submit-btn:hover {
            background-color: var(--button-hover);
            transform: translateY(-2px);
        }

        .options {
            margin-top: 15px;
            text-align: center;
        }

        .options a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .options a:hover {
            color: #c0392b;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
            animation: shake 0.82s cubic-bezier(.36, .07, .19, .97) both;
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }

        @media (max-width: 768px) {
            .login-box {
                flex-direction: column;
            }

            .background-image {
                min-height: 200px;
            }
        }
    </style>
</head>

<body>
<?php if ($message): ?>
        <div class="message" id="message">
            <?= $message ?>
        </div>
    <?php endif; ?>
<button class="manual-btn" onclick="window.open('../vista/MANUALUSUARIO .pdf', '_blank')">Manual de Usuario</button>
    <div class="login-box">
        <div class="background-image"></div>
        <div class="login-container">
            <div class="logo-container">
                <img src="https://img.freepik.com/premium-vector/supermarket-logo_23-2148490224.jpg" alt="MERCACHI Logo" class="logo">
                <span class="brand-name">MERCACHI</span>
            </div>
            <h1>Iniciar Sesión</h1>
            <?php
            if (!empty($error_message)) {
                echo "<div class='message error-message'>$error_message</div>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">
                <div class="input-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required value="<?php echo htmlspecialchars($correo); ?>">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="input-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                    <i class="fas fa-lock"></i>
                </div>
                <button type="submit" class="submit-btn">Iniciar Sesión</button>
            </form>
            <div class="options">
                <a href="registrarse.php">¿No tienes una cuenta? Regístrate</a>
                <br>
                <a href="password.php">¿Olvidaste tu contraseña?</a>
                <br>
                 <a href="../index.php">Volver al inicio</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            var correo = document.getElementById('correo').value;
            var contrasena = document.getElementById('contrasena').value;

            if (!correo || !contrasena) {
                e.preventDefault();
                alert('Por favor, complete todos los campos.');
            }
        });
    </script>
    <script>

        window.onload = function() {
            const messageElement = document.getElementById('message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 5000); 
            }
        };
    </script>
</body>

</html>