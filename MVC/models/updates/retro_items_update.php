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
        $sprintId = $retroItem->get('sprint_id');
        $categoria = $retroItem->get('categoria');
        $descripcion = $retroItem->get('descripcion');
        $cumplida = $retroItem->get('cumplida');
        $fechaRevision = $retroItem->get('fecha_revision');
        $id = $retroItem->get('id');

        $stmt->bind_param("issssi", $sprintId, $categoria, $descripcion, $cumplida, $fechaRevision, $id);
        return $stmt->execute();
    }
}