<?php

namespace app\models\updates;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsUpdate {
    private $conexion;

    static function update(Sprints $sprint) {
        $sql = "UPDATE sprints 
        SET nombre = '" . $sprint->get('nombre') . "', fecha_inicio = '" . $sprint->get('fecha_inicio') . "', fecha_fin = '" . $sprint->get('fecha_fin') . "' WHERE id = " . $sprint->get('id');
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
        return $result;
    }
}