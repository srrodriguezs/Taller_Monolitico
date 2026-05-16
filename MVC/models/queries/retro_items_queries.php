<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsQueries {
    private $conexion;

    static function getRetroItems() {
        $sql = "SELECT * FROM retro_items";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql); 
        $lista = [];
        while($row = $result->fetch_assoc()){
            $retroItem = new RetroItems(
                $row['id'], 
                $row['sprint_id'], 
                $row['categoria'], 
                $row['descripcion'], 
                $row['cumplida'], 
                $row['fecha_revision']
            );
            if ($retroItem->get('categoria') === 'accion') {
                $retroItem->set('cumplida', $row['cumplida'] == 1);
            }
            array_push($lista, $retroItem);
        }
        $conexion->Close();
        return $lista;
    }

    static function getRetroItemsPorSprint(int $sprint_id) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = $sprint_id";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $lista = [];
        while($row = $result->fetch_assoc()){
            $retroItem = new RetroItems(
                $row['id'], 
                $row['sprint_id'], 
                $row['categoria'], 
                $row['descripcion'], 
                $row['cumplida'], 
                $row['fecha_revision']
            );
            if ($retroItem->get('categoria') === 'accion') {
                $retroItem->set('cumplida', $row['cumplida'] == 1);
            }
            array_push($lista, $retroItem);
        }
        $conexion->Close();
        return $lista;
    }

    static function getSprintPorCategoria(int $sprint_id, string $categoria) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = $sprint_id AND categoria = $categoria";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql);
        $lista = [];
        while($row = $result->fetch_assoc()){
            if ($categoria === 'accion') {
                $cumplida = $row['cumplida'] == 1;
            } else {
                $cumplida = null;
            }
            $retroItem = new RetroItems(
                $row['id'], 
                $row['sprint_id'], 
                $row['categoria'], 
                $row['descripcion'], 
                $cumplida, 
                $row['fecha_revision']
            );
            array_push($lista, $retroItem);
        }
        $conexion->Close();
        return $lista;
    }
}