<?php

namespace app\models\queries;

use app\models\config\Conexion;
use app\models\entities\Sprints;

class SprintsQueries {
    static function getSprints(string $search = null, int $page = 1, int $perPage = 5) {
        $sql = "SELECT * FROM sprints";
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= " WHERE nombre LIKE ?";
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
                $sprint = new Sprints(
                    $row['id'], 
                    $row['nombre'], 
                    $row['fecha_inicio'], 
                    $row['fecha_fin']
                );
                array_push($lista, $sprint);
            }
        }

        $conexion->Close();
        return $lista;
    }

    static function countSprints(string $search = null) {
        $sql = "SELECT COUNT(*) AS total FROM sprints";
        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= " WHERE nombre LIKE ?";
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
}