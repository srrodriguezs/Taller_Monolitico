<?php

namespace app\models\deletes;

use app\models\config\Conexion;

class SprintsDelete {
    static function delete($id) {
        $sql = "DELETE FROM sprints WHERE id = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [$id]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}