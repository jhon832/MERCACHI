<?php
session_start();
require_once '../modelo/conexion.php';

function addToCart($userId, $productId, $productDetails = null)
{
    $db = getDbConnection();
    try {
        $checkStmt = $db->prepare("SELECT id FROM productos WHERE id = :producto_id");
        $checkStmt->bindParam(':producto_id', $productId, PDO::PARAM_INT);
        $checkStmt->execute();

        if (!$checkStmt->fetch()) {
            if ($productDetails) {
                $insertStmt = $db->prepare("INSERT INTO productos (id, nombre, precio, categoria, imagen, descripcion) 
                                            VALUES (:id, :nombre, :precio, :categoria, :imagen, :descripcion)");
                $insertStmt->execute([
                    ':id' => $productId,
                    ':nombre' => $productDetails['nombre'],
                    ':precio' => $productDetails['precio'],
                    ':categoria' => $productDetails['categoria'],
                    ':imagen' => $productDetails['imagen'],
                    ':descripcion' => $productDetails['descripcion']
                ]);
            } else {
                return false;
            }
        }

        $stmt = $db->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) 
                              VALUES (:usuario_id, :producto_id, :cantidad)
                              ON DUPLICATE KEY UPDATE cantidad = cantidad + :nueva_cantidad");

        $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $productId, PDO::PARAM_INT);
        $cantidad = 1;
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':nueva_cantidad', $cantidad, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al agregar al carrito: " . $e->getMessage());
        return false;
    }
}

function getCart($userId)
{
    $db = getDbConnection();
    try {
        $stmt = $db->prepare("SELECT c.producto_id, c.cantidad, p.nombre, p.precio, p.imagen, p.descripcion 
                              FROM carrito c 
                              JOIN productos p ON c.producto_id = p.id 
                              WHERE c.usuario_id = :usuario_id");
        $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener el carrito: " . $e->getMessage());
        return [];
    }
}

function removeFromCart($userId, $productId)
{
    $db = getDbConnection();
    try {
        $stmt = $db->prepare("DELETE FROM carrito WHERE usuario_id = :usuario_id AND producto_id = :producto_id");

        $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $productId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al eliminar del carrito: " . $e->getMessage());
        return false;
    }
}

function updateCartQuantity($userId, $productId, $quantity)
{
    $db = getDbConnection();
    try {
        $stmt = $db->prepare("UPDATE carrito SET cantidad = :cantidad 
                              WHERE usuario_id = :usuario_id AND producto_id = :producto_id");

        $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $quantity, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al actualizar la cantidad: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = ['success' => false, 'message' => 'Operación no válida'];

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'add':
                    if (isset($data['product_id'])) {
                        $productDetails = null;

                        foreach ($products as $product) {
                            if ($product['id'] == $data['product_id']) {
                                $productDetails = $product;
                                break;
                            }
                        }
                        $success = addToCart($userId, $data['product_id'], $productDetails);
                        $response = ['success' => $success, 'message' => $success ? 'Producto agregado al carrito' : 'Error al agregar el producto'];
                    }
                    break;
                case 'remove':
                    if (isset($data['product_id'])) {
                        $success = removeFromCart($userId, $data['product_id']);
                        $response = ['success' => $success, 'message' => $success ? 'Producto eliminado del carrito' : 'Error al eliminar el producto'];
                    }
                    break;
                case 'update':
                    if (isset($data['product_id']) && isset($data['quantity'])) {
                        $success = updateCartQuantity($userId, $data['product_id'], $data['quantity']);
                        $response = ['success' => $success, 'message' => $success ? 'Cantidad actualizada' : 'Error al actualizar la cantidad'];
                    }
                    break;
                case 'get':
                    $cart = getCart($userId);
                    $response = ['success' => true, 'cart' => $cart];
                    break;
            }
        }
    } else {
        $response = ['success' => false, 'message' => 'Usuario no autenticado'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$db = getDbConnection();
$stmt = $db->prepare("SELECT * FROM productos WHERE categoria = 'Verduras'");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($products)) {
    $exampleProducts = [
        ['nombre' => 'Tomates', 'descripcion' => 'Frescos y llenos de sabor', 'precio' => 4500, 'imagen' => 'https://png.pngtree.com/png-vector/20210529/ourmid/pngtree-tomato-plant-red-vegetables-png-image_3369377.jpg', 'categoria' => 'Verduras'],
        ['nombre' => 'Zanahorias', 'descripcion' => 'Ricas en vitamina A', 'precio' => 8000, 'imagen' => 'https://img.freepik.com/foto-gratis/imagen-realista-monton-zanahorias-sobre-fondo-colorido_125540-3798.jpg', 'categoria' => 'Verduras'],
        ['nombre' => 'Pimientos', 'descripcion' => 'Colores variados y crujientes', 'precio' => 8000, 'imagen' => 'https://d2u1z1lopyfwlx.cloudfront.net/thumbnails/6c6c7d34-8924-5e84-ae84-979b2ffc7562/156432d4-bf93-5c25-88ff-d5a308e616e6.jpg', 'categoria' => 'Verduras'],
        ['nombre' => 'Brócoli', 'descripcion' => 'Ideal para ensaladas y guisos', 'precio' => 9500, 'imagen' => 'https://plus.unsplash.com/premium_photo-1724250160975-6c789dbfdc9f?fm=jpg&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fGJyb2Njb2xpfGVufDB8fDB8fHww&ixlib=rb-4.0.3&q=60&w=3000', 'categoria' => 'Verduras'],
        ['nombre' => 'Espinacas', 'descripcion' => 'Hojas verdes ricas en hierro', 'precio' => 6000, 'imagen' => 'https://www.conasi.eu/blog/wp-content/uploads/2023/07/recetas-con-espinacas-1.jpg', 'categoria' => 'Verduras'],
        ['nombre' => 'Papas', 'descripcion' => 'Versátiles y nutritivas', 'precio' => '7500 x/libra', 'imagen' => 'https://images.cookforyourlife.org/wp-content/uploads/2018/08/Roasted-Potatoes-e1443469101276.jpg', 'categoria' => 'Verduras'],
    ];

    $insertStmt = $db->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, categoria) VALUES (:nombre, :descripcion, :precio, :imagen, :categoria)");

    foreach ($exampleProducts as $product) {
        $insertStmt->execute($product);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VERDURAS - MERCACHI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --form-background: rgba(255, 255, 255, 0.25);
            --primary-color: #ffffff;
            --text-color: #ffffff;
            --placeholder-color: #e5e5e5;
            --neon-color: #00ffff;
            --button-bg: #4CAF50;
            --button-hover: #45a049;
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

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            min-height: 100vh;
            background-image: url("https://s2.abcstatics.com/abc/www/multimedia/bienestar/2024/04/30/verduras-pocas-calorias-R3Vfbjx9Nok0nxp9QObH7xH-1200x840@diario_abc.jpg");
            background-color: rgba(0, 0, 0, 0.7);
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            margin: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 2.5em;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 10px var(--neon-color), 0 0 20px var(--neon-color), 0 0 30px var(--neon-color);
            animation: neon-flicker 1.5s infinite alternate;
            text-align: center;
            margin-bottom: 40px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-item {
            background-color: var(--form-background);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.4);
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-item h2 {
            font-size: 1.5em;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--neon-color);
            margin-bottom: 10px;
        }

        .product-item p {
            font-size: 14px;
            color: var(--placeholder-color);
            margin-bottom: 10px;
        }

        .product-item .price {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--neon-color);
            margin-bottom: 15px;
        }

        .button {
            background-color: var(--button-bg);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: var(--button-hover);
            transform: scale(1.05);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: #080710;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: center;
        }

        .back-link:hover {
            background-color: var(--text-color);
            box-shadow: 0 0 20px var(--primary-color);
            transform: scale(1.05);
        }

        #notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 255, 0, 0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            display: none;
        }

        .sheet {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 400px;
            background-color: rgba(254, 254, 254, 0.9);
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            color: #333;
            backdrop-filter: blur(10px);
        }

        .sheet.open {
            transform: translateX(0);
        }

        .sheet-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e5e5;
        }

        .sheet-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin: 0;
        }

        .sheet-content {
            padding: 1rem;
            overflow-y: auto;
            max-height: calc(100vh - 120px);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem;
            border-radius: 5px;
        }

        .quantity-input {
            width: 3rem;
            text-align: center;
            margin: 0 0.5rem;
        }

        .total {
            font-weight: bold;
            margin-top: 1rem;
        }

        #cartButton {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1000;
            background-color: var(--neon-color);
            color: #000;
            padding: 10px 20px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            box-shadow: 0 0 10px var(--neon-color);
        }

        #cartButton:hover {
            background-color: #00cccc;
        }

        .close-button {
            position: absolute;
            right: 10px;
            top: 10px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="container">
        <h1>VERDURAS-MERCACHI</h1>

        <div id="notification" role="alert" aria-live="assertive"></div>

        <div id="products" class="grid"></div>

        <a href="menuproductos.php" class="back-link">Volver al Menú Principal</a>
    </div>

    <button id="cartButton" class="button" aria-label="Ver Carrito">
        <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Ver Carrito (<span id="cartCount">0</span>)
    </button>

    <div id="overlay"></div>

    <div id="cartSheet" class="sheet" aria-hidden="true">
        <button id="closeCartButton" class="close-button" aria-label="Cerrar carrito">&times;</button>
        <div class="sheet-header">
            <h2 class="sheet-title">Carrito de Compras</h2>
        </div>
        <div class="sheet-content">
            <div id="cartItems"></div>
            <div id="cartTotal" class="total"></div>
            <button id="checkoutButton" class="button" style="width: 100%; margin-top: 1rem;">Proceder al pago</button>
        </div>
    </div>

    <script>
        const products = <?php echo json_encode($products); ?>;
        let cart = [];

        function initializeCart() {
            fetch('verduras.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'get' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = data.cart;
                    updateCartUI();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function renderProducts() {
            const productsContainer = document.getElementById('products');
            productsContainer.innerHTML = '';
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-item';
                productCard.innerHTML = `
                    <img src="${product.imagen}" alt="${product.nombre}" onerror="this.src='https://via.placeholder.com/150'">
                    <h2>${product.nombre}</h2>
                    <p>${product.descripcion}</p>
                    <div class="price">${typeof product.precio === 'number' ? '$' + parseFloat(product.precio).toFixed(2) : product.precio}</div>
                    <button class="button" onclick="addToCart(${product.id})">Agregar al carrito</button>
                `;             
                productsContainer.appendChild(productCard);
            });
        }

        function addToCart(productId) {
            const product = products.find(p => p.id == productId);
            if (product) {
                fetch('verduras.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        action: 'add', 
                        product_id: product.id, 
                        quantity: 1,
                        nombre: product.nombre,
                        precio: typeof product.precio === 'string' ? product.precio : parseFloat(product.precio).toFixed(2),
                        categoria: product.categoria,
                        imagen: product.imagen,
                        descripcion: product.descripcion
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const existingItem = cart.find(item => item.producto_id == productId);
                        if (existingItem) {
                            existingItem.cantidad += 1;
                        } else {
                            cart.push({ ...product, producto_id: product.id, cantidad: 1, precio: typeof product.precio === 'string' ? product.precio : parseFloat(product.precio) });
                        }
                        updateCartUI();
                        showNotification(product.nombre + ' se añadió correctamente al carrito');
                    } else {
                        console.error('Failed to add product to cart:', data.message);
                        showNotification('Error al añadir el producto al carrito');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    showNotification('Error al añadir el producto al carrito');
                });
            }
        }

        function removeFromCart(productId) {
            fetch('verduras.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'remove', product_id: productId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cart = cart.filter(item => item.producto_id != productId);
                    updateCartUI();
                    showNotification('Producto eliminado del carrito');
                } else {
                    console.error('Failed to remove product from cart:', data.message);
                    showNotification('Error al eliminar el producto del carrito');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Error al eliminar el producto del carrito');
            });
        }

        function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1) return;
            fetch('verduras.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'update', product_id: productId, quantity: newQuantity }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = cart.find(item => item.producto_id == productId);
                    if (item) {
                        item.cantidad = newQuantity;
                        updateCartUI();
                    }
                } else {
                    console.error('Failed to update cart quantity:', data.message);
                    showNotification('Error al actualizar la cantidad');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                showNotification('Error al actualizar la cantidad');
            });
        }

        function updateCartUI() {
            const cartItemsContainer = document.getElementById('cartItems');
            const cartTotalElement = document.getElementById('cartTotal');
            const cartCountElement = document.getElementById('cartCount');

            cartItemsContainer.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <span>${item.nombre}</span>
                    <div>
                        <button class="button" onclick="updateQuantity(${item.producto_id}, ${item.cantidad - 1})">-</button>
                        <input type="number" value="${item.cantidad}" min="1" class="quantity-input" onchange="updateQuantity(${item.producto_id}, parseInt(this.value))">
                        <button class="button" onclick="updateQuantity(${item.producto_id}, ${item.cantidad + 1})">+</button>
                    </div>
                    <span>${typeof item.precio === 'string' ? item.precio : '$' + (item.precio * item.cantidad).toFixed(2)}</span>
                    <button class="button" onclick="removeFromCart(${item.producto_id})">X</button>
                `;
                cartItemsContainer.appendChild(itemElement);
                total += typeof item.precio === 'string' ? parseFloat(item.precio) * item.cantidad : item.precio * item.cantidad;
            });

            cartTotalElement.textContent = `Total: $${total.toFixed(2)}`;
            cartCountElement.textContent = cart.reduce((sum, item) => sum + item.cantidad, 0);
        }

        function toggleCart() {
            const cartSheet = document.getElementById('cartSheet');
            const overlay = document.getElementById('overlay');
            cartSheet.classList.toggle('open');
            overlay.style.display = cartSheet.classList.contains('open') ? 'block' : 'none';
        }

        function closeCart() {
            const cartSheet = document.getElementById('cartSheet');
            const overlay = document.getElementById('overlay');
            cartSheet.classList.remove('open');
            overlay.style.display = 'none';
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        document.getElementById('cartButton').addEventListener('click', toggleCart);
        document.getElementById('closeCartButton').addEventListener('click', closeCart);
        document.getElementById('overlay').addEventListener('click', closeCart);

        document.getElementById('checkoutButton').addEventListener('click', () => {
            if (cart.length === 0) {
                showNotification('El carrito está vacío');
                return;
            }
            
            const encodedCart = encodeURIComponent(JSON.stringify(cart));
            
            window.location.href = `pagos.php?cart=${encodedCart}`;
        });

        renderProducts();
        initializeCart();
    </script>
</body>
</html>

