<?php

namespace Controllers;

use JetBrains\PhpStorm\NoReturn;
use Models\Database;
use Models\User;
use includes\Redirect;
use Exception;

session_start();

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../../includes/Redirect.php';

class AuthController
{
    private Database $db;
    private User $userModel;
    private Redirect $redirect;

    public function __construct() {
        try {
            $this->db = new Database();
            $this->userModel = new User($this->db->getConnection());
            $this->redirect = new Redirect();
        } catch (Exception $e) {
            error_log("Error al inicializar AuthController: " . $e->getMessage());
            die("Error al cargar la aplicación. Por favor, intenta más tarde.");
        }
    }

    public function login(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['user'];
                $password = $_POST['pass'];

                if ($this->verifyInput($username, $password)) {
                    $_SESSION['error_message'] = "Usuario o contraseña no pueden estar vacíos";
                    $this->redirect->redirectTo("../../index.php");
                }

                $username = $this->cleanInput($username, $this->db->getConnection());
                $password = $this->cleanInput($password, $this->db->getConnection());
                $password = $this->hasPassSha256($password);

                if ($this->userModel->validateUser($username, $password)) {
                    $_SESSION['user'] = $username;
                    $this->redirect->redirectTo("../Views/welcome.php");
                } else {
                    $_SESSION['error_message'] = 'Nombre de usuario o contraseña incorrectos.';
                    $this->redirect->redirectTo("../../index.php");
                }
            }
        } catch (Exception $e) {
            error_log("Error en el proceso de login: " . $e->getMessage());
            $_SESSION['error_message'] = "Hubo un problema al iniciar sesión. Por favor, intenta más tarde.";
            $this->redirect->redirectTo("../../index.php");
        }
    }

    public function updateProfile(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = $_SESSION['user'];

                $stmt = $this->db->getConnection()->prepare("SELECT nombre, apellido, usuario, cedula, correo, contrasena FROM usuarios WHERE usuario = ?");
                $stmt->bind_param('s', $user);
                $stmt->execute();
                $result = $stmt->get_result();
                $currentData = $result->fetch_assoc();

                $updated_name = !empty($_POST['name']) ? $this->cleanInput($_POST['name'], $this->db->getConnection()) : $currentData['nombre'];
                $updated_lastname = !empty($_POST['lastname']) ? $this->cleanInput($_POST['lastname'], $this->db->getConnection()) : $currentData['apellido'];
                $updated_username = !empty($_POST['username']) ? $this->cleanInput($_POST['username'], $this->db->getConnection()) : $currentData['usuario'];
                $updated_identity = !empty($_POST['identity']) ? $this->cleanInput($_POST['identity'], $this->db->getConnection()) : $currentData['cedula'];
                $updated_email = !empty($_POST['email']) ? $this->cleanInput($_POST['email'], $this->db->getConnection()) : $currentData['correo'];
                $updated_password = !empty($_POST['password']) ? $this->hasPassSha256($_POST['password']) : $currentData['contrasena'];

                $fieldsToUpdate = [];
                $params = [];
                $types = '';

                if ($updated_name !== $currentData['nombre']) {
                    $fieldsToUpdate[] = "nombre = ?";
                    $params[] = $updated_name;
                    $types .= 's';
                }
                if ($updated_lastname !== $currentData['apellido']) {
                    $fieldsToUpdate[] = "apellido = ?";
                    $params[] = $updated_lastname;
                    $types .= 's';
                }
                if ($updated_username !== $currentData['usuario']) {
                    $fieldsToUpdate[] = "usuario = ?";
                    $params[] = $updated_username;
                    $types .= 's';
                }
                if ($updated_identity !== $currentData['cedula']) {
                    $fieldsToUpdate[] = "cedula = ?";
                    $params[] = $updated_identity;
                    $types .= 'i';
                }
                if ($updated_email !== $currentData['correo']) {
                    $fieldsToUpdate[] = "correo = ?";
                    $params[] = $updated_email;
                    $types .= 's';
                }
                if ($updated_password !== $currentData['contrasena']) {
                    $fieldsToUpdate[] = "contrasena = ?";
                    $params[] = $updated_password;
                    $types .= 's';
                }

                if (!empty($fieldsToUpdate)) {
                    $query = "UPDATE usuarios SET " . implode(", ", $fieldsToUpdate) . " WHERE usuario = ?";
                    $params[] = $user;
                    $types .= 's';

                    $stmt = $this->db->getConnection()->prepare($query);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        $_SESSION['update_message'] = "Perfil actualizado correctamente.";
                    } else {
                        $_SESSION['update_message'] = "No se realizaron cambios.";
                    }
                } else {
                    $_SESSION['update_message'] = "No se realizaron cambios, los datos son los mismos.";
                }
                $this->redirect->redirectTo("../Views/user-profile.php");
            }
        } catch (Exception $e) {
            error_log("Error al actualizar el perfil: " . $e->getMessage());
            $_SESSION['update_message'] = "Hubo un problema al actualizar el perfil. Intente nuevamente.";
        }
    }

    private function cleanInput($input, $connection): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = str_replace('*', '', $input);
        $input = mysqli_real_escape_string($connection, $input);
        return htmlspecialchars($input);
    }

    private function verifyInput($usuario, $password): bool
    {
        return empty($usuario) || empty($password);
    }

    private function hasPassSha256($password): string
    {
        return hash("sha256", $password);
    }

    #[NoReturn] public function logout(): void
    {
        try {
            session_destroy();
            $this->redirect->redirectTo("../../index.php");
        } catch (Exception $e) {
            error_log("Error al cerrar sesión: " . $e->getMessage());
            echo "Hubo un problema al cerrar sesión. Por favor, intenta más tarde.";
        }
    }
}

$authController = new AuthController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
    case 'updateProfile':
        $authController->updateProfile();
        break;
    default:
        echo "Acción no válida.";
        break;
}

