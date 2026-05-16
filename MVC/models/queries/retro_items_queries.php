<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsQueries {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function getRetroItems(RetroItems $retroItem) {
        $sql = "SELECT * FROM retro_items";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->execute();
        $lista = [];
        while($row = $stmt->get_result()->fetch_assoc()){
            $retroItem = new RetroItems($row['id'], $row['nombre'], $row['email']);
            array_push($lista, $retroItem);
        }
        $this->conexion->Close();
        return $lista;
    }

    public function getRetroItemsPorSprint(int $sprint_id) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = :sprint_id";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param(':sprint_id', $sprint_id);
        $stmt->execute();
        $lista = [];
        while($row = $stmt->get_result()->fetch_assoc()){
            $retroItem = new RetroItems($row['id'], $row['sprint_id'], $row['categoria'], $row['descripcion'], $row['cumplida'], $row['fecha_revision']);
            array_push($lista, $retroItem);
        }
        $this->conexion->Close();
        return $lista;
    }

    public function getSprintPorCategoria(int $sprint_id, string $categoria) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = :sprint_id AND categoria = :categoria";
        $stmt = $this->conexion->conx_db->prepare($sql);
        $stmt->bind_param(':sprint_id', $sprint_id);
        $stmt->bind_param(':categoria', $categoria);
        $stmt->execute();
        $lista = [];
        while($row = $stmt->get_result()->fetch_assoc()){
            $retroItem = new RetroItems($row['id'], $row['sprint_id'], $row['categoria'], $row['descripcion'], $row['cumplida'], $row['fecha_revision']);
            array_push($lista, $retroItem);
        }
        $this->conexion->Close();
        return $lista;
    }
}