<?php
error_reporting(E_ALL);
session_start();

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
    <div>
        <p>UNIVERSIDAD TECNOLOGICA DE PANAMA</p>
        <p>FACULTAD DE INGENIERIA EN SISTEMAS COMPUTACIONALES</p>
        <p>LABORATORIO - PARCIAL</p>
        <p>ESTUDIANTE:</p>
        <p>ELADIO GONZALEZ</p>
        <p>FACILITADORA:</p>
        <p>ING. IRINA FONG</p>
        <p>DESARROLLO SE SOFTWARE VII</p>
        <p>1LS132</p>
    </div>
</main>
<footer>
    <p>&copy; <?php date('Y') ?> My application. All rights reserved.</p>
</footer>
</body>
</html>