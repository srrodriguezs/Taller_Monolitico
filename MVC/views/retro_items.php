<?php

require __DIR__ . '/../models/config/model.php';
require __DIR__ . '/../models/config/conexion.php';
require __DIR__ . '/../models/entities/retro_items.php';
require __DIR__ . '/../models/queries/retro_items_queries.php';
require __DIR__ . '/../models/inserts/retro_items_insert.php';
require __DIR__ . '/../models/updates/retro_items_update.php';
require __DIR__ . '/../models/deletes/delete_retro_item.php';
require __DIR__ . '/../controllers/retro_items_controller.php';

use app\controllers\RetroItemsController;

$controller = new RetroItemsController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $success = $controller->createRetroItem($_POST);
        $message = $success ? 'Retro item creado correctamente.' : 'Error al crear retro item.';
    }

    if ($action === 'update') {
        $success = $controller->updateRetroItem($_POST);
        $message = $success ? 'Retro item actualizado correctamente.' : 'Error al actualizar retro item.';
    }

    if ($action === 'delete') {
        $success = $controller->deleteRetroItem($_POST);
        $message = $success ? 'Retro item eliminado correctamente.' : 'Error al eliminar retro item.';
    }

    header('Location: retro_items.php?message=' . urlencode($message));
    exit;
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

$items = $controller->getRetroItems();
$editItem = null;
if (isset($_GET['edit_id'])) {
    foreach ($items as $it) {
        if ($it->get('id') == $_GET['edit_id']) { $editItem = $it; break; }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Retro Items</title>
    <style>
        body{font-family:Arial;margin:20px}
        table{border-collapse:collapse;width:100%;max-width:1000px}
        th,td{border:1px solid #ccc;padding:8px}
        th{background:#f2f2f2}
        form{margin-bottom:16px}
        .message{padding:10px;margin-bottom:10px;border:1px solid #4caf50;background:#e8f5e9;color:#2e7d32}
    </style>
</head>
<body>
    <h1>Retrospectivas - Items</h1>
    <?php if ($message): ?><div class="message"><?php echo $message; ?></div><?php endif; ?>

    <section>
        <h2><?php echo $editItem ? 'Editar item' : 'Crear item'; ?></h2>
        <form method="post" action="retro_items.php">
            <?php if ($editItem): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo $editItem->get('id'); ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="create">
            <?php endif; ?>
            <div>
                <label>Sprint ID:</label><br>
                <input type="number" name="sprint_id" required value="<?php echo $editItem ? htmlspecialchars($editItem->get('sprint_id')) : ''; ?>">
            </div>
            <div>
                <label>Categoria (logro/impedimento/accion):</label><br>
                <input type="text" name="categoria" required value="<?php echo $editItem ? htmlspecialchars($editItem->get('categoria')) : ''; ?>">
            </div>
            <div>
                <label>Descripcion:</label><br>
                <textarea name="descripcion" required><?php echo $editItem ? htmlspecialchars($editItem->get('descripcion')) : ''; ?></textarea>
            </div>
            <div>
                <label>Cumplida (solo para 'accion'):</label>
                <input type="checkbox" name="cumplida" value="1" <?php echo ($editItem && $editItem->get('cumplida')) ? 'checked' : ''; ?> >
            </div>
            <div>
                <label>Fecha revision:</label><br>
                <input type="date" name="fecha_revision" value="<?php echo $editItem ? htmlspecialchars($editItem->get('fecha_revision')) : ''; ?>">
            </div>
            <div style="margin-top:8px"><button type="submit"><?php echo $editItem ? 'Actualizar' : 'Crear'; ?></button>
                <?php if ($editItem): ?> <a href="retro_items.php">Cancelar</a><?php endif; ?></div>
        </form>
    </section>

    <section>
        <h2>Lista de items</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Sprint</th><th>Categoria</th><th>Descripcion</th><th>Cumplida</th><th>Fecha revision</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php if (count($items) === 0): ?>
                    <tr><td colspan="7">No hay items registrados.</td></tr>
                <?php endif; ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item->get('id'); ?></td>
                        <td><?php echo htmlspecialchars($item->get('sprint_id')); ?></td>
                        <td><?php echo htmlspecialchars($item->get('categoria')); ?></td>
                        <td><?php echo htmlspecialchars($item->get('descripcion')); ?></td>
                        <td><?php echo ($item->get('categoria') === 'accion') ? ($item->get('cumplida') ? 'Sí' : 'No') : '-'; ?></td>
                        <td><?php echo htmlspecialchars($item->get('fecha_revision')); ?></td>
                        <td>
                            <a href="retro_items.php?edit_id=<?php echo $item->get('id'); ?>">Editar</a>
                            <form method="post" action="retro_items.php" style="display:inline;margin:0">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $item->get('id'); ?>">
                                <button type="submit" onclick="return confirm('Eliminar item?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
