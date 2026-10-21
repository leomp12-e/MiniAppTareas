<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['titulo'])) {
        $titulo = trim($_POST['titulo']);
        if ($titulo !== '' && mb_strlen($titulo) <= 150) {
            $stmt = db()->prepare('INSERT INTO tareas (usuario_id, titulo) VALUES (?, ?)');
            $stmt->execute([currentUser()['id'], $titulo]);
        }
    } elseif (isset($_POST['toggle_id'])) {
        $stmt = db()->prepare('UPDATE tareas SET completada = 1 - completada
                               WHERE id = ? AND usuario_id = ?');
        $stmt->execute([(int)$_POST['toggle_id'], currentUser()['id']]);
    }
    header('Location: index.php');
    exit;
}