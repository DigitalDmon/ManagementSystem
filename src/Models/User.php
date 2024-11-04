<?php

namespace Models;

use mysqli;
use Exception;

class User
{
    private mysqli $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function validateUser($username, $password): bool
    {
        try {
            $query = "SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
            }

            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            $isValidUser = $result && $result->num_rows > 0;

            $stmt->close();
            return $isValidUser;

        } catch (Exception $e) {
            error_log("Error en validateUser: " . $e->getMessage());
            return false;
        } finally {
            $this->conn->close();
        }
    }

    public function getUserById($id): false|array|null
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $name, $surname, $username, $identity, $email, $active, $password): void
    {
        $passwordHash = $this->hasPassSha256($password);
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, usuario = ?, cedula = ?, correo = ?, activo = ?, contrasena = ? WHERE id_usuarios = ?");
        $stmt->bind_param("sssisisi", $name, $surname, $username, $identity, $email, $active, $passwordHash, $id);
        $stmt->execute();
    }

    private function hasPassSha256($pass): string
    {
        return hash("sha256", $pass);
    }

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM usuarios";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}