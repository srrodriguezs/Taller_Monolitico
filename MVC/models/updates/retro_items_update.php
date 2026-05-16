<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsUpdate {
    private $conexion;

    static function update(RetroItems $retroItem) {
        $sql = "UPDATE retro_items 
        SET sprint_id = '" . $retroItem->get('sprint_id') . 
        "', categoria = '" . $retroItem->get('categoria') . 
        "', descripcion = '" . $retroItem->get('descripcion') . 
        "', cumplida = " . ($retroItem->get('cumplida') ? 1 : 0) . 
        ", fecha_revision = '" . $retroItem->get('fecha_revision') . 
        "' WHERE id = " . $retroItem->get('id');
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
        return $result;
    }
}