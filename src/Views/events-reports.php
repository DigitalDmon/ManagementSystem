<?php
session_start();

use Controllers\EventController;
use Models\Database;
use includes\Redirect;

require_once '../../src/Controllers/EventController.php';
require_once '../../src/Models/Database.php';
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

$database = new Database();
$connection = $database->getConnection();
$eventController = new EventController($connection);
$events = $eventController->listPaginatedEvents();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Events reports</title>
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
    <main>
        <?php if (count($events) > 0): ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>País de Residencia</th>
                    <th>País de Nacionalidad</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Observaciones</th>
                    <th>Temas de Interés</th>
                </tr>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= $event["nombre"] ?></td>
                        <td><?= $event["apellido"] ?></td>
                        <td><?= $event["edad"] ?></td>
                        <td><?= $event["genero"] ?></td>
                        <td><?= $event["pais_residencia"] ?></td>
                        <td><?= $event["pais_nacionalidad"] ?></td>
                        <td><?= $event["correo"] ?></td>
                        <td><?= $event["celular"] ?></td>
                        <td><?= $event["observaciones"] ?></td>
                        <td><?= $event["temas_interes"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No se obtuvieron resultados</p>
        <?php endif; ?>

        <div class="pagination">
            <?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php endif; ?>
            <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
        </div>
    </main>
</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> My application. All rights reserved.</p>
</footer>
</body>
</html>
