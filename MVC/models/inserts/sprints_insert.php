<?php

namespace app\models\inserts;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsInsert {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function insert(Sprints $sprint) {
        $sql = "INSERT INTO sprints (nombre, fecha_inici, fecha_fin) 
        VALUES (?, ?, ?)";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $nombre = $sprint->get('nombre');
        $fecha_inici = $sprint->get('fecha_inici');
        $fecha_fin = $sprint->get('fecha_fin');
        $stmt->bind_param('sss', $nombre, $fecha_inici, $fecha_fin);
        return $stmt->execute();
    }
}