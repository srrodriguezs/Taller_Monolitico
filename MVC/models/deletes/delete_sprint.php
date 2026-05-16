<?php

namespace app\models\deletes;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsDelete {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function delete($id) {
        $sql = "DELETE FROM sprints WHERE id = ?";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param("i", $id);
        $this->conexion->Close();
        return $stmt->execute();
    }
}