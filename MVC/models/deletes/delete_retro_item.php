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
        $sql = "DELETE FROM retro_items WHERE id = $id";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
    }
}