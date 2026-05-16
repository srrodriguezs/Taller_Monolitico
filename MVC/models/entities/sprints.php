<?php

namespace app\models\entities;

use app\models\config\Model;

class Sprints extends Model {
    protected $id;
    protected $nombre;
    protected $fecha_inicio;
    protected $fecha_fin;

    public function __construct($id = null, $nombre = null, $fecha_inicio = null, $fecha_fin = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }
}