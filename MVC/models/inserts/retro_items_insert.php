<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsInsert {
    static function insert(RetroItems $retroItem) {
        $sql = "INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision) VALUES (?, ?, ?, ?, ?)";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [
            $retroItem->get('sprint_id'),
            $retroItem->get('categoria'),
            $retroItem->get('descripcion'),
            $retroItem->get('cumplida') ? 1 : 0,
            $retroItem->get('fecha_revision')
        ]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}