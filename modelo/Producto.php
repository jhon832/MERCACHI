<?php
class Producto {
    private $conn;
    private $table = 'productos';

    public $id;
    public $nombre;
    public $precio;
    public $categoria;
    public $imagen;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, precio, categoria, imagen, descripcion)
                  VALUES (:nombre, :precio, :categoria, :imagen, :descripcion)";

        try {
            $stmt = $this->conn->prepare($query);

            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->precio = htmlspecialchars(strip_tags($this->precio));
            $this->categoria = htmlspecialchars(strip_tags($this->categoria));
            $this->imagen = htmlspecialchars(strip_tags($this->imagen));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':categoria', $this->categoria);
            $stmt->bindParam(':imagen', $this->imagen);
            $stmt->bindParam(':descripcion', $this->descripcion);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("A database error occurred: " . $e->getMessage());
        }
    }
}