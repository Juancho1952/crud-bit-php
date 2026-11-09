<?php
require_once 'conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: index.php');
    exit;
}

// Buscar la tarea que se va a editar.
$stmt = $pdo->prepare('SELECT * FROM tareas WHERE id = ?');
$stmt->execute([$id]);
$tarea = $stmt->fetch();

if (!$tarea) {
    header('Location: index.php');
    exit;
}

$error = '';
$titulo = $tarea['titulo'];
$materia = $tarea['materia'];
$prioridad = $tarea['prioridad'];
$horas = $tarea['horas_estimadas'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $materia = trim($_POST['materia'] ?? '');
    $prioridad = $_POST['prioridad'] ?? 'Media';
    $horas = trim($_POST['horas_estimadas'] ?? '');

    if ($titulo === '' || $materia === '' || $prioridad === '' || $horas === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!in_array($prioridad, ['Alta', 'Media', 'Baja'], true)) {
        $error = 'Selecciona una prioridad válida.';
    } elseif (!is_numeric($horas) || (float) $horas < 0) {
        $error = 'Las horas estimadas deben ser un número mayor o igual a cero.';
    } else {
        $sql = 'UPDATE tareas SET titulo = ?, materia = ?, prioridad = ?, horas_estimadas = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titulo, $materia, $prioridad, $horas, $id]);
        header('Location: index.php?mensaje=actualizada');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Editar tarea #<?= (int) $id ?></h1>

        <?php if ($error !== ''): ?>
            <p class="error"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= (int) $id ?>">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= e($titulo) ?>" required>

            <label for="materia">Materia:</label>
            <input type="text" id="materia" name="materia" value="<?= e($materia) ?>" required>

            <label for="prioridad">Prioridad:</label>
            <select id="prioridad" name="prioridad" required>
                <option value="Alta" <?= $prioridad === 'Alta' ? 'selected' : '' ?>>Alta</option>
                <option value="Media" <?= $prioridad === 'Media' ? 'selected' : '' ?>>Media</option>
                <option value="Baja" <?= $prioridad === 'Baja' ? 'selected' : '' ?>>Baja</option>
            </select>

            <label for="horas_estimadas">Horas estimadas:</label>
            <input type="number" id="horas_estimadas" name="horas_estimadas" min="0" step="0.5" value="<?= e($horas) ?>" required>

            <button type="submit">Actualizar tarea</button>
        </form>

        <p><a href="index.php">Volver al listado</a></p>
    </main>
</body>
</html>
