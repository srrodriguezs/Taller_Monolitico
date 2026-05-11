<?php

namespace app\models\entities;

use app\models\config\Model;

class Sprints extends Model {
    protected $id;
    protected $nombre;
    protected $fecha_inici;
    protected $fecha_fin;

    public function __construct($id = null, $nombre = null, $fecha_inici = null, $fecha_fin = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_inici = $fecha_inici;
        $this->fecha_fin = $fecha_fin;
    }
}