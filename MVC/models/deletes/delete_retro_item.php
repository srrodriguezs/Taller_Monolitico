<?php

namespace app\models\deletes;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsDelete {
    public function delete($id) {
        $sql = "DELETE FROM retro_items WHERE id = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [$id]);
        $success = $result !== false;
        $error = $success ? '' : $conexion->getLastError();
        $conexion->Close();
        return ['success' => $success, 'error' => $error];
    }
}