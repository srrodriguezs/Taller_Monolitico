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

function friendlyErrorMessage(string $error): string {
    if (stripos($error, 'foreign key') !== false) {
        return 'No existe el sprint referenciado. Verifica el ID del sprint.';
    }
    if (stripos($error, 'duplicate') !== false) {
        return 'Ya existe un sprint con esos datos.';
    }
    return $error ?: 'Ocurrió un error al procesar la petición.';
}

$sprintsController = new SprintsController();
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
        $result = $sprintsController->createSprint($_POST);
        $message = $result['success'] ? 'Sprint creado correctamente.' : friendlyErrorMessage($result['error']);
    }

    if ($action === 'update') {
        $result = $sprintsController->updateSprint($_POST);
        $message = $result['success'] ? 'Sprint actualizado correctamente.' : friendlyErrorMessage($result['error']);
    }

    if ($action === 'delete') {
        $result = $sprintsController->deleteSprint($_POST);
        $message = $result['success'] ? 'Sprint eliminado correctamente.' : friendlyErrorMessage($result['error']);
    }

    $messageType = $result['success'] ? 'success' : 'error';
    $redirect = 'sprints.php?message=' . urlencode($message) . '&message_type=' . $messageType;
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

$totalSprints = $sprintsController->countSprints($search);
$sprints = $sprintsController->getSprints($search, $page, $perPage);
$editSprint = null;
if (isset($_GET['edit_id'])) {
    foreach ($sprints as $s) {
        if ($s->get('id') == $_GET['edit_id']) {
            $editSprint = $s;
            break;
        }
    }
}

$searchQuery = $search !== '' ? '&search=' . urlencode($search) : '';
$totalPages = max(1, intval(ceil($totalSprints / $perPage)));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprints</title>
    <link rel="stylesheet" href="CSS/sprints.css">
</head>
<body>
    <div class="page-container">
        <nav class="top-nav">
            <a href="index.html">Inicio</a>
            <a href="retro_items.php">Retro Items</a>
        </nav>

        <header class="page-header">
            <h1>Gestión de Sprints</h1>
            <p>Busca, crea, edita y elimina sprints con mensajes claros y paginación.</p>
        </header>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <section class="list-controls">
            <form method="get" action="sprints.php" class="search-form">
                <input type="text" name="search" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
            <div class="list-summary">
                Mostrando <?php echo count($sprints); ?> de <?php echo $totalSprints; ?> sprint<?php echo $totalSprints === 1 ? '' : 's'; ?>.
            </div>
        </section>

        <section class="form-panel">
            <h2><?php echo $editSprint ? 'Editar sprint' : 'Crear sprint'; ?></h2>
            <form method="post" action="sprints.php" class="crud-form">
                <?php if ($editSprint): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $editSprint->get('id'); ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="page" value="<?php echo $page; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('nombre')) : ''; ?>">

                <label for="fecha_inicio">Fecha inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('fecha_inicio')) : ''; ?>">

                <label for="fecha_fin">Fecha fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required value="<?php echo $editSprint ? htmlspecialchars($editSprint->get('fecha_fin')) : ''; ?>">

                <div class="form-actions">
                    <button type="submit"><?php echo $editSprint ? 'Actualizar sprint' : 'Crear sprint'; ?></button>
                    <?php if ($editSprint): ?>
                        <a class="secondary-link" href="sprints.php?<?php echo trim($searchQuery . '&page=' . $page, '&'); ?>">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="table-panel">
            <h2>Lista de sprints</h2>
            <div class="table-wrapper">
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
                                    <a class="action-link" href="sprints.php?edit_id=<?php echo $sprint->get('id'); ?><?php echo $searchQuery; ?>&page=<?php echo $page; ?>">Editar</a>
                                    <form method="post" action="sprints.php" class="inline-form" onsubmit="return confirm('¿Eliminar este sprint?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $sprint->get('id'); ?>">
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

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="sprints.php?page=<?php echo $page - 1; ?><?php echo $searchQuery; ?>">« Anterior</a>
                    <?php endif; ?>
                    <span>Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a href="sprints.php?page=<?php echo $page + 1; ?><?php echo $searchQuery; ?>">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>