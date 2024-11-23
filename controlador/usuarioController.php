<?php
require_once __DIR__ . '/../modelo/conexion.php';

class UsuarioController
{
    private $conn;

    public function __construct()
    {
        $this->conn = getDbConnection();
        if (!$this->conn) {
            throw new Exception("Error al conectar con la base de datos.");
        }
    }

    // Crear usuario
    public function createUser($correo, $contrasena)
    {
        try {
            if ($this->getUserByEmail($correo)) {
                return ['success' => false, 'message' => 'El correo ya está registrado.'];
            }

            $contrasenaHasheada = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (correo, contraseña, fecha_registro) VALUES (:correo, :contrasena, NOW())";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $this->conn->errorInfo()[2]);
            }

            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasenaHasheada, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Usuario creado exitosamente.', 'id' => $this->conn->lastInsertId()];
            }

            return ['success' => false, 'message' => 'Error al crear usuario.'];
        } catch (Exception $e) {
            error_log("Error en createUser: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Obtener usuario por correo
    public function getUserByEmail($correo)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $this->conn->errorInfo()[2]);
            }

            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getUserByEmail: " . $e->getMessage());
            return null;
        }
    }

    // Validar inicio de sesión
    public function login($correo, $contrasena)
    {
        try {
            $usuario = $this->getUserByEmail($correo);
            if (!$usuario || !password_verify($contrasena, $usuario['contraseña'])) {
                return ['success' => false, 'message' => 'Correo o contraseña incorrectos.'];
            }

            return ['success' => true, 'message' => 'Inicio de sesión exitoso.', 'id' => $usuario['id']];
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Actualizar contraseña
    public function updatePassword($id, $nueva_contrasena)
    {
        try {
            $contrasenaHasheada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contraseña = :contrasena WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':contrasena', $contrasenaHasheada, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Contraseña actualizada exitosamente.'];
            }

            return ['success' => false, 'message' => 'Error al actualizar la contraseña.'];
        } catch (Exception $e) {
            error_log("Error en updatePassword: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Eliminar usuario
    public function deleteUser($id)
    {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Usuario eliminado exitosamente.'];
            }

            return ['success' => false, 'message' => 'Error al eliminar usuario.'];
        } catch (Exception $e) {
            error_log("Error en deleteUser: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}