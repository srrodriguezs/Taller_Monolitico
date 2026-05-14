<?php

namespace app\controllers;

use app\models\queries\RetroItemsQueries;
use app\models\inserts\RetroItemsInsert;
use app\models\updates\RetroItemsUpdate;
use app\models\deletes\RetroItemsDelete;

class RetroItemsController {
    // Consultas
    function getRetroItems() {
        $retroItems = RetroItemsQueries::getRetroItems();
        return $retroItems;
    }

    function getRetroItemsPorSprint($sprintId) {
        $retroItems = RetroItemsQueries::getRetroItemsPorSprint($sprintId);
        return $retroItems;
    }

    function getRetroItemsPorCategoria($categoria) {
        $retroItems = RetroItemsQueries::getRetroItemsPorCategoria($categoria);
        return $retroItems;
    }

    //Inserciones
    function insertLogro() {
        $retroItem = RetroItemsInsert::insertLogro($_POST);
        return $retroItem;
    }

    function insertImpedimento() {
        $retroItem = RetroItemsInsert::insertImpedimento($_POST);
        return $retroItem;
    }

    function insertAccion() {
        $retroItem = RetroItemsInsert::insertAccion($_POST);
        return $retroItem;
    }

    function createRetroItem() {
        $retroItem = RetroItemsInsert::insert($_POST);
        return $retroItem;
    }

    function updateRetroItem() {
        $retroItem = RetroItemsUpdate::update($_POST);
        return $retroItem;
    }

    function deleteRetroItem() {
        $retroItem = RetroItemsDelete::delete($_POST);
        return $retroItem;
    }
}