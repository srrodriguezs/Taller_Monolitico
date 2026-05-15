<?php

namespace app\models\deletes;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsDelete {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function delete($id) {
        $sql = "DELETE FROM retro_items WHERE id = ?";
        $conexion = new Conexion();
        $stmt = $conexion->conx_db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conexion->Close();
    }
}