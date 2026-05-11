<?php

namespace app\models\entities;

use app\models\config\Model;

class RetroItems extends Model {
    protected $id;
    protected $name;
    protected $start_date;
    protected $end_date;

    public function __construct($id = null, $name = null, $start_date = null, $end_date = null) {
        $this->id = $id;
        $this->name = $name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
}