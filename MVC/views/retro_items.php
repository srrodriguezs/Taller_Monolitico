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

function friendlyErrorMessage(string $error): string {
    if (stripos($error, 'foreign key') !== false) {
        return 'No existe el sprint referenciado. Verifica el ID del sprint.';
    }
    if (stripos($error, 'duplicate') !== false) {
        return 'Ya existe un retro item similar.';
    }
    return $error ?: 'Ocurrió un error al procesar la petición.';
}

$controller = new RetroItemsController();
$message = '';
$messageType = 'success';
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $search = trim($_POST['search'] ?? $search);
    $page = max(1, intval($_POST['page'] ?? $page));
    $result = ['success' => false, 'error' => ''];

    if ($action === 'create') {
        $result = $controller->createRetroItem($_POST);
        $message = $result['success'] ? 'Retro item creado correctamente.' : friendlyErrorMessage($result['error']);
    }

    if ($action === 'update') {
        $result = $controller->updateRetroItem($_POST);
        $message = $result['success'] ? 'Retro item actualizado correctamente.' : friendlyErrorMessage($result['error']);
    }

    if ($action === 'delete') {
        $result = $controller->deleteRetroItem($_POST);
        $message = $result['success'] ? 'Retro item eliminado correctamente.' : friendlyErrorMessage($result['error']);
    }

    $messageType = $result['success'] ? 'success' : 'error';
    $redirect = 'retro_items.php?message=' . urlencode($message) . '&message_type=' . $messageType;
    if ($search !== '') {
        $redirect .= '&search=' . urlencode($search);
    }
    if ($page > 1) {
        $redirect .= '&page=' . $page;
    }
    header('Location: ' . $redirect);
    exit;
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
if (isset($_GET['message_type']) && $_GET['message_type'] === 'error') {
    $messageType = 'error';
}

$totalItems = $controller->countRetroItems($search);
$items = $controller->getRetroItems($search, $page, $perPage);
$editItem = null;
if (isset($_GET['edit_id'])) {
    foreach ($items as $it) {
        if ($it->get('id') == $_GET['edit_id']) { $editItem = $it; break; }
    }
}

$searchQuery = $search !== '' ? '&search=' . urlencode($search) : '';
$totalPages = max(1, intval(ceil($totalItems / $perPage)));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrospectivas</title>
    <link rel="stylesheet" href="CSS/retro_items.css">
</head>
<body>
    <div class="page-container">
        <nav class="top-nav">
            <a href="index.html">Inicio</a>
            <a href="sprints.php">Sprints</a>
        </nav>

        <header class="page-header">
            <h1>Retrospectivas</h1>
            <p>Administra retro items con búsqueda, paginación y manejo de errores.</p>
        </header>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <section class="list-controls">
            <form method="get" action="retro_items.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar por sprint, categoría o descripción..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
            <div class="list-summary">
                Mostrando <?php echo count($items); ?> de <?php echo $totalItems; ?> item<?php echo $totalItems === 1 ? '' : 's'; ?>.
            </div>
        </section>

        <section class="form-panel">
            <h2><?php echo $editItem ? 'Editar item' : 'Crear item'; ?></h2>
            <form method="post" action="retro_items.php" class="crud-form">
                <?php if ($editItem): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $editItem->get('id'); ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="page" value="<?php echo $page; ?>">

                <label>Sprint ID</label>
                <input type="number" name="sprint_id" required value="<?php echo $editItem ? htmlspecialchars($editItem->get('sprint_id')) : ''; ?>">

                <label>Categoria</label>
                <input type="text" name="categoria" required value="<?php echo $editItem ? htmlspecialchars($editItem->get('categoria')) : ''; ?>">

                <label>Descripción</label>
                <textarea name="descripcion" required><?php echo $editItem ? htmlspecialchars($editItem->get('descripcion')) : ''; ?></textarea>

                <label class="checkbox-label">
                    Cumplida (solo para categoría acción)
                    <input type="checkbox" name="cumplida" value="1" <?php echo ($editItem && $editItem->get('cumplida')) ? 'checked' : ''; ?> >
                </label>

                <label>Fecha revisión</label>
                <input type="date" name="fecha_revision" value="<?php echo $editItem ? htmlspecialchars($editItem->get('fecha_revision')) : ''; ?>">

                <div class="form-actions">
                    <button type="submit"><?php echo $editItem ? 'Actualizar' : 'Crear'; ?></button>
                    <?php if ($editItem): ?> <a class="secondary-link" href="retro_items.php?<?php echo trim($searchQuery . '&page=' . $page, '&'); ?>">Cancelar</a><?php endif; ?>
                </div>
            </form>
        </section>

        <section class="table-panel">
            <h2>Lista de items</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sprint</th>
                            <th>Categoria</th>
                            <th>Descripción</th>
                            <th>Cumplida</th>
                            <th>Fecha revisión</th>
                            <th>Acciones</th>
                        </tr>
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
                                    <a class="action-link" href="retro_items.php?edit_id=<?php echo $item->get('id'); ?><?php echo $searchQuery; ?>&page=<?php echo $page; ?>">Editar</a>
                                    <form method="post" action="retro_items.php" class="inline-form" onsubmit="return confirm('Eliminar item?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $item->get('id'); ?>">
                                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php $totalPages = ceil($totalItems / $perPage);
            if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="retro_items.php?page=<?php echo $page - 1; ?><?php echo $searchQuery; ?>">« Anterior</a>
                    <?php endif; ?>
                    <span>Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a href="retro_items.php?page=<?php echo $page + 1; ?><?php echo $searchQuery; ?>">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
