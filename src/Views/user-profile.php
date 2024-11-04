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
<main>
    <h1>Welcome <?php echo $_SESSION['user'] ?></h1>
    <form action="../../src/Controllers/AuthController.php?action=updateProfile" method="POST">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name">
        <br>
        <label for="lastname">Apellido:</label>
        <input type="text" id="lastname" name="lastname">
        <br>
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username">
        <br>
        <label for="identity">Cédula:</label>
        <input type="text" id="identity" name="identity">
        <br>
        <label for="email">Correo:</label>
        <input type="email" id="email" name="email">
        <br>
        <label for="password">Nueva contraseña:</label>
        <input type="password" id="password" name="password">
        <br>
        <?php
        if (isset($_SESSION['update_message'])) {
            echo "<p style='color: red'>" . $_SESSION['update_message'] . "</p>";
            unset($_SESSION['update_message']);
        }
        ?>
        <input type="submit" value="Actualizar">
    </form>
</main>
<footer>
    <p>&copy; <?php date('Y') ?> My application. All rights reserved.</p>
</footer>
</body>
</html>