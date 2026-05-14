<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsInsert {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function insert(Sprints $sprint) {
        $sql = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) 
        VALUES (:nombre, :fecha_inicio, :fecha_fin)";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bindValue(':nombre', $sprint->get('nombre'));
        $stmt->bindValue(':fecha_inicio', $sprint->get('fecha_inicio'));
        $stmt->bindValue(':fecha_fin', $sprint->get('fecha_fin'));
        $this->conexion->Close();
        return $stmt->execute();
    }
}