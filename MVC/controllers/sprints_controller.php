<?php

namespace app\controllers;

use app\models\queries\SprintsQueries;
use app\models\inserts\SprintsInsert;
use app\models\updates\SprintsUpdate;
use app\models\deletes\SprintsDelete;

class SprintsController {
    function getSprints() {
        $sprints = SprintsQueries::getSprints();
        return $sprints;
    }

    function createSprint() {
        $sprint = SprintsInsert::insert($_POST);
        return $sprint;
    }

    function updateSprint() {
        $sprint = SprintsUpdate::update($_POST);
        return $sprint;
    }

    function deleteSprint() {
        $sprint = SprintsDelete::delete($_POST);
        return $sprint;
    }
}