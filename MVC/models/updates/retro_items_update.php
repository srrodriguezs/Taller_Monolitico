<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsUpdate {
    static function update(RetroItems $retroItem) {
        $sql = "UPDATE retro_items SET sprint_id = ?, categoria = ?, descripcion = ?, cumplida = ?, fecha_revision = ? WHERE id = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [
            $retroItem->get('sprint_id'),
            $retroItem->get('categoria'),
            $retroItem->get('descripcion'),
            $retroItem->get('cumplida') ? 1 : 0,
            $retroItem->get('fecha_revision'),
            $retroItem->get('id')
        ]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}