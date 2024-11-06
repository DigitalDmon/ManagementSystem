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
    <link rel="stylesheet" href="../../assets/css/style.css">
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
    <div class="content-container">
        <form action="export-events.php" method="post" class="export-form">
            <button type="submit" class="export-button">Exportar a Excel</button>
        </form>

        <div class="events-table-container">
            <?php if (count($events) > 0): ?>
                <table class="events-table">
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
                            <td><?= htmlspecialchars($event["nombre"]) ?></td>
                            <td><?= htmlspecialchars($event["apellido"]) ?></td>
                            <td><?= htmlspecialchars($event["edad"]) ?></td>
                            <td><?= htmlspecialchars($event["genero"]) ?></td>
                            <td><?= htmlspecialchars($event["pais_residencia"]) ?></td>
                            <td><?= htmlspecialchars($event["pais_nacionalidad"]) ?></td>
                            <td><?= htmlspecialchars($event["correo"]) ?></td>
                            <td><?= htmlspecialchars($event["celular"]) ?></td>
                            <td><?= htmlspecialchars($event["observaciones"]) ?></td>
                            <td><?= $event["temas_interes"] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="no-results">No se obtuvieron resultados</p>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php endif; ?>
            <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
        </div>
    </div>
</main>
<footer>
    <p>&copy; <?php echo date('Y'); ?> My application. All rights reserved.</p>
</footer>
</body>
</html>
