<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

    class RetroItemsInsert {
        private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function insertLogro(RetroItems $retroItem) {
        return $this->insert($retroItem, 'logro');
    }

    public function insertImpedimento(RetroItems $retroItem) {
        return $this->insert($retroItem, 'impedimento');
    }

    public function insertAccion(RetroItems $retroItem) {
        return $this->insert($retroItem, 'accion');
    }

    private function insert(RetroItems $retroItem, string $categoria) {
        $sql = "INSERT INTO retro_items (sprint_id, categoria, descripcion, cumplida, fecha_revision) 
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->conx_db->prepare($sql);

        $sprint_id = $retroItem->get('sprint_id');
        $descripcion = $retroItem->get('descripcion');
        $cumplida = $categoria === 'accion' ? $retroItem->get('cumplida') : null;
        $fecha_revision = $retroItem->get('fecha_revision');

        $stmt->bind_param('issis', $sprint_id, $categoria, $descripcion, $cumplida, $fecha_revision);
        return $stmt->execute();
    }
}