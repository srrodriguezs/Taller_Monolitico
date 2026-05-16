<?php

namespace app\controllers;

use app\models\entities\Sprints;
use app\models\queries\SprintsQueries;
use app\models\inserts\SprintsInsert;
use app\models\updates\SprintsUpdate;
use app\models\deletes\SprintsDelete;

class SprintsController {
    function getSprints(string $search = null, int $page = 1, int $perPage = 5) {
        return SprintsQueries::getSprints($search, $page, $perPage);
    }

    function countSprints(string $search = null) {
        return SprintsQueries::countSprints($search);
    }

    function createSprint(array $data) {
        $nombre = trim($data['nombre'] ?? '');
        $fechaInicio = $data['fecha_inicio'] ?? null;
        $fechaFin = $data['fecha_fin'] ?? null;

        if ($nombre === '' || !$fechaInicio || !$fechaFin) {
            return ['success' => false, 'error' => 'Todos los campos son obligatorios.'];
        }

        $sprint = new Sprints(null, $nombre, $fechaInicio, $fechaFin);
        $insert = new SprintsInsert();
        return $insert->insert($sprint);
    }

    function updateSprint(array $data) {
        $id = $data['id'] ?? null;
        $nombre = trim($data['nombre'] ?? '');
        $fechaInicio = $data['fecha_inicio'] ?? null;
        $fechaFin = $data['fecha_fin'] ?? null;

        if (!$id || !is_numeric($id)) {
            return ['success' => false, 'error' => 'ID de sprint inválido.'];
        }
        if ($nombre === '' || !$fechaInicio || !$fechaFin) {
            return ['success' => false, 'error' => 'Todos los campos son obligatorios.'];
        }

        $sprint = new Sprints($id, $nombre, $fechaInicio, $fechaFin);
        $update = new SprintsUpdate();
        return $update->update($sprint);
    }

    function deleteSprint(array $data) {
        $id = $data['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            return ['success' => false, 'error' => 'ID de sprint inválido.'];
        }

        $delete = new SprintsDelete();
        return $delete->delete($id);
    }
}