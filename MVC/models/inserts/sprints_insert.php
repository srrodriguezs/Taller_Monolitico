<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsInsert {
    static function insert(Sprints $sprint) {
        $sql = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [
            $sprint->get('nombre'),
            $sprint->get('fecha_inicio'),
            $sprint->get('fecha_fin')
        ]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}