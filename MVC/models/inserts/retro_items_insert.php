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
        VALUES (:sprint_id, :categoria, :descripcion, :cumplida, :fecha_revision)";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bindValue(':sprint_id', $retroItem->get('sprint_id'));
        $stmt->bindValue(':categoria', $categoria);
        $stmt->bindValue(':descripcion', $retroItem->get('descripcion'));
        if ($categoria === 'accion') {
            $stmt->bindValue(':cumplida', $retroItem->get('cumplida'));
        } else {
            $stmt->bindValue(':cumplida', null);
        }
        $stmt->bindValue(':fecha_revision', $retroItem->get('fecha_revision'));
        $this->conexion->Close();
        return $stmt->execute();
    }
}