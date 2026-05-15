<?php

namespace app\controllers;

use app\models\entities\Sprints;
use app\models\queries\SprintsQueries;
use app\models\inserts\SprintsInsert;
use app\models\updates\SprintsUpdate;
use app\models\deletes\SprintsDelete;

class SprintsController {
    function getSprints() {
        return SprintsQueries::getSprints();
    }

    function createSprint(array $data) {
        $sprint = new Sprints(null, $data['nombre'] ?? null, $data['fecha_inicio'] ?? null, $data['fecha_fin'] ?? null);
        $insert = new SprintsInsert();
        return $insert->insert($sprint);
    }

    function updateSprint(array $data) {
        $sprint = new Sprints($data['id'] ?? null, $data['nombre'] ?? null, $data['fecha_inicio'] ?? null, $data['fecha_fin'] ?? null);
        $update = new SprintsUpdate();
        return $update->update($sprint);
    }

    function deleteSprint(array $data) {
        $delete = new SprintsDelete();
        return $delete->delete($data['id'] ?? null);
    }
}