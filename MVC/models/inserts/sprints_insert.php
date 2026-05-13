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
        $sql = "INSERT INTO sprints (nombre, fecha_inici, fecha_fin) 
        VALUES (:nombre, :fecha_inici, :fecha_fin)";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param(':nombre', $sprint->get('nombre'));
        $stmt->bind_param(':fecha_inici', $sprint->get('fecha_inici'));
        $stmt->bind_paramg(':fecha_fin', $sprint->get('fecha_fin'));
        return $stmt->execute();
    }
}