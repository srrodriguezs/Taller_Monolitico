<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsQueries {
    private $conexion;

    static function getSprints() {
        $sql = "SELECT * FROM sprints";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $lista = [];

        while($row = $result->fetch_assoc()){
            $sprint = new Sprints($row['id'], $row['nombre'], $row['fecha_inicio'], $row['fecha_fin']);
            array_push($lista, $sprint);
        }
        $conexion->Close();
        return $lista;
    }
}