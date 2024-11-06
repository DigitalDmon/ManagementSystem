<?php
session_start();
error_reporting(E_ALL);

use includes\Redirect;

require_once '../../includes/Redirect.php';

try {
    if (!isset($_SESSION["user"])) {
        $redirect = new Redirect();
        $redirect->redirectTo("../../index.php");
    }
} catch (Exception $ex) {
    echo "Se produjo un error: " . $ex->getMessage();
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Profile</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="welcome.php">Welcome</a></li>
            <li><a href="events-reports.php">Events reports</a></li>
            <li><a href="users-reports.php">Users reports</a></li>
            <li><a href="user-profile.php">Profile</a></li>
            <li><a href="../Controllers/AuthController.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</header>
<main class="content-container">
    <div class="profile-container">
        <h1 class="profile-title">Welcome <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
        <form action="../../src/Controllers/AuthController.php?action=updateProfile" method="POST" class="profile-form">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="lastname">Apellido:</label>
                <input type="text" id="lastname" name="lastname">
            </div>
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="identity">Cédula:</label>
                <input type="text" id="identity" name="identity">
            </div>
            <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Nueva contraseña:</label>
                <input type="password" id="password" name="password">
            </div>
            <?php
            if (isset($_SESSION['update_message'])) {
                $messageClass = str_contains($_SESSION['update_message'], 'éxito') ? 'success' : 'error';
                echo "<p class='update-message {$messageClass}'>" . htmlspecialchars($_SESSION['update_message']) . "</p>";
                unset($_SESSION['update_message']);
            }
            ?>
            <input type="submit" value="Actualizar" class="submit-button">
        </form>
    </div>
</main>
<footer>
    <p>&copy; <?php date('Y') ?> My application. All rights reserved.</p>
</footer>
</body>
</html>