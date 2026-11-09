<?php
require_once 'conexion.php';

$buscar = trim($_GET['buscar'] ?? '');

// Indicadores generales de todas las tareas.
$sqlResumen = "SELECT
    COUNT(*) AS total_tareas,
    COALESCE(SUM(prioridad = 'Alta'), 0) AS tareas_altas,
    COALESCE(SUM(horas_estimadas), 0) AS total_horas
    FROM tareas";
$resumen = $pdo->query($sqlResumen)->fetch();

// Listado con buscador por título o materia.
if ($buscar !== '') {
    $sql = "SELECT * FROM tareas
            WHERE titulo LIKE ? OR materia LIKE ?
            ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $termino = "%$buscar%";
    $stmt->execute([$termino, $termino]);
    $tareas = $stmt->fetchAll();
} else {
    $stmt = $pdo->query('SELECT * FROM tareas ORDER BY id DESC');
    $tareas = $stmt->fetchAll();
}

$mensajes = [
    'creada' => 'La tarea se creó correctamente.',
    'actualizada' => 'La tarea se actualizó correctamente.',
    'eliminada' => 'La tarea se eliminó correctamente.'
];
$mensaje = $mensajes[$_GET['mensaje'] ?? ''] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Gestor de tareas</h1>
        <p>CRUD básico de tareas y proyectos.</p>

        <?php if ($mensaje !== ''): ?>
            <p class="mensaje"><?= e($mensaje) ?></p>
        <?php endif; ?>

        <section class="tarjetas">
            <div class="tarjeta">
                <h3>Total tareas</h3>
                <p><?= (int) $resumen['total_tareas'] ?></p>
            </div>
            <div class="tarjeta">
                <h3>Prioridad alta</h3>
                <p><?= (int) $resumen['tareas_altas'] ?></p>
            </div>
            <div class="tarjeta">
                <h3>Horas acumuladas</h3>
                <p><?= number_format((float) $resumen['total_horas'], 2, ',', '.') ?></p>
            </div>
        </section>

        <p><a href="crear.php">+ Crear nueva tarea</a></p>

        <form method="GET" action="index.php" class="buscador">
            <input type="text" name="buscar" placeholder="Buscar por título o materia" value="<?= e($buscar) ?>">
            <button type="submit">Buscar</button>
            <a href="index.php">Limpiar</a>
        </form>

        <h2>Listado de tareas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Materia</th>
                    <th>Prioridad</th>
                    <th>Horas estimadas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tareas) > 0): ?>
                    <?php foreach ($tareas as $tarea): ?>
                        <tr>
                            <td><?= (int) $tarea['id'] ?></td>
                            <td><?= e($tarea['titulo']) ?></td>
                            <td><?= e($tarea['materia']) ?></td>
                            <td><?= e($tarea['prioridad']) ?></td>
                            <td><?= number_format((float) $tarea['horas_estimadas'], 2, ',', '.') ?></td>
                            <td>
                                <a href="editar.php?id=<?= (int) $tarea['id'] ?>">Editar</a>
                                <form method="POST" action="eliminar.php" class="form-eliminar" onsubmit="return confirm('¿Seguro que deseas eliminar esta tarea?');">
                                    <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron tareas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
