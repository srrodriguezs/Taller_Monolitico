<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\RetroItems;

class RetroItemsQueries {
    static function getRetroItems(string $search = null, int $page = 1, int $perPage = 5) {
        $sql = "SELECT * FROM retro_items";
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= " WHERE sprint_id = ? OR categoria LIKE ? OR descripcion LIKE ?";
            $params[] = trim($search);
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
        }

        $offset = max(0, ($page - 1) * $perPage);
        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $conexion = new Conexion();
        $result = $conexion->Execute($sql, $params);
        $lista = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
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
        }

        $conexion->Close();
        return $lista;
    }

    static function countRetroItems(string $search = null) {
        $sql = "SELECT COUNT(*) AS total FROM retro_items";
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= " WHERE sprint_id = ? OR categoria LIKE ? OR descripcion LIKE ?";
            $params[] = trim($search);
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
        }

        $conexion = new Conexion();
        $result = $conexion->Execute($sql, $params);
        $total = 0;

        if ($result !== false && $row = $result->fetch_assoc()) {
            $total = intval($row['total']);
        }

        $conexion->Close();
        return $total;
    }

    static function getRetroItemsPorSprint(int $sprint_id) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [$sprint_id]);
        $lista = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
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
        }

        $conexion->Close();
        return $lista;
    }

    static function getSprintPorCategoria(int $sprint_id, string $categoria) {
        $sql = "SELECT * FROM retro_items WHERE sprint_id = ? AND categoria = ?";
        $conexion = new Conexion();
        $result = $conexion->Execute($sql, [$sprint_id, $categoria]);
        $lista = [];

        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $cumplida = $categoria === 'accion' ? $row['cumplida'] == 1 : null;
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
        }

        $conexion->Close();
        return $lista;
    }
}