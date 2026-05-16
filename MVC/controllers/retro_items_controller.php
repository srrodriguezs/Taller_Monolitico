<?php

namespace app\controllers;

use app\models\entities\RetroItems;
use app\models\queries\RetroItemsQueries;
use app\models\inserts\RetroItemsInsert;
use app\models\updates\RetroItemsUpdate;
use app\models\deletes\RetroItemsDelete;

class RetroItemsController {
    function getRetroItems() {
        return RetroItemsQueries::getRetroItems();
    }

    function getRetroItemsPorSprint($sprintId) {
        return RetroItemsQueries::getRetroItemsPorSprint($sprintId);
    }

    function getRetroItemsPorCategoria($categoria) {
        return RetroItemsQueries::getRetroItemsPorCategoria($categoria);
    }

    function createRetroItem(array $data) {
        $retroItem = new RetroItems(
            null,
            $data['sprint_id'] ?? null,
            $data['categoria'] ?? null,
            $data['descripcion'] ?? null,
            isset($data['cumplida']) ? ($data['cumplida'] ? 1 : 0) : 0,
            $data['fecha_revision'] ?? null
        );
        $insert = new RetroItemsInsert();
        return $insert->insert($retroItem);
    }

    function updateRetroItem(array $data) {
        $retroItem = new RetroItems(
            $data['id'] ?? null,
            $data['sprint_id'] ?? null,
            $data['categoria'] ?? null,
            $data['descripcion'] ?? null,
            isset($data['cumplida']) ? ($data['cumplida'] ? 1 : 0) : 0,
            $data['fecha_revision'] ?? null
        );
        $update = new RetroItemsUpdate();
        return $update->update($retroItem);
    }

    function deleteRetroItem(array $data) {
        $delete = new RetroItemsDelete();
        return $delete->delete($data['id'] ?? null);
    }
}