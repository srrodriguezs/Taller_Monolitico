<?php

namespace app\models\entities;

use app\models\config\Model;

class RetroItems extends Model {
    protected $id;
    protected $sprint_id;
    protected $categoria;
    protected $descripcion;
    protected $cumplida;
    protected $fecha_revision;

    public function __construct($id = null, $sprint_id = null, $categoria = null, $descripcion = null, $cumplida = null, $fecha_revision = null) {
        $this->id = $id;
        $this->sprint_id = $sprint_id;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
        $this->cumplida = $cumplida;
        $this->fecha_revision = $fecha_revision;
    }
}