<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsQueries {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function getSprints(Sprints $sprint) {
        $sql = "SELECT * FROM sprint";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->execute();
        $lista = [];
        while($row = $stmt->get_result()->fetch_assoc()){
            $estudiante = new Estudiante($row['id'], $row['nombre'], $row['email']);
            array_push($lista, $estudiante);
        }
        return $lista;
    }
}