<?php

require __DIR__ . '/../models/config/model.php';
require __DIR__ . '/../models/config/conexion.php';
require __DIR__ . '/../models/entities/Sprints.php';
require __DIR__ . '/../models/queries/sprints_queries.php';
require __DIR__ . '/../models/inserts/sprints_insert.php';
require __DIR__ . '/../controllers/sprints_controller.php';

use app\controllers\SprintsController;

$sprintsController = new SprintsController();
$sprints = $sprintsController->getSprints();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Sprints</h1>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sprints as $sprint): ?>
                <tr>
                    <td><?php echo $sprint->get('id'); ?></td>
                    <td><?php echo $sprint->get('nombre'); ?></td>
                    <td><?php echo $sprint->get('fecha_inici'); ?></td>
                    <td><?php echo $sprint->get('fecha_fin'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>