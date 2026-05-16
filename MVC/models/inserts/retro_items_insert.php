<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsInsert {
    private $conexion;

    static function insert(RetroItems $retroItem) {
        $sql = "INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision) 
        VALUES (' " . $retroItem->get('sprint_id') . "', '" . 
        $retroItem->get('categoria') . "', '" . $retroItem->get('descripcion') . "', " . 
        ($retroItem->get('cumplida') ? 1 : 0) . ", '" . 
        $retroItem->get('fecha_revision') . "')";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
        return $result;
    }
}