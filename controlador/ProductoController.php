<?php
require_once __DIR__ . '/../modelo/conexion.php';
require_once __DIR__ . '/../modelo/Producto.php';

class ProductoController {
    private $db;
    private $producto;

    public function __construct() {
        $this->db = getDbConnection();
        if (!$this->db) {
            throw new Exception("Error al conectar con la base de datos.");
        }
        $this->producto = new Producto($this->db);
    }

    public function agregarProducto($producto) {
        $this->producto->nombre = $producto['name'];
        $this->producto->precio = $producto['price'];
        $this->producto->categoria = $producto['category'];
        $this->producto->imagen = $producto['image'];
        $this->producto->descripcion = $producto['description'];

        try {
            if($this->producto->create()) {
                return ['success' => true, 'message' => "Producto {$producto['name']} agregado con éxito."];
            } else {
                return ['success' => false, 'message' => "Error al agregar el producto {$producto['name']}."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Error al agregar el producto {$producto['name']}: " . $e->getMessage()];
        }
    }

    // Existing agregarProductos method remains unchanged
}