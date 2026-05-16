<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsInsert {
    private $conexion;

    static function insert(Sprints $sprint) {
        $sql = "INSERT INTO sprints (nombre, fecha_inicio, fecha_fin) 
        VALUES ('" . $sprint->get('nombre') . "', '" . $sprint->get('fecha_inicio') . 
        "', '" . $sprint->get('fecha_fin') . "')";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $conexion->Close();
        return $result;
    }
}