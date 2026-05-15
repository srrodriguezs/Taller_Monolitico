<?php

namespace app\models\deletes;

use app\models\config\Conexion;

class SprintsDelete {
    private $conexion;

    static function delete($id) {
        $sql = "DELETE FROM sprints WHERE id = $id";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
    }
}