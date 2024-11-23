<?php
require_once __DIR__ . '/../modelo/conexion.php';

class CarritoController {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
        if (!$this->db) {
            throw new Exception("Error al conectar con la base de datos.");
        }
    }

    public function agregarAlCarrito($userId, $productId, $quantity) {
        try {
            $query = "INSERT INTO carrito (usuario_id, producto_id, cantidad) 
                      VALUES (:usuario_id, :producto_id, :cantidad) 
                      ON DUPLICATE KEY UPDATE cantidad = cantidad + :nueva_cantidad";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':producto_id', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':nueva_cantidad', $quantity, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Producto agregado al carrito.'];
            } else {
                return ['success' => false, 'message' => 'Error al agregar el producto al carrito.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}