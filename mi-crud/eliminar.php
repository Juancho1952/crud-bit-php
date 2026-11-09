<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id && $id > 0) {
    $stmt = $pdo->prepare('DELETE FROM tareas WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: index.php?mensaje=eliminada');
exit;
?>
