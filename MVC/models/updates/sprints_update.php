<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsUpdate {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function update(Sprints $sprint) {
        $sql = "UPDATE sprints SET nombre = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param("sss", $sprint->get('nombre'), $sprint->get('fecha_inicio'), $sprint->get('fecha_fin'), $sprint->get('id'));
        return $stmt->execute();
    }
}