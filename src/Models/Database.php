<?php

namespace Models;

use mysqli;
use Exception;

class Database
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = 'rootPass123#';
    private string $dbname = 'formulario_db';
    private int $port = 3306;
    private mysqli $connection;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("No se pudo conectar a la base de datos. Por favor, inténtalo de nuevo más tarde.");
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}