<?php

namespace Models;

use mysqli;

class Event
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getPaginatedEvents(int $limit, int $offset): array
    {
        $query = "SELECT i.id_inscriptor, i.nombre, i.apellido, i.edad, i.genero, pr.pais_nombre AS pais_residencia, 
              pn.pais_nombre AS pais_nacionalidad, i.correo, i.celular, i.observaciones, 
              GROUP_CONCAT(ti.tema SEPARATOR ', ') AS temas_interes
              FROM inscriptores i
              LEFT JOIN paises pr ON i.id_pais_residencia = pr.id_pais
              LEFT JOIN paises pn ON i.id_pais_nacionalidad = pn.id_pais
              LEFT JOIN inscriptores_tema it ON i.id_inscriptor = it.id_inscriptor
              LEFT JOIN tema_interes ti ON it.id_tema = ti.id_tema_interes
              GROUP BY i.id_inscriptor
              LIMIT ? OFFSET ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllEvents(): array
    {
        $query = "SELECT i.id_inscriptor, i.nombre, i.apellido, i.edad, i.genero, pr.pais_nombre AS pais_residencia, 
              pn.pais_nombre AS pais_nacionalidad, i.correo, i.celular, i.observaciones, 
              GROUP_CONCAT(ti.tema SEPARATOR ', ') AS temas_interes
              FROM inscriptores i
              LEFT JOIN paises pr ON i.id_pais_residencia = pr.id_pais
              LEFT JOIN paises pn ON i.id_pais_nacionalidad = pn.id_pais
              LEFT JOIN inscriptores_tema it ON i.id_inscriptor = it.id_inscriptor
              LEFT JOIN tema_interes ti ON it.id_tema = ti.id_tema_interes
              GROUP BY i.id_inscriptor";

        $result = $this->connection->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


}