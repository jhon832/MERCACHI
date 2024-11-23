<?php
include '../modelo/conexion.php';
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $message = "Por favor, complete todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Correo electrónico inválido.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Las contraseñas no coinciden.";
    } else {
        $conn = getDbConnection();
        $sql = "SELECT * FROM usuarios WHERE correo = :correo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE usuarios SET contraseña = :contrasena WHERE correo = :correo";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':contrasena', $hashed_password, PDO::PARAM_STR);
            $update_stmt->bindParam(':correo', $email, PDO::PARAM_STR);

            if ($update_stmt->execute()) {
                // Mensaje de éxito al crear la nueva contraseña
                $message = "Contraseña nueva creada exitosamente. Puedes iniciar sesión.";
                header("Location: login.php?message=" . urlencode($message)); // Pasar el mensaje a la página de login
                exit();
            } else {
                $message = "Error al actualizar la contraseña. Inténtalo de nuevo.";
            }
        } else {
            $message = "El correo electrónico no está registrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - MERCACHI Supermercado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --success-color: #2ecc71;
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
            justify-content: center;
            align-items: center;
        }

        .password-reset-box {
            display: flex;
            max-width: 800px;
            width: 90%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .background-image {
            flex: 1;
            background-image: url('https://dicocustom.com/wp-content/themes/yootheme/cache/4f/IMG_6093-1-4f52ef63.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 400px;
        }

        .form-container {
            flex: 1;
            padding: 30px;
            background-color: var(--form-background);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 10px 15px;
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
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        .success-message {
            background-color: rgba(46, 204, 113, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .password-reset-box {
                flex-direction: column;
            }

            .background-image {
                min-height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Crear Nueva Contraseña</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'exitosamente') !== false ? 'success-message' : 'error-message' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
            </div>
            <div class="input-group">
                <label for="new_password">Nueva Contraseña</label>
                <input type="password" id="new_password" name="new_password" placeholder="Ingrese su nueva contraseña" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme su nueva contraseña" required>
            </div>
            <button type="submit" class="submit-btn">Crear Contraseña</button>
        </form>
        <div class="options">
            <a href="login.php">Volver al Inicio de Sesión</a>
        </div>
    </div>
</body>
</html>