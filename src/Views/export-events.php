<?php
session_start();

use Controllers\EventController;
use Models\Database;

require_once '../../src/Controllers/EventController.php';
require_once '../../src/Models/Database.php';

if (!isset($_SESSION["user"])) {
    header("Location: ../../index.php");
    exit();
}

$database = new Database();
$connection = $database->getConnection();
$eventController = new EventController($connection);
$events = $eventController->getAllEvents();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="events_report.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Nombre', 'Apellido', 'Edad', 'Género', 'País de Residencia', 'País de Nacionalidad', 'Correo', 'Celular', 'Observaciones', 'Temas de Interés']);

foreach ($events as $event) {
    fputcsv($output, [
        $event["nombre"],
        $event["apellido"],
        $event["edad"],
        $event["genero"],
        $event["pais_residencia"],
        $event["pais_nacionalidad"],
        $event["correo"],
        $event["celular"],
        $event["observaciones"],
        $event["temas_interes"]
    ]);
}

fclose($output);
exit();