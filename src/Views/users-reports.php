<?php
session_start();

use Controllers\UserController;
use Models\Database;
use includes\Redirect;

require_once '../../src/Controllers/UserController.php';
require_once '../../src/Models/Database.php';
require_once '../../includes/Redirect.php';

$redirect = new Redirect();

try {
    if (!isset($_SESSION["user"])) {
        $redirect->redirectTo("../../index.php");
    }
} catch (Exception $ex) {
    echo "Se produjo un error: " . $ex->getMessage();
    exit();
}

$database = new Database();
$connection = $database->getConnection();
$userController = new UserController($connection);
$users = $userController->showUsers();
$selectedUser = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user_id'])) {
    $selectedUser = $userController->getUserById($_POST['edit_user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $userController->updateUser($_POST);
    $redirect->redirectTo("users-reports.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Reports</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="welcome.php">Welcome</a></li>
            <li><a href="events-reports.php">Events reports</a></li>
            <li><a href="users-reports.php">User reports</a></li>
            <li><a href="user-profile.php">Profile</a></li>
            <li><a href="../Controllers/AuthController.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <section class="user-list">
        <h2>User Reports</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Cedula</th>
                <th>Correo</th>
                <th>Activo</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= ($user['id_usuarios']); ?></td>
                    <td><?= ($user['nombre']); ?></td>
                    <td><?= ($user['apellido']); ?></td>
                    <td><?= ($user['usuario']); ?></td>
                    <td><?= ($user['cedula']); ?></td>
                    <td><?= ($user['correo']); ?></td>
                    <td><?= ($user['activo']); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="edit_user_id" value="<?= htmlspecialchars($user['id_usuarios']); ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section class="edit-panel">
        <?php if ($selectedUser): ?>
            <h2>Edit User</h2>
            <form method="post">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="id" value="<?= $selectedUser['id_usuarios']; ?>">

                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="<?= $selectedUser['nombre']; ?>"><br>

                <label for="surname">Apellido:</label>
                <input type="text" id="surname" name="surname" value="<?= $selectedUser['apellido']; ?>"><br>

                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?= $selectedUser['usuario']; ?>"><br>

                <label for="identity">Cedula:</label>
                <input type="text" id="identity" name="identity" value="<?= $selectedUser['cedula']; ?>"><br>

                <label for="email">Correo:</label>
                <input type="text" id="email" name="email" value="<?= $selectedUser['correo']; ?>"><br>

                <label for="active">Activo:</label>
                <input type="text" id="active" name="active" value="<?= $selectedUser['activo']; ?>"><br>

                <label for="password">Contrasena:</label>
                <input type="password" id="password" name="password" value="<?= $selectedUser['contrasena']; ?>"><br>

                <button type="submit">Update</button>
            </form>
        <?php else: ?>
            <p>Select a user to edit.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; <?= date('Y'); ?> My application. All rights reserved.</p>
</footer>
</body>
</html>
