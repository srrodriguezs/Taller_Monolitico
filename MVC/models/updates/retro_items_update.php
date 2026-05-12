<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsUpdate {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function update(RetroItems $retroItem) {
        $sql = "UPDATE retro_items SET sprint_id = ?, categoria = ?, descripcion = ?, cumplida = ?, fecha_revision = ? WHERE id = ?";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param("sss", $retroItem->get('sprint_id'), $retroItem->get('categoria'), $retroItem->get('descripcion'), $retroItem->get('cumplida'), $retroItem->get('fecha_revision'), $retroItem->get('id'));
        return $stmt->execute();
    }
}