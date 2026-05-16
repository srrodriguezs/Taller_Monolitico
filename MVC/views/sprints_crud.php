<?php

require __DIR__ . '/../models/config/model.php';
require __DIR__ . '/../models/config/conexion.php';
require __DIR__ . '/../models/entities/Sprints.php';
require __DIR__ . '/../models/queries/sprints_queries.php';
require __DIR__ . '/../models/inserts/sprints_insert.php';
require __DIR__ . '/../models/updates/sprints_update.php';
require __DIR__ . '/../models/deletes/delete_sprint.php';
require __DIR__ . '/../controllers/sprints_controller.php';

use app\controllers\SprintsController;

$sprintsController = new SprintsController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $success = $sprintsController->createSprint($_POST);
        $message = $success ? 'Error al crear el sprint' : 'Sprint creado correctamente.';
    }

    if ($action === 'update') {
        $success = $sprintsController->updateSprint($_POST);
        $message = $success ? 'Error al actualizar el sprint' : 'Sprint actualizado correctamente.';
    }

    if ($action === 'delete') {
        $success = $sprintsController->deleteSprint($_POST);
        $message = $success ? 'Error al eliminar el sprint' : 'Sprint eliminado correctamente.';
    }

    header('Location: index.php?message=' . urlencode($message));
    exit;
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

$sprints = $sprintsController->getSprints();
$editSprint = null;

if (isset($_GET['edit_id'])) {
    foreach ($sprints as $s) {
        if ($s->get('id') == $_GET['edit_id']) {
            $editSprint = $s;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Sprints</title>
    <link rel="stylesheet" href="CSS/sprints_crud.css">
</head>
<body>
    <h1>CRUD de Sprints</h1>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <section>
        <h2><?php echo $editSprint ? 'Editar sprint' : 'Crear sprint'; ?></h2>
        <form method="post" action="index.php">
            <?php if ($editSprint): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo $editSprint->get('id'); ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="create">
            <?php endif; ?>
            <div>
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('nombre')) : ''; ?>">
            </div>
            <div>
                <label for="fecha_inicio">Fecha inicio:</label><br>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('fecha_inicio')) : ''; ?>">
            </div>
            <div>
                <label for="fecha_fin">Fecha fin:</label><br>
                <input type="date" id="fecha_fin" name="fecha_fin" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('fecha_fin')) : ''; ?>">
            </div>
            <div style="margin-top: 10px;">
                <button type="submit"><?php echo $editSprint ? 'Actualizar sprint' : 'Crear sprint'; ?></button>
                <?php if ($editSprint): ?>
                    <a href="index.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section>
        <h2>Lista de sprints</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($sprints) === 0): ?>
                    <tr>
                        <td colspan="5">No hay sprints registrados.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($sprints as $sprint): ?>
                    <tr>
                        <td><?php echo $sprint->get('id'); ?></td>
                        <td><?php echo htmlspecialchars($sprint->get('nombre')); ?></td>
                        <td><?php echo htmlspecialchars($sprint->get('fecha_inicio')); ?></td>
                        <td><?php echo htmlspecialchars($sprint->get('fecha_fin')); ?></td>
                        <td class="actions">
                            <a href="index.php?edit_id=<?php echo $sprint->get('id'); ?>">Editar</a>
                            <form method="post" action="index.php" style="display:inline; margin:0;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $sprint->get('id'); ?>">
                                <button type="submit" onclick="return confirm('¿Eliminar este sprint?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>