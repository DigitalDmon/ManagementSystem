<?php

namespace Controllers;

use Models\Event;

require_once __DIR__ . '/../Models/Event.php';

class EventController
{
    private Event $eventModel;

    public function __construct($connection)
    {
        $this->eventModel = new Event($connection);
    }

    public function listPaginatedEvents(): array
    {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        return $this->eventModel->getPaginatedEvents($limit, $offset);
    }

    public function getAllEvents() : array {
        return $this->eventModel->getAllEvents();
    }

}