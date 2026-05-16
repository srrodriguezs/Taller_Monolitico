<?php

namespace app\controllers;

use app\models\entities\RetroItems;
use app\models\queries\RetroItemsQueries;
use app\models\inserts\RetroItemsInsert;
use app\models\updates\RetroItemsUpdate;
use app\models\deletes\RetroItemsDelete;

class RetroItemsController {
    function getRetroItems(string $search = null, int $page = 1, int $perPage = 5) {
        return RetroItemsQueries::getRetroItems($search, $page, $perPage);
    }

    function countRetroItems(string $search = null) {
        return RetroItemsQueries::countRetroItems($search);
    }

    function getRetroItemsPorSprint($sprintId) {
        return RetroItemsQueries::getRetroItemsPorSprint($sprintId);
    }

    function getRetroItemsPorCategoria($categoria) {
        return RetroItemsQueries::getRetroItemsPorCategoria($categoria);
    }

    function createRetroItem(array $data) {
        $sprintId = $data['sprint_id'] ?? null;
        $categoria = trim($data['categoria'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $cumplida = isset($data['cumplida']) ? 1 : 0;
        $fechaRevision = $data['fecha_revision'] ?? null;

        if (!$sprintId || !is_numeric($sprintId)) {
            return ['success' => false, 'error' => 'El sprint ID es inválido o está vacío.'];
        }
        if ($categoria === '' || $descripcion === '') {
            return ['success' => false, 'error' => 'La categoría y la descripción son obligatorias.'];
        }

        $retroItem = new RetroItems(
            null,
            $sprintId,
            $categoria,
            $descripcion,
            $cumplida,
            $fechaRevision
        );
        $insert = new RetroItemsInsert();
        return $insert->insert($retroItem);
    }

    function updateRetroItem(array $data) {
        $id = $data['id'] ?? null;
        $sprintId = $data['sprint_id'] ?? null;
        $categoria = trim($data['categoria'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $cumplida = isset($data['cumplida']) ? 1 : 0;
        $fechaRevision = $data['fecha_revision'] ?? null;

        if (!$id || !is_numeric($id)) {
            return ['success' => false, 'error' => 'ID de retro item inválido.'];
        }
        if (!$sprintId || !is_numeric($sprintId)) {
            return ['success' => false, 'error' => 'El sprint ID es inválido o está vacío.'];
        }
        if ($categoria === '' || $descripcion === '') {
            return ['success' => false, 'error' => 'La categoría y la descripción son obligatorias.'];
        }

        $retroItem = new RetroItems(
            $id,
            $sprintId,
            $categoria,
            $descripcion,
            $cumplida,
            $fechaRevision
        );
        $update = new RetroItemsUpdate();
        return $update->update($retroItem);
    }

    function deleteRetroItem(array $data) {
        $id = $data['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            return ['success' => false, 'error' => 'ID de retro item inválido.'];
        }

        $delete = new RetroItemsDelete();
        return $delete->delete($id);
    }
}