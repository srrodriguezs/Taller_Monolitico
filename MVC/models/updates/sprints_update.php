<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsUpdate {
    static function update(Sprints $sprint) {
        $sql = "UPDATE sprints SET nombre = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [
            $sprint->get('nombre'),
            $sprint->get('fecha_inicio'),
            $sprint->get('fecha_fin'),
            $sprint->get('id')
        ]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}